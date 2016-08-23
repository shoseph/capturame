jQuery.menuRapido = function( ){
	
	var owner = this;
	var urlAtual = null;
    var caixas = new Array('caixaEnviarDica', 'caixaEnviarFoto', 'caixaCriarEvento');
        
	/** Construtor da classe */
	this.__constructor = function(){
		
		owner.regraListagemMenus();
		owner.regraEnviarDicas();
		owner.regraEnviarCapturadas();
		owner.regraEnviarEventos();
		
		owner.regraAbreItemMenu();
		
		            
	}
	
	this.regraAbreItemMenu = function()
	{
		$('#abreEnviarFotos').click(function(){
			$('#enviarFoto')[0].click();
		});
	}
	
	this.regraEnviarEventos = function()
	{
		$('.data').datepicker();
		$('input[class="hora"]').focus(function(){
			$('input[class="hora"]').mask('99:99');
		});
		
		$('#btEnviarEvento').click(function(){
			
			var itens = new Array('evento', 'contato', 'local', 'data', 'hora');
			var itensEmBranco = new Array();
			for(var indice in itens){
				if( $("#caixaCriarEvento ." + itens[indice]).val().length < 3 ){
					itensEmBranco.push(itens[indice]);
				}
			}
			if(itensEmBranco.length > 0){
				return $.msg().falha('Estão em branco os itens: ' + itensEmBranco.join(', '));
			}
			$('#loadEvento').fadeIn('slow');
			$.ajax({
	    	    url : '/adicionar-evento',
	    	    type: "POST",
	    	    dataType : "json",
	    	    data:{
	    	    	evento : $('#caixaCriarEvento .evento').val(),
	    	    	email : $('#caixaCriarEvento .contato').val(),
	    	    	hora : $('#caixaCriarEvento .hora').val(),
	    	    	data : $('#caixaCriarEvento .data').val(),
	    	    	descricao : $('#caixaCriarEvento .local').val()
	    	    },
	    	    success : function(data){
	    	    	
	    	    	$('#loadEvento').fadeOut('fast');
	    	    	if(data.success){
	    	    		$.msg().sucesso('Evento adicionado com sucesso!');
	    	    		$('#caixaCriarEvento .evento').val('');
	    	    		$('#caixaCriarEvento .contato').val('');
	    	    		$('#caixaCriarEvento .hora').val('');
	    	    		$('#caixaCriarEvento .data').val('');
	    	    		$('#caixaCriarEvento .local').val('');
	    	    		$('#dicas').keyup();
	    	    	} else {
	    	    		for(var indice in data.data){
	    	    			$.msg().falha("(<span class='negrito'>" + indice + "</span>)<br />" + data.data[indice].join(', '));
	    	    		}
	    	    	}
	    	    }
	    	});
		});
		
	}
	
	/**
	 * Regra que aciona a marcação em massa de uma adição de tag nas imagens.
	 */
	this.regraAdicionarTag = function()
	{
	    	
		$('#btnMaisTags').click(function(){
			
			var tag = $('#maisTags').val();
			var tamanho = $('.tags').length;
			if(tamanho > 0 && tag){
				$.map($('.tags'), function(val, indice){
					var valores = $(val).val().length == 0 ? new Array() : $(val).val().split(',');
					if(valores.indexOf(tag) < 0){
						valores.push(tag);
						$(val).val(valores.join(','));
					}
				});
				$('#maisTags').val('');
			}
			$.map($('.tituloMultiUpload'), function(val){
			    if($(val).html().toLowerCase() == 'tags'){
			        $(val).next().fadeIn();
			    }
			});
		});
		
		$('#maisTags').keypress(function (e) {
			if (e.which == 13) {
				$('#btnMaisTags').trigger('click');
			}
		});
		
	}
	
	this.regraEnviarCapturadas = function()
	{
		
		$('#btEnviarFoto').click(function(){			
			$('#submitHandler').trigger('click');
		});
		
		owner.regraAdicionarTag();
		config = {
			support: "image/jpeg,image/jpg",
			form: "multiplasCapturadas",		// Form ID
			dragArea: "dragAndDropFiles",		// Upload Area ID
			uploadUrl: "/adicionar-capturada"    // Server side upload url
		};
		initMultiUploader(config);
		
		$('#selecioneFotos').click(function(){
			$('#arquivo').trigger('click');
		});
		
		
	}
	
	this.quantidadePalavrasDicas = function()
	{
		return $('#dicas').val().split(/[\s\n]+/g).length;
	}
	
	this.regraEnviarDicas = function()
	{
		// regra que mostra os itens superiores das dicas
		$('#dicas').live('keyup',function(e){
			var quantidadeTotalDicas = 1500;
			var resto = quantidadeTotalDicas - $('#dicas').val().length;
		    if(resto >= 1000){
		        $('#contagemDicas').css('color', 'green');
		    } else if(resto >= 500) {
		        $('#contagemDicas').css('color', 'yellow');        
		    } else {
		        $('#contagemDicas').css('color', 'red');
		    }
		    
		    $('#contagemDicas').html(resto);
		    
		    if(owner.quantidadePalavrasDicas() < 10){
		    	$('#itensDica').fadeOut('slow');
		    } else {
		    	$('#itensDica').fadeIn('slow');
		    }
		});
		
		// regra de validação e envio da dica
		$('#btEnviarDica').click(function(){
			
			// verificando se tem o mínimo de 10 palavras
			if(owner.quantidadePalavrasDicas() < 10){
				return $.msg().alerta('uma dica deve pelo ao menos ter 10 palavras');
			    
			    return 1;
			}
			if($('#tituloDica').val() == ""){
				$('#tituloDica').toggleClass('fieldrequired');
				return $.msg().alerta('Você deve informar um título para a dica');
			}
			$('#tituloDica').removeClass('fieldrequired');

			var explode = $('#itensDica').parent().find('.botoesCaixa').next().html().split(/<br>/);
			// regra do ultimo campo
			explode[explode.length-1] = explode[explode.length-1].replace(/(&nbsp;)/, ' ');
			var htmlResposta = $.map(explode, function(item){
				return (item == "" || item == " " || item == "   ") ? "<p>&nbsp;</p>" : '<p>' + item + '</p>';
			});
			var conteudo = htmlResposta.join('');
			$.ajax({
        	    url : '/adicionar-dica',
        	    type: "POST",
        	    dataType : "json",
        	    data:{
        	    	titulo : $('#tituloDica').val(),
        	    	conteudo: conteudo
        	    },
        	    success : function(data){
        	    	
        	    	if(data.success){
        	    		$.msg().sucesso('Dica adicionada com sucesso!');
        	    		$('#tituloDica').val('');
        	    		$('#dicas').val('');
        	    		$('#dicas').keyup();
        	    	} else {
        	    		for(var indice in data.data){
        	    			$.msg().falha("(<span class='negrito'>" + indice + "</span>)<br />" + data.data[indice].join(','));
        	    		}
        	    	}
        	    }
        	});
			
			
		});
	}
	
	this.regraListagemMenus = function()
	{
		$('#dicas').css('fontSize','12px').html('').val('').elastic()
		.css('height',12);
		
        $('#menuRapido a ').click(function(){
            if($(this).attr('class') == 'inativo'){
                $('#linhaSuperior').hide();
                var objVisivel = 'caixa' + $(this).attr('id').replace($(this).attr('id').substring(0,1),$(this).attr('id').substring(0,1).toUpperCase());
                var arrayInativos = owner.removeFromArray(objVisivel, caixas);
                owner.esconderItens(arrayInativos);
                owner.inativarItens(arrayInativos);
                $('#' + objVisivel).show();
                $('.botoesCaixa').show();
                $(this).attr('class', 'ativo');
            } else {
                $('#linhaSuperior').show();
                owner.esconderItens(caixas);
                $('.botoesCaixa').hide();
                $(this).attr('class', 'inativo');
            }
        }); 
	}
	
    this.esconderItens = function(itens){
        var length = itens.length;
        for(var i =0; i < length ; i++){
            $('#' + itens[i]).hide();
        }
    }
    
    this.inativarItens = function(itens){
        var length = itens.length;
        for(var i =0; i < length ; i++){
            var inativo = itens[i].replace(/caixa/gi,'');
            inativo = inativo.replace(inativo.substring(0,1),inativo.substring(0,1).toLowerCase());
            $('#' + inativo).attr('class', 'inativo');
        }
    }
    
    this.removeFromArray = function(item, array){

        var length = array.length;
        var retorno = new Array();
        for(var i =0; i < length ; i++){
            if( array[i] != item){
                retorno.push(array[i]);
            }
        }
        return retorno;
    }
	
        
	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$('body').ready(function(){ $.menuRapido().__constructor(); });



