<?php
defined('_JEXEC') or die;

$comment = $this->airline->getVoucherComment();

$registry = new JRegistry();
$registry->loadString($comment);

$commentData = $registry->toArray();
?>

<div class="sfs-white-wrapper floatbox">
	<fieldset>
		<div class="fieldset-title" style="width:500px;">
			<span>General voucher comments for specific airlines</span>
		</div>
	
		<div class="fieldset-fields" style="padding-top: 35px; padding-left:220px;">
			
			<table class="vouchercommenttable">
				<tr>
					<th>Airline code</th>
					<th>Airline name</th>
					<th>Comment (max 150 characters)</th>
				</tr>
				<?php 
				$airlines = $this->airline->getServicingAirlines();
				foreach ($airlines as $item): ?>
					<tr>
						<td>
							<?php echo $item->code;?>
						</td>
						<td>
							<?php echo $item->name;?>
						</td>
						<td>
							<?php 
								$vc = (string)$commentData[$item->airline_id];
								
								if( strlen($vc) ) {
									echo $vc;
								} else {
									echo 'none';
								}
							?>
						</td>
					</tr>					
				<?php endforeach;?>
			</table>
					
		</div>
	
	</fieldset>
</div>
