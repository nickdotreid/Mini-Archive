<?php

// check if post2post is active ~ else fail

add_filter('mini_archive_filter_query_type_list','mini_archive_post2post_relationships',10,2);
function mini_archive_post2post_relationships($relationships,$post_type){
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
	return $relationships;
}

add_filter('mini_archive_filter_query_object','mini_archive_post2post_objects',10,2);
function mini_archive_post2post_objects($objects,$query){
	extract($query);
	if($type == 'post2post' && isset($direction)){
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
	return $objects;
}

add_filter('mini_archive_filter_query','mini_archive_post2post_filter_query',10,2);
function mini_archive_post2post_filter_query($args,$filter){
	if($filter['type'] == 'post2post'){
		return array_merge($args,array(
			'connected_type' => $filter['term'],
			'connected_items' => $filter['value'],
			));
	}
	return $args;
}

add_filter('mini_archive_filter_user_query','mini_archive_post2post_filter_user_query',10,2);
function mini_archive_post2post_filter_user_query($args,$filter){
	if($filter['type'] == 'post2post'){
		$args = array_merge($args,array(
		  'connected_type' => $filter['term'],
		  'connected_items' => $filter['value'],
		));
	}
	return $args;
}

?>