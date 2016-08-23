jQuery.userUserEditarUsuario = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		$('input[name=cpf]').focus(function(){
    	    $('input[name=cpf]').mask("999.999.999-99");
	    });
		
		$('input[name=telefone]').focus(function(){
    	    $('input[name=telefone]').mask("(99) 9999 9999");
	    });
		
	}
	

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.userUserEditarUsuario().__constructor(); });



