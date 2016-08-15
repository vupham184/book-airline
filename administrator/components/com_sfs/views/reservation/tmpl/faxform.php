<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$airlineId = JRequest::getInt('airline_id',0);
?>

<div>
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">
	
		<fieldset>
			<div class="fltrt">
				<button type="submit">
					Resend
				</button>			
				<button onclick="  window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				Resend block fax
			</div>
		</fieldset>
		
		
		<iframe src="<?php echo JURI::root().'media/sfs/attachments/faxblock'.JRequest::getInt('id').'.html'?>" width="100%" height="500"></iframe>
		
		<input type="hidden" name="task" value="reservation.sendFax" /> 
		<input type="hidden" name="option" value="com_sfs" />				
		<input type="hidden" name="reservation_id" value="<?php echo JRequest::getInt('id'); ?>" />		
		
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>

