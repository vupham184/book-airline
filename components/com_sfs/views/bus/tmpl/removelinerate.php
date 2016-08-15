<?php
defined('_JEXEC') or die;
$profile = null;

$first = JRequest::getInt('rate_first');
$second = JRequest::getInt('rate_second');
$three = JRequest::getInt('rate_three');

foreach ($this->profiles as $row)
{
	if( (int)$row->id == $this->profile_id )
	{		
		$profile = $row;
		break;
	}
}
if($profile):
?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post">
	
	<div class="fs-14 midmargintop">
		Are you sure to remove Rate line:
		<strong>Distance <?php echo $first." To ". $second. " => Rate:". $three ?></strong> ?
	</div>		
	
	<div class="largemargintop">		
		<button onclick="window.parent.SqueezeBox.close();" type="button" class="small-button float-left" style="margin:0 0 0 55px;">No</button>
		<button type="submit" class="small-button float-left" style="margin:0 0 0 15px;">Yes</button>
	</div>

	<input type="hidden" name="task" value="bus.removeLineRate" /> 
	<input type="hidden" name="option" value="com_sfs" />
	<input type="hidden" name="profile_id" value="<?php echo $this->profile_id;?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" /> 	
	<input type="hidden" name="rate_first" value="<?php echo JRequest::getInt('rate_first');?>" />
	<input type="hidden" name="rate_second" value="<?php echo JRequest::getInt('rate_second');?>" />
	<input type="hidden" name="rate_three" value="<?php echo JRequest::getInt('rate_three');?>" /> 	
	
	<?php echo JHtml::_('form.token'); ?>
	
</form>
<?php endif;?>
