<option <? if($query && $query['term']==$term->slug){ echo 'selected="selected"'; }?> value="<?=$term->slug;?>"><?if($child){	echo "-";	}?><?=$term->name;?> (<?=$term->slug;?>)</option>
<?
$children = get_terms($term->taxonomy,array("parent"=>$term->term_id,'hide_empty'=>false));
foreach($children as $term){
	$child = true;
	include("query-item.php");
}
?>