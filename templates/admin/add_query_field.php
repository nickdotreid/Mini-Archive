<div id="mini_archive_add_query" class="form_row">
	<label for="mini_archive_add_query"><?_e("Pick a relation ship type to query");?></label>
	<select id="mini_archive_add_query">
	<? foreach($relationships as $term):	?>
		<option value="<?=$term->name;?>"><?=$term->label;?></option>
	<?	endforeach;	?>
	</select>
	<a id="mini_archive_add_query_button" href="#" class="button"><?=_e("Add Query Type");?></a>
</div>