jQuery.busca = function( param ){
	
	var owner = this;
	var ajax = null;
	var pane = null;
	var contentPane = null;
	
	/** Construtor da classe */
	this.__constructor = function(){
		
		owner.regraScrollPane();
		owner.regraLinhasPesquisa();
		owner.filtroEventos();
		owner.filtroTags();
		owner.cliquePesuisar();
		owner.autoCompleateTag();
		
	}
	
	this.regraScrollPane = function()
	{
		 var settings = {
			 showArrows: false,
			 autoReinitialise: true,
			 mouseWheelSpeed : 20,
			 verticalDragMaxHeight : 50
		 };
		 
		 pane = $('#divPesquisa');
		 pane.jScrollPane(settings);
		 contentPane = pane.data('jsp').getContentPane();
		 pane.hover(function(){
			 pane.find('.jspVerticalBar').stop(true,true).fadeIn();
		 },function(){
			 pane.find('.jspVerticalBar').stop(true,true).fadeOut();
		 });
		 
	}
	
	this.regraLinhasPesquisa = function()
	{
		$('#resultadoPesquisa > li').live('click', function(e){
			var linhaResultado = $(this).find('.linhaResultado');
			var link = $(linhaResultado).find('a');
			link[0].click();
		});
		
	}
	
	this.autoCompleateTag = function()
	{
	    $( ".busca" ).autocomplete({
	    	source: function( request, response ) {
	    		$.ajax({
	    			type : 'POST',
	    			url: "/get-tag",
	    			dataType: "JSON",
	    			data: { nome : $( ".busca" ).val() },
	    			success: function( data ) {

	    				if(data.success){
	    					response($.map( data.data, function( item ) {
	    						return {label: item.nome, value: item.nome, id: item.id_tag}
	    					}));
	    				}
	    			},
	    		});
	    	},
	    	minLength: 2,
	    	search: function( event, ui ) {
	    		$('#valorBusca').val('');
	    	},
	    	select: function( event, ui ) {
	    		$('#valorBusca').val(ui.item.id);
	    	},
	    });
	}
	
	this.ajaxBuscarConteudo = function(nome, evento)
	{
		$.ajax({
    	    url : '/buscar-tags',
    	    type: "POST",
    	    dataType : "json",
    	    data:{
    	    	nome : nome,
    	    	evento : evento
    	    },
    	    success : function(data){

    	    	if(data.success){
    	    		
    	    		var tags = data.data.tags;
    	    		
    	    		$('#resultadoPesquisa').html('');
    	    		for(var indice in tags)
    	    		{
    	    			var thumbs = new Array();
    	    			for(var i in tags[indice].imagens){
    	    				thumbs.push('<img src="' + tags[indice].imagens[i].thumb + '" />');
    	    			}
    	    			$html = '<li>' + 
	    	    					'<span class="linhaResultado"><a href="/tag/' + tags[indice].tag  + '/1" > ' + tags[indice].tag  + '</a></span>' + 
	    	    					'<span class="imagensLinhaResultado">' + thumbs.join(' ')  + '</span>' + 
	    	    					'<span class="separadorResultadoPesquisa">&nbsp;</span>' + 
	    	    				'</li>';
    	    			contentPane.find('ul').append($html);
    	    			$('#divPesquisa').show();
    	    		}
    	    	} else {
    	    		contentPane.find('ul').html('');
    	    	}
    	    }
    	});
	}
	
	/**
	 * Método que faz visualizar os espaço referente a busca.
	 */
	this.visualizarItens = function()
	{
		$('#menuBusca').fadeIn('slow');
		$('#divPesquisa').fadeIn('slow');
	}
	
	/**
	 * Método que esconde os espaços referentes a busca.
	 */
	this.esconderItens = function()
	{
		$('#menuBusca').fadeOut('slow');
		$('#divPesquisa').fadeOut('slow');
	}
	
	/**
	 * 
	 */
	this.cliquePesuisar = function()
	{
		
		$('.busca').keypress(function (e) {
			if (e.which == 13) {
				owner.visualizarItens();
				$('#filtroTags').trigger('click');
			}
		});
		
	}
	
	this.filtroTags = function()
    {
        $('#filtroTags').click(function(){
        	if($('#separadorMenuBusca').attr('class') != 'filtroTag'){
        		$('#separadorMenuBusca').attr('class', 'filtroTag'); 
        		$('#resultadoPesquisa').attr('class','filtroTags');
        	} 
        	owner.ajaxBuscarConteudo( $('#buscaCaptura .busca').val(), 0);
        });
    }
    
    this.filtroEventos = function()
    {
        $('#filtroEventos').click(function(){
            if($('#separadorMenuBusca').attr('class') != 'filtroEvento'){
                $('#separadorMenuBusca').attr('class', 'filtroEvento'); 
                $('#resultadoPesquisa').attr('class','filtroEventos');
            }
            owner.ajaxBuscarConteudo( $('#buscaCaptura .busca').val(), 1);
        });
    }

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.busca().__constructor(); });



