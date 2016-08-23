jQuery.applicationIndexInfo= function( param ){
	
	var owner = this;
	var ultimo = null;

	/** Construtor da classe */
	this.__constructor = function(){
		
		$('h2').click(function(){
			var atual = $(this).next().attr('id');
			$('#' + atual).fadeIn();
			if(ultimo != null){
				$('#' + ultimo).fadeOut()
			}
			ultimo = atual;
		});
		
	}
	

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.applicationIndexInfo().__constructor(); });



