jQuery.msgSobreposta = function( param ){
	
	var owner = this;
	var ultimo = null;
	var local = 'body';

	/** Construtor da classe */
	this.__constructor = function(){
		owner.regraMensagem();
	}
	
	this.verificaMudouMensagem = function(){
		if($('#mensagensSobrepostas').html()){
			$('#mensagensSobrepostas').delay( 500 ).fadeOut();
		}
	}
	this.regraMensagem = function(){
		$('button, a').click(function(event){
			setTimeout(owner.verificaMudouMensagem, 500);
		});
		if($('#mensagensSobrepostas').html()){
			setTimeout(owner.verificaMudouMensagem, 500);
		}
		
	}
	
	/**
	 * Método loga uma mensagem de informação
	 */
	this.info = function(msg)
	{
		return owner.printMsg(local, 'msgInfo', msg)
	}
	
	/**
	 * Método loga uma mensagem de alerta
	 */
	this.alerta = function(msg)
	{
		return owner.printMsg('msgAlerta', msg)
	}
	
	/**
	 * Método loga uma mensagem de sucesso
	 */
	this.sucesso = function(msg)
	{
		return owner.printMsg('msgSucesso', msg)
	}
	
	
	/**
	 * Método loga uma mensagem de falha
	 */
	this.falha = function(msg)
	{
		return owner.printMsg('msgFalha', msg)
	}
	
	/**
	 * Método que faz ação de logar algo na tela
	 */
	this.printMsg = function(tipo, msg)
	{
		$(local).find('#mensagensSobrepostas').length > 0 ? $('#mensagensSobrepostas').html('<div class="' + tipo + '">' + msg + '</div>') : $(local).append('<div id="mensagensSobrepostas"><div class="' + tipo + '">' + msg + '</div></div>');
		$('#mensagensSobrepostas').fadeIn('slow').delay( 1000 ).fadeOut();
		return true;
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.msgSobreposta().__constructor(); });



