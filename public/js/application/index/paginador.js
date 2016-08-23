jQuery.paginador = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		owner.regraPagina();
		
	}
	
	this.regraPagina = function()
	{
		$('#paginaPaginador').change(function(){
			var selecionado = $(this).find('option:selected').val();
		    if(selecionado >= 1){
		        window.location.href=  $("#linkPaginador").val() + selecionado;
		    }
		})
	}
	
	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.paginador().__constructor(); });



