jQuery.userConteudoAdicionarNovidade = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		CKEDITOR.replace( 'idEditor',{ 
			uiColor: '#DFFFBA'
		});
	}
	

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.userConteudoAdicionarNovidade().__constructor(); });



