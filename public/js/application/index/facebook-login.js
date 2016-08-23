jQuery.facebookLogin = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		$('#facebook-login-btn').click(function(e){
			e.preventDefault();
			window.location = $('#publicar-facebook').is(':checked') > 0 ? $(this).attr('href').replace(/facebook-login%2F[0-1]/, 'facebook-login%2F1') : $(this).attr('href').replace(/facebook-login%2F[0-1]/, 'facebook-login%2F0');
		});
        
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.facebookLogin().__constructor(); });



