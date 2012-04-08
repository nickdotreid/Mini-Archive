<?php

function mini_archive_on_page($ID=false){
	if(!$ID){
		$ID = get_the_ID();
	}
	$archive = get_post_meta($ID,'mini_archive',true);
	if($archive){
		return $archive;
	}
	return false;
}

function mini_archive_get_filters($ID=false){
	if(!$ID){
		$ID = get_the_ID();
	}
	$filters = get_post_meta($ID,'mini_archive_filters',false);
	$unserialized_filters = array();
	foreach($filters as $filter){
		array_push($unserialized_filters,unserialize($filter));
	}
	return $unserialized_filters;
}

function mini_archive_get_query($ID=false){
	if(!$ID){
		$ID = get_the_ID();
	}
	$archive_type = mini_archive_on_page($ID);
	if(!$archive_type){
		return false;
	}
	$args = array(
		"post_type"=>get_post_meta($ID,'mini_archive',true),
		"post_status"=>'publish',
		"order_by" => 'menu_order date title',
	);
	$filters = mini_archive_get_filters($ID);
	foreach($filters as $filter){
		$args = apply_filters('mini_archive_filter_query',$args,$filter);
	}
	$args = mini_archive_filter_by_url_vars($args);
	$query = new WP_Query($args);
	return $query;
}

function mini_archive_filter_by_url_vars($args){
	$tax_query = array('relation'=>'AND');
	if(isset($args['tax_query'])){
		$tax_query = $args['tax_query'];
	}
	$get_keys = array_keys($_GET);
	foreach($get_keys as $key){
		if(taxonomy_exists($key)){
			$term = get_term_by("slug",$_GET[$key],$key);
			if($term){
				array_push($tax_query,array(
					'taxonomy' => $term->taxonomy,
					'field' => 'slug',
					'terms' => $term->slug,
				));
			}
		}
	}
	$args['tax_query'] = $tax_query;
	return $args;
}

function mini_archive_get_users($ID=false){
	if(!$ID){
		$ID = get_the_ID();
	}
	$archive_type = mini_archive_on_page($ID);
	if(!$archive_type){
		return false;
	}
	$args = array();
	$filters = mini_archive_get_filters($ID);
	foreach($filters as $filter):
		$args = apply_filters('mini_archive_filter_user_query',$args,$filter);
	endforeach;
	return get_users($args);
}

?>