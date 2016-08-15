<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<span class="xclose" onclick="window.parent.SqueezeBox.close();"><?php echo JText::_('COM_SFS_CLOSE')?></span>

<h1 class="title1">
	<?php echo JText::sprintf('COM_SFS_TAXI_RATE_AGREEMENT_FOR', $this->taxi->name );?>
</h1>
<div class="fs-16">
	<?php echo JText::_('COM_SFS_TAXI_RATE_AGREEMENT_DESC')?>
</div>

<div id="taxiRateFormWraper" class="fs-14">
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
		
		<div class="rate-period">
			<?php echo $this->loadTemplate('period');?>
		</div>
		
		<?php if( count($this->hotels) ):?>
		<div class="rate-hotels">
			<?php echo $this->loadTemplate('hotels');?>
		</div>
		<?php endif;?>
		
		<?php if($this->airline->allowEditTaxiDetails() ) : ?>
		<div class="float-right" style="padding: 0 20px 10px 0;">			
			<button type="submit" class="validate small-button float-left" style="margin:0 0 0 15px;">
				<?php echo JText::_('COM_SFS_SAVE')?>
			</button>
		</div>
		<?php endif;?>
		
		<input type="hidden" name="task" value="taxi.saveRate" /> 
		<input type="hidden" name="option" value="com_sfs" />		
		<input type="hidden" name="taxi_id" value="<?php echo $this->taxi->taxi_id; ?>" />		
		
		<?php echo JHtml::_('form.token'); ?>
		
	</form>	
			
</div>

