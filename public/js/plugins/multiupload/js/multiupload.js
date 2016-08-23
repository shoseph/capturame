function multiUploader(config){
  
	this.config = config;
	this.items = "";
	this.all = []
	var self = this;
	
	multiUploader.prototype._init = function(){
		
		$('.close').live('click', function(){
			
		    var patt=/dfiles/g;
		    var dfiles = $(this).parent();
		    var result=patt.test($(dfiles).attr('class'));
		    if(result){
		    	var hidden = $(this).parent().find('input[type="hidden"]');
		    	var tamanho = self.all.length;
		    	self.all = $.map(self.all, function(x,i){
		    		if(x.name != $(hidden).attr('val')){
		    			return x;
		    		}
		    	});
		    	
			    $(dfiles).remove();
			}
		});
		
		$('.closeImgErro').live('click', function(){
			var patt=/dfiles/g;
		    var dfiles = $(this).parent().parent();
		    var result=patt.test($(dfiles).attr('class'));
		    if(result){
		    	var hidden = $(dfiles).parent().find('input[type="hidden"]');
		    	var tamanho = self.all.length;
		    	self.all = $.map(self.all, function(x,i){
		    		if(x.name != $(hidden).attr('val')){
		    			return x;
		    		}
		    	});
			    $(dfiles).remove();
			}
		});
		
		if (window.File && window.FileReader && window.FileList && window.Blob) {		
			 var inputId = $("#"+this.config.form).find("input[type='file']").eq(0).attr("id");
			 document.getElementById(inputId).addEventListener("change", this._read, false);
			 document.getElementById(this.config.dragArea).addEventListener("dragover", function(e){ e.stopPropagation(); e.preventDefault(); }, false);
			 document.getElementById(this.config.dragArea).addEventListener("drop", this._dropFiles, false);
			 document.getElementById(this.config.form).addEventListener("submit", this._submit, false);
		} else {
			console.log("Browser supports failed");
		}
		
		self._rulleTitle('tituloMultiUpload');
	}
	
	multiUploader.prototype._submit = function(e){
		e.stopPropagation(); e.preventDefault();
		vazios = $.map($('.inputs').find('.titulo'), function(e){
		    if($(e).val() == '' || $(e).val() == ' ' || $(e).val() == '  ' || $(e).val() == '   ' || $(e).val() == '    '){
			    return 1;
		    }
		});
		if(vazios.length == 0){
			self._startUpload();
		} else {
			alert('Existem títulos em branco, por favor adicione um título.');
		}
	}
	multiUploader.prototype._rulleTitle = function(itemClass){
		
		$('.' + itemClass).live('click', function(item){
			var patt = /inativo/g
			var found = patt.test($(this).attr('class'));
			// inativo
			if(found){
			    $(this).attr('class','tituloMultiUpload pointer ativo');
			    $(this).next().fadeIn();
			} else { // ativo
			    $(this).attr('class','tituloMultiUpload pointer inativo');
			    $(this).next().fadeOut();
			}
		});
	}
	multiUploader.prototype._preview = function(data){
		this.items = data;
		if(this.items.length > 0){
			var html = "";		
			var uId = "";
 			for(var i = 0; i<this.items.length; i++){
 				
 				if(!self._arrayExists(this.items[i])){
					uId = this.items[i].name._unique();
					var sampleIcon = '<img src="/js/plugins/multiupload/images/image.png" />';
					var errorClass = "";
					if(typeof this.items[i] != undefined){
						
						archive = this.items[i].name.split('.');
						this.items[i].archiveName = archive[0]; 
						if(self._validate(this.items[i].type) < 0) {
							sampleIcon = '<img src="/js/plugins/multiupload/images/unknown.png" />';
							errorClass =" invalid";
						} 
						
						html += '<span class="dfiles' + errorClass + ' blocoImagem" rel="'+uId+'">' + 
									'<span class="close pointer">X</span>' + 
									'<div id="'+uId+'" class="progress" style="display:none;">' + 
										'<img src="/js/plugins/multiupload/images/ajax-loader.gif" />' + 
									'</div>' + 
							        '<span class="blocoImagemEsquerdo">' + '<canvas rel="' + uId + '">&nbsp;</canvas></span>' +
							        '<span class="blocoImagemDireito">' +
							            'Titulo <input class="titulo" value="' +this.items[i].archiveName+ '"/>'+
							            'Tags <input class="tags" value="" />' +
							            'Descricao <textarea class="descricao" value="" />' +
							            '<input type="hidden" val="' + this.items[i].name + '" />' + 
							        '</span>' +
							        '<div class="erro" style="display:none;">Erro</div>' + 
						        '</span>';
					}
 				} else {
 					$('#mensagensCentralizadas').html('<div class="msgAlerta"> Arquivo já está na lista! </div> <br class="clear">');
					$('#mensagensCentralizadas').fadeIn().delay( 1200 ).fadeOut();
 				}
			}
			$("#dragAndDropFiles").append(html);
			
			$.map($('.tituloMultiUpload'), function(i){
				var patt = /inativo/g
				patt.test($(i).attr('class')) ? $(i).next().fadeOut() : $(i).next().fadeIn();
			});
		}
	}

	multiUploader.prototype._arrayExists = function(item){
	    return $.map(self.all, function(value, index){
			if(value.name == item.name){
				return 1;
			}
		}).length;
	}
	
	multiUploader.prototype._arrayPush = function(fileList){
		var tamanho = fileList.length;
		for(var i = 0; i< tamanho; i++){
			var existe = self._arrayExists(fileList[i]);
			existe ? null : self.all.push(fileList[i]);
		}
	}
	
	multiUploader.prototype._read = function(evt){
		if(evt.target.files){
			self._preview(evt.target.files);
			self._arrayPush(evt.target.files);
			self._fileReader(evt);
		} else {
			console.log("Failed file reading");
		}
	}
	
	multiUploader.prototype._fileReader = function(evt){
		
		var fileList = evt.target.files;
		var tamanho = fileList.length;
		var idList = new Array();
		for(var i = 0; i< tamanho; i++){
			var reader = new FileReader();
			idList.push(fileList[i].name._unique());
			reader.onload = function(e){
				uId = idList.shift();
				var img = new Image;
				var canvas = $('canvas[rel="' +uId +  '"]')[0];
	            img.onload = function(){
	            	var context = canvas.getContext( '2d' );
	            	var world = new Object();
	                world.width = canvas.offsetWidth;
	                world.height = canvas.offsetHeight;
	                canvas.width = world.width;
	                canvas.height = world.height;
	                if( typeof img === "undefined" ){
	                	return;
	                }
	                var WidthDif = img.width - world.width;
	                var HeightDif = img.height - world.height;
	                var Scale = 0.0;
	                if( WidthDif > HeightDif ){
	                    Scale = world.width / img.width;
	                } else {
	                    Scale = world.height / img.height;
	                }
	                if( Scale > 1 ) { 
	                	Scale = 1;
	                }
	                var UseWidth = Math.floor( img.width * Scale );
	                var UseHeight = Math.floor( img.height * Scale );
	                var x = Math.floor( ( world.width - UseWidth ) / 2 );
	                var y = Math.floor( ( world.height - UseHeight ) / 2 );
	                context.drawImage( this, x, y, UseWidth, UseHeight ); 
	            } 
	            img.src = e.target.result;
				
			}
			reader.readAsDataURL(fileList[i]);
		}
	}
	
	multiUploader.prototype._validate = function(format){
		var arr = this.config.support.split(",");
		return arr.indexOf(format);
	}
	
	multiUploader.prototype._dropFiles = function(e){
		e.stopPropagation(); e.preventDefault();
		self._preview(e.dataTransfer.files);
		self._arrayPush(e.dataTransfer.files);
		self._fileReader(e);
	}
	
	multiUploader.prototype._uploader = function(){
		
		var file = self.all.shift();
		
		if(typeof file != undefined && self._validate(file.type) >= 0){
			
			var data = new FormData();
			var ids = file.name._unique();
			
			data.append('file',file);
			data.append('index',ids);
			data.append('nome', $(".dfiles[rel='"+ids+"']").find('.titulo').val());
			var descricao = $(".dfiles[rel='"+ids+"']").find('.descricao').val();
			!descricao ? null : data.append('descricao', descricao);
			
			var tags = $(".dfiles[rel='"+ids+"']").find('.tags').val();
			!tags ? null : data.append('tags', tags);
			$(".dfiles[rel='"+ids+"']").find(".progress").show();
			
			$.ajax({
				type:"POST",
				url:this.config.uploadUrl,
				data:data,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success : function(response){
					
					if(response.success == true){
						$('#mensagensCentralizadas').html('<div class="msgSucesso">' + response.data + '</div> <br class="clear">');
						$('#mensagensCentralizadas').fadeIn().delay( 1200 ).fadeOut();
						$(".dfiles[rel='" + ids + "']").remove();
					} else {
						$('#mensagensCentralizadas').html('<div class="msgFalha">' + response.data + '</div> <br class="clear">');
						$('#mensagensCentralizadas').fadeIn().delay( 1200 ).fadeOut();
						$(".dfiles[rel='"+ids+"']").find('div[class="erro"]').html('Erro: ' + response.data + '<span class="closeImgErro pointer">X</span>').fadeIn();
						$(".dfiles[rel='"+ids+"']").find(".progress").remove();
						$(".dfiles[rel='"+ids+"']").find('div[class="inputs"]').remove();
					}
					
					if(self.all.length > 0){
						self._uploader();
					}
					
				}
			});
		} else {
			console.log("Invalid file format - " + file.name);
		}
	}
	
	multiUploader.prototype._startUpload = function(){
		if(this.all.length > 0){
				this._uploader();
		}
	}
	
	String.prototype._unique = function(){
		return this.replace(/[a-zA-Z]/g, function(c){
     	   return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    	});
	}

	this._init();
}

function initMultiUploader(){
	new multiUploader(config);
}