<?php

include_once('functions.php');

add_action('get_footer','mini_archive_draw',10);
function mini_archive_draw(){
	if(is_page() && mini_archive_on_page()){
			?>
			<aside class="mini_archive">
			<?
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
				$query = mini_archive_get_query();
				while($query->have_posts()) {
					$query->the_post();
					get_template_part( 'content', $archive_type );
				}	
			endif;
			?>
			</aside><!-- .mini-archive -->
			<?
	}
}

?>