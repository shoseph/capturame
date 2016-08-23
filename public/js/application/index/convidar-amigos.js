jQuery.convidarAmigos = function( param ){
	
	var owner = this;
	var flagConvidarAmigos = false;
	
	/** Construtor da classe */
	this.__constructor = function()
	{
		owner.inicio();
		owner.btnConvidarAmigos();
	}
	
	this.inicio = function()
	{

		FB._https = (window.location.protocol == "https:");
		FB.init({
			appId:'154431104749038',
			cookie:true,
			status:true,
			xfbml:true,
			frictionlessRequests : true
		});
	}
	
	this.getToken = function()
	{
	   var token = null;
	   FB.getLoginStatus(function (response) {
		   if (response.status === 'connected') {
			   FB.api('/me', function (response) {
				   if (response.session) {
					   token  = response.session.access_token;
				   }
			   });
		   } else {
               FB.login(function (response) {
	    	       if (response.session) {
	    		       token  = response.session.access_token;
	    		   }
	    	   });
	       }
	    });
		return token;
	}
	
	this.btnConvidarAmigos = function()
	{
		
		$('#convidarAmigos').click(function(e){
			if(flagConvidarAmigos == false)
			{
				flagConvidarAmigos = true;
				var params = {};
				var mentions = new Array();
				params['access_token'] = owner.getToken();
				
				FB.api({
				    method: 'fql.query',
				    query: "SELECT uid,name FROM user WHERE uid IN (SELECT uid2 FROM friend where uid1 = me()) ORDER BY rand() LIMIT 50"
				}, function(response){
					
					var length = response.length;
					for (var i = 0; i < length; i++) {
//						mentions.push('@[' + response[i].uid + ':1:' + response[i].name + '.]');
						mentions.push(response[i]);
					}
					
//					options = {
//					    message: 'Captura convida você!'
////					    	, 
////					    with_tags: { data : mentions }
//					};
					
					var body = 'Reading JS SDK documentation';
					FB.api('/me/feed', 'post', { message: body }, function(response) {
					  if (!response || response.error) {
					    alert('Error occured');
					  } else {
					    alert('Post ID: ' + response.id);
					  }
					});
					
//					FB.api('/me/feed', 'post', options, function(response) {
//						console.info(response);
//						if (response && response.post_id) {
//							alert('Post was published.');
//						} else {
//							console.info(response);
//							alert('puts!.');
//						}
//						flagConvidarAmigos = false;
//					});
					
					
					
//					FB.ui({
//						method: 'feed',
//						link : 'http://captura.me',
//						picture: 'http://captura.me/images/captura2/logo.png',
//						name: 'Facebook chamando você para capturar',
//						caption: 'Entre no Captura.Me',
////						message: mentions.join(' '),
//						message: '@[100001315843813:1:Jivarlos Cruz.]',
//						description: 'Convide novos amigos a entrarem em nosso portal de imagens livres! ',
//					},function(response) {
//						console.info(response);
//						if (response && response.post_id) {
//							alert('Post was published.');
//						} else {
//							console.info(response);
//							alert('puts!.');
//						}
//						flagConvidarAmigos = false;
//					});
				});
				
				
			}
		});
		
	}

	/**
	 * retorna a instancia do objeto
	 **/
	return this;
};
$(document).ready(function(){ $.convidarAmigos().__constructor(); });