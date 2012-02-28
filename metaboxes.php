<?php

	
	/* Fire our meta box setup function on the post editor screen. */
	add_action( 'load-page.php', 'mini_archive_meta_boxes_setup' );
	add_action( 'load-page-new.php', 'mini_archive_meta_boxes_setup' );
	
	function mini_archive_meta_boxes_setup(){
		add_action( 'add_meta_boxes', 'mini_archive_meta_boxes' );
		
		add_action( 'save_post', 'mini_archive_save', 10, 2 );
	}
	
	function mini_archive_save($post_id,$post){
		$meta_key = 'mini_archive';
		if(!isset($_POST['mini_archive_opt_in']) || !isset($_POST['mini_archive_type'])){
			delete_post_meta( $post_id, $meta_key);
			return true;
		}
		// generate wp-query string to make archive class values
		$new_value = sanitize_html_class( $_POST['mini_archive_type'] );
		update_post_meta($post_id, $meta_key, $new_value);
	}
	
	function mini_archive_meta_boxes(){
		add_meta_box(
				'mini-archive-page-class',			// Unique ID
				esc_html__( 'Mini Archive', 'example' ),		// Title
				'mini_archive_meta_box',		// Callback function
				'page',					// Admin page (or post type)
				'normal',					// Context
				'default'					// Priority
			);
	}
		
	function mini_archive_meta_box($object,$box){
		$archive_value = get_post_meta($object->ID,'mini_archive',true);
		$post_types = get_post_types(Array(),'objects');
		// remove nav menus?
		// add buddypress?
		?>
		<fieldset>
			<p>
				<input id="mini_archive_opt_in" name="mini_archive_opt_in" value="true" type="checkbox" <? if($archive_value){ echo 'checked="checked"'; } ?> />
				<label for="mini_archive_opt_in"><?=_e("Add an archive to this page.");?></label>
			</p>
			<p>
				<label for="mini_archive_type"><?=_e("Type of content to list");?></label>
				<select id="mini_archive_type" name="mini_archive_type">
					<? foreach($post_types as $post_type):	?>
					<option value="<?=$post_type->name;?>" <? if($archive_value==$post_type->name){ echo 'selected="selected"'; } ?>><?=_e($post_type->label);?></option>
					<? endforeach; ?>
				</select>
			</p>
			<p>Query filters will be added here</p>
		</fieldset>
		<?
	}

?>