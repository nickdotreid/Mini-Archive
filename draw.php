<?php

include_once('functions.php');

add_action('get_footer','mini_archive_draw',10);
function mini_archive_draw(){
	if(is_page() && mini_archive_on_page()){
			?>
			<aside class="mini_archive">
			<?
			$archive_type = get_post_meta(get_the_ID(),'mini_archive',true);
			$query = mini_archive_get_query();
			do_action('mini_archive_before');
			while($query->have_posts()) {
				$query->the_post();
				get_template_part( 'list', $archive_type );
			}
			do_action('mini_archive_after');	
			?>
			</aside><!-- .mini-archive -->
			<?
	}
}

?>