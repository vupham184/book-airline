<?php
defined('_JEXEC') or die;
?>      
<div class="blockstatus">	
	<h3>
    <?php 
		echo JText::_('Search Results' ,'hotel');
    ?> 
    </h3>
    <div class="blockreview">                        
        <div style="padding:15px;width:530px;margin:0 auto">
                        
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
                $link = '';		        
                $button_name='View';	                	
                switch ($block->status){	
                    case 'C':	
                        $link = JRoute::_('index.php?option=com_sfs&view=rooming&layout=challenge&code='.$block->blockcode.'&Itemid='.JRequest::getInt('Itemid'));							
                        break;																
                    case 'A':	
                        $link = JRoute::_('index.php?option=com_sfs&view=block&layout=approved&blockid='.$block->id.'&Itemid='.JRequest::getInt('Itemid'));                    																		
                        break;
                    case 'R':
                        $link = JRoute::_('index.php?option=com_sfs&view=block&layout=approved&blockid='.$block->id.'&Itemid='.JRequest::getInt('Itemid'));                    		
                        break;
                    default:
                        $button_name='Load';
                        $link = JRoute::_('index.php?option=com_sfs&view=rooming&code='.$block->blockcode.'&Itemid='.JRequest::getInt('Itemid'));							
                        break;						
                } 		        	                    
            ?>         
                <tr class="<?php echo ($i%2) ? 'odd':'even' ?>">
                    <td style="padding:0 5px;"><?php echo ++$i;?></td>
                    <td style="padding:0 5px;"><?php echo JHTML::_('date',$block->date,'d-m-Y');?></td>
                    <td style="padding:0 5px;"><?php echo $block->blockcode;?></td>
                    <td class="last" style="padding:0 5px;"><span class="status-<?php echo strtolower($block->status);?>"><?php echo SFSCore::$blockStatus[$block->status];?></span></td>
                    <td class="blank" style="padding:2px 0 2px 15px" width="135"><a href="<?php echo $link;?>" class="small-button"><?php echo $button_name;?></a></td>                		
                </tr>                                      
            <?php 
            endforeach;
            endforeach; ?> 
            </table>                                   
        </div>                    
    </div>
</div>            
        
                