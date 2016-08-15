<?php
defined('_JEXEC') or die;
$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Bookings</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main">

    <div id="airblock">    
    
    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiprofile&layout=bookings');?>" method="post">
        <div class="sfs-yellow-wrapper orange-top-border">
            <div class="sfs-white-wrapper airblock-search">

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
            </div>
        </div>
    
    	<div class="sfs-main-wrapper bottom-border-radius" style=" padding:0 1px 15px 1px;">
            <table cellpadding="0" cellspacing="0" width="100%" border="0" class="airblocktable">
                <tr>                    	            	                                      
                    <th><?php echo JText::_('JDATE');?></th>                
                    <th>Reference number</th>
                    <th>Flight number</th>    
                    <th>Pick up</th>
                    <th>Time</th>
                    <th>Drop off</th> 					                                                 
                    <th>Rate</th>     
                    <th>Made by</th>                                   
                                                                                                                      
                </tr>
                <?php foreach ( $this->reservations as $item ) : ?>
                <tr>                               
                    <td>
                    <?php                     	
                    	echo JHTML::_('date', $item->block_date  , 'd/m/Y' );                    	                    	
                    ?>
                    </td>
					<td>
	                    <?php if($item->comment):
						$commentTip = '<p>'.$item->comment.'</p>';
						?>	
						<span class="hasTip2 <?php echo 'underline-text'; ?>" title="<?php echo SfsHelper::escape($commentTip);?>">
							<?php echo $item->reference_number;?>
						</span>
						<?php else:?>
							<?php echo $item->reference_number;?>
						<?php endif;?>
                    </td>
                    
                    <td><?php echo $item->flight_number ;?></td>
                                                                                                                      
                    <td>
                    	<?php                    	
                    		echo $item->hotel_name;                    	                   		
                    	?>
                    </td>
                    <td>	
                    	<?php
                    	if( $item->requested_time == '0' || empty($item->requested_time) ) {
                    		echo 'asap';
                    	} else {
                    		echo $item->requested_time;
                    	} 
                    	?>
                    </td>
                    <td>
                    	<?php  
                    	if($item->terminal)
                    	{
                    		echo $item->terminal ;	
                    	} else {
                    		echo !empty($item->airline_name) ? $item->airline_name : $item->company_name ;	
                    	}                             	                 	
                    	?>
                    </td>                                                                                                                                                                
                    <td>
                        <?php echo floatval($item->rate) > 0 ? floatval($item->rate) : '';?>
                    </td>
                    
                    <td><?php echo $item->booked_name ;?></td>
                                                                					                      
                </tr>
                <?php endforeach ; ?>
            </table>
            
            <?php if ( $this->pagination->get('pages.total') > 1 ) : ?>				
			<div class="pagination">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php endif; ?> 
            
        </div>
            
        <input type="hidden" name="task" value="taxiprofile.filterBookings" />			
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" /> 
    
    </form>
    
    </div>
    
</div>    
