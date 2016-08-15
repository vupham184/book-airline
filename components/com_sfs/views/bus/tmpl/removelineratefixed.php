<?php
defined('_JEXEC') or die;
$rate = JRequest::getInt("rate");
$to = JRequest::getVar("airport_to");
?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post">
	
	<div class="fs-14 midmargintop">
		Are you sure to remove Rate line fixed:
		<strong>From DUS to <?php echo $to; ?>  Rate: <?php echo $rate; ?></strong> ?
	</div>		
	
	<div class="largemargintop">		
		<button onclick="window.parent.SqueezeBox.close();" type="button" class="small-button float-left" style="margin:0 0 0 55px;">No</button>
		<button type="submit" class="small-button float-left" style="margin:0 0 0 15px;">Yes</button>
	</div>

	<input type="hidden" name="task" value="bus.removeLineRateFixed" /> 
	<input type="hidden" name="option" value="com_sfs" />
	<input type="hidden" name="profile_id" value="<?php echo JRequest::getInt('profile_id');?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" /> 	
	<input type="hidden" name="airport_to" value="<?php echo JRequest::getVar("airport_to");?>" />
	<input type="hidden" name="rate" value="<?php echo JRequest::getInt('rate');?>" />	
	
	<?php echo JHtml::_('form.token'); ?>
	
</form>

