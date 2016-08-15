<?php
// no direct access
defined('_JEXEC') or die;
?>


	<h3>Your Request</h3>


<div id="search-request" class="sfs-orange-wrapper floatbox">
	<div class="search-request-inner" >
		<form name="requestSearchFrom" action="<?php echo JRoute::_('index.php?option=com_sfs&view=search');?>" method="post" class="sfs-form form-horizone">
					
			<div class="block-group block-inline">
				<div class="row">
					<div class="col w50">
						<div class="block bg grey" style="height: 215px;"  data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('search_request_left', $text, 'airline');?>">					
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="100">Rooms:</td>
									<td><?php echo $this->state->get('filter.rooms') ?></td>					
								</tr>
								<tr>
									<td>From:</td>
									<td><?php echo JHtml::_('date',$this->state->get('filter.date_start'), JText::_('DATE_FORMAT_LC3')) ?></td>					
								</tr>
								<tr>
									<td>Until:</td>
									<td><?php echo JHtml::_('date',$this->state->get('filter.date_end'), JText::_('DATE_FORMAT_LC3')) ?></td>					
								</tr>								
							</table>				
			                <div class="request-button">
			                	<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=search&Itemid='.JRequest::getInt('Itemid'))?>" class="small-button">New Search</a>                
			                </div>
						</div>
					</div>
					
					<div class="col w50">
						<div class="block bg grey" data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('search_request_right', $text, 'airline');?>">	
							<div style="padding-bottom:10px;">Show only hotels:</div>					
							<?php ob_start();?>
							<div style="width:280px;">
							
								<div class="form-group">
									<label for="show_all_rooms"><input type="checkbox" name="show_all_rooms" id="show_all_rooms" value="1" <?php if($this->state->get('filter.show_all_rooms')) echo 'checked="checked"';?> /> That can take all the rooms</label>
								</div>	

								<div class="form-group">
									<label for="transport_included"><input type="checkbox" name="transport_included" id="transport_included" value="1" <?php if($this->state->get('filter.transport_included')) echo 'checked="checked"';?> />With transport included</label>
								</div>
								
								<div class="form-group">
									<label for="filter_hotel_star">
										<input type="checkbox" name="filter_hotel_star" id="filter_hotel_star" value="1" <?php if( $this->state->get('filter.hotel_star') ) echo 'checked="checked"';?> />
										<select name="hotel_star" class="thin-size" style="padding:1px; width:200px;">
											<option value="3"<?php if( (int)$this->state->get('filter.hotel_star') == 3 ) echo ' selected="selected"';?>> &gt;= 3 stars</option>
											<option value="4"<?php if( (int)$this->state->get('filter.hotel_star') == 4 ) echo ' selected="selected"';?>> &gt;= 4 stars</option>
											<option value="5"<?php if( (int)$this->state->get('filter.hotel_star') == 5 ) echo ' selected="selected"';?>> = 5 stars</option>						
										</select>                                        
									</label>
								</div>
								
							</div>
							<?php echo ob_get_clean(); ?>
			                
			                <br/>
			                <div class="request-button">
			                    <button type="button" onClick="this.form.submit()" name="B1" class="small-button" style="text-align: left; text-indent:19px;">Update Result</button>                    
			                </div>						                
						</div>
					</div>
				</div>
			</div>
			
			
			<input type="hidden" name="rooms" value="<?php echo $this->state->get('filter.rooms');?>" />
			<input type="hidden" name="adults" value="<?php echo $this->state->get('filter.adults');?>" />
			<input type="hidden" name="children" value="<?php echo $this->state->get('filter.children');?>" />
			<input type="hidden" name="date_start" value="<?php echo $this->state->get('filter.date_start');?>" />
			<input type="hidden" name="date_end" value="<?php echo $this->state->get('filter.date_end');?>" />
			<input type="hidden" name="hour_start" value="<?php echo $this->state->get('filter.hour_start');?>" />
			<input type="hidden" name="hour_end" value="<?php echo $this->state->get('filter.hour_start');?>" />
			<input type="hidden" name="extend" value="<?php echo JRequest::getInt('extend');?>" />
			
			<input type="hidden" name="ordering" value="" />
			<input type="hidden" name="task" value="search.search" />			
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />		
				
		</form>
	</div>
</div>

