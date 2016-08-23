jQuery.msg = function( param ){
	
	var owner = this;
	var ultimo = null;
	var local = '#direita';

	/** Construtor da classe */
	this.__constructor = function(){
		owner.regraMensagem();
	}
	
	this.verificaMudouMensagem = function(){
		if($('#mensagens').html()){
			$('#mensagens').delay( 1000 ).fadeOut();
		}
	}
	this.regraMensagem = function(){
		$('button, a').click(function(event){
			setTimeout(owner.verificaMudouMensagem, 1000);
		});
		if($('#mensagens').html()){
			setTimeout(owner.verificaMudouMensagem, 1000);
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
		$(local).find('#mensagens').length > 0 ? $('#mensagens').html('<div class="' + tipo + '">' + msg + '</div>') : $(local).append('<div id="mensagens"><div class="' + tipo + '">' + msg + '</div></div>');
		$('#mensagens').fadeIn('slow').delay( 1400 ).fadeOut();
		return true;
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.msg().__constructor(); });



