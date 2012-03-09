<fieldset id="mini_archive">
	<fieldset>
		<input id="mini_archive_opt_in" name="mini_archive_opt_in" value="true" type="checkbox" <? if($archive_value){ echo 'checked="checked"'; } ?> />
		<label for="mini_archive_opt_in"><?=_e("Add an archive to this page.");?></label>
	</fieldset>
	<fieldset>
		<label for="mini_archive_type"><?=_e("Type of content to list");?></label>
		<select id="mini_archive_type" name="mini_archive_type">
			<? foreach($post_types as $post_type):	?>
			<option value="<?=$post_type->name;?>" <? if($archive_value==$post_type->name){ echo 'selected="selected"'; } ?>><?=_e($post_type->label);?></option>
			<? endforeach; ?>
		</select>
		<label for="mini_archive_add_query">
		<select id="mini_archive_add_query">
		<? foreach($taxonomies as $term):	?>
			<option value="<?=$term->name;?>"><?=$term->label;?></option>
		<?	endforeach;	?>
		</select>
		<a id="mini_archive_add_query_button" href="#" class="button"><?=_e("Add Query Type");?></a>
	</fieldset>
	<fieldset id="mini_archive_queries" class="queries">
		<legend><?=_e('Queries');?></legend>
		<? foreach($archive_filters as $filter):
			$filter = unserialize($filter);
			mini_archive_draw_query($filter['type'],$filter);
		endforeach;	?>
	</fieldset>
</fieldset>