<?php

add_action('get_footer','mini_archive_draw',10);
function mini_archive_draw(){
	global $wp_query;
	$old_query = $wp_query; // stash original query
	if(is_page()){
		$archive_value = get_post_meta(get_the_ID(),'mini_archive',true);
		if($archive_value){
			?>
			<aside class="mini_archive">
			<?
			$tax_query = array('relation'=>'AND');
			$filters = get_post_meta(get_the_ID(),'mini_archive_filters',false);
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
			$wp_query = new WP_Query(array(
				"post_type"=>$archive_value,
				"post_status"=>'publish',
				"order_by" => 'menu_order date title',
				"tax_query" => $tax_query
			));
			get_template_part( 'loop', $archive_value );
			?>
			</aside><!-- .mini-archive -->
			<?
		}
	}
	$wp_query = $old_query;
}

?>