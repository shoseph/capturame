jQuery.capturadaIndexAdicionar = function( param ){
	
	var owner = this;

	/** Construtor da classe */
	this.__constructor = function(){
		
		owner.regraAdicionarTag();
		
		//support : "image/jpg, image/png,image/bmp,image/jpeg,image/gif",		// Valid file formats
		config = {
			support: "image/jpeg,image/jpg",
			form: "multiplasCapturadas",		// Form ID
			dragArea: "dragAndDropFiles",		// Upload Area ID
			uploadUrl: "adicionar-multiplas-capturadas"    // Server side upload url
		};
		initMultiUploader(config);
		
		$('#selecionar').click(function(){
			$('#arquivo').trigger('click');
		});
	}
	
	/**
	 * Regra que aciona a marcação em massa de uma adição de tag nas imagens.
	 */
	this.regraAdicionarTag = function()
	{
	    	
		$('#btnAddTag').click(function(){
			
			var tag = $('#addTag').val();
			var tamanho = $('.tags').length;
			if(tamanho > 0 && tag){
				$.map($('.tags'), function(val, indice){
					var valores = $(val).val().length == 0 ? new Array() : $(val).val().split(',');
					if(valores.indexOf(tag) < 0){
						valores.push(tag);
						$(val).val(valores.join(','));
					}
				});
				$('#addTag').val('');
			}
			$.map($('.tituloMultiUpload'), function(val){
			    if($(val).html().toLowerCase() == 'tags'){
			        $(val).next().fadeIn();
			    }
			});
		});
		
		$('#addTag').keypress(function (e) {
			if (e.which == 13) {
				$('#btnAddTag').trigger('click');
			}
		});
		
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.capturadaIndexAdicionar().__constructor(); });



