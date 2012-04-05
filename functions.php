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
		"tax_query" => mini_archive_get_tax_query($ID),
	);
	$query = new WP_Query($args);
	return $query;
}

function mini_archive_get_tax_query($ID){
	$tax_query = array('relation'=>'AND');
	$filters = mini_archive_get_filters($ID);
	foreach($filters as $filter){
		$query = array(
			'taxonomy' => $filter['type'],
			'field' => 'slug',
			'terms' => $filter['term'],
		);
		if(array_key_exists('operator',$filter)){
			$query['operator'] = $filter['operator'];
		}
		array_push($tax_query,$query);
	}
	return array_merge($tax_query,mini_archive_get_url_vars());
}

function mini_archive_get_url_vars(){
	$tax_query = array();
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
	return $tax_query;
}

function mini_archive_get_members($ID=false){
	$ID = mini_archive_on_page($ID);
	if(!$ID){
		return false;
	}
	$members = array();
	$filters = get_post_meta($ID,'mini_archive_filters',false);
	foreach($filters as $filter):
		$filter = unserialize($filter);
		if($filter['type']=='groups'):
			bp_group_has_members("group_id=".$filter['term']."&exclude_admins_mods=false&per_page=100");
			while(bp_group_members()):
				bp_group_the_member();
				$user = new WP_User(bp_get_group_member_id());
				array_push($members,$user);
			endwhile;
		endif;
	endforeach;
	return $members;
}

?>