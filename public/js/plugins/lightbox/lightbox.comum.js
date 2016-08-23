$(document).ready(function(){ 
	(function($) {
		
		var LightboxComum = (function() {
			
			/**
			 * Método que é executado após carregar os elementos do lightbox.
			 */
			LightboxComum.prototype.posBuildEvent = function($lightbox)
			{
				// método que clica na imagem caso for passado o númeo da imagem na url
		        if(indicacaoCapturada){
					var item = indicacaoCapturada > 0 ? indicacaoCapturada -1 : 0;
					$(this.options.appliedElements + ':eq(' + item + ')').click();
				}
		        this.acoesBotoes($lightbox);
			}
			
		});
	    
	    $lightboxComum = new Lightbox({
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
	    $.extend($lightboxComum, new ExtendedLightBox);
	    $.extend($lightboxComum, new LightboxComum);
	    $lightboxComum.init();
	})(jQuery);
	
	
});




