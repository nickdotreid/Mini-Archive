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

function mini_archive_get_query($ID=false){
	$ID = mini_archive_on_page($ID);
	if(!$ID){
		return false;
	}
	
	$query = new WP_Query(array(
		"post_type"=>get_post_meta($ID,'mini_archive',true),
		"post_status"=>'publish',
		"order_by" => 'menu_order date title',
		"tax_query" => mini_archive_get_tax_query($ID),
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

function mini_archive_bp_filter_group($archive_ID){
	$filters = get_post_meta($archive_ID,'mini_archive_filters',false);
	foreach($filters as $filter){
		$filter = unserialize($filter);
		if($filter['type'] == 'bp_group_children'):
			if(bp_get_group_id()==$filter['term']){
				return true;
			}
		endif;
	}
	return false;
}

?>