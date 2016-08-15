<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	window.addEvent('domready', function(){
		Joomla.submitbutton = function(task) {
			if ( task == 'airlineregister.additional' ) {
			//alert('Enter the required fields first');
			SqueezeBox.initialize(); 
			SqueezeBox.open( '<?php echo JRoute::_('index.php?option=com_sfs&view=airlineregister&layout=addcontact&tmpl=component&Itemid='.JRequest::getInt('Itemid'));?>', {	
				handler: 'iframe', 			
				size: {x: 670, y: 520},
				onClose: function(){ 						 
					var result = $('additional_contacts');	  		     
					new Request.HTML({
						url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.getAirlineAdditionalContact',
						update: result,			       
						onRequest: function(){
							result.empty().addClass('ajax-loading'); 
						},
						onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
							result.removeClass('ajax-loading');
							result.addClass("block border orange");
						}
					}).send();			
				}
			})
		} 
	var flag = 0;
		for (var i = 0; i < 2; i++) {
			flag += document.getElementById('job_title_'+i).value == ''? 0 : 1;
			flag += document.getElementById('name_'+i).value == ''? 0 : 1;
			flag += document.getElementById('surname_'+i).value == ''? 0 : 1;
			flag += document.getElementById('email_'+i).value == ''? 0 : 1;
			flag += document.getElementById('phone_code_'+i).value == ''? 0 : 1;
			flag += document.getElementById('fax_code_'+i).value == ''? 0 : 1;
			
		}
		if (flag>0 && flag<12) {
			location.href = "<?php echo htmlspecialchars_decode(JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid')));?>";
		} 
		else{
			if (document.formvalidator.isValid(document.id('airlineContactRegisterForm'))) {	
				if ( task == 'airlineregister.savecontacts' ) {
					Joomla.submitform(task, document.getElementById('airlineContactRegisterForm'));
				} 
 			} 
			
		}
}
});	
</script>

<div class="heading-block clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo JText::_('COM_SFS_AIRLINE_CONTACT_DETAILS_SIGN_UP')?></h3>        
	</div>
</div>
<div class="main">
	<div class="info-block bg light-blue">
		<?php
		$text = SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_02'));
		echo empty($text) ? JText::_('COM_SFS_AIRLINE_REGISTER_CONTACTS_DESC') : $text;
		
		$text = SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_03'));
		echo empty($text) ? JText::_('COM_SFS_AIRLINE_CONTACT_PERSONS_DESC') : $text;
		
		?>
	</div>

	<div id="airline-registration">	
		<form name="airlineContactRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" id="airlineContactRegisterForm" class="form-validate sfs-form form-vertical register-form">	    
			<div class="block-group">            
				<?php 
				$session 		= JFactory::getSession();
				$airContacts 	= $session->get('airContacts');
				
				if(count($airContacts)) {
					$n = count($airContacts);
					foreach ($airContacts as $key => $value) {
						$this->contact_item = $value;
						$this->contact_index = $key;
						$this->main_contact = $value['main_contact'];
						echo $this->loadTemplate('fields');
					}
					
				}  else {
					$n = 2;
					$i=0;
					while ( $i < $n ){		
						$this->contact_item = null;		
						if(isset($airContacts[$i])) $this->contact_item = $airContacts[$i];
						$this->contact_index = $i;
						
						if($i==0) {
							$this->main_contact = 1;
						} else {
							$this->main_contact = 0;
						}
						
						echo $this->loadTemplate('fields');
						$i++;	                	
					}
				}             												
				?>

				<div id="additional_contacts"></div>

				<div class="form-group">
					<button type="button" onClick="Joomla.submitbutton('airlineregister.additional')") class="btn orange lg pull-right" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_add_contact', $text, 'airline'); ?>">
						<?php echo JText::_('COM_SFS_AIRLINE_ADDITIONAL_CONTACT_BUTTON')?>
					</button>
				</div>
			</div>

			<div class="form-group">
				<div class="airline-note">
					<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_STEP2_NOTE')?>
				</div>                    
				<button onClick="Joomla.submitbutton('airlineregister.savecontacts')") class="btn orange lg pull-right">
					<?php echo JText::_('COM_SFS_AIRLINE_SAVE_TO_LAST_BUTTON')?>
				</button>
			</div>

			<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>                    
		</form>
	</div>
</div>