<?php 
defined('_JEXEC') or die;
// echo "<pre>";
// print_r($this->airline);
// echo "</pre>";
?>
<style type="text/css">	
	.info-service-other.service-active{
		background: #eeeeee;	
	}
	.info-service-header-other.service-active .tableInfo td{
		border-bottom: 2px solid #eeeeee!important;
	}
	.info-service-header-other.service-active  .tableInfo,.info-service-other.service-active .tableInfo{
		background: #eeeeee;	
	}
	.service-list.content-other{
		width: 99%;
		background: #fff;
		padding: 5px;
	}
</style>
<div class="info-service-header-other" >	
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr>			
			<td width="40%">Other</td>
			<td width="10%">
				<img class="img_status" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
			</td>	
			<td width="40%" class="title-other">Select</td>
			<td  width="10%"><div id="btn-other" class="btn-open"><span>Open<span><img class="icon_other" src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
		</tr>							
	</table>	
</div>
<div class="info-service-other" style="display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr id="rental-car-content">	
			<td>				
				<div class="">
					<div class="service-list content-other" >
						<div>
							<p>Title</p>
							<select class="id_title save_issue" style="width: 300px;">
								<?php foreach ($this->titleariline as $key => $value): ?>
									<?php echo '<option value ='.$value->id.'>'.$value->title.'</option>'; ?>";
								<?php endforeach; ?>
								<option id="id_title_other" value="0" >Other</option>
								<input type="text" class="title_other save_issue" placeholder="Title Other">
							</select>
						</div>
						
						<div>
							<p>Description</p>
							<input type="text" style="width: 500px;" id="description" class="save_issue"></input>
						</div>
						<div>
							<p>Costs</p><input id="number_cost" class="save_issue" type="text" style="width: 150px;"></input><select id="currency_code" class="save_issue" style="width:150px;padding-top:4px;margin-left: 5px;"><option><?php echo $this->airline->currency_code; ?></option></select>
						</div>
						<div>
							<p style="margin-top: -100px;">Iternal Comment</p><textarea class="internal_comment save_issue" rows="4" cols="39"></textarea>
						</div>
						<div  style="float: right; padding-right: 30px; "><a style="cursor:pointer;" class="button_issue save_issue" >Save</a></div>
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
		var v_isvIdTitleAirline=$('#isvIdTitleAirline').val();
		var v_isvDescription=$('#isvDescription').val();
		var v_isvNumberCosts=$('#isvNumberCosts').val();
		var v_isvCodeCurrency=$('#isvCodeCurrency').val();
		var v_isvInternalComment=$('#isvInternalComment').val();
		var v_isvTitleAirline=$('#isvTitleAirline').val();
		if(v_isvTitleAirline!='' && v_isvIdTitleAirline!=''){
			$('.title-other').html(v_isvTitleAirline);
			$('.info-service-header-other .img_status').attr('src','<?php echo JURI::root().'media/media/images/VoucherOK.png'?>');
		}

		$('.id_title').on('click', function(){
			var vOther 		= $(this).val();
			if (vOther == 0) {
				$('.title_other').val('');
				$('.title_other').val('').show();
			}
			else{
				$('.title_other').val('').hide();
			}
		});  


		$( "#btn-other" ).click(function() {
			if($( "#btn-other span" ).text()=="Open"){
				
				$('.id_title option').each(function() {
					if ($(this).val() == v_isvIdTitleAirline) {
						$(this).attr('selected', true);
					}
				});

				$('#description').val(v_isvDescription);
				$('#number_cost').val(v_isvNumberCosts);
				$('.internal_comment').val(v_isvInternalComment);
				$('#currency_code').each(function() {
					if ($(this).text() == v_isvCodeCurrency) {
						$(this).attr('selected', true);
					}
				});
				$('.id_title option:selected').each(function() {

					if ($(this).text() == 'Other') {
						$('.title_other').val(v_isvTitleAirline).show();

					}
					else{
						$('.title_other').hide();
					}
				});

			}else{
				$('.info-service-header-other .img_status').attr('src','<?php echo JURI::root().'media/media/images/VoucherNOK.png'?>');

			}

		});	


		$('.save_issue').on('blur', function() {
			var title 				= $('.id_title').val().trim();
			var description 		= $('#description').val().trim(); 
			var number_cost 		= $('#number_cost').val().trim() ;
			var currency_code		= $('#currency_code').val().trim();
			var internal_comment	= $('.internal_comment').val();
			var passenger_id = [];
			//$('.trContent input:checked').each(function() {
			passenger_id.push($('#passenger_id').val());			   
			//});
			
			var validate = '';
			var title_airline ='';
			var id_title_select = $('.id_title option:selected').text();
			if (id_title_select == 'Other') {
				title_airline = $('.title_other').val();
			}
			else{
				title_airline = id_title_select;
			}
			if (title=='' || title_airline == '') {
				validate += 'title is null<br>';
			}
			if(description==''){
				validate += 'description is null<br>';
			}
			if(number_cost==''){
				validate += 'number cost is null<br>';
			}
			if(internal_comment==''){
				validate += 'internal comment is null<br>';
			}


			if (validate != '') {
				$('.validate').html(validate).show();
			}				
			else{
				$('.validate').hide();
				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.infoIssueVoucher',
					type: 'POST',
					data: {
						title: title,
						description:description,
						number_cost:number_cost,
						currency_code:currency_code,
						internal_comment:internal_comment,
						title_airline : title_airline,
						passenger_id:passenger_id.toString()
					},
					success:function(data) { 
						if (data>0) {
							$('.info-service-header-other .img_status').attr('src','<?php echo JURI::root().'media/media/images/VoucherOK.png' ?>');
						}
						else{
							$('.info-service-header-other .img_status').attr('src','<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>');
						}
					}
				});
				
			}
			
		}); 


		$( "#btn-other" ).click(function() {
			if($( "#btn-other span" ).text()=="Open"){    			
				$( ".info-service-header-other" ).addClass('service-active');
				$( ".info-service-other" ).addClass('service-active');    			
				$( "#btn-other" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
			}else{
				$( ".info-service-header-other" ).removeClass('service-active');    			
				$( ".info-service-other" ).removeClass('service-active');    			
				$( "#btn-other" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
			}
			$(".info-service-other").slideToggle( "slow" );	
		});	


		$('#number_cost').keypress(function (event) {
			return isNumber(event, this)
		});
		function isNumber(evt, element) {
			var charCode = (evt.which) ? evt.which : event.keyCode;

			if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
				return false;

			return true;
		} 
	});
</script>