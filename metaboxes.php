<?php

	
	/* Fire our meta box setup function on the post editor screen. */
	add_action( 'load-page.php', 'mini_archive_meta_boxes_setup' );
	add_action( 'load-page-new.php', 'mini_archive_meta_boxes_setup' );
	
	add_action('wp_ajax_mini_archive_get_terms', 'mini_archive_ajax_get_terms');
	
	function mini_archive_ajax_get_terms(){
		if(isset($_POST['taxonomy']) && $_POST['taxonomy']!=""){
			mini_archive_draw_query($_POST['taxonomy'],get_terms($_POST['taxonomy']));
		}
		die();
	}
	
	function mini_archive_meta_boxes_setup(){
		add_action( 'admin_head','mini_archive_admin_head');
		add_action( 'add_meta_boxes', 'mini_archive_meta_boxes' );
		
		add_action( 'save_post', 'mini_archive_save', 10, 2 );
	}
	
	function mini_archive_admin_head(){
		?>
		<link rel="stylesheet" type="text/css" href="<?=plugins_url( 'mini_archive/assets/css/mini_archive.css');?>" />
		<script src="<?=plugins_url( 'mini_archive/assets/js/mini_archive.js');?>" type="text/javascript"></script>
		<?
	}
	
	function mini_archive_save($post_id,$post){
		$meta_key = 'mini_archive';
		if(!isset($_POST['mini_archive_opt_in']) || !isset($_POST['mini_archive_type'])){
			delete_post_meta( $post_id, $meta_key);
			delete_post_meta( $post_id, 'mini_archive_filters'); // delete all filters
			return true;
		}
		// generate wp-query string to make archive class values
		$new_value = sanitize_html_class( $_POST['mini_archive_type'] );
		update_post_meta($post_id, $meta_key, $new_value);
		
		
		delete_post_meta( $post_id, 'mini_archive_filters'); // delete all filters
		foreach($_POST['mini_archive_filters'] as $filter):
			if($filter['term'] && $filter['term']!=""){
				add_post_meta($post_id, 'mini_archive_filters', serialize($filter));
			}
		endforeach;
		// read all filters ~ then serialize and save
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
	
	function mini_archive_draw_query($tax,$terms,$query=false){
		?>
		<fieldset class="query">
			<input type="hidden" name="mini_archive_filters[position][type]" value="<?=$tax;?>" />
			<label>Pick a query term</label>
			<select name="mini_archive_filters[position][term]">
				<? foreach($terms as $term):	?>
				<option <? if($query && $query['term']==$term->name){ echo 'selected="selected"'; }?> value="<?=$term->slug;?>"><?=$term->name;?></option>
				<? endforeach; ?>
			</select>
			<label class="checkbox">
				<input type="checkbox" value="NOT IN" name="mini_archive_filters[position][operator]" <? if($query && array_key_exists('operator',$query) && $query['operator']=='NOT IN'){ echo 'checked="checked"'; }?> />
				Not in selected fields
			</label>
			<a href="#" class="remove">Remove</a>
		</fieldset>
		<?
	}
		
	function mini_archive_meta_box($object,$box){
		$archive_value = get_post_meta($object->ID,'mini_archive',true);
		$archive_filters = get_post_meta($object->ID,'mini_archive_filters',false);
		$post_types = get_post_types(Array(),'objects');
		$taxonomies = get_taxonomies(Array(),'objects');
		// add buddypress?
		?>
		<fieldset id="mini_archive">
			<fieldset>
				<input id="mini_archive_opt_in" name="mini_archive_opt_in" value="true" type="checkbox" <? if($archive_value){ echo 'checked="checked"'; } ?> />
				<label for="mini_archive_opt_in"><?=_e("Add an archive to this page.");?></label>
			</fieldset>
			<fieldset>
				<label for="mini_archive_type"><?=_e("Type of content to list");?></label>
				<select id="mini_archive_type" name="mini_archive_type">
					<? foreach($post_types as $post_type):	?>
					<option value="<?=$post_type->name;?>" <? if($archive_value==$post_type->name){ echo 'selected="selected"'; } ?>><?=_e($post_type->label);?></option>
					<? endforeach; ?>
				</select>
				<label for="mini_archive_add_query">
				<select id="mini_archive_add_query">
				<? foreach($taxonomies as $term):	?>
					<option value="<?=$term->name;?>"><?=$term->label;?></option>
				<?	endforeach;	?>
				</select>
				<a id="mini_archive_add_query_button" href="#" class="button">Add Query Type</a>
			</fieldset>
			<fieldset id="mini_archive_queries" class="queries">
				<legend>Queries</legend>
				<? foreach($archive_filters as $filter):
					$filter = unserialize($filter);
					mini_archive_draw_query($filter['type'],get_terms($filter['type']),$filter);
				endforeach;	?>
			</fieldset>
		</fieldset>
		<?
	}

?>