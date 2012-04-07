<option <?
	if($query && $query['value']==$object->slug){ echo 'selected="selected"'; }
	?> value="<?=$object->slug;?>"><?if($child){	echo "-";	}?><?=$object->name;?> (<?=$object->slug;?>)</option>