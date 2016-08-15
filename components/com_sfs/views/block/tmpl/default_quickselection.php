<?php
defined('_JEXEC') or die;
?>
<h3><?php echo JText::_('COM_SFS_BLOCK_QUICK_SELECTION');?>:</h3>

<div class="roomblock">    
    <div class="quickroom-block" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('quick_selection_open', $text, 'hotel'); ?>">
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&blockstatus=O&Itemid='.JRequest::getInt('Itemid'));?>" title="">
            <span><?php echo JText::_('COM_SFS_BLOCK_OPEN');?></span>
            <strong><?php echo isset( $this->block_count['O'] ) ? $this->block_count['O'] : 0;  ?> <?php echo JText::_('COM_SFS_BLOCK_BLOCKS');?></strong>
        </a>        
     </div>

    <div class="quickroom-block" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('quick_selection_pending', $text, 'hotel'); ?>">
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&blockstatus=P&Itemid='.JRequest::getInt('Itemid'))?>" title="">
            <span><?php echo JText::_('COM_SFS_BLOCK_PENDING');?></span>
            <strong><?php echo isset( $this->block_count['P'] ) ? $this->block_count['P'] : 0;  ?> <?php echo JText::_('COM_SFS_BLOCK_BLOCKS');?></strong>
        </a>        
    </div>

    <div class="quickroom-block" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('quick_selection_tentative', $text, 'hotel'); ?>">
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&blockstatus=T&Itemid='.JRequest::getInt('Itemid'))?>" title="">
            <span><?php echo JText::_('COM_SFS_BLOCK_TENTATIVE');?></span>
            <strong><?php echo isset( $this->block_count['T'] ) ? $this->block_count['T'] : 0;  ?> <?php echo JText::_('COM_SFS_BLOCK_BLOCKS');?></strong>
        </a>        
    </div>

    <div class="quickroom-block" data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('quick_selection_challenged', $text, 'hotel'); ?>">
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&blockstatus=C&Itemid='.JRequest::getInt('Itemid'))?>" title="">
            <span><?php echo JText::_('COM_SFS_BLOCK_CHALLENGED');?></span>
            <strong><?php echo isset( $this->block_count['C'] ) ? $this->block_count['C'] : 0;  ?> <?php echo JText::_('COM_SFS_BLOCK_BLOCKS');?></strong>
        </a>        
    </div>

    <div class="quickroom-block" data-step="5" data-intro="<?php echo SfsHelper::getTooltipTextEsc('quick_selection_approved', $text, 'hotel'); ?>">
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&blockstatus=A&Itemid='.JRequest::getInt('Itemid'))?>" title="">
            <span><?php echo JText::_('COM_SFS_BLOCK_APPROVED');?></span>
            <strong><?php echo isset( $this->block_count['A'] ) ? $this->block_count['A'] : 0;  ?> <?php echo JText::_('COM_SFS_BLOCK_BLOCKS');?></strong>
        </a>        
    </div>

    <div class="quickroom-block" data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('quick_selection_archived', $text, 'hotel'); ?>">      
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&blockstatus=R&Itemid='.JRequest::getInt('Itemid'))?>" title="">
            <span><?php echo JText::_('COM_SFS_BLOCK_ARCHIVED');?></span>
            <strong><?php $text = isset( $this->block_count['R'] ) ? $this->block_count['R'] : 0;  echo JText::_($text.' '.JText::_('COM_SFS_BLOCK_BLOCKS') ,'hotel');?></strong>
        </a>        
    </div>
</div>