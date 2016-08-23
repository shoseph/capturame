jQuery.capturadaIndexVisualizar = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
//		owner.botoes();
//		owner.botaoEditar();
//		owner.pegou();
//		owner.download();
	}

	/**
	 * Método que controla a ação do teclado ao passar de pasta de imagens
	 */
	this.botoes = function(){
		
		$("body").keydown(function(e) {
			if(e.keyCode == 37 && $('#setaEsq').length != 0 ){
				$('#setaEsq')[0].click();
			} 
			if(e.keyCode == 39 && $('#setaDir').length != 0 ){
				$('#setaDir')[0].click();
			} 
		});
		
	}

	/**
	 * Método que executa a ação de editar uma capturada
	 */
	this.botaoEditar = function(){
		$('#editarCapturada').click(function(){
			window.location = "/editar-capturada/" + $('#user').val()  + '/' + $('#capturada').val();
		});
	}
	
	this.pegou = function()
	{
		$('.btnPegou').click(function(){
			if(/pegou/.test($('.btnPegou').attr('class')) == false){
				$.ajax({
	        	    url : '/peguei-imagem-capturada/',
	        	    type: "POST",
	        	    dataType : "json",
	        	    data:{
						capturada: $('#capturada').val(),
						usuario: $('#usuario').val()
					},
	        	    success : function(data){
	        	    	if(data.success){
	        	    		$('#quantidadePeguei').html( $('#quantidadePeguei').html() ? parseInt($('#quantidadePeguei').html()) + 1 : 1);
	        	    		$('.btnPegou').attr('class','pegou');
	        	    	} 
	        	    }
	        	});
			} else {
				$("#dialog").dialog({
					resizable: false,
					height: 200,
					width: 500,
					modal: true,
					buttons: {
						"Fechar": function() {
							$(this).dialog("close");	
						}
					}
				});
			}

		});
	}
	
	this.download = function()
	{
		$('#download').click(function(){
			$.fileDownload(/download-capturada/, {
				httpMethod : 'POST',
				data:{
					capturada: $('#capturada').val(),
					usuario: $('#usuario').val()
				},
			});
		});
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.capturadaIndexVisualizar().__constructor(); });



