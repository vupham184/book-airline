<?php
defined('_JEXEC') or die;
?>      
<div class="blockstatus">
    <h3 class="introtitle"><?php echo JText::_('COM_SFS_BLOCK_STATUS_ARCHIVED');?></h3>
    <div class="blockreview">
        <p class="reviewp"><?php echo JText::_('COM_SFS_BLOCK_STATUS_ARCHIVED_DESC');?></p>
        
        <div style="padding:15px;width:570px;margin:0 auto">
        
            <table class="block-table" width="570" cellspacing="0" cellpadding="0">
            <tr>
                <th width="35">#</th>
                <th><?php echo JText::_('COM_SFS_BLOCK_DATE');?></th>
                <th><?php echo JText::_('COM_SFS_BLOCK_CODE');?></th>   
                <th><?php echo JText::_('COM_SFS_STATUS');?></th>
                <th class="last"></th>
            </tr>    
            
            <?php 
            $i=0;
            foreach ( $this->blocks as $date => $reservations ) : 
            foreach ($reservations as $block):   
                $link = JRoute::_('index.php?option=com_sfs&view=block&layout=approved&blockid='.$block->id.'&Itemid='.JRequest::getInt('Itemid'));                    
            ?>         
                <tr class="<?php echo ($i%2) ? 'odd':'even' ?>">
                    <td><?php echo ++$i;?></td>
                    <td><?php echo JHTML::_('date',$block->blockdate,'d-m-Y');?></td>
                    <td><?php echo $block->blockcode;?></td>
                    <td class="last"><span class="status-<?php echo strtolower($block->status);?>"><?php echo SFSCore::$blockStatus[$block->status];?></span></td>
                    <td class="blank">
                        <div class="form-group"><a href="<?php echo $link;?>" class="btn orange sm"><?php echo JText::_('COM_SFS_VIEW')?></a></div>
                    </td>
                </tr>                               
            <?php 
            endforeach;
            endforeach;  
            ?>                    
            </table> 
        </div>
        
        <p><?php echo JText::_('COM_SFS_BLOCK_STATUS_ARCHIVED_NOTE');?></p>
    </div>
</div>            
        
                