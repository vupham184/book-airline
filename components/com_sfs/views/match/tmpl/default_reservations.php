<?php
	defined('_JEXEC') or die;
	$airline = SFactory::getAirline();
	$sd_room_total = 0;
	$t_room_total = 0;
	$s_room_total = 0;
	$q_room_total = 0;

	$countS  = false;
	$countSD = false;
	$countT  = false;
	$countQ  = false;
	$single_room_available = 0;
	$quad_room_available = 0;
	$t = 0;
	$modelMatch = JModel::getInstance('Match','SfsModel');
	
	if( count($this->reservations) ){
		foreach ( $this->reservations as $item )
		{
			if ( $t == 0 ) {
				$t = 1;
				$HotelBackendParams = $modelMatch->getHotelBackendParams( $item->hotel_id );
				$single_room_available = (int)$HotelBackendParams->single_room_available;
				$quad_room_available = (int)$HotelBackendParams->quad_room_available;
			}
			
			if( (int)$item->s_room > 0 )
			{
				$countS = true;
			}
			if( (int)$item->sd_room > 0 )
			{
				$countSD = true;
			}
			if( (int)$item->t_room > 0 )
			{
				$countT = true;
			}
			if( (int)$item->q_room > 0 )
			{
				$countQ = true;
			}
		}
	}
?>
<div class="contrast-block-wrapper"  style="width:705px; float:right; overflow:hidden;" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('hotel_match', $text, 'airline');?>">
	<h4><?php echo JText::_('COM_SFS_AIRLINE_HOTELS');?></h4>
	<div class="contrast-block table">			    
		<table>
			<thead>
				<?php if( count($this->reservations) ) : ?>
				<tr>
					<th></th>
					
                    <?php if($countS && $single_room_available == 1 ):?>
						<th>								
							<h4>S</h4>
						</th>
					<?php endif;?>
                    
                    <?php if($countSD):?>
						<th>							
							<!--<h4><?php if ($single_room_available == 1 ): ?> D <?php else: ?> S/D <?php endif;?></h4>-->
                            <h4>S/D</h4>
						</th>
					<?php endif;?>
                    <?php if($countT):?>
						<th>							
							<h4>T</h4>
						</th>							
					<?php endif;?>
					<?php if($countQ && $quad_room_available == 1):?>
						<th>						    
						    <h4>Q</h4>
						</th>
					<?php endif;?>
				</tr>
				<?php endif;?>
			</thead>

			<tbody class="select-reservation" >
			    <?php
			    	if( count($this->reservations) ) :
			        $usedBlocks = array();
			     ?>
			        
		        <?php
			        foreach ( $this->reservations as $item ) :
						if(!empty($item->ws_room_type)) {
							$usedBlocks[$item->association_id][$item->hotel_id] = $item->name;
							continue;
						}
			        	$availableSD = $item->sd_room - $item->sd_room_issued;
			        	$availableT  = $item->t_room - $item->t_room_issued;
			        	$availableS  = $item->s_room - $item->s_room_issued;
			        	$availableQ  = $item->q_room - $item->q_room_issued;
			        	
			        	$totalAvailableRooms = $availableSD + $availableS + $availableT + $availableQ;
			        	
			            $sd_room_total += $availableSD;
			            $t_room_total  += $availableT;
			            $s_room_total  += $availableS;
			            $q_room_total  += $availableQ;

			            if( (int)$totalAvailableRooms == 0 )
			            {
			            	if( !isset($usedBlocks[$item->association_id]) )
			            	{
			            		$usedBlocks[$item->association_id] = array();
			            	}
			            	
			            	if( !isset($usedBlocks[$item->association_id][$item->hotel_id]) )
			            	{
			            		$usedBlocks[$item->association_id][$item->hotel_id] = $item->name;
			            	}
			            	continue;
			            }

			            $link  = 'index.php?option=com_sfs&view=match&layout=vouchers&hotelid='.$item->hotel_id;
			            $link .= '&reservationid='.$item->id;
			            $link .= '&nightdate='.$this->night;
			            if($item->association_id) {
			            	$link .= '&association_id='.$item->association_id;
			            }
			            $link .= '&Itemid='.JRequest::getInt('Itemid');
		            ?>
				            
					<tr>
						<td>
							<div data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('hotel_match_item', $text, 'airline');?>">
			                <div class="m-h-col float-left">
			                    <div class="m-h-col-inner">
			                         <input type="radio" name="reservationid" value="<?php echo $item->id ;?>" class="required reservationItem" />
			                         <span class="hasTip" title="Date <?php echo $item->blockdate;?>"><?php echo $item->name;?></span>
			                         <a href="<?php echo $link?>" data-step="5" data-intro="<?php echo SfsHelper::getTooltipTextEsc('issued_vouchers_overview', $text, 'airline');?>">issued vouchers overview</a>
			                    </div>
			                </div>
			                        
			                <div class="floatbox clear m-h-col-inner">
			                    <input type="hidden" id="availableqroom<?php echo $item->id ;?>" name="availableqroom<?php echo $item->id ;?>" value="<?php echo (int)$availableQ ;?>"  />
								<?php if ($item->payment_type == 'passenger'): ?>
			                     <div style="padding-left:25px;">
			                     	Invoice for Passenger
			                     </div>
			                     <?php endif;?>
			                     
			                     <?php if ($item->payment_type == 'airline'): ?>
				                     <div style="padding-left:25px;">
				                     	Invoice for <?php echo $airline->name;?>
				                     </div>
			                     <?php endif;?>
			                     
			                     <?php if ( floatval($item->mealplan) > 0 ):?>
				                     <div style="padding-left:20px;">
				                         <?php
				                        	$item->stop_selling_time = ( $item->stop_selling_time == '24' ) ? JText::_('COM_SFS_24_HOURS'): str_replace(':','h',$item->stop_selling_time) ;
				                         ?>
				                     	<input type="checkbox" name="mealplan<?php echo $item->id ;?>" id="mealplan<?php echo $item->id ;?>" value="1" checked="checked" />
				                     	<?php echo JText::_('COM_SFS_DINNER');?>
				                    	&lt;<?php echo $item->course_type.' '.JText::_('COM_SFS_COURSE').' - '.$item->stop_selling_time ?>&gt;
				                     </div>
			                     <?php endif;?>
			                                    
			                     <?php if ( floatval($item->breakfast) > 0 ) : ?>
				                     <div style="padding-left:20px;">
				                     	<?php
                                            if( (int) $item->bf_service_hour==1){
                                                $item->breakfastTime = "<".JText::_('COM_SFS_24_HOURS').">;";
                                            } else if((int) $item->bf_service_hour==2){
                                                $item->breakfastTime = "<".str_replace(':','h',$item->bf_opentime).'-'.str_replace(':','h',$item->bf_closetime).">;";
                                            } else {
                                                $item->breakfastTime ='';
                                            }
				                     	?>
				                     	<input type="checkbox" name="breakfast<?php echo $item->id ;?>" id="breakfast<?php echo $item->id ;?>" value="1" checked="checked" />
                                         <?php echo JText::_('COM_SFS_BREAKFAST');?> <?php echo $item->breakfastTime ?>
				                     </div>
			                     <?php endif;?>
			                     
			                     <?php if( floatval($item->lunch) > 0 ) : ?>
				                     <div style="padding-left:20px;">
				                     	<?php
				                         if( (int) $item->lunch_service_hour==1){
				                                $item->lunchTime = "<".JText::_('COM_SFS_24_HOURS').">";
				                            } else if((int) $item->lunch_service_hour==2){
				                                $item->lunchTime = "<".str_replace(':','h',$item->lunch_opentime).'-'.str_replace(':','h',$item->lunch_closetime).">";
				                            } else {
				                                $item->lunchTime ='';
				                            }
				                         ?>
				                   	 	<input type="checkbox" name="lunch<?php echo $item->id ;?>" id="lunch<?php echo $item->id ;?>" value="1" checked="checked" /> <?php echo JText::_('COM_SFS_LUNCH');?> <?php echo $item->lunchTime ?>
				                   	 </div>
			                   	 <?php endif;?>
			                                             
			                     <?php //Transport ?>
				                     <div style="padding-left:20px;">
										<?php if( (int) $item->transport > 0 ) {
											$transportTooltip = 'Transport to accommodation included: ';

											switch ( (int)$item->transport_available ) {
												case 1:
													$transportTooltip .= 'Yes';
													break;
												case 2:
													$transportTooltip.='Not necessary (walking distance)';
													break;
												default :
													$transportTooltip .= 'No';
													break;
											}

											$transportTooltip .='<br />';
											$transportTooltip .= (int)$item->transport_complementary == 1 ? 'Complimentary: Yes':'Complimentary: No';
											$transportTooltip .='<br />';
											$item->operating_hour = (int)$item->operating_hour	;
											if($item->operating_hour == 0 ){
												$transportTooltip .='Operation hours: Not available';
											} else if($item->operating_hour == 1) {
												$transportTooltip .='Operation hours: 24-24 for stranded';
											} else if($item->operating_hour == 2) {
												$transportTooltip .='Operation hours: From '.str_replace(':','h',$item->operating_opentime).' till '.str_replace(':','h',$item->operating_closetime);
											}
											$transportTooltip .='<br />';
											$transportTooltip .='Every: '.$item->frequency_service.' minutes';
											if($item->pickup_details){
												$transportTooltip .='<br /><br />Details:<br />'.$item->pickup_details;
											}
										}
										?>
				                     	* <?php echo JText::_('COM_SFS_TRANSPORTATION');?> &lt;<?php echo (int)$item->transport ? 'Yes' : 'No' ?>&gt; <?php if( (int) $item->transport > 0 ):?><img src="components/com_sfs/assets/images/info16.png" alt="" class="hasTip" title="<?php echo $transportTooltip;?>" /><?php endif;?>
				                     </div>
			                     <?php //End Transport ?>
			                     
			                     <div id="taxiTransportWrap<?php echo $item->id ;?>" class="taxiTransportWrap">
			                         <?php
			                         $this->item = $item;
			                         echo $this->loadTemplate('taxi');
			                         ?>
			                     </div>
			                     
			                     <div id="groupTransportWrap<?php echo $this->item->id ;?>" class="groupTransportWrap" style="display:none;">						                     
				                     <?php if($this->transportCompany) : ?>
				                     <script type="text/javascript">
										 <!--
										 window.addEvent('domready', function() {
											$('isGroupTransport<?php echo $this->item->id ;?>').addEvent('change',function(e){
										
												if( $('isGroupTransport<?php echo $this->item->id ;?>').checked )
												{
													$$('.grouptransport-list<?php echo $this->item->id ;?>').setStyle('display','block');
												} else {
													$$('.grouptransport-list<?php echo $this->item->id ;?>').setStyle('display','none');
												}
												
											});
										 });
										 -->
									 </script>							                    
				                    <div style="padding-left: 20px;">
				                     	<input type="checkbox" name="reservations[<?php echo $this->item->id ;?>][isGroupTransport]" id="isGroupTransport<?php echo $this->item->id ;?>" value="1" />
										Add Transportation
									</div>
									<div style="padding-left: 40px;display:none" class="grouptransport-list grouptransport-list<?php echo $this->item->id ;?>">
										<input type="radio" name="reservations[<?php echo $this->item->id ;?>][group_transport_id]" value="<?php echo $this->transportCompany->id ;?>" checked="checked" />
										<?php //echo $this->transportCompany->name;?>Group Transportation
									</div>
				                     <?php endif;?>
			                     </div>
			                 </div>						                
						</td>
						
                        <?php if($countS  && $single_room_available == 1 ):?>		
							<td>							
								<?php echo $availableS;?>
                                <input type="hidden" name="s_number_room<?php echo $item->id ;?>" value="<?php echo $availableS ;?>" />
							</td>
						<?php endif?>
                        
                        <?php if($countSD):?>
							<td>								
				                <div data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('amount_of_rooms', $text, 'airline');?>"><?php echo $availableSD;?>
                                <input type="hidden" name="sd_number_room<?php echo $item->id ;?>" value="<?php echo $availableSD ;?>" />
	                		</td>
                		<?php endif;?>
                        
                        <?php if($countT):?>
							<td>									
				                <?php echo $availableT;?>
                                <input type="hidden" name="t_number_room<?php echo $item->id ;?>" value="<?php echo $availableT ;?>" />
							</td>
						<?php endif;?>
                        
						<?php if($countQ && $quad_room_available == 1 ):?>
							<td>					            								
								<?php echo $availableQ;?>	
                                <input type="hidden" name="q_number_room<?php echo $item->id ;?>" value="<?php echo $availableQ ;?>" />							
							</td>		
						<?php endif;?>
					</tr>
				<?php
					endforeach;
				?>
			</tbody>
			
			<tfoot>
				<?php if( count($usedBlocks) ):?>
				<tr>							
					<td>
			        	<div style="padding: 10px 0 10px 8px" class="fs-14">
			        		Used blocks for the night starting: <?php echo JFactory::getDate($this->night)->format('d M Y')?> ending: <?php echo JFactory::getDate($this->nextNight)->format('d M Y')?>
			        	</div>
			        	<?php
				        	foreach ($usedBlocks as $assoc_id => $hotels ):
				        	foreach ($hotels as $k => $v):
			        	?>
			    		<div style="padding: 0 0 5px 45px">
			    			<?php echo $v?> <a href="index.php?option=com_sfs&view=match&layout=vouchers&hotelid=<?php echo $k;?>&nightdate=<?php echo $this->night?>&association_id=<?php echo $assoc_id;?>&Itemid=<?php echo JRequest::getInt('Itemid')?>">issued vouchers overview</a>
			    		</div>
			        	<?php
				        	endforeach;
				        	endforeach;
			        	?>
			        
			        	<?php endif ?>								        								     							     
					</td>
				</tr>
				<?php endif; ?>	

				<?php if( count($this->reservations) ) : ?>
				<tr>
					<td></td>
					<?php if($countS && $single_room_available == 1 ):?>
						<td>								
							<?php echo (int)$s_room_total.' S';?>
						</td>
					<?php endif;?>								

					<?php if($countSD):?>
						<td>			
							<?php //echo $sd_room_total; echo ($single_room_available == 1 ) ? ' D' : ' S/D';?>
                            <?php echo $sd_room_total; echo ' S/D';?>
						</td>
					<?php endif;?>
                    <?php if($countT):?>
						<td>								
							<?php echo $t_room_total.' T';?>
						</td>
					<?php endif;?>	
					<?php if($countQ && $quad_room_available == 1):?>
						<td>																
							<?php echo (int)$q_room_total.' Q';?>
						</td>
					<?php endif;?>
				</tr>
				<?php endif?>
			</tfoot>
		</table>
	</div>
</div>
</div>
<script>
jQuery(function($){
	if ( $('.reservationItem').length == 1 ) {
		$('.reservationItem').attr('checked', true);
	}
});
</script>
