<?php
	
	add_action('wp_ajax_mini_archive_get_terms', 'mini_archive_ajax_get_terms');
	function mini_archive_ajax_get_terms(){
		if(isset($_POST['type']) && $_POST['type']!=""){
			mini_archive_draw_query($_POST);
		}
		die();
	}
	
	add_action('wp_ajax_mini_archive_get_relations', 'mini_archive_ajax_get_relations');
	function mini_archive_ajax_get_relations(){
		if(isset($_POST['post_type']) && $_POST['post_type']!=""){
			mini_archive_draw_add_query_field($_POST['post_type']);
		}
		die();
	}
	
	function mini_archive_draw_add_query_field($post_type){
		$relationships = array();
		
		$p2p_relations = P2P_Connection_Type_Factory::get_all_instances();
		foreach($p2p_relations as $p2p){
			$add = array();
			if($post_type == "user"){
				foreach($p2p->object as $side => $obj){
					if($obj == $post_type){
						$add[$side] = true;
					}
				}
			}
			foreach($p2p->side as $side => $obj){
				if(isset($obj->post_type) && is_array($obj->post_type)){
					foreach($obj->post_type as $pt){
						if($pt == $post_type){
							$add[$side] = true;
						}
					}
				}
			}
			foreach($add as $side => $val){
				$other_side = "from";
				if($side == "from"){
					$other_side = "to";
				}
				$relationships[] = (object) array(
					"name" => $p2p->name,
					"label" => $p2p->title[$side],
					"type" => "post2post",
					"direction" => $other_side,
				);
			}
		}
		
		$taxonomies = get_object_taxonomies( $post_type );
		if($taxonomies || count($taxonomies)>0 ){
			foreach($taxonomies as $taxonomy){
				$tax = get_taxonomy($taxonomy);
				$relationships[] = (object) array(
					"name" => $tax->name,
					"label" => $tax->label,
					"type" => "taxonomy",
				);
			}
		}
		
		$template_path = MINI_ARCHIVE_PLUGIN_DIR.'/templates/admin/add_query_field.php';
		if(file_exists($template_path)) include $template_path;
	}
	
	add_action( 'load-page-new.php', 'mini_archive_meta_boxes_setup' );
	add_action( 'load-page.php', 'mini_archive_meta_boxes_setup' );
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
		if(!isset($_POST['mini_archive_opt_in']))	delete_post_meta( $post_id, 'mini_archive_active');
		else update_post_meta($post_id, 'mini_archive_active', true);
		
		if(isset($_POST['mini_archive_type'])){
			// generate wp-query string to make archive class values
			$new_value = sanitize_html_class( $_POST['mini_archive_type'] );
			update_post_meta($post_id, 'mini_archive', $new_value);
		}
		
		
		delete_post_meta( $post_id, 'mini_archive_filters');
		if(isset($_POST['mini_archive_filters'])){
			foreach($_POST['mini_archive_filters'] as $filter):
				if($filter['term'] && $filter['term']!=""){
					add_post_meta($post_id, 'mini_archive_filters', serialize($filter));
				}
			endforeach;
		}
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
	
	function mini_archive_draw_query($query){
		extract($query);
		
		$objects = array();
		
		if($type == 'taxonomy'){
			$terms = get_terms($term);
			foreach($terms as $t){
				$objects[] = (object) array(
					'slug' => $t->slug,
					'name' => $t->name,
				);
			}
		}else if($type == 'post2post' && isset($direction)){
			$connected = p2p_get_connections($term,array(
				'fields' => 'p2p_'.$direction,
			));
			$added_ids = array();
			foreach($connected as $post_id){
				if(!in_array($post_id,$added_ids)){
					$added_ids[] = $post_id;
					$post = get_post($post_id);
					$objects[] = (object) array(
						'slug' => $post->ID,
						'name' => $post->post_title,
					);
				}
			}
		}
		
		$template_path = MINI_ARCHIVE_PLUGIN_DIR.'/templates/admin/query.php';
		if(file_exists($template_path)) include $template_path;
	}
	
	function mini_archive_get_post_types(){
		$post_types = get_post_types(Array(),'objects');
		array_push($post_types,(object) array(
			"name"=>"user",
			"label"=>"Users"
		));
		return $post_types;
	}
	
	function mini_archive_get_taxonomies(){
		$taxonomies = get_taxonomies(Array(),'objects');
		return $taxonomies;
	}
	
	function mini_archive_meta_box($object,$box){
		$archive_active = get_post_meta($object->ID,'mini_archive_active',true);
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