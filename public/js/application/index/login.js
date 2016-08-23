jQuery.applicationIndexlogin = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		
		$('#entrar').click(function(){
			$('#submitbutton').trigger('click');
		});
		
		$('#login-captura').bind('keyup', function(e){
		    if(e.keyCode == 13 && $('#senha').val().length > 3 && $('#login').val().length > 3 ){
		    	e.preventDefault();
		        $('#submitbutton').trigger('click');
		    }
		});
		
	}
	

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.applicationIndexlogin().__constructor(); });