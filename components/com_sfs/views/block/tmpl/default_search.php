<?php
defined('_JEXEC') or die;
?>
<h3>
    <?php 
		echo SfsHelper::htmlTooltip('block_status_search_title', JText::_('COM_SFS_SEARCH') ,'hotel');
    ?> 
</h3>


    <div class="col w20">
        <div class="form-group" data-step="7" data-intro="<?php echo SfsHelper::getTooltipTextEsc('search_date_from', $text, 'hotel'); ?>">
            <label><?php echo JText::_('COM_SFS_FROM');?>:</label>
            <?php SfsHelperField::getCalendar('date_from', $this->state->get('block.from'));?>
        </div>
    </div>

    <div class="col w20">
        <div class="form-group" data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('search_date_to', $text, 'hotel'); ?>">
            <label><?php echo JText::_('COM_SFS_TO');?>:</label>
            <?php SfsHelperField::getCalendar('date_to', $this->state->get('block.to'));?>
        </div>
    </div>

    <div class="col w15">
        <div class="form-group" data-step="9" data-intro="<?php echo SfsHelper::getTooltipTextEsc('block_code', $text, 'hotel'); ?>">
            <label><?php echo JText::_('COM_SFS_BLOCK_CODE');?>:</label>
            <input type="text" name="blockcode" class="inputbox" value="<?php echo $this->state->get('block.code')?>" />
        </div>
    </div>

    <div class="col w15">
        <div class="form-group" data-step="10" data-intro="<?php echo SfsHelper::getTooltipTextEsc('status_select', $text, 'hotel'); ?>">
            <label><?php echo JText::_('COM_SFS_STATUS');?>:</label>
            <select name="blockstatus">
                <option value="0">Select Status</option>
                <option value="O"<?php if($this->state->get('block.status')=='O') echo ' selected="selected"'; ?>><?php echo JText::_('COM_SFS_BLOCK_OPEN');?></option>
                <option value="T"<?php if($this->state->get('block.status')=='T') echo ' selected="selected"'; ?>><?php echo JText::_('COM_SFS_BLOCK_TENTATIVE');?></option>
                <option value="C"<?php if($this->state->get('block.status')=='C') echo ' selected="selected"'; ?>><?php echo JText::_('COM_SFS_BLOCK_CHALLENGED');?></option>
                <option value="A"<?php if($this->state->get('block.status')=='A') echo ' selected="selected"'; ?>><?php echo JText::_('COM_SFS_BLOCK_APPROVED');?></option>
            </select>
        </div>
    </div>

    <div class="col w30">        
        <div class="col w50">
            <button type="submit" class="btn orange lg btn-block" style="margin-top:18px" data-step="11" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_search', $text, 'hotel'); ?>"><?php echo JText::_('COM_SFS_SEARCH');?></button>
        </div>
        <div class="col w50">
            <button type="reset" class="btn orange lg btn-block" style="margin-top:18px"><?php echo JText::_('COM_SFS_RESET');?></button>
        </div>
    </div>
