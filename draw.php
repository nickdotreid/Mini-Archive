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
			$wp_query = new WP_Query( 'post_type='.$archive_value );
			get_template_part( 'loop', $archive_value );
			?>
			</aside>
			<?
		}
	}
	$wp_query = $old_query;
}

?>