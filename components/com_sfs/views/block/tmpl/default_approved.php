<?php
defined('_JEXEC') or die;
?>      
<div class="blockstatus">
    <h3 class="introtitle"><?php echo JText::_('COM_SFS_BLOCK_STATUS_APPROVED');?></h3>
    <div class="blockreview">
        <p class="reviewp"><?php echo JText::_('COM_SFS_BLOCK_STATUS_APPROVED_DESC');?></p>
        
        <div style="padding:15px 0;width:680px;margin:0 auto">
        
            <table class="block-table" width="100%" cellspacing="0" cellpadding="0">
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
                $link  = 'index.php?option=com_sfs&view=block&layout=approved&blockid=';
            	$link .= isset($block->reservation_id) && $block->reservation_id > 0 ? $block->reservation_id : $block->id;            	
            	if($block->airport)
            	{
            		$link .= '&airport='.$block->airport;
            	}     
            	$link .=  '&Itemid='.JRequest::getInt('Itemid'); 
                            	            	   
            	$archiveLink  = 'index.php?option=com_sfs&task=block.archive&id=';
            	$archiveLink .= isset($block->reservation_id) && $block->reservation_id > 0 ? $block->reservation_id : $block->id;            	
            	if($block->airport)
            	{
            		$archiveLink .= '&airport='.$block->airport;
            	}     
            	$archiveLink .=  '&Itemid='.JRequest::getInt('Itemid');                             
            ?>       
                <tr class="<?php echo ($i%2) ? 'odd':'even' ?>">
                    <td style="padding:0 5px;"><?php echo ++$i;?></td>
                    <td style="padding:0 5px;"><?php echo JHTML::_('date',$block->blockdate,'d-m-Y');?></td>
                    <td style="padding:0 5px;"><?php echo $block->blockcode;?></td>
                    <td class="last" style="padding:0 5px;"><span class="status-<?php echo strtolower($block->status);?>"><?php echo SFSCore::$blockStatus[$block->status];?></span></td>
                    <td class="blank" style="padding:2px 0 2px 15px">
                    	<div class="s-button float-left">
                    	<a href="<?php echo $link;?>" class="s-button"><?php echo JText::_('COM_SFS_VIEW')?></a>
                        </div>
                        <div class="s-button float-left" style="margin-left:10px;">                    
	                    	<a href="<?php echo $archiveLink;?>" title="" class="s-button float-left">Archive</a>
                        </div>
                    </td>           		
                </tr>                              
            <?php 
            endforeach;
            endforeach;  
            ?>                          
            </table> 
             
        </div>
        
        <p><?php echo JText::_('COM_SFS_BLOCK_STATUS_APPROVED_NOTE');?></p>
    </div>
</div>            
        
                