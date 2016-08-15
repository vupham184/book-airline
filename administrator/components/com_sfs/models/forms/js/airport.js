window.addEvent('domready', function() {
	document.formvalidator.setHandler('code',
		function (value) {
			regex=/^[a-zA-Z0-9]+$/;
			return regex.test(value);
	});
});

