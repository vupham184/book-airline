<?php
defined('_JEXEC') or die;

$airlineName = '';

if($this->airline->grouptype == 3) {
	$selectedAirline = $this->airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
} else {
	$airlineName = 	$this->airline->name;
}
$showall = JRequest::getString('showall');
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $airlineName;?>: <?php echo JText::_('COM_SFS_AIRBLOCK_PAGE_TITLE');?></h3>
    </div>
</div>
<div id="sfs-wrapper" class="main">
    <div id="airblock">
        <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=airblock');?>" method="post">
            <div class="sfs-main-wrapper-none">
                <div class="sfs-yellow-wrapper orange-top-border">
                    <div class="sfs-white-wrapper airblock-search"> 
                   		<?php echo $this->loadTemplate('search');?>                
                    </div>
                </div>
            </div>
            
            <div class="sfs-main-wrapper bottom-border-radius" style=" padding:0 1px 15px 1px;">
                <table cellpadding="0" cellspacing="0" width="100%" border="0" class="airblocktable">
                    <tr>
                        <th>Airport</th>
                    	<?php
                    	if($this->airline->grouptype == 3 && (int)$showall == 1) :
                    	?>
                    	<th>Airline</th>
                    	<?php endif;?>
                        <th><?php echo JText::_('COM_SFS_STATUS');?></th>
                        <th><?php echo JText::_('JDATE');?></th>
                        <th><?php echo JText::_('COM_SFS_HOTEL_NAME');?></th>
                        <th>Block code</th>
                        <th>Flight number</th>
                        <th>Initial<br/>rooms</th>
                        <th>Claimed<br/>rooms</th>
                        <th width="7%">S price</th>
                        <th width="7%">S/D price</th>
                        <th width="7%">T price</th>
                        <th width="7%">Q price</th>
                        <th>&nbsp;</th>                                                                                                
                    </tr>
                    <?php foreach ( $this->blocked_rooms as $item ) :?>
                    <tr>
                        <td><?php echo $item->airport_code;?></td>
                   		<?php
                    	if($this->airline->grouptype == 3 && (int)$showall == 1) :
                    	?>
                    	<td><?php echo $item->airline_name;?></td>
                    	<?php endif;?>
                        <td>
                            <?php               			
                                if( $item->status == 'P') {
                                    echo 'O' ;
                                } else {
                                    echo $item->status;
                                }               		
                            ?>
                        </td>
                        <td><?php echo JHTML::_('date', $item->blockdate  , 'd/m/Y' );?></td>
                        <td><?php echo $item->hotel_name ;?></td>
                        <td><?php echo $item->blockcode ;?></td>
                        <td><?php echo $item->flight_code;?></td>
                        <td>
                            <?php echo (int)$item->sd_room+(int)$item->t_room+(int)$item->s_room+(int)$item->q_room;?>
                        </td>
                        <td><?php echo (int)$item->claimed_rooms ;?></td>
                        <td><?php echo $item->currency_symbol." ".$item->s_rate ;?></td>
                        <td><?php echo $item->currency_symbol." ".$item->sd_rate ;?></td>
                        <td><?php echo $item->currency_symbol." ".$item->t_rate ;?></td>
                        <td><?php echo $item->currency_symbol." ".$item->q_rate ;?></td>
                        <td>
                        <?php
                        if ( $item->status != 'O' &&  $item->status != 'P' ) {
                            $link = JRoute::_('index.php?option=com_sfs&view=airblock&layout=detail&id='.$item->id.'&Itemid='.JRequest::getInt('Itemid'));
							$link2 = JRoute::_('index.php?option=com_sfs&view=airblock&layout=detail2&id='.$item->id.'&Itemid='.JRequest::getInt('Itemid'));
                            ?>    
                            <?php if( ! SFSAccess::isAirlineAccounting($this->user) ) :?>                        
							    <a href="<?php echo $link;?>" class="btn orange sm">View</a>
                             <?php else: ?>
                                <a href="<?php echo $link2;?>" class="btn orange sm">View</a>	
                             <?php endif; ?>						
                            <?php                          
                        } else {
                            $link = JRoute::_('index.php?option=com_sfs&view=airblock&layout=pending&id='.$item->id.'&Itemid='.JRequest::getInt('Itemid'));                            
                            ?>                            
								<a href="<?php echo $link;?>" class="btn orange sm">Pending</a>							
                            <?php 	                	
                        }
                        ?>
                        </td>
                    </tr>
                    <?php endforeach ; ?>
                </table>
                
                <?php if($this->airline->grouptype == 3) : ?>
                <div class="mid-button float-left" style="margin:10px 0 0 10px;">
			    	<a style="text-indent:22px;" href="index.php?option=com_sfs&view=airblock&showall=1&Itemid=<?php echo JRequest::getInt('Itemid'); ?>">
			        	Show all blocks			        
			        </a>
		        </div>
                    <input type="hidden" name="showall" value="<?php echo JRequest::getInt('showall',0)?>" />					        
                <?php endif; ?>
                
				<?php if ( $this->pagination->get('pages.total') > 1 ) : ?>				
				<div class="pagination">
					<?php echo $this->pagination->getPagesLinks(); ?>
				</div>
				<?php endif; ?>                
                
            </div>
            
            <input type="hidden" name="task" value="airblock.filter" />			
            <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />            
                                
        </form>
    </div>

</div>

