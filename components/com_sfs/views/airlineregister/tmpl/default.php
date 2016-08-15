<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<script type="text/javascript">
	window.addEvent('domready', function(){
		
	    // The elements used.
		var airlineRegisterForm = document.id('airlineRegisterForm');
		// Validation.
		var airlineFValidator = new Form.Validator(airlineRegisterForm);
		
		airlineFValidator.add('validate-timezone-check', {
		    errorMsg: 'This field is required.',
		    test: function(field){
			    if(!field.checked) {
			    	$('timezone-error-msg').set('styles', {
			    	    display: 'block'		    	    
			    	});
			    	new Fx.Scroll(window).toElement($('airline-registration')); 
			    } else {
			    	$('timezone-error-msg').set('styles', {
			    	    display: 'none'		    	    
			    	});
			    } 
		    	return !!field.checked;
		    }
		});	 		
		
		
		$('iatacode_id').addEvent('change',function(){	     
		     var result = $('company_name');	    
		     new Request.HTML({
		       url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.iataname&id='+this.value ,
		       update: result,
		       onRequest: function(){
		    	   result.empty().addClass('ajax-loading');
		       },
		       onSuccess: function(txt){
		    	   result.removeClass('ajax-loading'); 
		       }
		     }).send();	     
		});	

		$('airport_id').addEvent('change',function(){
			var code_selected =  $('iatacode_id').getSelected().get('value');	   
			if (code_selected==0) {
				$('airport_id').options[0].selected='selected';
				alert('Please select airline code first');			
			}else {
			     new Request({
			       url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.checkstation&id='+this.value+'&iatacode_id='+code_selected,		      
			       onRequest: function(){
			    	   $('airport-location-loading').empty().addClass('ajax-loading');
			       },
			       onSuccess: function(txt){
			    	   $('airport-location-loading').removeClass('ajax-loading');		       
			    	   if(txt==1) {
			    		   $('airport_id').options[0].selected='selected';
			    		   SqueezeBox.open($('air-error-msg'), {handler: 'clone',size: {x: 300, y: 90}});
			    	   }
			       }
			     }).send();	     			
			}
			 	     	    	    
		});		
		
		$('time_zone').addEvent('change',function(){	     
		     var result = $('time_display');	 
		        
		     new Request.HTML({
		       url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.getTime&id='+this.value ,
		       update: result,
		       onRequest: function(){
		    	   result.empty().addClass('ajax-loading'); 
		       },
		       onSuccess: function(txt){
		    	   result.removeClass('ajax-loading'); 
		       }
		     }).send();	     
		});			
			
		$('copy_address').addEvent('click',function(){	  
			   		
			$('billing_address').set('value', $('office_address').get('value') );	
			$('billing_address1').set('value', $('office_address2').get('value') );	
			$('billing_city').set('value', $('office_city').get('value') );
			$('billing_zipcode').set('value', $('office_zipcode').get('value') );

			var country = $('country_id').getSelected().get('value');

			for (var i = 0; i < $('billingcountry_id').options.length; i++) 
				if ($('billingcountry_id').options[ i ].value == country) {
					$('billingcountry_id').options[ i ].selected='selected';
					break;
				}
					
		});		
	});
</script>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_SIGN_UP')?></h3>        
    </div>
</div>
<div class="main">	
	<div class="info-block bg light-blue">
		<?php echo SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_01'));	?>
	</div>

	<div id="airline-registration" style="overflow: visible;">
		<form id="airlineRegisterForm" name="airlineRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=airlineregister'); ?>" method="post" class="form-validate sfs-form form-vertical register-form">
			<div class="block-group">
				<div class="block border orange">
					<?php echo $this->loadTemplate('detail'); ?>
				</div>
				<div class="block border orange">
					<?php echo $this->loadTemplate('address'); ?>
				</div>
				<div class="block border orange">
					<?php echo $this->loadTemplate('billing'); ?>
				</div>
			</div>
						
			<div class="form-group">
				<div class="airline-note">
					<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_STEP1_NOTE')?>
				</div>								
					<button type="submit" class="btn orange lg validate pull-right"><?php echo JText::_('COM_SFS_SAVE')?></button>				                    				
			</div>

			<input type="hidden" name="task" value="airlineregister.saveairline" />
			<input type="hidden" name="Itemid"
				value="<?php echo JRequest::getInt('Itemid');?>" />
				<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>
