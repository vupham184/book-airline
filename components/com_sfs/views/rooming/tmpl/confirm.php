<?php
defined('_JEXEC') or die;

?>

<h3>Important message:</h3>

<p style="font-size:16px;">
	<br />
	You will be sending the rooming list to: <?php echo $this->airline->name;?>

<br />
<br />
<br />
	The number of vouchers will be used by the airline to determine the charge that they will recive from your hotel. Confirm that you have inserted all vouchers relating to this block code.
</p>

<div class="floatbox" style="padding-top:10px;">
<form name="roomingForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=rooming');?>" method="post">
	<input type="submit" name="confirm" value="Confirm" class="button" style="margin-right:100px;" />
	
	<button onclick="window.parent.SqueezeBox.close();" type="button" class="button float-left" style="margin-left:100px;">Back</button>
	
        
    <input type="hidden" name="task" value="rooming.confirm" />
    <input type="hidden" name="blockcode" value="<?php echo JRequest::getVar('code');?>" />
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />        
    <?php echo JHtml::_('form.token'); ?>
</form>
</div>