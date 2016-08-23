jQuery.scroll = function( param ){
	
	var owner = this;
	var id = null;
	/** Construtor da classe */
	this.__constructor = function(){
		
	}
	
	this.create = function(id)
	{
		var id = this.id = id;
		pane = $(id);
		$(id).hasClass('scroll-pane') ? '': $(id).addClass('scroll-pane');
		pane.jScrollPane({
			showArrows: false,
			autoReinitialise: true,
			mouseWheelSpeed : 20,
			verticalDragMaxHeight : 50
		});
		
		contentPane = pane.data('jsp').getContentPane();
		pane.find('.jspVerticalBar').hide();
		
		pane.hover(function(){
			pane.find('.jspVerticalBar').stop(true,true).fadeIn();
		},function(){
			pane.find('.jspVerticalBar').stop(true,true).fadeOut();
		});
	}
	
	this.getContentPane = function(id){
		return $(id).data('jsp').getContentPane();
	}
	

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.scroll().__constructor(); });



