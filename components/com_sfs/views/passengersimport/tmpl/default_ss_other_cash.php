<?php 
$airline = SFactory::getAirline();
?>


<style type="text/css">
	.info-service-header-cash-refunds.service-active .tableInfo,.info-ss-cash.service-active .tableInfo{
		background: #eeeeee;	
	}
	.info-service-header-cash-refunds.service-active .tableInfo td{
		border-bottom:0;
	}
	.content-cash-refunds{
		width: 99%;
		background: #fff;
		padding: 5px;
	}
</style>

<div class="info-ss-cash info_sub_service" style="display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr id="mdiv" class="cash_service">
			<td colspan="4">
				<div class="content-cash-refunds">
				<div class="push-left" style="width:250px">
							<img src="<?php echo JURI::base().'media/media/images/cash-payment.png'; ?>" style="width:90px" alt="">
						</div>
				<div style="padding-top: 10px">
					<span>Amount</span>
					<input type="text" id="amount" class="inputamount" style="width: 150px;  margin:0px 0px 0px 65px;">
					<select style="width:100px" class="currency_code">
						<?php if ($airline->currency_code != 'EUR'): ?>
							<option value="EUR">EUR</option>
							<option value="<?php echo $airline->currency_code; ?>"><?php echo $airline->currency_code; ?></option>
						<?php else: ?>
							<option value="EUR">EUR</option>
						<?php endif ?>
					</select>
				</div>
				
				<div style="margin-top: 20px">
					<p class="color-text" style="vertical-align:top;width:70px">Internal Comment</p>     
					<textarea rows="6" cols="30" class=" internal_comment" style="margin-left: 10px;"></textarea>
				</div>
				<div style="clear: both;"></div>
				<div style="float: left; display: none;" class="validate"></div>
				<div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;"><a  class="button_issue save_ss_cash">Save</a></div>
				</div>				
			</td>
		</tr>
	</table>
</div>
