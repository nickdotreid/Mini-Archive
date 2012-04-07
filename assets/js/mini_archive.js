jQuery(document).ready(function(){
	jQuery("#mini_archive").delegate("#mini_archive_add_query_button","click",function(event){
		event.preventDefault();
		var selected_obj = jQuery("#mini_archive_add_query option:selected");
		
		jQuery.ajax({
			url:ajaxurl,
			type:"POST",
			data:{
				action:"mini_archive_get_terms",
				type:selected_obj.attr("connection_type"),
				term:selected_obj.val(),
				direction:selected_obj.attr("direction")
			},
			success:function(data){
				if(data!=""){
					jQuery("#mini_archive_queries").append(data);
				}
			}
		})
	}).delegate("#mini_archive_queries .query .remove","click",function(event){
		event.preventDefault();
		jQuery(this).parents(".query:first").remove();
	}).delegate("#mini_archive_type").change(function(event){
		if(event.target != jQuery("#mini_archive_type")[0]) return true;
		jQuery("#mini_archive_add_query").remove();
		jQuery("#mini_archive_queries .query").remove();
		jQuery.ajax({
			url:ajaxurl,
			type:"POST",
			data:{
				action:"mini_archive_get_relations",
				post_type:jQuery("#mini_archive_type option:selected").val(),
			},
			success:function(data){
				if(data!=""){
					jQuery("#mini_archive_pick_post_type").after(data);
				}
			}
		})
	});
	
	jQuery("#post").submit(function(event){
		jQuery("#mini_archive_queries .query").each(function(index){
			var number = index;
			var query = jQuery(this);
			jQuery("input,select",query).each(function(){
				this.name = this.name.replace('position',number);
			});
		});
	});
});