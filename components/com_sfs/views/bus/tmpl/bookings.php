<?php
defined('_JEXEC') or die;
$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);
?>

<div id="sfs-wrapper" class="main">

    <div id="airblock">    
    
    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=bookings');?>" method="post">
    
    	<div class="sfs-above-main">
	        <h3>Bookings</h3>
  		</div>
  		
  		<div class="sfs-main-wrapper-none">
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
                    <th>Seats</th>             
                    <th>Rate</th>     
                    <th>Made by</th>                                   
                    <th></th>                                                                                                    
                </tr>
                <?php foreach ( $this->reservations as $item ) : ?>
                <tr>                               
                    <td>
                    <?php 
                    	if( $item->requested_date != '0000-00-00') {
                    		echo JHTML::_('date', $item->requested_date  , 'd/m/Y' );
                    	} else {
                    		echo JHTML::_('date', $item->booked_date  , 'd/m/Y' );
                    	}                    	
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
                    	if($item->departure_type=='airport'){
                    		echo $item->terminal ;
                    	} else {
                    		echo $item->hotel_name;
                    	}                    		
                    	?>
                    </td>
                    <td>	
                    	<?php
                    	if( $item->requested_time == '0' ) {
                    		echo 'asap';
                    	} else {
                    		echo $item->requested_time;
                    	} 
                    	?>
                    </td>
                    <td>
                    	<?php
                    	if($item->departure_type=='hotel'){
                    		echo $item->terminal ;
                    	} else {
                    		echo $item->hotel_name;
                    	}                    		
                    	?>
                    </td>
                                                                                                                                             
                    <td><?php echo $item->total_passengers ;?></td>  
                    <td>
                        <?php echo floatval($item->rate) > 0 ? floatval($item->rate) : '';?>
                    </td>
                    
                    <td><?php echo $item->booked_name ;?></td>
                                              
                    <td>
                    	<?php if( $item->status == 'pending' ) : ?>
                    	
                    	<a href="index.php?option=com_sfs&task=bus.acceptBooking&id=<?php echo $item->id?>" style="background:#98f881;border: solid 1px #a5f792;display:inline-block;color:#393a39;text-decoration:none; font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; padding:2px 5px; width:60px; text-align:center; border-radius:5px;margin-left:5px;float:right;">
                    		Accept
                		</a> 
                    	
                    	<a href="index.php?option=com_sfs&task=bus.declineBooking&id=<?php echo $item->id?>" style=" background:#f5f6f7; border: solid 1px #ccc; display:inline-block; color:#393a39; text-decoration:none; font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; padding:2px 5px; width:60px; text-align:center; border-radius:5px;float:right;">
                    		Decline 
                		</a>   
                    	
                    	<?php else : ?>
                    	<div class="sfs-highlight sfs-highlight-<?php echo $item->status?>" style="width:65px;float:right;">
                    	<?php
                    	if( $item->status == 'accepted' ) {
                    		echo 'Confirmed';
                    	} else if( $item->status == 'declined' ) {
                    		echo 'Declined';
                    	}
                    	?>
                    	</div>
                    	<?php endif;?>
                    </td>    					                      	
                </tr>
                <?php endforeach ; ?>
            </table>
            
            <?php if ( $this->pagination->get('pages.total') > 1 ) : ?>				
			<div class="pagination">
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php endif; ?> 
            
        </div>
            
        <input type="hidden" name="task" value="bus.filterBookings" />			
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" /> 
    
    </form>
    
    </div>
    
</div>    
