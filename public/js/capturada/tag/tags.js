jQuery.capturadaTags = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function()
	{
		owner.autoCompleateTag();
		owner.removeTag();
	}
	
	this.removeTag = function()
	{
		$('.removeTag').click(function(){
			
			var removerTag = $(this).parent();
			var idRemover = $(this).attr('id');
			var idCapturada = $('#capt').val();
			$.ajax({
				type : 'POST',
				url: "/desvincular-tag",
				dataType: "JSON",
				data: {
					capturada : idCapturada,
					tag : idRemover
				},
				success: function( data ) {
					if(data.success){
						$(removerTag).remove();
					}
				},
			});
		});
		
	}
	
	/**
	 * Método que faz com que o campo seja auto completável
	 */
	this.autoCompleateTag = function()
	{
	    $( "#nomeTag" ).autocomplete({
	    	source: function( request, response ) {
	    		$.ajax({
	    			type : 'POST',
	    			url: "/get-tag",
	    			dataType: "JSON",
	    			data: { nome : $( "#nomeTag" ).val() },
	    			success: function( data ) {
	    				if(data.success){
	    					response($.map( data.data, function( item ) {
	    						return {label: item.nome, value: item.nome, id: item.id_tag}
	    					}));
	    				}
	    			},
	    		});
	    	},
	    	minLength: 2,
	    	search: function( event, ui ) {
	    		$('#idTag').val('');
	    	},
	    	select: function( event, ui ) {
	    		$('#idTag').val(ui.item.id);
	    	},
	    });
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.capturadaTags().__constructor(); });



