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
    <div class="fs-14"><?php echo JText::_('COM_SFS_BLOCK_CODE');?>:</div>
    <input type="text" name="blockcode" class="inputbox" value="<?php echo $this->state->get('block.code')?>" />
</div>
<div class="flightcode float-left">
    <div class="fs-14"><?php echo JText::_('Flight number');?>:</div>
    <input type="text" name="flightcode" class="inputbox" value="<?php echo $this->state->get('block.flightcode')?>" />
</div>
     
<div class="air-status float-left">
    <div class="fs-14"><?php echo JText::_('COM_SFS_STATUS');?>:</div>
    <select name="blockstatus" style="width:120px; padding:2px;">
        <option value="0">Select Status</option>
        <option value="O"<?php if($this->state->get('block.status')=='O') echo ' selected="selected"'; ?>>Open</option>
        <option value="T"<?php if($this->state->get('block.status')=='T') echo ' selected="selected"'; ?>>Tentative</option>
        <option value="C"<?php if($this->state->get('block.status')=='C') echo ' selected="selected"'; ?>>Challenged</option>
        <option value="A"<?php if($this->state->get('block.status')=='A') echo ' selected="selected"'; ?>>Approved</option>
    </select>
</div>
 
<div class="float-left">
    <input type="submit" value="<?php echo JText::_('COM_SFS_SEARCH');?>" class="small-button" />
</div>
<div class="float-left">
    <button type="reset" class="small-button"><?php echo JText::_('COM_SFS_RESET');?></button>
</div>