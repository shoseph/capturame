jQuery.index = function( param ){
	
	var owner = this;
	var urlAtual = null;
	
	
	/** Construtor da classe */
	this.__constructor = function(){
		
		// ação dos comentários
        $('.box > ul > li[class^="botao"]').click(function(){
            
            var botaoClicado = $(this).attr('class');
            var divPai = $(this).parent().parent();
            var opcaoPai = botaoClicado.replace('botao','');
            var regex = new RegExp(opcaoPai,'gi');
            if(!regex.test($(divPai).attr('class'))){
                var novaClasse = $(divPai).attr('class').replace(/(facebook|captura)/gi, '');
                $(divPai).attr('class', novaClasse + opcaoPai.toLowerCase());
                $(divPai).find('article[class^="comentarios"]').slideUp('slow');
                $(divPai).find('.comentarios' + opcaoPai).slideDown('slow');
            }
        });
        
        $('li.hover').click(function(e){
		    var link = $(this).find('a');
		    link[0].click();
		});
				
		urlAtual = $('p.escondido').html();
//		$('input[type="submit"]').button();
//		$('input[type="button"]').button();
		var du=1200;  
		$( document ).tooltip({ 
			position: {
				my: "center bottom-15",
                    at: "center top",
                    using: function( position, feedback ) {
                        $( this ).css( position );
                        $( "<div>" ).addClass( "arrow" ).addClass( feedback.vertical ).addClass( feedback.horizontal ).appendTo( this );
                    }
		    },
		       open: function( event, ui ) {
		    	   setTimeout(function(){
		    		   $(ui.tooltip).hide({effect: 'fade'});
		    	   }, du * 1);
		    } 
		});
        
		owner.regraRequired();
//		owner.facebookButton();
//		owner.tweetButton();
//		owner.googleButton();
		owner.datepickerBr();
		owner.regraHistoryBack();
//		owner.regraAnimarArticle();
//		owner.twitters();
//		owner.regraCurtir();
		owner.regraVoltar();
		owner.regraOverflowMouseUp();
	}
	
	this.regraOverflowMouseUp = function(){
		
		$('.overflowMouseUp').live('mouseenter',function(){
		    $(this).css('overflow','auto');
		});

		$('.overflowMouseUp').live('mouseleave',function(){
			$(this).css('overflow','hidden');
		});		
	}
	
	this.regraCurtir = function(){
		$('.curtirBackground').mouseenter(function(){
			$('.curtirItens').delay( 300 ).fadeIn();
		});
		$('.curtirBackground').mouseleave(function(){
			$('.curtirItens').delay( 3000 ).fadeOut();
		});
	}
	
	this.regraRequired = function()
	{
		$('*[required="required"]').after('<span class="required"></span>');
	}
	
	this.regraVoltar = function()
	{
		$('#ultimaAcao').live('click',function(){
			history.back();
		});
	}
		
	this.regraAnimarArticle = function(){
		
		if(urlAtual != '/'){
			owner.animarAteArticle();
		}
		
		$('#navBar a').click(function(event){
			if($(this).attr('href') == urlAtual){ 
				owner.animarAteArticle();
				return false;
			}
			
		});
		
	}
	
	this.twitters = function(){
		$('.conteudoTwitters').tweet({
			username : "mecaptura",
			page : 1,
			count : 6,
			loading_text : "loading ..."
		}).bind("loaded", function() {
			var ul = $(this).find(".tweet_list");
			var ticker = function() {
				setTimeout(function() {
					ul.find('li:first').animate({
						marginTop : '-4em'
					}, 500, function() {
						$(this).detach().appendTo(ul).removeAttr('style');
					});
					ticker();
				}, 5000);
			};
			ticker();
		});
	}
	
	this.datepickerBr = function()
	{
		$.datepicker.regional['pt-BR'] = {
		    closeText: 'Fechar',
		    prevText: '<Anterior',
		    nextText: 'Seguinte',
		    currentText: 'Hoje',
		    monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
		    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
		    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
		    'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
		    dayNames: ['Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'S&aacute;bado'],
		    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b'],
		    dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'S&aacute;b'],
		    weekHeader: 'Sem',
		    dateFormat: 'dd/mm/yy',
		    firstDay: 0,
		    isRTL: false,
		    showMonthAfterYear: false,
		    yearSuffix: ''
	    };
		$.datepicker.setDefaults( $.datepicker.regional['pt-BR']);
	}
	
	this.animarAteArticle = function(){
		$("body, html").animate({scrollTop: 600}, 500);
	};

	this.regraHistoryBack = function(){
		$(".historyBack").click(function(){
		    history.back();
		});
	};
	
	this.facebookButton = function(){
		$(".box-curtir-flutuante").hover(function() {
			$(this).stop().animate({right: "0"}, "medium");
	    }, function() {
	    	$(this).stop().animate({right: "-263"}, "medium");
	    }, 500);
		
		var container = document.getElementById('flbCont');
	    var w = '250px';
	    var h = container.style.height;
	    fbFrame = document.createElement("IFRAME");
	    fbFrame.setAttribute("src", fbURL);
	    fbFrame.setAttribute("scrolling", "no");
	    fbFrame.setAttribute("frameBorder", 0);
	    fbFrame.setAttribute("allowTransparency", true);
	    fbFrame.style.border = "none";
	    fbFrame.style.overflow = "hidden";
	    fbFrame.style.width = w;
	    fbFrame.style.height = h;
	    $('#flbCont').html(fbFrame);
		
	};
	
	this.tweetButton = function(){
		$(".box-twitter-flutuante").hover(function() {
			$(this).stop().animate({right: "0"}, "medium");
	    },function() {
	    	$(this).stop().animate({right: "-250"}, "medium");}
	    , 500);
		!function(d,s,id){
			var js,fjs=d.getElementsByTagName(s)[0];
			if(!d.getElementById(id)){
				js=d.createElement(s);
				js.id=id;
				js.src="//platform.twitter.com/widgets.js";
				fjs.parentNode.insertBefore(js,fjs);
			}
		}(document,"script","twitter-wjs");
	};
	this.googleButton = function(){
//		$(".box-mais-um-flutuante").hover(function() {
//			$(this).stop().animate({right: "0"}, "medium");
//		},function() {
//			$(this).stop().animate({right: "-250"}, "medium");}
//		, 500);
	};
	
	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$('body').ready(function(){ $.index().__constructor(); });



