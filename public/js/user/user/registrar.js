jQuery.userUserRegistrar = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		$('input[name=cpf]').focus(function(){
    	    $('input[name=cpf]').mask("999.999.999-99");
	    });
		
	}
	

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.userUserRegistrar().__constructor(); });



