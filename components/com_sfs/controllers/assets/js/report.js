/**
 * Ajax script for report
 */


function airlineReport(dateFrom,dateTo,ghAirline) {
	
	new Fx.Scroll(window).toElement($('your-report'));	
	
	$('roomnights').empty().addClass('report-ajax-loading');
	$('average').empty().addClass('report-ajax-loading');
	$('revenue').empty().addClass('report-ajax-loading');
	$('iatacode').empty().addClass('report-ajax-loading');
	$('marketpicked').empty().addClass('report-ajax-loading');
	$('transportation').empty().addClass('report-ajax-loading');
	$('initial-blocked').empty().addClass('report-ajax-loading');
	
	var reportRequests = {
		r1 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.tophotels&type=1',
		       method: 'get',		  
		       update: $('roomnights'),
		       onSuccess: function(txt){
		    	   $('roomnights').removeClass('report-ajax-loading'); 
		       }
		}),
		r2 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.tophotels&type=2',
		       method: 'get',
		       update: $('average'),	   
		       onSuccess: function(txt){
		    	   $('average').removeClass('report-ajax-loading'); 
		       }
		}), 
		r3 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.tophotels&type=3',
		       method: 'get',
		       update: $('revenue'),	       
		       onSuccess: function(txt){
		    	   $('revenue').removeClass('report-ajax-loading'); 
		       }
		}), 			
		r4 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.percentage&type=1',
		       method: 'get',
		       update: $('iatacode'),	       
		       onSuccess: function(txt){
		    	   $('iatacode').removeClass('report-ajax-loading'); 
		       }
		}),
		r5 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.percentage&type=2',
		       method: 'get',
		       update: $('marketpicked'),	       
		       onSuccess: function(txt){
		    	   $('marketpicked').removeClass('report-ajax-loading'); 
		       }
		}), 
	    
		r6 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.percentage&type=3',
		       method: 'get',
		       update: $('transportation'),	       
		       onSuccess: function(txt){
		    	   $('transportation').removeClass('report-ajax-loading'); 
		       }
		}), 
		r7 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.percentage&type=4',
		       method: 'get',
		       update: $('initial-blocked'),	       
		       onSuccess: function(txt){
		    	   $('initial-blocked').removeClass('report-ajax-loading'); 
		       }
		})     
	};
	  
	var requestQueue = new Request.Queue({
	    requests: reportRequests	
	});
	
	reportRequests.r1.send('date_from='+dateFrom+'&date_to='+dateTo+'&gh_airline='+ghAirline);	
	reportRequests.r2.send('date_from='+dateFrom+'&date_to='+dateTo+'&gh_airline='+ghAirline);	
	reportRequests.r3.send('date_from='+dateFrom+'&date_to='+dateTo+'&gh_airline='+ghAirline);
	reportRequests.r4.send('date_from='+dateFrom+'&date_to='+dateTo+'&gh_airline='+ghAirline);
	reportRequests.r5.send('date_from='+dateFrom+'&date_to='+dateTo+'&gh_airline='+ghAirline);
	reportRequests.r6.send('date_from='+dateFrom+'&date_to='+dateTo+'&gh_airline='+ghAirline);
	reportRequests.r7.send('date_from='+dateFrom+'&date_to='+dateTo+'&gh_airline='+ghAirline);
}

function hotelReport(m_from,y_from,m_to,y_to){
	new Fx.Scroll(window).toElement($('your-report'));	
	
	$('roomnights').empty().addClass('report-ajax-loading');
	$('average').empty().addClass('report-ajax-loading');
	$('revenue').empty().addClass('report-ajax-loading');	
	
	var reportRequests = {
		r1 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.hotelreport&type=1',
		       method: 'get',		  
		       update: $('roomnights'),
		       onSuccess: function(txt){
		    	   $('roomnights').removeClass('report-ajax-loading'); 
		       }
		}),
		r2 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.hotelreport&type=2',
		       method: 'get',
		       update: $('average'),	   
		       onSuccess: function(txt){
		    	   $('average').removeClass('report-ajax-loading'); 
		       }
		}), 
		r3 : new Request.HTML({
		       url: siteurl+'index.php?option=com_sfs&format=raw&task=report.hotelreport&type=3',
		       method: 'get',
		       update: $('revenue'),	       
		       onSuccess: function(txt){
		    	   $('revenue').removeClass('report-ajax-loading'); 
		       }
		})
	}
	var requestQueue = new Request.Queue({
	    requests: reportRequests	
	});
	
	reportRequests.r1.send('m_from='+m_from+'&y_from='+y_from+'&m_to='+m_to+'&y_to='+y_to);	
	reportRequests.r2.send('m_from='+m_from+'&y_from='+y_from+'&m_to='+m_to+'&y_to='+y_to);	
	reportRequests.r3.send('m_from='+m_from+'&y_from='+y_from+'&m_to='+m_to+'&y_to='+y_to);		
}
