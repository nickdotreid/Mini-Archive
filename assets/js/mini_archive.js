jQuery(document).ready(function(){
	jQuery("#mini_archive").delegate("#mini_archive_add_query_button","click",function(event){
		event.preventDefault();
		jQuery.ajax({
			url:ajaxurl,
			type:"POST",
			data:{
				action:"mini_archive_get_terms",
				taxonomy:jQuery("#mini_archive_add_query option:selected").val(),
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