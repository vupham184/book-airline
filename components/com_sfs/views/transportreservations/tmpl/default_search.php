<?php
defined('_JEXEC') or die;
?>
<div>
    <div class="fs-16 midmarginbottom"><?php echo JText::_('COM_SFS_SEARCH');?></div>
</div>      
<div class="clear"></div> 
    
<div class="dateon float-left">
    <div class="fs-14"><?php echo JText::_('COM_SFS_FROM');?>:</div>	                	                	
    <?php SfsHelperField::getCalendar('date_from', $this->state->get('block.from'));?>	              
</div>

<div class="dateto float-left">
    <div class="fs-14"><?php echo JText::_('COM_SFS_TO');?>:</div>	                	                	
    <?php SfsHelperField::getCalendar('date_to', $this->state->get('block.to'));?>	               
</div>

<div class="blockcode float-left">
    <div class="fs-14"><?php echo JText::_('Reference Number');?>:</div>
    <input type="text" name="reference_number" class="inputbox" value="<?php echo $this->state->get('block.reference_number')?>" />
</div>
     
<div class="float-left">
    <input type="submit" value="<?php echo JText::_('COM_SFS_SEARCH');?>" class="small-button" />
</div>
<div class="float-left">
    <button type="reset" class="small-button"><?php echo JText::_('COM_SFS_RESET');?></button>
</div>