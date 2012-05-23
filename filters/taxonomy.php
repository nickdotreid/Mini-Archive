<?php

add_filter('mini_archive_filter_query_type_list','mini_archive_taxonomy_relationships',10,2);
function mini_archive_taxonomy_relationships($relationships,$post_type){
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
	return $relationships;
}

add_filter('mini_archive_filter_query_object','mini_archive_taxonomy_objects',10,2);
function mini_archive_taxonomy_objects($objects,$query){
	extract($query);
	if($type == 'taxonomy'){
		$terms = get_terms($term,array(
			"hide_empty" => false,
		));
		foreach($terms as $t){
			$objects[] = (object) array(
				'slug' => $t->slug,
				'name' => $t->name,
			);
		}
	}
	return $objects;
}

add_filter('mini_archive_filter_query','mini_archive_taxonomy_filter_query',10,2);
function mini_archive_taxonomy_filter_query($args,$filter){
	if($filter['type'] == 'taxonomy'){
		$tax_query = array('relation'=>'AND');
		if(isset($args['tax_query'])){
			$tax_query = $args['tax_query'];
		}
		$query = array(
			'taxonomy' => $filter['term'],
			'field' => 'slug',
			'terms' => $filter['value'],
		);
		if(array_key_exists('operator',$filter)){
			$query['operator'] = $filter['operator'];
		}
		array_push($tax_query,$query);
		$args['tax_query'] = $tax_query;
		return $args;
	}
	return $args;
}

?>