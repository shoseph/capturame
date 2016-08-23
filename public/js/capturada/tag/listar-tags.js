jQuery.capturadaTagListarTags = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
			
		$('.titulo').hover(function(){
		   $(this).next().css('background', 'none repeat scroll 0 0 #94C6C8');
		   $(this).next().css('border', 'none');
		});

		$('.titulo').mouseout(function(){
		   $(this).next().css('background', 'none repeat scroll 0 0 #b5c5d6');
		   $(this).next().css('border', 'none');
		});
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.capturadaTagListarTags().__constructor(); });



