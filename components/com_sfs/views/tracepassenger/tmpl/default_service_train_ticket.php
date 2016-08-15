<?php 
defined('_JEXEC') or die;
$startDateList = SfsHelperDate::getSearchDate('expire', 'class="inputbox fs-13" style="width:155px;"');
$airline = SFactory::getAirline();
$passenger = $this->item;?>
<style type="text/css">	
	.info-service-trainTicket.service-active{
		background: #eeeeee;	
	}
	.info-service-header-trainTicket.service-active .tableInfo td{
		border-bottom: 2px solid #eeeeee!important;
	}
	.info-service-header-trainTicket.service-active  .tableInfo,.info-service-trainTicket.service-active .tableInfo{
		background: #eeeeee;	
	}
	.service-list.content-other{
		width: 99%;
		background: #fff;
		padding: 5px;
	}
	.push-right{
		float: right;
	}
	.push-left{
		float: left;
	}
	.clear{
		clear: both;
	}
</style>
<div class="info-service-header-trainTicket" >	
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr>			
			<td width="40%">Train Ticket</td>
			<td width="10%">
				<img class="icon_train" src="<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>">
			</td>	
			<td width="40%" class="destination_train">Please selected destination</td>
			<td  width="10%"><div id="btn-train_ticket" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
		</tr>							
	</table>	
</div>
<div class="info-service-trainTicket" style="display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr >
			<td >				
				<div class="">
					<div class="service-list content-other" >
						<div class="push-left">
							<img src="<?php echo JURI::base().'media/media/images/train.jpg'; ?>" alt="">
						</div>

						<div class="push-right">
							<p>From Transtation</p>
							<select class="from_transtation save_with_trainstation" style="width: 300px;">
								<?php foreach ($this->airlinetrains as $airlinetrain):?>
									<option value="<?php echo $airlinetrain->id; ?>"><?php echo $airlinetrain->cityname.' '.$airlinetrain->stationname; ?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="push-right">
							<p>To Transtation</p>
							<select class="to_transtation save_with_trainstation" style="width: 300px;">
								<?php foreach ($this->airlinetrains as $airlinetrain):?>
									<?php $nameTrain = $airlinetrain->cityname.' '.$airlinetrain->stationname; ?>
									<option value="<?php echo $airlinetrain->id; ?>" data-nameTrain="<?php echo $nameTrain ?>"><?php echo $nameTrain ?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="push-right date_train" style="margin-right: 145px ">
							<p>Valid for Travel on</p>
							<?php echo $startDateList; ?>
						</div><div class="clear"></div>
						<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_issue_train" >Save</a></div><div class="clear"></div>
					</div><div style= "display:none" class="validate"></div>
				</div>
			</div>
		</td>
	</tr>
</table>
</div>
<script type="text/javascript">
	jQuery(function($){
		<?php echo $script_check; ?>    
		$( "#btn-train_ticket" ).click(function() {
			if($( "#btn-train_ticket span" ).text()=="Open"){    			
				$( ".info-service-header-trainTicket" ).addClass('service-active');
				$( ".info-service-trainTicket" ).addClass('service-active');    			
				$( "#btn-train_ticket" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
			}else{
				$( ".info-service-header-trainTicket" ).removeClass('service-active');    			
				$( ".info-service-trainTicket" ).removeClass('service-active');    			
				$( "#btn-train_ticket" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}
			$(".info-service-trainTicket").slideToggle( "slow" );	
		});	

		function saveIssueTrain(statusTrain){			
			var selected=[];	
			selected.push(<?php echo $passenger->id ?>);	
			var data = {
				id_from_trainstation:$('.from_transtation').val(),
				id_to_trainstation:$('.to_transtation').val(),
				travel_date:$('#date_expire').val(),
				type:2,
				data_passenger:selected
			}
			$.ajax({
				url: 'index.php?option=com_sfs&task=passengersimport.saveTrainTicket',
				type: 'POST',
				data : data
			})
			.done(function(data) {
				if (data>0 && statusTrain == 2) {
					$('.icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png'?>");
					$('.destination_train').text($('.to_transtation ').find(":selected").text());
					
				}
			})
			.fail(function() {
				alert("error");
			});
		}
		jQuery(document).ready(function($) {
		//respond ajax when click button save
		var flag = 0;
		$('.info-service-trainTicket #date_expire').on('change', function() {
			$('.icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>");
			saveIssueTrain(2);
			
		});
		$('.save_with_trainstation').on('change', function() {
			flag += 1; 
			$('.icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>");
			saveIssueTrain(flag);
			if (flag == 2) {
				flag =0;
			}
		});
		$('.save_issue_train').on('click', function() {
			$('.icon_train').attr("src","<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>");
			$('.destination_train').text($('.to_transtation ').find(":selected").text());
		});

	});
	});
</script>