<fieldset class="query">
	<?	if(count($terms)>0):	?>
	<input type="hidden" name="mini_archive_filters[position][type]" value="<?=$tax;?>" />
	<label>Pick a query term</label>
	<select name="mini_archive_filters[position][term]">
		<? foreach($terms as $term):	?>
		<option <? if($query && $query['term']==$term->slug){ echo 'selected="selected"'; }?> value="<?=$term->slug;?>"><?=$term->name;?></option>
		<? endforeach; ?>
	</select>
	<label class="checkbox">
		<input type="checkbox" value="NOT IN" name="mini_archive_filters[position][operator]" <? if($query && array_key_exists('operator',$query) && $query['operator']=='NOT IN'){ echo 'checked="checked"'; }?> />
		Not in selected fields
	</label>
	<a href="#" class="remove">Remove</a>
	<?	else:	?>
	<p>Nothing to query</p>
	<?	endif;	?>
</fieldset>