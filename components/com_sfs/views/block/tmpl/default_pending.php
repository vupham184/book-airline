<?php
defined('_JEXEC') or die;
?>
<div class="blockstatus">
    <h3 class="introtitle"><?php echo JText::_('COM_SFS_BLOCK_STATUS_PENDING');?></h3>
    <div class="blockreview">
    
        <p class="reviewp"><?php echo JText::_('COM_SFS_BLOCK_STATUS_PENDING_DESC');?></p>
        
        <div style="padding:15px;width:450px;margin:0 auto">
            <table class="block-table" width="450" cellspacing="0" cellpadding="0">
            <tr>
                <th width="35">#</th>
                <th><?php echo JText::_('COM_SFS_BLOCK_DATE');?></th>
                <th><?php echo JText::_('COM_SFS_BLOCK_CODE');?></th>               	
                <th class="last"></th>
            </tr>            
            <?php 
            $i=0;
            foreach ( $this->blocks as $date => $reservations ) : 
            foreach ($reservations as $block):
            	$link = 'index.php?option=com_sfs&view=rooming&code='.$block->blockcode;
            	if($block->airport)
            	{
            		$link .= '&airport='.$block->airport;
            	}
                $link = JRoute::_($link.'&Itemid='.JRequest::getInt('Itemid'));                
            ?>               
                <tr class="<?php echo ($i%2) ? 'odd':'even' ?>">
                    <td><?php echo ++$i;?></td>
                    <td><?php echo JHTML::_('date',$block->date,'d-m-Y');?></td>
                    <td class="last"><?php echo $block->blockcode;?></td>						
                    <td class="blank" style=" padding:2px 0 2px 15px">
                    	<?php if($i==1):?>
                    		<?php echo SfsHelper::htmlTooltip('block_status_load_button', '<a href="'.$link.'" class="small-button">'.JText::_('COM_SFS_LOAD').'</a>' ,'hotel');?>
                    	<?php else:?>
                    		<a href="<?php echo $link;?>" class="small-button">
                    		<?php echo JText::_('COM_SFS_LOAD')?>
                    		</a>
                    	<?php endif;?>
                    </td>                
                </tr>                     
            <?php 
            endforeach;
            endforeach;  
            ?>
            </table>    
        </div>
        
        <p><?php echo JText::_('COM_SFS_BLOCK_STATUS_PENDING_NOTE');?></p>
        
    </div>
</div>    
            
                
                