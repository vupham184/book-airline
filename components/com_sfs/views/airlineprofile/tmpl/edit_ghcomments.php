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
	
		<div class="fieldset-fields" style="padding-top: 35px; padding-left:200px;">
			
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
							<input type="text" name="vouchercomments[<?php echo $item->airline_id;?>]" value="<?php if(isset($commentData[$item->airline_id])) echo $commentData[$item->airline_id];?>" size="50" class="vcommentinput">
						</td>
					</tr>					
				<?php endforeach;?>
			</table>
					
		</div>
	
	</fieldset>
</div>
