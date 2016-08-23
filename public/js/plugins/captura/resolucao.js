jQuery.resolucao = function( ){
	
	var owner = this;
        
	/** Construtor da classe */
	this.__constructor = function(){
		
		$.ajax({
			type : 'POST',
			url: "/resolucao",
			dataType: "JSON",
			data: {
				width : window.outerWidth,
				height : window.outerHeight,
				innerWidth : window.innerWidth,
				innerHeight : window.innerHeight
			},
			success: function( data ) { },
		});
		
		
	}
	
               
	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$('body').ready(function(){ $.resolucao().__constructor(); });