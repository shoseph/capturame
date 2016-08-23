jQuery.capturadaAdicionarTag = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function()
	{
		owner.adicionarTag();
	}
	
	/**
	 * Método que executa o modal de adicionar uma nova tag
	 */
	this.adicionarTag = function()
	{
		$('#adicionarTag').click(function(){
			$("#addTag").dialog({
				resizable: false,
				height: 200,
				width: 350,
				modal: true,
				buttons:{
					"Sim": function() {
						if($('#nomeTag').val().trim() != ''){
							$('#nomeTag').val($('#nomeTag').val().trim());
							$('form#adicionar-tag').submit();
						} else {
							$('#nomeTag').val($('#nomeTag').val().trim());
							$('#nomeTag').trigger('mouseover');
						}
					},
					"Fechar": function() {
						$(this).dialog("close");
					}
				}
			});
		});
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.capturadaAdicionarTag().__constructor(); });



