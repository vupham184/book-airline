<?php ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		/*$(document).on('click','#mbtn', function() {
			var name = $("#mbtn span").text();
			
			if(name=="Open"){
				$("#mbtn").html('<span>Close</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
			}else{
				$("#mbtn").html('<span>Open</span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}
			$('#mdiv').slideToggle("slow");//.animate({height:300},700);
		});*/
		$(document).on('click','.save_issue_cash', function() {
			var title 				= $('.cash_service #id_title').val().trim();			
			var number_cost 		= $('.cash_service #amount').val().trim();
			var currency_code		= $('.cash_service #currency_code').val().trim();
			var internal_comment	= $('.cash_service .internal_comment').val().trim();
			var id_group_voucher 	= $(' #id_group_voucher').val().trim();
			
			var validate = '';
			var data = {title: title,
				number_cost:number_cost,
				currency_code:currency_code,
				internal_comment:internal_comment,
				service_type:2,
				id_group_voucher:id_group_voucher};
			
			if(number_cost==''){
				validate += 'Amount is null<br>';
			}
			if(internal_comment==''){
				validate += 'internal comment is null<br>';
			}
			if (validate != '') {
				$('.validate').html(validate).show();
			}				
			else{
				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.infoIssueVoucher',
					type: 'POST',
					data: data,
					success:function(data) {
						if (data) {
							$('.img_status').attr('src','<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
							console.log(data);
						}
					}
				});
				
			}
			
		});
	});
	
</script>
<style type="text/css">
	.info-service-header-cash-refunds.service-active .tableInfo,.info-voucher-cash-refunds.service-active .tableInfo{
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
<div class="info-service-header-cash-refunds"  >	
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr class="cash_title">
			<td width="40%">Cash Reimbursement</td>
			<td width="10%">
				<img class="img_status" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
			</td>
			<td width="40%">Euro Cash</td>
			<td width="10%" id="mbtn" value="" style="cursor: pointer;">
				<div id="btn-cash-refunds" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div>
			</td>
		</tr>
	</table>
</div>
<div class="info-voucher-cash-refunds" style="display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr id="mdiv" class="cash_service">
			<!-- <td colspan="1">Cash Reimbursement</td> -->
			<td colspan="4">
				<div class="content-cash-refunds">
				<div>
					<span>Type of Measure</span>
					<select style="width: 300px; margin-left: 17px" name="id_title" id="id_title">
						<?php foreach ($this->titleariline as $key => $value): ?>
							<option value ='<?php echo $value->id ;?>'><?php echo $value->title.' ('.$value->value.')'; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div style="padding-top: 10px">
					<span>Amount</span>
					<input type="text" id="amount" style="width: 150px;  margin:0px 0px 0px 65px;">
					<select style="width: 150px; margin: 5px 5px" id="currency_code">
						<option>
							Euro
						</option>
					</select>
				</div>
				
				<div style="margin-top: 20px">
					<span style="float: left;">Internal Comment</span>
					<textarea cols="50" rows="5" style=" margin: 0px 15px" class="internal_comment">
					</textarea>
				</div>
				<div style="clear: both;"></div>
				<div style="float: left; display: none;" class="validate"></div>
				<div style="cursor:pointer;float: right;margin: 10px 30px 0px 0px;"><a  class="button_issue save_issue_cash">Save</a></div>
				</div>				
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
	jQuery(function($){
    	
    	$( "#btn-cash-refunds" ).click(function() {
    		if($( "#btn-cash-refunds span" ).text()=="Open"){    			
    			$( ".info-service-header-cash-refunds" ).addClass('service-active');
    			$( ".info-voucher-cash-refunds" ).addClass('service-active');    			
    			$( "#btn-cash-refunds" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
    		}else{
    			$( ".info-service-header-cash-refunds" ).removeClass('service-active');    			
    			$( ".info-voucher-cash-refunds" ).removeClass('service-active');    			
    			$( "#btn-cash-refunds" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
    		}
		  	$(".info-voucher-cash-refunds").slideToggle( "slow" );	
		});	
    });

</script>