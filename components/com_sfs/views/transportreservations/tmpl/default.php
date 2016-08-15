<?php
defined('_JEXEC') or die;
$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);

$airlineName = '';

if($this->airline->grouptype == 3) {
	$selectedAirline = $this->airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
} else {
	$airlineName = 	$this->airline->name;
}

$companies = $this->airline->getTaxiCompanies();
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo $airlineName;?>: Group Transportation</h3>
	</div>
</div>
<div id="sfs-wrapper" class="main">
    <div id="airblock">    
    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=transportreservations');?>" method="post">
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
                    <th><?php echo JText::_('JDATE');?></th>                    
                    <th>Bus company</th>                                                                                                    
                    <th>Phone</th>                    
                    <th>Reference number</th>
					<th>Made by</th>
                    <th>Flight number</th>       
                    <th>Persons</th>             
                    <th>Rate</th>                                    
                    <th colspan="2"></th>                                                                                                    
                </tr>
                <?php foreach ( $this->reservations as $item ) : ?>
                <tr>                               
                    <td><?php echo JHTML::_('date', $item->booked_date  , 'd/m/Y' );?></td>                                                                              
                    <td><?php echo $item->bus_company ;?></td>                                        
                    <td><?php echo $item->telephone ;?></td>
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
                    <td><?php echo $item->created_by ;?></td>
                    <td><?php echo $item->flight_number ;?></td> 
                    <td><?php echo $item->total_passengers ;?></td>  
                    <td>
                        <?php echo floatval($item->rate) > 0 ? floatval($item->rate) : '';?>
                    </td>
                                              
                    <td>
                    	<div class="sfs-highlight sfs-highlight-<?php echo $item->status?>">
                    	<?php
                    	if( $item->status == 'accepted' ) {
                    		echo 'Confirmed';
                    	} else if( $item->status == 'pending' ) {
                    		echo 'Pending';
                    	} else if( $item->status == 'declined' ) {
                    		echo 'Declined';
                    	}
                    	?>
                    	</div>
                    </td>    
					<td>
                 		<div class="mid-button" style="margin-right: 10px;float:right;">
							<a href="index.php?option=com_sfs&task=transportreservations.export&id=<?php echo $item->id?>" style="text-indent: 22px;">Export</a>
						</div>                 		
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
            
        <input type="hidden" name="task" value="transportreservations.filter" />			
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" /> 
    
    </form>
    
    </div>
    
</div>    
