window.addEvent('domready', function(){ 
	$('activitylog-reset').addEvent('click', function() {
		reset();
	});
	$('ualog_filter_id').addEvent('change' , function () {
		activityLogSubmitForm();
	});
	
	$('ualog_filter_option').addEvent('change' , function () {
		activityLogSubmitForm();
	})
	
	
});

function reset() {
	$('ualog_filter_id').set('value', 0);
	$('ualog_filter_option').set('value',0);
	var request = new Request.JSON({
		url: activitylogPro.baseRoute + 'index.php?option=com_adminpraise&view=activity&task=reset&format=raw',
		data: $('ualog_form'),
		onProgress: function(event, xhr) {
			
		},
		onComplete: function(data) {
			$('activitylog-data').innerHTML = data.message;
		}
		
	});
	request.send();
}

function activityLogSubmitForm() {
	var request = new Request.JSON( {
		url: activitylogPro.baseRoute+'index.php',
		data: $('ualog_form'),
		method: 'post',
		onComplete: function (data) {
			$('activitylog-data').innerHTML = data;
		}
	});
	
	request.send();
}