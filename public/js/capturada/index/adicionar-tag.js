jQuery.capturadaIndexAdicionarTag = function( param ){
	
	var owner = this;
	var enviar = false;
	var mostrarModal = true;
	var focusOut = 0;

	/** Construtor da classe */
    this.__constructor = function()
    {
    	$('#editarCapturada').button();
    	owner.regrasTeclado();
        owner.regraForm();	
	    owner.regraFocusOut();
	}
    
    /**
     * Método que retorna a regra do formulário
     */
    this.regraForm = function()
    {
    	$('form').submit( function(ev){
    		ev.preventDefault();
    		if(enviar == true){
    			mostrarModal = false;
    			this.submit();
    		} 
    	});
    }
    
    /**
     * Método que retorna a regra do teclado
     */
	this.regrasTeclado = function()
	{
		$('input[name="nome"]').keypress(function (e) {
			if (e.which == 13) {
				mostrarModal = true;
				$('input[name="nome"]').trigger('focusout');
			}
		});
	}
	
	/**
	 * Método que retorna a regra de quando sair do campo de tag
	 */
	this.regraFocusOut = function()
	{
		$('input[name="nome"]').focusout(function(e){
			e.preventDefault();
			if(focusOut == 0){
				owner.mostrarModal();		    
			}
		});
	}
	
	/**
	 * Método que retorna a regra do campo modal 
	 */
	this.mostrarModal = function()
	{
		if(mostrarModal == true) {
			focusOut++;
			$('#tag').html($('input[name="nome"]').val());
	    	$("#dialog").dialog({
				resizable: false,
				height: 200,
				width: 400,
				modal: true,
				buttons: {
					"Sim": function() {
						enviar = true;
						$('form#adicionar-tag').submit();		
					},
					"Não": function() {
						$(this).dialog("close");
						focusOut = 0;
					}
				}
			});
		}
	}
	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.capturadaIndexAdicionarTag().__constructor(); });