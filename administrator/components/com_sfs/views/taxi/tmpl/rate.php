<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<div id="taxiRateFormWraper" class="fs-14" style="margin:0;">
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">
	
		<fieldset>
			<div class="fltrt">
				<button type="submit">
					Save
				</button>			
				<button onclick="  window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				<?php echo JText::sprintf('COM_SFS_TAXI_RATE_AGREEMENT_FOR', $this->taxi->name );?>
			</div>
		</fieldset>
		
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
			<div class="rate-period">
				<?php echo $this->loadTemplate('period');?>
			</div>
			<div class="clr"></div>
			<?php if( count($this->hotels) ):?>
			<div class="rate-hotels">
				<?php echo $this->loadTemplate('hotels');?>
			</div>
			<?php endif;?>
		</div>
		
		<input type="hidden" name="task" value="taxi.saveRate" /> 
		<input type="hidden" name="option" value="com_sfs" />		
		<input type="hidden" name="taxi_id" value="<?php echo $this->taxi->id; ?>" />
		<input type="hidden" name="airline_id" value="<?php echo $this->taxi->airline_id; ?>" />		
		
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>

