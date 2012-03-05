<?php

function mini_archive_on_page($ID=false){
	if(!$ID){
		$ID = get_the_ID();
	}
	$archive = get_post_meta($ID,'mini_archive',true);
	if($archive){
		return $ID;
	}
	return false;
}

function mini_archive_get_query($ID=false,$posts_per_page=-1,$paged=1){
	$ID = mini_archive_on_page($ID);
	if(!$ID){
		return false;
	}
	$query = new WP_Query(array(
		"post_type"=>get_post_meta($ID,'mini_archive',true),
		"post_status"=>'publish',
		"order_by" => 'menu_order date title',
		"tax_query" => mini_archive_get_tax_query($ID),
		"posts_per_page" => $posts_per_page,
		"paged" => $paged
	));
	return $query;
}

function mini_archive_get_tax_query($ID){
	$tax_query = array('relation'=>'AND');
	$filters = get_post_meta($ID,'mini_archive_filters',false);
	foreach($filters as $filter){
		$filter = unserialize($filter);
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
	
	return $tax_query;
}

?>