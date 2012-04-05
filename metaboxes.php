<?php

	
	/* Fire our meta box setup function on the post editor screen. */
	add_action( 'load-page.php', 'mini_archive_meta_boxes_setup' );
	add_action( 'load-page-new.php', 'mini_archive_meta_boxes_setup' );
	
	add_action('wp_ajax_mini_archive_get_terms', 'mini_archive_ajax_get_terms');
	
	function mini_archive_ajax_get_terms(){
		if(isset($_POST['taxonomy']) && $_POST['taxonomy']!=""){
			mini_archive_draw_query($_POST['taxonomy']);
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
		if(!isset($_POST['mini_archive_filters'])){
			return true;
		}
		foreach($_POST['mini_archive_filters'] as $filter):
			if($filter['term'] && $filter['term']!=""){
				add_post_meta($post_id, 'mini_archive_filters', serialize($filter));
			}
		endforeach;
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
	
	function mini_archive_draw_query($tax,$query=false){
		$terms = get_terms($tax);
		
		$locations = array(
			'/templates/mini_archive/admin/query.php',
			'/mini_archive/admin/query.php',
			'/admin/mini_archive_query.php',
		);
		if(locate_template( $locations )==""):
			include MINI_ARCHIVE_PLUGIN_DIR.'/templates/admin/query.php';
		endif;
	}
	
	function mini_archive_get_post_types(){
		$post_types = get_post_types(Array(),'objects');
		array_push($post_types,(object) array(
			"name"=>"users",
			"label"=>"Users"
		));
		return $post_types;
	}
	
	function mini_archive_get_taxonomies(){
		$taxonomies = get_taxonomies(Array(),'objects');
		return $taxonomies;
	}
	
	function mini_archive_meta_box($object,$box){
		$archive_value = get_post_meta($object->ID,'mini_archive',true);
		$archive_filters = get_post_meta($object->ID,'mini_archive_filters',false);
		
		$post_types = mini_archive_get_post_types();
		$taxonomies = mini_archive_get_taxonomies();
		
		$locations = array(
			'/templates/mini_archive/admin/metabox.php',
			'/mini_archive/admin/metabox.php',
			'/admin/mini_archive_metabox.php',
		);
		if(locate_template( $locations )==""):
			include MINI_ARCHIVE_PLUGIN_DIR.'/templates/admin/metabox.php';
		endif;
	}

?>