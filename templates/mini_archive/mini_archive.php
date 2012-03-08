<div id="mini_archive">
	<?	while($query->have_posts()):	$query->the_post();?>
	
	<?	get_template_part( 'content', $archive_type );	?>
	
	<?	endwhile;	?>
</div><!-- end #min_archive-->