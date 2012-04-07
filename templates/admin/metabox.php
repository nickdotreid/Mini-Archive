<fieldset id="mini_archive">
	<fieldset>
		<div class="form_row">
			<input id="mini_archive_opt_in" name="mini_archive_opt_in" value="true" type="checkbox" <? if($archive_active){ echo 'checked="checked"'; } ?> />
			<label for="mini_archive_opt_in"><?=_e("Add an archive to this page.");?></label>
		</div>
	</fieldset>
	<fieldset>
		<div id="mini_archive_pick_post_type" class="form_row">
			<label for="mini_archive_type"><?=_e("Type of content to list");?></label>
			<select id="mini_archive_type" name="mini_archive_type">
				<? foreach($post_types as $post_type):	?>
				<option value="<?=$post_type->name;?>" <? if($archive_value==$post_type->name){ echo 'selected="selected"'; } ?>><?=_e($post_type->label);?></option>
				<? endforeach; ?>
			</select>
		</div>
		<?	if($archive_value):	?>
		<?	mini_archive_draw_add_query_field($archive_value);	?>
		<?	endif;	?>
	</fieldset>
	<fieldset id="mini_archive_queries" class="queries">
		<legend><?=_e('Queries');?></legend>
		<? foreach($archive_filters as $filter):
			$filter = unserialize($filter);
			mini_archive_draw_query($filter['type'],$filter);
		endforeach;	?>
	</fieldset>
</fieldset>