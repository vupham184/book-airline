function adminPraiseCheckFrame() {
	if(typeof parent.frames[0] != 'undefined') {
		window.parent.document.getElementById('sbox-window').close();
		
		var systemMessage = document.getElementById('system-message');

		var div = new Element('div', {
			'class': 'adminpraise-modal-system-message',
			html: systemMessage.get('html')
		});
		
		div.inject(window.parent.document.body);
		
		setTimeout(function() {
			div.destroy();
		}, 2000)
	}
}

function adminPraiseUnpublishModule(id) {
	var adminPraiseRequest = new Request({
			url: adminPraiseLiveSite + 'index.php?option=com_adminpraise&view=ajax&task=unpublishModule&format=raw' ,
			method: 'get',
			data: 'id='+id,
			onComplete: function(response) {
				var html = 'Module Removed';
				if(response) {
					html = response;
				} else {
					document.id('module-'+id).destroy();
				}
				var div = new Element('div', {
					'class': 'adminpraise-system-notification',
					'html': html
				}).inject(document.body);
				
				setTimeout(function(){
					div.destroy();
					
				},3000);
			}
		});

		adminPraiseRequest.send();
}