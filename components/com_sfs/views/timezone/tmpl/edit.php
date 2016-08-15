<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	
    // The elements used.
	var timeZoneForm = document.id('timeZoneForm');
	  // 	Labels over the inputs.
	timeZoneForm.getElements('select').each(function(el){
    	new OverText(el);
	});
	// Validation.
	new Form.Validator(timeZoneForm); 		
	
});
</script>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Time zone</h3>
	</div>
</div>
<div id="sfs-wrapper" class="main">
	<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post" id="timeZoneForm">
	
		<div class="sfs-main-wrapper">
			<div class="sfs-orange-wrapper">
			
				<div class="sfs-white-wrapper" style="margin-bottom:25px;">
					It's important that we note the correct time and date please check the information below and adjust is neccesary.		
				</div>
				
				<div class="sfs-white-wrapper">
					
					Date and Time <br />
					
					Today it is: Date: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('DATE_FORMAT_LC3'));?><br />
					
					Our timezone is: <?php echo SfsHelperField::getTimeZone( $this->user->getParam('timezone') );?><br />				
					
					Local time is: <?php echo JHTML::_('date',JFactory::getDate()->toSQL(), JText::_('H:i A'));?>								
											
				</div>			
				
			</div>
		</div>
		
		<button type="submit" class="button">Save</button>
		
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
		<input type="hidden" name="task" value="user.timezone" />
				
		<?php echo JHtml::_('form.token'); ?>
	
	</form>
	
</div>