jQuery.capturadaCadastrarBatalha = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		// Adicionando datepiker das datas início e fim
		$('input[name="dtInicio"]').datepicker();
		$('input[name="dtFim"]').datepicker();
	}
	

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.capturadaCadastrarBatalha().__constructor(); });



