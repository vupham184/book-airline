<?php
defined('_JEXEC') or die;
$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$airline = SFactory::getAirline();
$url_code = SfsUtil::getRandomString(5);
$link_mobile = JUri::root(). 'mobile/?code=' . $url_code . '&tmp=' . $airline->id;
$textSMS = 'View your booking details on \\n' . $link_mobile;

$arrInfo['title']=$this->item->title;
$arrInfo['first_name']=$this->item->first_name;
$arrInfo['last_name']=$this->item->last_name;
$arrInfo['phone_number']=$this->item->phone_number;
$arrInfo['email_address']=$this->item->email_address;
$arrInfo['flight_number']=$this->item->rebook[0]->carrier.$this->item->rebook[0]->flight_no;
$arrInfo['pnr']=$this->item->pnr;
$arrInfo['code']=$this->item->code;
$arrInfo['dep']=$this->item->dep;
$arrInfo['arr']=$this->item->arr;
$arrInfo['std']=$this->item->std;
$arrInfo['etd']=$this->item->etd;
$arrInfo['passenger_id']=$this->item->id;
$passenger = $this->item;
//print_r($this->item);die();

?>
<style type="text/css">
	/*body.contentpane{background: #F0F8FF;}*/
	
	.list-service .box-s {
		background-color: #eff8ff;
		padding: 10px;
		position: relative;
		z-index: 10;
		overflow:hidden;
	}

	.issue_head h3 { color: #1DA9B8; }
	#issue_top{float: left;width: 100%;}
	.list-service .top_left{float: left;width: 32%; background: #ffffff; height: 116px; padding:10px 0 10px 10px;}
	.list-service .top_right{float: left;width: 22%; margin-left: 7px; height: 116px; background: #ffffff; text-align: center;}
	.list-service .top_center{float: left; width: 42%; margin-left: 15px;}
	.list-service .center_on, .center_down{float: left; width: 100%; height: 56px; background-color: #ffffff; padding:10px;}
	.list-service .center_down{margin-top: 3px;}
	.list-service .input_center{width:160px; height: 32px; border-color: #25ABB8;}
	.list-service .top_right button{margin-top: 40px;}
	.list-service .button_issue{
		border:none;
		padding: 8px 10px;
		background-color: #ff8806;
		color: #ffffff;
		font-weight: 600;
	}
	.list-service .info_service{float: left; width: 99%; margin-top: 20px;}
	.list-service .tableInfo{float: left; width: 100%;background-color: #ffffff;}
	.list-service .tableInfo th{
		color: #1DA9B8; text-align: left; padding: 20px 0 10px 5px;
		font-size: 13px !important; font-weight: 600;
	}
	.list-service .tableInfo td{
		border-bottom: 2px solid #1DA9B8; 
		padding: 10px 0 10px 10px;
		font-weight: 600;
	}

	.list-service #sendemailvoucher, 
	.list-service #printvoucher, 
	.list-service #sendsmsvoucher{background: #ff8806; padding: 8px 7px; color:#ffffff;}
	#printvoucher{position: relative; top: 40%;}
	.list-service .header-info-service{
		width: 100%;
		float: left;
	}
	.list-service .service-list p {width: 100px; display: inline-block;} 
	.list-service .service-list div{
		margin: 10px 0px;
	}
	.list-service .tr-color{
		background-color: #EEEEEE;
	}
	.list-service .title_other{width: 200px; margin:-4px 0px 0px 30px; display: none;}
	.list-service span{color: #1DA9B8;}
	.list-service .list-passenger{
		width: 100%;
	}
	.list-service .tableInfo{
		margin:0px;
		margin-right:20px;
	}
</style>

<div>
<a href="javascript:void(0);" onclick="closeListService();" class="sfs-button pull-right close-content" style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px">Close</a>
</div><br />
<div style="margin-left: 10px;" class="main-issue">	
    <div style="margin-left: 10px;">	
        <div class="issue_head">
        <h3>Issue vouchers</h3>
        </div>
        
        <div id="issue_top">
            <div class="top_left">
            <img src="<?php  echo $link_Img.'/group.png' ?>" alt="" width="25px"><br/>
            <div class="list-passenger"><?php echo $this->item->title;?>: <?php echo $this->item->first_name;?><?php echo $this->item->last_name;?></div>
            
            <input type="hidden" style="display: none;" id="id_group_voucher" value="2">
        </div><!--End issue_top-->
        <div class="top_center">
            <div class="center_on">
            <input type="text" id="email" value="<?php echo $this->item->email_address;?>" name="email" class="input_center">
            <!-- <button id="sendemailvoucher" class="button_issue">EMAIL VOUCHERS</button> -->
            <a id="sendemailvoucher" href="javascript:void(0);">EMAIL VOUCHERS</a>
            </div>
            <div class="center_down" style="position:relative;">
            <input type="text" id="tel" value="<?php echo $this->item->phone_number;?>" name="sms" class="input_center">
            <!-- <button  class="button_issue">SMS VOUCHERS</button> -->
            <a id="sendsmsvoucher" data-flight-number="<?php echo $this->item->rebooked_fltno->carrier . $this->item->rebooked_fltno->flight_no;?>" data-std="<?php echo $this->item->rebooked_fltno->std;?>" data-etd = "<?php echo $this->item->rebooked_fltno->etd;?>" data-passengers="<?php echo str_replace('"',"'", json_encode($arrInfo)) ; ?>" data-url-code="<?php echo $url_code;?>" onclick="sendsmsvoucher(this);" href="javascript:void(0);">SMS VOUCHERS</a>
            <span class="ajx-loading loading" style="display:none; position:absolute; left:150px; top:0px;">&nbsp;</span>
            
            
            
            </div>
        </div><!--End top_center-->
        
        <div class="top_right">
        <!-- <button class="button_issue">PRINT VOUCHERS</button> -->
        <a id="printvoucher" href="javascript:void(0);">PRINT VOUCHERS</a>
        </div>
        
        <div class="info_service">
            <div class="info-service-header">
            <table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
            <tr>
            <th width="40%">Service</th>
            <th width="10%">Status</th>
            <th width="40%">Details</th>
            <th width="10%">Details</th>
            </tr>
            </table>
            </div>
            <!-- Hotel -->
        	<div id="service1" class="obj-service" style="display:none;">
				<?php  echo $this->loadTemplate('service_hotel');?>
            </div>
            <!-- Taxi Transfer-->
            <div id="service4" class="obj-service" style="display:none;">
            <?php  echo $this->loadTemplate('service_taxi');?>
            </div>
            <!-- Rentel car -->
            <div id="service6" class="obj-service" style="display:none;">
            <?php  echo $this->loadTemplate('service_rental_car');?>
            </div>
            <!--  Train service -->
            <div id="service5" class="obj-service" style="display:none;">
            <?php echo $this->loadTemplate('service_train_ticket'); ?>
            </div>
            <!-- Refreshment -->
            <div id="service2" class="obj-service" style="display:none;">
            <table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
            <?php echo $this->loadTemplate('issue_voucher_refreshment'); ?>
            </table>
            </div>	
            <div id="service3" class="obj-service" style="display:none;">
            <table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
            <?php echo $this->loadTemplate('issue_group_transport_company'); ?>
            </table>
            </div>	
            <!-- Other -->
            <div id="service8" class="obj-service sub_service_other" style="display:none;">
				<?php echo $this->loadTemplate('ss_other_maas'); ?>
			</div>
			<div id="service10" class="obj-service sub_service_other" style="display:none;">
				<?php echo $this->loadTemplate('ss_other_snackbags'); ?>
			</div>	
			<div id="service11" class="obj-service sub_service_other" style="display:none;">
				<?php echo $this->loadTemplate('ss_other_phonecard'); ?>
			</div>	
			<div id="service12" class="obj-service sub_service_other" style="display:none;">
				<?php echo $this->loadTemplate('ss_other_cash'); ?>
			</div>
			<div id="service14" class="obj-service sub_service_other" style="display:none;">
				<?php echo $this->loadTemplate('ss_other_miscellaneous'); ?>
			</div>
			<div style="display:none">
				<input type="hidden" class="sub_service_id">
			</div>
        </div><!--End info_service-->
		
    </div><!--End margin-left: 10px; 2-->
</div><!--End main-issue-->

<script type="text/javascript">
	var v_voucher_id='<?php echo $this->item->voucher_id; ?>';
	var v_voucher_groups_id ='<?php echo $this->item->voucher_groups_id; ?>';
	var v_flight_number='<?php echo $arrInfo['flight_number']; ?>';
	jQuery(document).ready(function($){
		<?php if($passenger->services_of_passenger){
			foreach($passenger->services_of_passenger as $service)	{
			?>			
			$('#service<?php echo $service->id; ?>').css('display','block');
			<?php
			}
		}  ?>	
		
		if ($('.id_title option').length == 1) {
			$('.title_other').val('').show();
		}

		$(document).on('click','.id_title', function(){
			var vOther 		= $(this).val();
			if (vOther == '-1') {
				$('.title_other').val('');
				$('.title_other').val('').show();
			}
			else{
				$('.title_other').val('').hide();
			}
		});

		$(document).on('blur','.title_other', function() {
			var id_ariline			= <?php echo $airline->iatacode_id;?>;
			var title = $('.title_other').val().trim();
				var titledata = {title: title,
					id_ariline:id_ariline};
			if (title != '') {
				$.ajax({
					url: 'index.php?option=com_sfs&task=passengersimport.addTileIssueVoucher',
					type: 'POST',
					data: titledata,
					success:function(data) {
						$('#id_title_other').attr('value',data);
					}
				});
			}
		});
		$(document).on('click','.save_issue', function() {
			var title 				= $('.id_title').val().trim();
			var description 		= $('#description').val().trim(); 
			var number_cost 		= $('#number_cost').val().trim() ;
			var currency_code		= $('#currency_code').val().trim();
			var internal_comment	= $('.internal_comment').val();
			var id_group_voucher 	= $('#id_group_voucher').val().trim();
			
			var validate = '';
			var data = {title: title,
				description:description,
				number_cost:number_cost,
				currency_code:currency_code,
				internal_comment:internal_comment,
				id_group_voucher:id_group_voucher};
			
			if (title=='') {
				validate += 'title is null<br>';
			}
			else if(description==''){
				validate += 'description is null<br>';
			}
			else if(number_cost==''){
				validate += 'number cost is null<br>';
			}
			else if(internal_comment==''){
				validate += 'internal comment is null<br>';
			}
			else{
				validate = '';
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
						}
					}
				});
				
			}
			
		});
		
		
	});
	
	function sendsmsvoucher(obj){
		var data_tel = jQuery('#tel').val();
		var data_passengers = jQuery(obj).attr("data-passengers");
		var data_url_code = jQuery(obj).attr("data-url-code");
		var data_flight_number = jQuery(obj).attr("data-flight-number");
		var data_std = jQuery(obj).attr("data-std");
		var data_etd = jQuery(obj).attr("data-etd");
		jQuery('.ajx-loading').css('display', 'block');
		var pass_ids=getPassengerCheck($);
		if( data_tel == '' ) {
			alert("We can not find the Phone number to the passenger Mrs Candy, we can not issue the vouchers per SMS per this passenger.");
			jQuery('.ajx-loading').css('display', 'none');
			return false;
		}

		var da = {
			'text_message': '<?php echo $textSMS;?>', 
			'data_tel': data_tel, 
			'data_passengers' : data_passengers,
			'flight_number' : data_flight_number,
			'data_url_code' : data_url_code,
			'std' : data_std,
			'etd' : data_etd,
			'pass_ids':pass_ids
		}
			
			jQuery.post("index.php?option=com_sfs&task=internalcomment.sendSMS",da,
			function(data, status){		
				jQuery('.ajx-loading').css('display', 'none');		
				if( data.successful == 1 ){
					alert(data.messages.message.errormessage);
				}
				else if( data.errorcode == 'maxl' ) {
					alert(data.errormessage);
				}
				else if( data.errorcode != 0 ) {
					alert(data.errormessage);
				}
			}, 'json');
				
				
	}
	//lchung
	jQuery(function( $ ){
		$('#printvoucher').click( function(){
			var v_pas_id_com = '<?php echo $passenger->id; ?>';
			// if (v_voucher_id == 0 ) {
			// 	alert('Please choose a service Hotel');
			// }
			// else {
			
			if(v_voucher_id == 0 ){
				v_voucher_id = 1;
			}

			<?php if( (int)$airline->id == 41): ?>
				SqueezeBox.open('<?php echo 'index.php?option=com_sfs&view=voucher&layout=default_single_specific&tmpl=component';?>&id='+v_pas_id_com+'&voucher_id=' + v_voucher_id + '&voucher_groups_id=' + v_voucher_groups_id + '&flight_number=' + v_flight_number, {handler: 'iframe', size: {x: 730, y: 540} });
			<?php else: ?>
				SqueezeBox.open('<?php echo 'index.php?option=com_sfs&view=voucher&tmpl=component';?>&voucher_id=' + v_voucher_id + '&voucher_groups_id=' + v_voucher_groups_id + '&flight_number=' + v_flight_number, {handler: 'iframe', size: {x: 730, y: 600} });
			<?php endif; ?>

			// SqueezeBox.open('<?php //echo 'index.php?option=com_sfs&view=voucher&tmpl=component';?>&voucher_id=' + v_voucher_id + '&voucher_groups_id=' + v_voucher_groups_id + '&flight_number=' + v_flight_number, {handler: 'iframe', size: {x: 730, y: 600} });
			
			var da = {'printtype':'print', 'voucher_id': v_voucher_id, 'passenger_id': v_pas_id_com};
			$.post("index.php?option=com_sfs&task=passengersimport.updateStatusVoucher",da,
			function(data, status){		
				//
			}, 'json');
			
			var dacommentPassenger = {'internal_comment':'Voucher printed on <?php echo date('d-M-Y H:i:s'); ?> by <?php echo $user->name;?>', 'passenger_id': v_pas_id_com };
			$.post("index.php?option=com_sfs&task=internalcomment.save", dacommentPassenger,
			function(data, status){		
				//
				document.location.reload( true );
			}, 'json');
				
			// }
		});
		
		$('#sendemailvoucher').click( function(){
			var v_pas_id_com = '<?php echo $passenger->id; ?>';
			var vemail = $('#content-issue input[id="email"]').val();
			if( vemail == '' ){
				alert('Please enter a email');
				return false;
			}
			if (v_voucher_id == 0 ) {
				alert('Please choose a service Hotel');
			}
			else {
				
				var da = {'printtype':'email', 'voucher_id': v_voucher_id,'passenger_id': v_pas_id_com,'email': vemail};
				
				$.ajax({	            
					url: "index.php?option=com_sfs&task=passengersimport.sendemailvoucher",
					type: "POST",
					data:da,
					dataType:"json",        
					success: function(data){
						if( data.successful == 0 ) {
							alert(data.errormessage);
							$('#email').focus();
						}
						else {
							alert(data.errormessage);
							$('.ajx-loading').css('display', 'block');				
							
							$.post("index.php?option=com_sfs&task=passengersimport.updateStatusVoucher",da,
							function(data, status){		
								//
								$('.ajx-loading').css('display', 'none');
							}, 'json');
							
							var dacommentPassenger = {'internal_comment':'Voucher send by email to ' + vemail + ' on <?php echo date('d-M-Y H:i:s'); ?> by <?php echo $user->name;?>', 'passenger_id': v_pas_id_com };
							$.post("index.php?option=com_sfs&task=internalcomment.save", dacommentPassenger,
							function(data, status){		
								//
								document.location.reload(true);
								$('.ajx-loading').css('display', 'none');
							}, 'json');
							
						}//End else of if( data.successful == 0 ) {
						
					}//End success:
					
				});
				
			}//End else of if (v_voucher_id == 0
			
		});//End $('#sendemailvoucher').click
		
	});
	//End lchung
</script>
