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
			elseif($archive_type="bp_group"):
				$filters = get_post_meta(get_the_ID(),'mini_archive_filters',false);
				if(bp_has_groups(array(
					"type" => "alphabetical",
				))):
					while(bp_groups()){
						bp_the_group();
						$add = true;
						foreach($filters as $filter){
							$filter = unserialize($filter);
							if($filter['type'] == 'bp_groups'):
								if(bp_get_group_id()==$filter['term']){
									$add = true;
								}else{
									$add = false;
								}
							endif;
						}
						if($add){
							include MINI_ARCHIVE_PLUGIN_DIR.'/templates/buddypress/list-group.php';	
						}
					}
				endif;
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