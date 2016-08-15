<?php
defined('_JEXEC') or die;

$profile = null;
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
		Are you sure to remove profile: <strong><?php echo $profile->name?></strong>?
	</div>		
	
	<div class="largemargintop">		
		<button onclick="window.parent.SqueezeBox.close();" type="button" class="small-button float-left" style="margin:0 0 0 55px;">No</button>
		<button type="submit" class="small-button float-left" style="margin:0 0 0 15px;">Yes</button>
	</div>

	<input type="hidden" name="task" value="bus.removeProfile" /> 
	<input type="hidden" name="option" value="com_sfs" />
	<input type="hidden" name="profile_id" value="<?php echo $this->profile_id;?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" /> 	
	
	<?php echo JHtml::_('form.token'); ?>
	
</form>

<?php endif;?>