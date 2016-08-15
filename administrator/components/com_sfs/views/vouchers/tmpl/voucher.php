<?php
defined('_JEXEC') or die;?>

<div>
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">
	
		<fieldset>
			<div class="fltrt">						
				<button onclick="  window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				Voucher Details
			</div>
		</fieldset>
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
			
			<div style="width:60%;float:left">						
			<fieldset class="adminform">						
				<table class="voucherdetailtable">					
					<tr>
						<td>Voucher number</td>
						<td>
							<strong><?php echo $this->voucher->code;?></strong>								
						</td>
					</tr>					
					<tr>
						<td>Blockcode</td>
						<td>
							<strong><?php echo $this->voucher->blockcode;?></strong>								
						</td>
					</tr>
					<tr>
						<td>Voucher type</td>
						<td>
							<strong><?php echo $this->voucher->vgroup==1?'group':'single';?></strong>								
						</td>
					</tr>	
					<tr>
						<td>Flight number</td>
						<td>
							<strong><?php echo $this->voucher->flight_code;?></strong>								
						</td>
					</tr>
					<tr>
						<td>seats</td>
						<td>
							<strong><?php echo $this->voucher->seats;?></strong>								
						</td>
					</tr>	
					<tr>
						<td>Rooms</td>
						<td>
							<strong>
							<?php
							switch ($this->voucher->room_type) {
								case 1:
									echo 'Single'; 
									break;
								case 2: 
									echo 'Double';
									break;
								case 3: 
									echo 'Triple';
									break;
								case 4: 
									echo 'Quad';
									break;
								default:
									$array = array();
									if( (int)$this->voucher->sroom > 0 ) {
										$array[] = $this->voucher->sroom.' Single';
									}
									if( (int)$this->voucher->sdroom > 0 ) {
										$array[] = $item->sdroom.' Double';
									}
									if( (int)$this->voucher->troom > 0 ) {
										$array[] = $this->voucher->troom.' Triple';
									}
									if( (int)$this->voucher->qroom > 0 ) {
										$array[] = $this->voucher->qroom.' Quad';
									}
									if(count($array))
									{
										echo implode('<br />', $array);
									}
									break;				
							}
							?>	
							</strong>								
						</td>
					</tr>	
					<tr>
						<td>Created</td>
						<td>
							<strong><?php echo sfsHelper::getATDate($this->voucher->created, 'd F Y H:i');?></strong>	
						</td>
					</tr>
					<tr>
						<td>Printed/Emailed</td>
						<td>
							<strong>
							<?php
							if( (int)$this->voucher->printed == 1 )
							{
								echo sfsHelper::getATDate($this->voucher->printed_date, 'd F Y H:i');
							}
							if( $this->voucher->passenger_email ) {
								echo $this->voucher->passenger_email;
							} 
							?>
							</strong>	
						</td>
					</tr>	
					<tr>
						<td>Comment</td>
						<td>
							<?php echo $this->voucher->comment;?> 
						</td>
					</tr>								
				</table>				
			</fieldset>	
			</div>
			
			<div style="width:39%;float:left">
				<?php
				$passengers = $this->voucher->getPassengers();
				if(count($passengers)) : 
				?>	
				<fieldset class="adminform">						
				<table class="voucherdetailtable">					
					<tr>
						<td>Trace Passengers</td>
						<td>
							<?php
							$names = array();
							foreach ($passengers as $passenger) {
								$names[] = $passenger->first_name.' '.$passenger->last_name;
							} 
							?>
							<strong><?php echo implode('<br />', $names);?></strong>								
						</td>
					</tr>	
				</table>
				</fieldset>					
				<?php endif;?>
			</div>
				
		</div>
		
		
		
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>

