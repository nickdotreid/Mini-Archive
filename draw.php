<?php

include_once('functions.php');

add_action('get_footer','mini_archive_draw',10);
function mini_archive_draw(){
	global $wp_query;
	if(is_page() && mini_archive_on_page()){
			$archive_type = get_post_meta(get_the_ID(),'mini_archive',true);
			if($archive_type=='members'):
				$members = mini_archive_get_members();
				foreach($members as $member):
					?>
					<article class="member">
						<?=$member->user_nicename;?>
					</article>
					<?
				endforeach;
			else:
				$query = mini_archive_get_query(get_the_ID());
				if($query):
					$locations = array(
						'/templates/mini_archive/'.$archive_type.'.php',
						'/mini_archive/'.$archive_type.'.php',
						'/mini_archive-'.$archive_type.'.php',
						'/templates/archive/'.$archive_type.'.php',
						'/archive/'.$archive_type.'.php',
						'/archive-'.$archive_type.'.php',
						'/templates/mini_archive/loop.php',
						'/mini_archive/loop.php',
						'/mini_archive-loop.php',
						'/mini_archive.php',
						'/templates/archive/loop.php',
						'/archive/loop.php',
						'/archive-loop.php',
						'/archive.php',
					);
					$template = locate_template( $locations );
					if($template==""):
						include MINI_ARCHIVE_PLUGIN_DIR.'/templates/mini_archive/mini_archive.php';
					else:
						$original_query = $wp_query;
						$wp_query = $query;
						include $template;
						$wp_query = $original_query;
						wp_reset_postdata();
					endif;
				endif;
			endif;
	}
}

?>