<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task) {
        if (task == 'hotel.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>
<?php 
$options = array();
foreach($this->airports as $key=>$value) :        
        $options[] = JHTML::_('select.option',$value->id, $value->code);
endforeach;
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit_ws&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
<div style="height: 10px;">&#160;</div>
<div class="width-100">    
    <fieldset class="adminform">
        <legend>Hotel Details</legend>
        <ul class="adminformlist">
            <li>
                <label for="hotel-name">Hotel Name:</label>
                <input type="text" size="40" name="address[name]" id="hotel-name" class="required" value="<?php echo $this->item->name;?>">
            </li>

	<div class="" style="overflow:hidden;float:left;padding:10px 0 0;margin:0 0 0 25px; width:auto;">
		<h3 style="padding:0px 0px 10px 0;margin:0;">
			<?php if( empty($this->todayInventory) ) : ?>
			The hotel did not loaded the rooms today.
			<?php else: ?>
			The hotel loaded the rooms today.
			<?php endif;?>
		</h3>
            <li>
                <label>Star</label>
                <select name="address[star]" class="inputbox">
                    <?php echo JHtml::_('select.options', SfsHelper::getStarOptions(), 'value', 'text', $this->item->star);?>
                </select>
            </li>
            <li>
                <label>Airport</label>
                <select name="airport" class="inputbox">
                    <?php echo JHtml::_('select.options', $options, 'value', 'text', $this->item->airport_id);?>
                </select>
            </li>
            <li>
                <label>Chain affiliation:</label>
                <input type="text" readonly="readonly" class="readonly" size="22" value="<?php echo $this->item->chain_id ? SfsHelperField::getChainName($this->item->chain_id):'none';?>">
            </li>
            <li>
                <label>Created Date:</label>
                <input type="text" size="30" value="<?php echo JHTML::_('date',$this->item->created_date, JText::_('DATE_FORMAT_LC2')); ?>" readonly="readonly" class="readonly">
            </li>

            <li>
                <label for="jform_block">Block this Hotel</label>
                <fieldset class="radio" id="jform_block">
                    <input type="radio" <?php if($this->item->block==0) echo 'checked="checked"';?> value="0" name="block" id="jform_block0"><label for="jform_block0">No</label>
                    <input type="radio" <?php if($this->item->block==1) echo 'checked="checked"';?> value="1" name="block" id="jform_block1"><label for="jform_block1">Yes</label>
                </fieldset>
            </li>

	<div style="overflow:hidden;float:left;padding:10px 0 0;margin:0 0 0 25px; font-size:16px;">
	<?php
	
	$airlineSent =SfsHelper::getAirlineSendNotification($this->item->id);
	if(count($airlineSent) > 0 ) :
	?> <?php foreach ($airlineSent as $u ): ?>  
    	Invited hotel by <?php echo $u->airline_alliance;?> username <a target="_blank" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . $u->uid); ?>">
    <span class="icon-32-options"> </span>
    <?php echo $u->username;?>
    </a> <?php echo $u->airline_alliance;?> has a contract with the hotel <br />
                <?php endforeach;?>
		<?php else :
        $sentUsers =SfsHelper::getSendNotification($this->item->id);
        if(count($sentUsers)) :
        ?>
            <?php foreach ($sentUsers as $u ): ?>                
                <?php echo $u->name;?> sent at <?php echo JHtml::_('date',$u->date, JText::_('DATE_FORMAT_LC2'));?><br />
            <?php endforeach;?>
        <?php endif;?>
    <?php endif;?>
	</div>
            <li>
                <label>Hotel ID</label>
                <input name="id" type="text" readonly="readonly" class="readonly" size="10" value="<?php echo $this->item->id;?>">
            </li>
        </ul>
        <iframe src="<?php echo  JURI::root() . 'index.php?option=com_sfs&view=hoteldetailadm&tmpl=component&id='. $this->item->id;  ?>" width = 100% height=280px >           
        </iframe>
        
    </fieldset>

    <fieldset class="adminform">
        <?php echo $this->loadTemplate('address');?>
    </fieldset>  

</div>

<div>
    <input type="hidden" name="airport" value="<?php echo $this->item->airport_id ?>">
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
    <input type="hidden" name="tmpl" value="<?php echo JRequest::getVar('tmpl')?>" />
    <?php echo JHtml::_('form.token'); ?>
</div>

</form>

