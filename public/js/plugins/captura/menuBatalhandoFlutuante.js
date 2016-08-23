jQuery.menuBatalhandoFlutuante = function( ){
	
	var owner = this;
        
	/** Construtor da classe */
	this.__constructor = function(){
            
        $('#menuFlutuante > ul > li:not(.cabecalho,.espacamento)').hover(function(){
            $(this).find('span').toggleClass('hover');
            $(this).find('a').toggleClass('hover');
        });
        $('#botaoCapturaHeader').click(function(e){
            e.preventDefault();
            $('#menuFlutuante').css('display') === 'none' ? $('#menuFlutuante').slideDown('slow') : $('#menuFlutuante').slideUp('slow');
        });
        $('header').mouseleave(function(){
        	owner.escondeMenuFlutuante();
        });
        $('#menuFlutuante').mouseleave(function(){
        	owner.escondeMenuFlutuante();
        });
        
	}
	
	/**
	 * Método que verifica se o mouse está fora do header e do menuFlutuante
	 * para só assim esconder o menu.
	 */
	this.escondeMenuFlutuante = function(){
		setTimeout(function(){
			if(!$('header').is(':hover') && !$('#menuFlutuante').is(':hover')){
				$('#menuFlutuante').slideUp('slow');
			}
		}, 1000);
	}
               
	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$('body').ready(function(){ $.menuBatalhandoFlutuante().__constructor(); });