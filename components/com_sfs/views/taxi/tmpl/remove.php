<?php
defined('_JEXEC') or die;
$taxiId = JRequest::getInt('taxi_id');

$taxiName = '';

foreach ($this->taxiCompanies as $taxi)
{
	if( (int)$taxi->taxi_id == $taxiId )
	{
		$taxiName = $taxi->name; 
		break;
	}
}
?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post">
	
	<div class="fs-16 midmargintop">
		Are you sure to remove taxi company <strong><?php echo $taxiName?></strong>?
	</div>		
	
	<div class="largemargintop">		
		<button onclick="window.parent.SqueezeBox.close();" type="button" class="small-button float-left" style="margin:0 0 0 55px;">No</button>
		<button type="submit" class="small-button float-left" style="margin:0 0 0 15px;">Yes</button>
	</div>

	<input type="hidden" name="task" value="taxi.removeTaxi" /> 
	<input type="hidden" name="option" value="com_sfs" />
	<input type="hidden" name="taxi_id" value="<?php echo $taxiId;?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" /> 	
	
	<?php echo JHtml::_('form.token'); ?>
	
</form>