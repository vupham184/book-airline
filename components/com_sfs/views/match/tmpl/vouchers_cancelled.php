<?php
defined('_JEXEC') or die;?>

<h4>Cancelled Voucher(s)</h4>

<div class="sfs-main-wrapper" style="padding:10px">
	<div class="floatbox sfs-white-wrapper voucher-match-table">
		<table class="airblocktable" width="100%">
			<tr>
				<th>Block code</th>
				<th>Flight number</th>
				<th>Voucher number</th>
				<th>Seats</th>
				<th>Creation</th>
				<th>Cxl_ed</th>
				<th>Creation Rep</th>
				<th>Room Type</th>
				<th>Group</th>
			</tr>
			<?php foreach ($this->vouchers as $item) :
			if($item->status==3) :
			?>
			<tr>
				<td>
					<?php echo $item->blockcode;?>
				</td>
				<td>
					<?php echo $item->flight_code;?>
				</td>
				<td>
					<?php
					$toolTip = '';
					$phoneNumbers = array();
					if(count($item->passengers))
					{
						
						foreach ($item->passengers as $passenger)
						{
							$toolTip .= $passenger->first_name . ' ' . $passenger->last_name.'<br />';
							if($passenger->phone_number && !in_array($passenger->phone_number, $phoneNumbers))
							{
								$phoneNumbers[] = $passenger->phone_number;
							}
						}
					}

					if( strlen($toolTip) ){
						$toolTip = '<strong>Names on voucher</strong>'.'<p>'.$toolTip.'</p>';
					}
					
					if(count($phoneNumbers)){
						$toolTip .= '<strong>Phone Number</strong>'.'<p>'. implode(', ', $phoneNumbers) .'</p>';
					}
					
					if($item->comment){
						$toolTip .= '<strong>Comment on voucher</strong>'.'<p>'.$item->comment.'</p>';
					}
					if($toolTip) :
					?>
						<span class="hasTip2 underline-text" title="<?php echo $toolTip;?>"><?php echo $item->code;?></span>
					<?php else :?>
						<span><?php echo $item->code;?></span>
					<?php endif;?>
				</td>
				<td>
					<?php echo $item->seats;?>
				</td>
				<td>
					<?php echo JHtml::_('date',$item->created,'H:i');?>
				</td>
				<td>
					<?php echo JHtml::_('date',$item->handled_date,'H:i');?>
				</td>
				<td>
					<?php echo $item->created_name;?>
				</td>
				<td>
					<?php
					if($item->room_type==1){
						echo 'Single';
					} else if($item->room_type==2){
						echo 'Double';
					} else if($item->room_type==3){
						echo 'Triple';
					} else if($item->room_type==4){
						echo 'Quad';
					} else {
						if( (int)$item->sroom > 0)
						{
							echo $item->sroom.' Single<br />';
						}
						if( (int)$item->sdroom > 0)
						{
							echo $item->sdroom.' Double<br />';
						}
						if( (int)$item->troom > 0)
						{
							echo $item->troom.' Triple<br />';
						}
						if( (int)$item->qroom > 0)
						{
							echo $item->qroom.' Quad<br />';
						}
					}
					?>
				</td>
				<td>
					<?php
						$item->vgroup = (int) $item->vgroup ;
						echo ($item->vgroup > 0 ) ? 'Yes ' : 'No';
					?>
				</td>
			</tr>
			<?php
			endif;
			endforeach;?>
		</table>
	</div>
</div>
