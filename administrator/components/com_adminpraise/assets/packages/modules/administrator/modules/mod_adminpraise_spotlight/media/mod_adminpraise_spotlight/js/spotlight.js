var spotlight = new Class({
	request: null,
	container: null,
	inside: false,
	
	initialize: function() {
		this.request = new Request.JSON({
			method: 'post'
		});
		
		var search = this.searchInput = document.id('ap-search');
		this.container = new Element('div',{
			id: 'search-results'
		}).inject(document.id('ap-spotlight'),'after');

		document.id('ap-spotlight').addEvent('submit', function(e) {
			e.stop();
		});
		
		search.addEvent('keyup', function() {
			if(search.get('value').length == 0) {
				this.container.set('html', '');
				this.container.setStyle('visibility', 'hidden');
			}
			if(search.get('value').length > 2) {
				this.search();
			}
		}.bind(this));
		this.container.addEvents( {
			'mouseover': function() {
				this.inside = true;
			}.bind(this),
			'mouseout': function() {
				this.inside = false;
			}.bind(this)
		});
		document.id(document.body).addEvent('click', function(e) {
			if(!this.inside) {
				this.container.setStyle('visibility', 'hidden');
			}
		}.bind(this));
	},
	
	search: function() {
		var self = this;
		this.request.setOptions({
			url: self.searchInput.getParent('form').get('action'),
			data: document.id('ap-spotlight'),
			link: 'cancel',
			onComplete: function(data) {
				self.container.set('html', '');
				self.container.setStyle('visibility', 'visible');
				if(data.length) {
					data.each(function(el) {
						
						var type = new Element('div', {
							'class': 'ap-type',
							'html': el[0].elementType
						}).inject(self.container);
						el.each(function(link) {
							var div = new Element('div', {
								'class': 'ap-link'
							}).adopt([
								new Element('a', {
									'href' : link.href,
									'class':'ap-spotlight-link',
									'html':link.name
								}),
								new Element('div', {
									'class' : 'ap-search-text',
									'html' : link.text
								})
								]).inject(self.container);                 
						});
					});
				} else {
					var div = new Element('div', {
						'class': 'ap-empty',
						html: 'Sorry, we couldn\'t find anything matching your search criteria'
					}).inject(self.container);
				}
			}
		});
		this.request.send();
	}
});

window.addEvent('domready', function() {
	var spotlightSearch = new spotlight();
});

