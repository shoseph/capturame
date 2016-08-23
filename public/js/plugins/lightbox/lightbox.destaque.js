$(document).ready(function(){ 
	(function($) {
		
		ExtendedLightBox = (function() {
			
			/**
			 * Método que retorna a posição de onde o lightbox vai aparecer na tela
			 */
			ExtendedLightBox.prototype.getPositionConfiguration = function(){
				var $window = $(window);
	    		var capturaTop = 40;
	            return {
	            	top : $window.scrollTop() + ($window.height() / capturaTop)  ,
	            	left : $window.scrollLeft()
	            };
			};
			
			/**
			 * Método que é executado após carregar os elementos do lightbox.
			 */
			ExtendedLightBox.prototype.posBuildEvent = function($lightbox)
			{
		        this.acoesBotoes($lightbox);
			}
			
			/**
			 * Método que cria um album, capturado os dados que estão na estrutura
			 * das imagens em miniatura.
			 */
			ExtendedLightBox.prototype.createAlbum = function()
			{
				var  a, i, _i, _len, _ref;
    	    	this.album = [];
    	        imageNumber = 0;
    	        $link = this.currentTarget;
    	        
    	        // caso de uma imagem separada
    	        if ($link.attr('rel') === 'lightbox') {
    	        	this.album.push({
    	                link: $link.attr('href'),
    	                title: $link.find('.title').val(),
    	                desc: $link.find('.description').html(),
    	                view: $link.find('.view').html(),
    	                identitem: $link.find('.identitem').val(),
    	                pegou: $link.find('.pegou').val(),
    	                datacapturada: $link.find('.data').val(),
    	                download: $link.find('.download').html(),
    	                iso: $link.find('.iso').val(),
    	                exposicao: $link.find('.exposicao').val(),
    	                distanciafocal: $link.find('.distanciafocal').val(),
    	                abertura: $link.find('.abertura').val(),
    	                flash: $link.find('.flash').val()
    	              });
    	        	
    	        	
    	          } else {
    	            _ref = $($link.prop("tagName") + '[rel="' + $link.attr('rel') + '"]');
    	            for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
    	              a = _ref[i];
    	              this.album.push({
    	            	  link: $(a).attr('href'),
    	                  title: $(a).find('.title').val(),
    	                  desc: $(a).find('.description').html(),
    	                  view: $(a).find('.view').html(),
    	                  identitem: $(a).find('.identitem').val(),
    	                  pegou: $(a).find('.pegou').val(),
    	                  datacapturada: $(a).find('.data').val(),
    	                  download: $(a).find('.download').html(),
    	                  iso: $(a).find('.iso').val(),
    	                  exposicao: $(a).find('.exposicao').val(),
    	                  distanciafocal: $(a).find('.distanciafocal').val(),
    	                  abertura: $(a).find('.abertura').val(),
    	                  flash: $(a).find('.flash').val()
    	              });
    	              if ($(a).attr('href') === $link.attr('href')) {
    	                imageNumber = i;
    	              }
    	            }
    	          }
    	        return imageNumber;
			};
		
			/**
			 * Método que executa a ação de reload do album
			 */
			ExtendedLightBox.prototype.reloadAlbum = function()
			{
				this.album = [];
		        _ref = $('*[rel^="' + this.options.linkrel  + '"]');
		        for (i = 0, _len = _ref.length; i < _len; i++) {
		            a = _ref[i];
		            this.album.push({
		              link: $(a).attr('href'),
		              title: $(a).find('.title').val(),
		              desc: $(a).find('.description').html(),
		              view: $(a).find('.view').html(),
		              identitem: $(a).find('.identitem').val(),
		              pegou: $(a).find('.pegou').val(),
		              datacapturada: $(a).find('.data').val(),
		              download: $(a).find('.download').html(),
		              iso: $(a).find('.iso').val(),
	                  exposicao: $(a).find('.exposicao').val(),
	                  distanciafocal: $(a).find('.distanciafocal').val(),
	                  abertura: $(a).find('.abertura').val(),
	                  flash: $(a).find('.flash').val()
		            });
		        }
			};
			
			/**
			 * Método que cria a estrutura do lightbox atual
			 */
			ExtendedLightBox.prototype.structure = function()
			{
				$("<div>", { 'class': 'lightboxOverlay' }).after(
			        $('<div/>', { 'class': 'lightbox'  }).append(
			            $('<div/>', { "class": 'lb-outerContainer' }).append(
		            		$('<span/>', { "class": 'lb-tags' }),
			                $('<div/>', { "class": 'lb-container' }).append(
			                    $('<span/>'),
			                    $('<img/>', { "class": 'lb-image' }),
			                    $('<div/>', { "class": 'lb-nav' }).append(
			                        $('<a/>', { "class": 'lb-prev' }),
			                        $('<a/>', { "class": 'lb-next' })
			                    ),
			                    $('<div/>', { "class": 'lb-loader' }).append(
			                        $('<a/>', { "class": 'lb-cancel' }).append(
			                            $('<img/>', { src: this.options.fileLoadingImage })
			                        )
			                    )
			                )
			            ),
			            $('<div/>', { "class": 'lb-dataContainer' }).append(
			                $('<div/>', { "class": 'lb-image-values' }),
	                		$('<div/>', { "class": 'lb-data' }).append(
			                    $('<div/>', { "class": 'lb-details' }).append(
			                        $('<span/>', { "class": 'lb-number' }),
			                        $('<span/>', { "class": 'lb-caption' }),
			                        $('<div/>', { "class": 'lb-exif' }).append(
			                    		$('<span/>', { "class": 'exif-iso' }),
				                        $('<span/>', { "class": 'exif-exposicao' }),
				                        $('<span/>', { "class": 'exif-distanciafocal' }),
				                        $('<span/>', { "class": 'exif-abertura' }),
				                        $('<span/>', { "class": 'exif-flash' })
				                    ),
			                        $('<span/>', { "class": 'lb-desc' }),
			                        $('<input/>', { "type": "hidden", "class": 'id-item' }),
			                        $('<input/>', { "type": "hidden", "class": 'lb-index' })
			                    ),
			                    $('<div/>', { "class": 'lb-closeContainer' }).append(
			                        $('<a/>', { "class": 'lb-close' }).append(
			                            $('<span/>', { 'class': 'imgClose'})
			                        )
			                    )
			                )
			            ).append(
			                $('<div/>', { "class": 'lb-like' }).append(
			                        $('<span/>',{'class' : 'sim'}).append(
			                        		$('<a/>', {"class": 'btnPegou'}).html('&nbsp;'),
			                        		$('<span/>', {"class": 'quantidadePegou'}).html(0)
			                        ),
			                        $('<span/>',{'class' : 'nao'}).append(
			                        		$('<a/>', {"class": 'btnNaoPegou'}).html('&nbsp;'),
			                        		$('<span/>', {"class": 'quantidadeNaoPegou'}).html(0)
			                    	)
			                ),
			                
			                $('<div/>', { "class": 'lb-anuncio' }).append(
			                ),
			                $('<div/>', { "class": 'lb-action-links escondido' }).append(
			                		$('<div/>', { "class": 'lb-exec' })
			                ),
			                $('<div/>', { "class": 'lb-links' }).append(
			                    $('<ul/>', { "class": 'lista-links' }).append(
		                    		$('<li/>', {"class": 'download'}).append($('<a/>', {"class": 'lb-download'}).html('&nbsp;')),
		                    		$('<li/>', {"class": 'autor'}).append($('<a/>', {"class": 'lb-view escondido'}).html('&nbsp;')),
		                    		$('<li/>', {"class": 'tag off'}).append($('<a/>', {"class": 'lb-tag'})),
			                	    $('<li/>', {"class": 'editar off'}).append($('<a/>', {"class": 'lb-editar escondido'}).html('&nbsp;')),
			                	    $('<li/>', {"class": 'batalha off'}).append($('<a/>', {"class": 'lb-batalha'}).html('&nbsp;'))
			                    )
	                        )
	                    ),
		                $('<div/>', { "class": 'lb-comment' }).append(
			                $('<span/>', {"class": 'comentar'}).html('Comentarios'),
			                $('<div/>', { "class": 'lb-facebook' }).append(
			                    $('<div/>', { 
			                        "class": 'fb-comments',  
			                        "data-href" : location.href, 
			                        "data-width" : 960, 
			                        "data-num-posts" : 4,
			                        "data-colorscheme" : "dark"
			                        }
			                    )
			                )
			            )
			        )
			    ).appendTo($(this.options.lightboxId));
			};
			
			/**
			 * Evento que é executando antes de iniciar o lightbox
			 */
			ExtendedLightBox.prototype.preStartEvent = function(){
				$('body').css('overflow','hidden');
			};			
			
			/**
			 * Evento que é executado antes de modificar a imagem
			 */
			ExtendedLightBox.prototype.preChangeImageEvent = function($lightbox){
				$lightbox.find('.lb-image, .lb-nav, .lb-prev, .lb-next').hide();
			};
			
			/**
			 * Efeito de como mostrar a imagem no lightbox
			 */
			ExtendedLightBox.prototype.showImageEffect = function($lightbox){
				$lightbox.find('.lb-image').fadeIn('fast');
			}
		
			/**
			 * Método que cria a regra do facebook no lightbox
			 */
			ExtendedLightBox.prototype.facebookStatus = function($lightbox)
			{
				$lightbox.find('.fb-comments').attr("data-href", location.href);
				var js, fjs = document.getElementsByTagName('script')[0];
				if (document.getElementById('facebook-jssdk') == null){
					js = document.createElement('script'); js.id = 'facebook-jssdk';
					js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=505987842795818";
					fjs.parentNode.insertBefore(js, fjs);
					return;
				}
				FB.XFBML.parse();
       	    	
			};
			
			/**
			 * Método que retorna qual é a uri
			 */
			ExtendedLightBox.prototype.getUri = function() {
		    	
		    	var selecionado = $.map(['visualizar-capturadas','visualizar-batalha', 'tag'], function(item){
		            var patt = new RegExp('/' + item + '/','g');
		            if(patt.test(urlLoadCapturada)){
		                return item;
		            }
		        });

		        if(selecionado.length > 0 ){
					var regexSelecionado = new RegExp('^([\\w\\W]+' + selecionado[0] + '\/)','g');
					var base = urlLoadCapturada.match(regexSelecionado)[0].replace(/\/$/,'');
					var params = urlLoadCapturada.replace(regexSelecionado, '').split('/');
					capturada = params.length >=3 ? params[2] : 0; 
					var finalurl = new Array();
					finalurl.push(base);
					for(var i in params){
						if(i>=2) break;
						finalurl.push(params[i]);
					}
					return finalurl.join('/');
		        }
		        
		        var selecionado = $.map(['mais-curtidas', 'novas-capturadas', 'cadastrar-capturada-batalha'], function(item){
					var patt = new RegExp('/' + item + '/','g');
					if(patt.test(urlLoadCapturada)){
						return item;
					}
				});
				
				if(selecionado.length > 0 ){
					var regexSelecionado = new RegExp('^([\\w\\W]+' + selecionado[0] + '\/)','g');
					var base = urlLoadCapturada.match(regexSelecionado)[0].replace(/\/$/,'');
					var params = urlLoadCapturada.replace(regexSelecionado, '').split('/');
					capturada = params.length >=3 ? params[2] : 0; 
					var finalurl = new Array();
					finalurl.push(base);
					for(var i in params){
						if(i>=1) break;
						finalurl.push(params[i]);
					}
					return finalurl.join('/');
				}
				
				return false;
		    	
		    };
		    
		    /**
		     * Método que modifica a url que está no navegador 
		     */
			ExtendedLightBox.prototype.rulleUrlChange = function($lightbox) 
		    {
		    	var uri = this.getUri();
		    	if(uri != 0){
					var numeroDaCapturada = parseInt($lightbox.find('.lb-index').val()) + 1;
					// TODO: verificar como se faz para pegar esse valor anterior, caso modifique para outro indice 
					window.history.replaceState("Captura.Me", "Captura.Me", uri + '/' +  numeroDaCapturada);
		    	}

		    }
		    
		    /**
		     * Método que ao clicar no botão deletar tag ele deleta a tag em questão
		     */
		    ExtendedLightBox.prototype.rulleDeleteTag = function()
		    {
		    	$(".lb-deletar-tag").click(function(e){
					e.preventDefault();
					var dados = $(this).attr('href').split('_');
					var botao = this;
					$.ajax({
						type : 'POST',
						url: "/desvincular-tag",
						dataType: "JSON",
						data: {
							capturada : dados[0],
							tag : dados[1]
						},
						success: function( data ) {
							if(data.success){
								$(botao).parent().remove();
								$('.tag_' + dados[0] + '_' + dados[1]).remove();
							}
						},
					});
				});
		    };
		    
		    /**
		     * Método faz o ajax de adicionar uma tag
		     */
		    ExtendedLightBox.prototype.ajaxAddTag = function($lightbox, owner,capturada,usuario,valorTag, numeroImagem)
		    {
		    	$.ajax({
		    		url : '/add-tag',
		    		type: "POST",
		    		dataType : "json",
		    		data:{
		    			capturada: capturada,
						usuario: usuario,
						nome : valorTag
					},
					success : function(retorno){
						if(retorno.success){
							$lightbox.find('.cp-add-tag').val('').hide();
							$lightbox.find('.cp-cancel-add-tag').val('').hide();
							$lightbox.find('.lb-lista-tags').append(
								$('<li/>').append(
									$('<a/>', {"class": 'lb-nome-tag', "href" : '/tag/' + valorTag + '/1'}).html(valorTag),
									$('<a/>', {"class": 'lb-deletar-tag', "href" : capturada + '_' + retorno.data.tag}).html('X')
								)
							);
							owner.rulleDeleteTag();
							$('.lb-nome-tag').click(function(){
								window.location = $(this).attr('href');
							});
							owner.enableKeyboardNav();
							$('.capturada_' + numeroImagem + '[rel^="' + owner.options.linkrel + '"]').append($('<input/>', {
								'type' : 'hidden',
								'class' : 'tag_' + capturada + '_' +  retorno.data.tag,
								'value' : capturada + '_' +  retorno.data.tag + '_' + valorTag + '_' + usuario
							}));
							$.msgSobreposta().sucesso(retorno.data.msg);
						} else {
						    $.msgSobreposta().falha(retorno.data.msg);
						}
						$lightbox.find('.lb-action-links').fadeOut('slow');
	        	    }
	        	});
		    }
		    
		    /**
		     *	Regra que adiciona uma tag 
		     */
		    ExtendedLightBox.prototype.rulleAddTag = function($lightbox, owner)
		    {
		    	$lightbox.find('.lb-tag').click(function()
		    	{
		    		if($lightbox.find('.lb-usuario').val() == usuarioLogado)
		    		{
		    			$lightbox.find('.lb-action-links').fadeIn('slow');
			    		owner.disableKeyboardNav();
			    		$lightbox.find('.lb-exec').html('').append(
		    				$('<input/>', {"type": "text", "class": 'cp-add-tag'}),
		    				$('<span/>', { "class": 'cp-cancel-add-tag'})
		    			);
			    		$lightbox.find('.cp-add-tag').trigger('focus');
			    		$lightbox.find('.cp-cancel-add-tag').click(function(){
			    			$lightbox.find('.cp-add-tag').trigger($.Event('keyup',{ keyCode: 27 }));
				    	});
			    		$lightbox.find('.cp-add-tag').keyup(function(e){
			    			e.stopPropagation();
			    			if(e.keyCode == 13){
			    				var valorTag = $(this).val();
			    				if(valorTag.length > 2){ // tamanho mínimo de uma tag
			    					owner.ajaxAddTag($lightbox, owner, $lightbox.find('.lb-capturada').val(), $lightbox.find('.lb-usuario').val() ,valorTag, $lightbox.find('.lb-numero-imagem').val());
			    				}
			    			}
			    			if(e.keyCode == 27){
			    				$lightbox.find('.cp-add-tag').val('').hide();
			    				$lightbox.find('.cp-cancel-add-tag').val('').hide();
			    				$lightbox.find('.lb-action-links').fadeOut('slow');
			    				owner.enableKeyboardNav();
			    			}
			    		});
		    		}
		    	});
		    }
		   
		    /**
		     *  método que abre o dialog da batalha, confirmação da seleção da imagem
		     *  para a batalha. 
		     */
		    ExtendedLightBox.prototype.dialogBatle = function($lightbox, idBatalha, capturada, numeroImagem, owner, listaBatalha, batalhas)
		    {
		    	$("#dialog").attr('title', 'Adicionar na batalha');
  			    $("#dialog").html('Deseja adicionar essa capturada na batalha?');
  			    $("#dialog").dialog({
  					resizable: false,
  					height: 200,
  					width: 400,
  		            modal: true,
  		            buttons: {
  		                "Sim": function() {
  		                	
  		                	$.ajax({
  		  		        	    url : '/selecionar-capturada',
  		  		        	    type: "POST",
  		  		        	    dataType : "json",
  		  		        	    data:{
  		  		        	    	capturada : capturada,
  			                	    batalha: idBatalha
  		  						},
  		  		        	    success : function(retorno){
  		  		        	    	
  		                	    	if(retorno.success){
  		                	    		$.msgSobreposta().sucesso(retorno.data.msg);
  		                	    		$('.capturada_' + numeroImagem + '[rel^="' + owner.options.linkrel + '"]').remove();
  		                	    		var imagensCapturadas = $('*[rel^="' + owner.options.linkrel + '"]');
  		                	    		$.map(imagensCapturadas, function(i,c){
  		                	    		    var identificador = 'capturada_' + (c + 1);
  		                	    		    $(i).attr('class', '');
  		                	    		    $(i).addClass(identificador);
  		                	    		});
  		                	    		owner.reloadAlbum();
  		                	    		var indiceBatalha = parseInt($(listaBatalha).find('.indiceBatalha').html());
  		                	    		batalhas[indiceBatalha]['capturadasEnviadas']++;
  		                	    		$lightbox.find('.lb-next').is(':visible') ? $lightbox.find('.lb-next').click() : $lightbox.find('.lb-prev').is(':visible') ? $lightbox.find('.lb-prev').click() : $lightbox.find('.lb-close').click();
  		                	    	} else {
  		                	    		$.msgSobreposta().falha(retorno.data.msg);
  		                	    	}
  		                	    	$('#dialog').dialog( "close" );
  		                	    	$lightbox.find('.lb-action-links').fadeOut('slow');
  			                	}
  		                	});
  		                },
  		                "Não": function() {
  		                	$('#dialog').dialog( "close" );
  		                }
  		            }
  				});
		    }
		    
		    /**
		     * Regras referentes a batalha.
		     */
		    ExtendedLightBox.prototype.rulleBatle = function($lightbox, owner)
		    {
		    	// verificando se o usuário não está com o modo batalha ligado
		    	if(batalhando == 0){
		    		return 1;
		    	}
		    	
		    	// ação de click no botão para exibição de uma batalha
		    	$lightbox.find('.lb-batalha').click(function(){
		    		if(batalhas && !$lightbox.find('.lb-lista-batalhas').is(':visible'))
		    		{
		    			$lightbox.find('.lb-action-links').fadeIn('slow');
		    			$lightbox.find('.lb-exec').html('').append($('<div/>', {'class' : 'lb-lista-batalhas' }).html('<h2>Batalhas atuais</h2><hr />')).fadeIn('slow');
		    			for(var indiceBatalhas in batalhas)
		    			{
		    				var quantidade = batalhas[indiceBatalhas]['totalBatalha'] - batalhas[indiceBatalhas]['capturadasEnviadas'];
		    				$lightbox.find('.lb-lista-batalhas').append(
		    					$('<span/>', {'class' : 'lb-lista-batalha'}).append(
		    						$('<label/>').html(batalhas[indiceBatalhas]['titulo']),
		    						$('<span/>',{ 'class': 'quantidadeRestante'}).html(quantidade),
		    						$('<span/>',{ 'class': 'indiceBatalha escondido'}).html(indiceBatalhas),
		    						$('<input/>', { "type" : "hidden", "value": batalhas[indiceBatalhas]['id_batalha']})
		    					)
			    			);
			    		}
		    			$lightbox.find('.lb-lista-batalha').click(function(e){
		    				var idBatalha = $(this).find('input[type="hidden"]').val();
		    				owner.dialogBatle($lightbox, idBatalha, $lightbox.find('.lb-capturada').val(), $lightbox.find('.lb-numero-imagem').val(), owner, this, batalhas);
		    			});
		    		} else {
		    			$lightbox.find('.lb-action-links').fadeOut('slow');
		    		}
		    	});
		    } 
		    
		    /**
		     *	Método que monta a listagem de tas e adiciona as regras dos botões
		     *  das tags. 
		     */
		    ExtendedLightBox.prototype.rulleMountTags = function($lightbox, numeroImagem, usuarioLogado)
		    {
		    	// Procura a lista de tags de uma capturada
		    	var tags = $('.capturada_' + numeroImagem + '[rel^="' + this.options.linkrel + '"]').find('input[class^="tag_"]');
		        if(tags.length > 0)
		        {
		        	$lightbox.find('.lb-tags').html($('<ul/>', {"class": 'lb-lista-tags'}));
		        	$.map(tags, function(tag){
		        		var dados = $(tag).val().split('_');
		        		if(usuarioLogado && usuarioLogado == dados[3]){
		        			$lightbox.find('.lb-lista-tags').append($('<li/>').append( $('<a/>', {"class": 'lb-nome-tag', "href" : '/tag/' + dados[2] + '/1'}).html(dados[2]), $('<a/>', {"class": 'lb-deletar-tag', "href" : dados[0] + '_' + dados[1]}).html('X') ));
		        		} else {
		        			$lightbox.find('.lb-lista-tags').append($('<li/>').append( $('<a/>', {"class": 'lb-nome-tag', "href" : '/tag/' + dados[2] + '/1'}).html(dados[2]) ));
		        		}
		        	});
		        	
		        	$lightbox.find('.lb-nome-tag').click(function(){
		        		window.location = $(this).attr('href');
		        	});
		        	this.rulleDeleteTag();
		        	
		        	// regra visualização de tags
		        	$lightbox.find('.lb-container, .lb-nav ').hover(function(){
		        		if($lightbox.find('.lb-tags').html() != ''){
		        			$lightbox.find('.lb-tags').fadeIn('fast');
		        		} else {
		        			$lightbox.find('.lb-tags').hide();
		        		}
		        	}, function(e,f){
		        		if($lightbox.find('.lb-tags').html() != '' && !$(e.relatedTarget).hasClass('lb-tags') && !$(e.relatedTarget).hasClass('lb-container')){
		        			$lightbox.find('.lb-tags').fadeOut('slow');
		        		}
		        	});
		        } else {
		        	$lightbox.find('.lb-tags').html('').hide();
		        }
		    	
		    }
		    
		    /**
		     * Método que envia uma votação de uma capturada onde o usuário pode pegar ou não pegar(curtir ou não curtir)
		     */
		    ExtendedLightBox.prototype.ajaxEnviarVotacao = function($lightbox, $listaImagens, url, tipoDeVotacao )
		    {
		    	if($listaImagens.find('.identitem[value=' + $lightbox.find('.id-item').val() + ']').parent().find('input.voted').length == 0) {
					$.ajax({
			    	    url : url,
			    	    type: "POST",
			    	    dataType : "json",
			    	    data:{
			    	    	usuarioCapturada: $lightbox.find('.id-item').val()
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
		    
		    /**
		     * Método que monta a regra dos botões peguei e não peguei
		     */
		    ExtendedLightBox.prototype.botoesPegouNaoPegou = function($lightbox, $listaImagens)
		    {
		    	
		    	$lightbox.find('.btnPegou').click(function(){
					owner.ajaxEnviarVotacao($lightbox, $listaImagens, '/peguei-imagem-capturada/','pegou');
				});
		    	
		    	$lightbox.find('.btnNaoPegou').click(function(){
					owner.ajaxEnviarVotacao($lightbox, $listaImagens, '/nao-peguei-imagem-capturada/','naoPegou');
				});
		    	
		    }
		    
		    /**
		     * Método que seta as ações para cada botão
		     */
		    ExtendedLightBox.prototype.acoesBotoes = function($lightbox)
		    {
		    	var owner = this;
		    	$listaImagens = $(this.options.appliedElements);
		    	
		    	// caso especial de montagem dos botões peguei e nãoPeguei
		    	this.botoesPegouNaoPegou($lightbox, $listaImagens);
		    			    	
		    	// regra de click na li de cada botão
		    	$lightbox.find('.lb-links > ul > li').click(function(e){
		    		$(this).find('a').get(0).click();
		    	});
		    	
		    	// Regra para os comentários
		    	$lightbox.find('.lb-comment').click(function()
		    	{  
				    if($lightbox.find('.lb-comment').hasClass('aberto'))
				    {
				    	$lightbox.find('.lb-comment').removeClass('aberto').animate({bottom: '-32px',}, 300, 'swing');
				    	$lightbox.find('.lb-facebook').show().animate({bottom: '-32px',}, 300, 'linear');
				    	$lightbox.find('.fb-comments').hide();
				    } else {
				    	$lightbox.find('.lb-comment').addClass('aberto').animate({bottom: '+380px',}, 200, 'swing');
				    	$lightbox.find('.lb-facebook').show().animate({bottom: '+380px',}, 300, 'linear');

				    	$lightbox.find('.fb-comments').show();
				    }  
				});
		    	
		    	
		    	// regra quando o botão pegou foi clicado
		    	$lightbox.find('.quantidadePegou, .quantidadeNaoPegou').live('change',function(e){
		    		owner.reloadAlbum();
		    	});

		    	// regra do botão editar capturada
		    	$lightbox.find('.lb-editar').click(function(){
        			if($lightbox.find('.lb-usuario').val() == usuarioLogado){
        				window.location = "/editar-capturada/" + $lightbox.find('.lb-usuario').val()  + '/' + $lightbox.find('.lb-capturada').val();
        			}
	  	        });

        		// regra adicionar tag
        		this.rulleAddTag($lightbox, owner);
		    	
		    	// Ação do botão downlaod
        		$lightbox.find('.lb-download').click(function(e){
		    		var numeroImagem = parseInt($lightbox.find('.lb-index').val()) + 1;
		    		var itens = $('.capturada_' + numeroImagem + '[rel^="' + owner.options.linkrel + '"]').find('.data').val().split('_');
		    		var data = { usuario: itens[0], capturada : itens[1] };
	    		   	$.fileDownload('/download-capturada/', {
	    		   		successCallback: function (url) { },
	    		   		failCallback: function (responseHtml, url) {
	    		   			alert('erro no download da imagem, por favor entrar em contato em email@captura.me');
	    		   		},
	    		   		httpMethod: "POST",
	    		   	    data: data
	    		   	});
				});
		    	
		    	// Ação do botão visualizar
        		$lightbox.find('.lb-view').click(function(){
		  			window.location = $lightbox.find('.lb-view').attr('href');
		        });
		    	
		    	this.rulleBatle($lightbox, owner);
		    	
		    }
		    
		    /**
		     * Método que imprime na tela os botões do lightbox
		     */
		    ExtendedLightBox.prototype.imprimeBotoes = function($lightbox) 
		    {
		    	var owner = this;
		    	var usuario = $lightbox.find('.lb-usuario').val();
		    	
		    	// pegou e não pegou
		        var pegou = this.album[this.currentImageIndex].pegou.split('_');
		        $lightbox.find('.btnPegou').fadeIn('fast');
		        $lightbox.find('.btnNaoPegou').fadeIn('fast');
		        $lightbox.find('.quantidadePegou').html(pegou[0]).fadeIn('fast');
		        $lightbox.find('.quantidadeNaoPegou').html(pegou[1]).fadeIn('fast');
		        
		    	if(usuarioLogado == usuario)
		        {
		    		// editar capturada
		    		$lightbox.find('.lista-links .editar').removeClass('off');		        	
		        	// adicionar tag
		    		$lightbox.find('.lista-links .tag').removeClass('off');
	        		if(batalhando != 0){
	        			$lightbox.find('.lista-links .batalha').removeClass('off');
	        		}
		        } else {
		        	// remove opção de adicionar uma nova tag
		        	$lightbox.find('.lista-links .tag').hasClass('off') ? '' : $lightbox.find('.lista-links .tag').addClass('off');
		        	
		        	// remove a opção de editar uma capturada
		        	$lightbox.find('.lista-links .editar').hasClass('off') ? '' : $lightbox.find('.lista-links .editar').addClass('off');
		        	
		        	// remove a opção de batalhar
		        	$lightbox.find('.lista-links .batalha').hasClass('off') ? '' : $lightbox.find('.lista-links .batalha').addClass('off');
		        }
		    	
		    	// Adicionando o valor no visualizar imagem do botão usuário
		        if (typeof this.album[this.currentImageIndex].view !== 'undefined' && this.album[this.currentImageIndex].view !== "") {
		            $lightbox.find('.lb-view').attr('href', '/visualizar-capturadas/' + usuario + '/1').fadeIn('fast');
		        }
		    }
		    
		    /**
		     * Método que adiciona o conteúdo textual do lightbox
		     */
		    ExtendedLightBox.prototype.addDataLightBox = function($lightbox) 
		    {
		    		        
		    	// Adicionando o valor do index da imagem
		        $lightbox.find('.lb-index').val(this.currentImageIndex);
		        
		        // adicionar o título da imagem
		        if (typeof this.album[this.currentImageIndex].title !== 'undefined' && this.album[this.currentImageIndex].title !== "") {
		          $lightbox.find('.lb-caption').html(this.album[this.currentImageIndex].title).fadeIn('fast');
		        } else {
		      	  $lightbox.find('.lb-caption').fadeOut('fast');
		        }
		        
		        // adicionar a descrição da imagem
		        if (typeof this.album[this.currentImageIndex].desc !== 'undefined' && this.album[this.currentImageIndex].desc !== "") {
		            $lightbox.find('.lb-desc').html(this.album[this.currentImageIndex].desc).fadeIn('fast');
		        }

		        // adicionar exif iso
		        if (typeof this.album[this.currentImageIndex].iso !== 'undefined' && this.album[this.currentImageIndex].iso !== "") {
		        	$lightbox.find('.exif-iso').html('<span>ISO</span><bloquenote>' + this.album[this.currentImageIndex].iso + '</bloquenote>').fadeIn('fast');
		        } else {
		        	$lightbox.find('.exif-iso').html('<span>ISO</span><bloquenote>(?)</bloquenote>').fadeIn('fast');
		        }
		        
		        // adicionar exif exposicao
		        if (typeof this.album[this.currentImageIndex].exposicao !== 'undefined' && this.album[this.currentImageIndex].exposicao !== "") {
		        	$lightbox.find('.exif-exposicao').html('<span>EXPO</span><bloquenote>' + this.album[this.currentImageIndex].exposicao + '</bloquenote>').fadeIn('fast');
		        } else {
		        	$lightbox.find('.exif-exposicao').html('<span>EXPO</span><bloquenote>(?)</bloquenote>').fadeIn('fast');
		        }
		        
		        // adicionar exif distanciafocal
		        if (typeof this.album[this.currentImageIndex].distanciafocal !== 'undefined' && this.album[this.currentImageIndex].distanciafocal !== "") {
		        	$lightbox.find('.exif-distanciafocal').html('<span>FOCAL</span><bloquenote>' + this.album[this.currentImageIndex].distanciafocal + '</bloquenote>').fadeIn('fast');
		        } else {
		        	$lightbox.find('.exif-distanciafocal').html('<span>FOCAL</span><bloquenote>(?)</bloquenote>').fadeIn('fast');
		        }
		        
		        // adicionar exif abertura
		        if (typeof this.album[this.currentImageIndex].abertura !== 'undefined' && this.album[this.currentImageIndex].abertura !== "") {
		        	$lightbox.find('.exif-abertura').html('<span>APP</span><bloquenote>' + this.album[this.currentImageIndex].abertura + '</bloquenote>').fadeIn('fast');
		        } else {
		        	$lightbox.find('.exif-abertura').html('<span>APP</span><bloquenote>(?)</bloquenote>').fadeIn('fast');
		        }
		        
		        // adicionar exif flash
		        if (typeof this.album[this.currentImageIndex].flash !== 'undefined' && this.album[this.currentImageIndex].flash !== "") {
		        	var usouFlash = this.album[this.currentImageIndex].flash == 1 ? 'Sim' : 'Não'; 
		        	$lightbox.find('.exif-flash').html('<span>FLASH</span><bloquenote>' + usouFlash + '</bloquenote>').fadeIn('fast');
		        } else {
		        	$lightbox.find('.exif-flash').html('<span>FLASH</span><bloquenote>(?)</bloquenote>').fadeIn('fast');
		        }
		        
                // adicionar idenficador da imagem no lightbox
		        $lightbox.find('.id-item').val(this.album[this.currentImageIndex].identitem).fadeIn('fast');
		    	
		    }
		    
		    /**
		     * Método que popula o lihgtbox
		     */
		    ExtendedLightBox.prototype.populateDataLightBox = function($lightbox) 
		    {
		    	
		    	// método que carrega o plugin mensagens facebook
		    	this.facebookStatus($lightbox); //TODO: estudar como executar uma vez e logo em seguida apenas fazer o FB:reload lá...
		    	
		    	// dados referentes a imagem, usuário e tag
		        var numeroImagem = this.currentImageIndex + 1;
		        var capturada = this.album[this.currentImageIndex].datacapturada.split('_')[1];
		        var usuario = this.album[this.currentImageIndex].datacapturada.split('_')[0];
		        owner = this;
		        
		        $lightbox.find('.lb-image-values').html('').append(
	        		$('<input/>', { "type": "hidden", "class": 'lb-capturada' }).val(capturada),
	        		$('<input/>', { "type": "hidden", "class": 'lb-usuario' }).val(usuario),
	        		$('<input/>', { "type": "hidden", "class": 'lb-numero-imagem' }).val(numeroImagem)
		        );
		        
		        // adicionando a propaganda a ser mostrada
		        $lightbox.find('.lb-anuncio').html('').append(
	        		'<h2 class="lb-titulo-apoio">Apoio</h2><a class="link" href="http://www.adufpb.org.br"><img src="/images/apoio/adufpb.gif" /></a>'
		        );
		        
		        $lightbox.find('.link').click(function(){
		    		window.location = $(this).attr('href');
		    	});
		        
		        // Adicionando valores referentes aos dados no lightbox
		        this.addDataLightBox($lightbox);
                
		        // regra de modificação da url caso mude a imagem
		        this.rulleUrlChange($lightbox);
		        
		        // adicionando a listagem das tags
		        this.rulleMountTags($lightbox, numeroImagem, usuarioLogado);
		        
		        // montagem de todos os botões do lightbox		        
		        this.imprimeBotoes($lightbox);
		        
		        // regra de visualização da quantidade de imagens
		        this.album.length > 1 ? $lightbox.find('.lb-number').html(this.options.labelImage + ' ' + (this.currentImageIndex + 1) + ' ' + this.options.labelOf + '  ' + this.album.length).fadeIn('fast') : $lightbox.find('.lb-number').hide();
		        
		    }
		    
		    /**
		     * Método que executa uma regra de tipo de url no fechar do lightbox
		     */
		    ExtendedLightBox.prototype.ruleUrlTypeOneChange = function() {
		    	
		    	var selecionado = $.map(['visualizar-capturadas','visualizar-batalha', 'tag'], function(item){
		            var patt = new RegExp('/' + item + '/','g');
		            if(patt.test(urlLoadCapturada)){
		                return item;
		            }
		        });

		        if(selecionado.length > 0 ){
		      	  var regexSelecionado = new RegExp('^([\\w\\W]+' + selecionado[0] + '\/)','g');
		      	  var url = urlLoadCapturada.match(regexSelecionado);
		      	  var params = urlLoadCapturada.replace(regexSelecionado, '');
		      	  itens = params.split('/');
		            var uri = url + ([itens[0], itens[1]].join('/'));
		            window.history.replaceState("Captura.Me", "Captura.Me", uri);
		        }
		    }
		    
		    /**
		     * Método que executa uma regra de tipo de url no fechar do lightbox
		     */
		    ExtendedLightBox.prototype.ruleUrlTypeTwoChange = function() {
		    	
		    	var selecionado = $.map(['mais-curtidas', 'novas-capturadas','cadastrar-capturada-batalha'], function(item){
		    		var patt = new RegExp('/' + item + '/','g');
		    		if(patt.test(urlLoadCapturada)){
		    			return item;
		    		}
		    	});
		    	
		    	if(selecionado.length > 0 ){
		    		var regexSelecionado = new RegExp('^([\\w\\W]+' + selecionado[0] + '\/)','g');
		    		var url = urlLoadCapturada.match(regexSelecionado)[0].replace(/\/$/,'');
		    		var params = urlLoadCapturada.replace(regexSelecionado, '').split('/');
		    		
		    		var uri = [url, params[0]].join('/');
		    		window.history.replaceState("Captura.Me", "Captura.Me", uri);
		    	}
		    }
		    
		    /**
		     * Evento que executa antes de fechar o lightbox
		     */
		    ExtendedLightBox.prototype.preEndEvent = function($lightbox)
		    {
		    	$('body').css('overflow','auto');
		        this.ruleUrlTypeOneChange();
		        this.ruleUrlTypeTwoChange();
		        $lightbox.find('.lb-comment').fadeOut('fast');
		        $lightbox.find('.lb-comment').hasClass('aberto') ? $lightbox.find('.lb-comment')[0].click() : '';
		        $lightbox.find('.lb-lista-batalhas').hide();
		        $lightbox.find('.lb-action-links').hide();
		    }

		});
	    
	    $lightboxDestaque = new Lightbox({
	    	'fileLoadingImage' :  '/js/plugins/lightbox/images/loading.gif',
	        'fileCloseImage' : '/js/plugins/lightbox/images/close.png',
	        'resizeDuration' : 1,
	        'fadeDuration' : 1,
	        'labelImage' : "Capturada",
	        'labelOf' :  "de",
	        'lightboxId' : '#destaquelightbox',
	        'appliedElements' : 'a[rel^=destaque]',
	        'linkPreviousAlbum' : linkAnterior,
        	'linkNextAlbum' : linkProxima,
        	'classLightBox' : 'lightbox',
	        'classLightBoxOverlay' : 'lightboxOverlay'
	    }); 
	    $.extend($lightboxDestaque, new ExtendedLightBox);
	    $lightboxDestaque.init();
	})(jQuery);
});