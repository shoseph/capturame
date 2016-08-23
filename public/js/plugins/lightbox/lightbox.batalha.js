$(document).ready(function(){ 
	(function($) {
		
		var LightboxBatalha = (function() {
			
			/**
			 * Método que é executado após carregar os elementos do lightbox.
			 */
			LightboxBatalha.prototype.posBuildEvent = function($lightbox)
			{
				// método que clica na imagem caso for passado o númeo da imagem na url
		        if(indicacaoCapturada){
					var item = indicacaoCapturada > 0 ? indicacaoCapturada -1 : 0;
					$(this.options.appliedElements + ':eq(' + item + ')').click();
				}
		        this.acoesBotoes($lightbox);
			}
			
			LightboxBatalha.prototype.ajaxEnviarVotacao = function($lightbox, $listaImagens, url, tipoDeVotacao )
		    {
		    	if($listaImagens.find('.identitem[value=' + $lightbox.find('.id-item').val() + ']').parent().find('input.voted').length == 0) {
					$.ajax({
			    	    url : url,
			    	    type: "POST",
			    	    dataType : "json",
			    	    data:{
			    	    	batalha: $lightbox.find('.id-item').val()
			    	    },
			    	    success : function(retorno){
			    	    	
			    	    	if(retorno.success){
			    	    		var idItem = $lightbox.find('.id-item').val();
			    	    		var quantidadePegou = 0;
			    	    		var quantidadeNaoPegou = 0;
			    	    		if(tipoDeVotacao != 'pegou')
			    	    		{
			    	    			quantidadePegou = parseInt($lightbox.find('.quantidadePegou').html());
			    	    			quantidadeNaoPegou = parseInt($lightbox.find('.quantidadeNaoPegou').html()) + parseInt(retorno.data.pontos);
			    	    			$lightbox.find('.quantidadeNaoPegou').html(quantidadeNaoPegou);
			    	    			$.msgSobreposta().sucesso('NãoPeguei cadastrado com sucesso!');
			    	    			
			    	    		} else {
			    	    			quantidadePegou = parseInt($lightbox.find('.quantidadePegou').html()) + parseInt(retorno.data.pontos);
				    	    		quantidadeNaoPegou = parseInt($lightbox.find('.quantidadeNaoPegou').html());
				    	    		$lightbox.find('.quantidadePegou').html(quantidadePegou);
				    	    		$.msgSobreposta().sucesso('Peguei cadastrado com sucesso!');
			    	    		}
			    	    		$listaImagens.find('.identitem[value=' + idItem + ']').parent().find('.pegou').val(quantidadePegou + '_' + quantidadeNaoPegou);
			    	    		$listaImagens.find('.identitem[value=' + idItem + ']').parent().append('<input type="hidden" class="voted">');
			    	    		tipoDeVotacao != 'peguei' ?  $lightbox.find('.quantidadeNaoPegou').trigger('change'): $lightbox.find('.quantidadePegou').trigger('change');
			    	    		
			    	    	} else {
			    	    		$.msgSobreposta().falha(retorno.data.msg);
			    	    	}
			    	    }
			    	});
					
				} else {
					$.msgSobreposta().falha('Você já votou nessa foto!');
				} 
		    }
			
			LightboxBatalha.prototype.botoesPegouNaoPegou = function($lightbox, $listaImagens)
		    {
		    	$lightbox.find('.btnPegou').click(function(){
					owner.ajaxEnviarVotacao($lightbox, $listaImagens, '/peguei-capturada/','pegou');
				});
		    	$lightbox.find('.btnNaoPegou').click(function(){
					owner.ajaxEnviarVotacao($lightbox, $listaImagens, '/nao-peguei-capturada/','naoPegou');
				});
		    }
		});
	    
	    $lightboxBatalha = new Lightbox({
	    	'fileLoadingImage' :  '/js/plugins/lightbox/images/loading.gif',
	        'fileCloseImage' : '/js/plugins/lightbox/images/close.png',
	        'resizeDuration' : 1,
	        'fadeDuration' : 1,
	        'labelImage' : "Capturada",
	        'labelOf' :  "de",
	        'lightboxId' : '#capturalightbox',
	        'appliedElements' : 'a[rel^=lightbox]',
	        'linkPreviousAlbum' : linkAnterior,
        	'linkNextAlbum' : linkProxima,
        	'classLightBox' : 'lightbox',
	        'classLightBoxOverlay' : 'lightboxOverlay'
	    }); 
	    $.extend($lightboxBatalha, new ExtendedLightBox);
	    $.extend($lightboxBatalha, new LightboxBatalha);
	    $lightboxBatalha.init();
	})(jQuery);
	
	
});




