jQuery.applicationIndexSugestao = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		$('#data').datepicker();
//		$('$todayButton1').button();
		// mascarando a hora
		$('input[name="hora"]').focus(function(){
			$('input[name="hora"]').mask('99:99');
		});
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.applicationIndexSugestao().__constructor(); });



