<fieldset class="query">
	<?	if(count($objects)>0):	?>
	<input type="hidden" name="mini_archive_filters[position][type]" value="<?=$type;?>" />
	<input type="hidden" name="mini_archive_filters[position][term]" value="<?=$term;?>" />
	<?	if(isset($direction)):	?>
	<input type="hidden" name="mini_archive_filters[position][direction]" value="<?=$direction;?>" />
	<?	endif;	?>
	<label>Pick a query term</label>
	<select name="mini_archive_filters[position][value]">
		<? foreach($objects as $object):
			$child = false;
			include "query-item.php";
		endforeach;	?>
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