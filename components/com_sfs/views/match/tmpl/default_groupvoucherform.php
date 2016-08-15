<?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$session = JFactory::getSession();
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$allowGroupTransport = false;
$groupTransportInclude = $session->get('groupTransportInclude',0);
if( $this->voucher[0]->id == $groupTransportInclude )
{
	$allowGroupTransport = true;	
}
foreach ( $this->voucher as $ks => $vs) {
$voucher_id[] = $vs->id;
}
$printUrl =  JURI::base().'index.php?option=com_sfs&view=voucher&voucher_id='.implode(",", $voucher_id).'&voucher_groups_id=' . $this->voucher_groups->id. '&tmpl=component';
$airport_current = $airline->getCurrentAirport();
$distance = SfsHelper::getDistanceHotelAirport($this->voucher[0]->hotel_id, $airport_current->id);
$ap_taxi_value = SfsWs::getHotelDistance($this->voucher[0]->hotel_id, $airplusparams['taxi_fee']);
$ap_meal_first_value = $airplusparams['meal_first_limit'];
$ap_meal_second_value = $airplusparams['meal_second_limit'];
?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>
<style>
	.airplus-block{
		background: #eee;
		border: 1px solid #ddd;
		border-radius: 5px;
		padding: 10px;
	}
	.airplus-block:after{
		display: block;
		content: ' ';
		clear: both;
	}
	.airplus-block h3{
		font-size: 110%;
	}
</style>
<script type="text/javascript">
	jQuery(function ($) {
	    $(document).ready(function() {
	        $(".ui.checkbox").checkbox();
            $("#return_taxi").html(getDateFormatted($("#returnflightdate").val()));
            $("#return_meal_first").html(getDateFormatted($("#returnflightdate").val()));
            $("#return_meal_second").html(getDateFormatted($("#returnflightdate").val()));
	    });
        $("#ap-taxi").on('change', function(){
            updateAirplusServices(this);
        });
        $("#ap-meal-first").on("change", function(){
            if($(this).is(":checked")){
                $("#ap-meal-second").prop('checked', false);
            }
            updateAirplusServices(this);
        });
        $("#ap-meal-second").on("change", function(){
            if($(this).is(":checked")){
                $("#ap-meal-first").prop('checked', false);
            }
            updateAirplusServices(this);
        });
        $("#returnflightdate").on("change", function(){
            $("#return_taxi").html(getDateFormatted($("#returnflightdate").val()));
            $("#return_meal_first").html(getDateFormatted($("#returnflightdate").val()));
            $("#return_meal_second").html(getDateFormatted($("#returnflightdate").val()));
        });

        function getDateFormatted(str){
            var date = new Date(str);
            var day = date.getDate();
            var month = date.getMonth();
            var mon = new Array();
            mon[0] = "January";
            mon[1] = "February";
            mon[2] = "March";
            mon[3] = "April";
            mon[4] = "May";
            mon[5] = "June";
            mon[6] = "July";
            mon[7] = "August";
            mon[8] = "September";
            mon[9] = "October";
            mon[10] = "November";
            mon[11] = "December";
            month = mon[month];
            var year = date.getFullYear();
            return day + " " + month + " " + year;
        }

	    function updateAirplusServices(_this){
	        var $ = jQuery;
	        var form = $(_this).closest('form');
            var meal;
            var taxi            = jQuery("#ap-taxi").is(':checked') ? 1 : 0;
            var mealfirst       = jQuery("#ap-meal-first").is(':checked') ? 1 : 0;
            var mealsecond      = jQuery("#ap-meal-second").is(':checked') ? 1 : 0;
            var enddate         = jQuery("#returnflightdate").val();
            if(mealfirst == 1){
                meal = 1;
            }else{
                if(mealsecond == 1){
                    meal = 2;
                }else{
                    meal = 0;
                }
            }
	        jQuery.ajax({
	            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.updateAirplusServices&format=raw'; ?>",
	            type:"post",
	            beforeSend: function(){
	                form.find('button').prop('disabled', true);
	                form.find('#ap-meal-first, #ap-meal-second, #ap-taxi').prop('disabled', true);
	            },
	            data: {
	                voucher_id: '<?php echo implode(",", $voucher_id);// $this->voucher_id?>',
	                'ap-meal': meal,
	                'ap-taxi': taxi,
                    enddate:enddate
	            },
	            dataType:"text",
	            success: function(response){
	                if(response == '0'){
	                    alert("ERROR!");
	                }
	            },
	            complete: function(){
	            	form.find('button').prop('disabled', false);
	            	form.find('#ap-meal-first, #ap-meal-second, #ap-taxi').prop('disabled', false);
	            }
	        })
	    }
	});
	<!--
	window.addEvent('domready', function() {

		var printLink = '<?php echo $printUrl;?>';
		var printVoucherForm  = document.id('groupVoucherPrintForm');

		var printVoucherFormRequest = new Form.Request(printVoucherForm, $('testre'),  {
		    requestOptions: {
		    	useSpinner: false
		    },
			onComplete: function(responseText){
				if(printVoucherForm.printtype.value =='print'){
					$('printRequestSpinner').removeClass('ajax-Spinner');
					if(printVoucherForm.separatevoucher.value==0){
						SqueezeBox.open(printLink, {handler: 'iframe',size: {x: 954, y: 690}});
					} else {
						printLink2 = printLink + '&separatevoucher=1';
						SqueezeBox.open(printLink2, {handler: 'iframe',size: {x: 954, y: 690}});
					}
				} else if(printVoucherForm.printtype.value =='email'){
					$('emailRequestSpinner').removeClass('ajax-Spinner');
				}
			},
		    resetForm : false
		});

		$('printRequest').addEvent('click', function(e){
			e.stop();
			$('printRequestSpinner').addClass('ajax-Spinner');
			printVoucherForm.printtype.value = 'print';
			printVoucherForm.separatevoucher.value = 0;
			printVoucherFormRequest.setTarget($('testre'));
			printVoucherFormRequest.send();
		});
		$('printRequest2').addEvent('click', function(e){
			e.stop();
			$('printRequestSpinner2').addClass('ajax-Spinner');
			printVoucherForm.printtype.value = 'print';
			printVoucherForm.separatevoucher.value = 1;
			printVoucherFormRequest.setTarget($('testre'));
			printVoucherFormRequest.send();
		});
		$('emailRequest').addEvent('click', function(e){
			e.stop();
			$('emailRequestSpinner').addClass('ajax-Spinner');
			printVoucherForm.printtype.value = 'email';
			printVoucherForm.separatevoucher.value = 0;
			printVoucherFormRequest.setTarget($('emailFormResult'));
			printVoucherFormRequest.send();
		});

		$('closeGroupVoucherForm').addEvent('click', function(e){
			e.stop();
			$('sfs-voucher-print-form').destroy();
		});
		$('showNamesForm').addEvent('click', function(e){
			e.stop();
			$('sfs-insertnames-form').setStyle('display','block');
		});

	});
-->
</script>

<?php
if( $allowGroupTransport ) {
	$wrapClass = 'sfs-print-voucher-2-column';	
} else {
	$wrapClass = 'sfs-print-voucher-1-column';
} 
?>

<div id="sfs-voucher-print-form"> <!-- class="<?echo $wrapClass;?>" -->

		<div class="sfs-main-wrapper sfs-voucher-print-box sfs-print-voucher-column">
			<form id="groupVoucherPrintForm" name="groupVoucherPrintForm" action="<?php echo JRoute::_('index.php')?>" method="post">
				<?php if( $allowGroupTransport ) : ?>
				<div class="sfs-white-wrapper floatbox midmarginbottom" style="padding: 2px 15px;min-height:10px;">
					<div class="voucher-hotel-icon">
						Hotel Voucher
					</div>	
				</div>	
				<?php endif;?>
				
				<?php
				if( isset($airline->params['show_vouchercomment']) && (int)$airline->params['show_vouchercomment'] == 1 ) :  
				?>
				<div class="sfs-white-wrapper floatbox midmarginbottom">			
					<?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?>:<br />				
					<textarea name="comment" id="vouchercomment" style="width:98%;height:90px;"><?php if($this->voucher->flight_comment) echo $this->voucher->flight_comment;?></textarea>
				</div>
				<?php endif;?>
				
				<div class="sfs-white-wrapper floatbox midmarginbottom">
			
					<div id="testre"></div>
					
					<div>Insert names (optional)</div>
					<div class="fs-11" style="padding-top: 5px; line-height:13px;">When you insert names it will be easier for you to trace the passengers at a later stage.</div>
					
					<div class="midmargintop">															
						<span id="showNamesForm" class="underlinetext">to insert names click here</span>
					</div>
					
				</div>	
				
				
				<?php if( isset($airline->params['enable_return_flight']) && (int)$airline->params['enable_return_flight'] == 1 ) : ?>
			
				<div class="sfs-white-wrapper floatbox midmarginbottom" style="min-height:50px;">
					<div class="sfs-row">
						<div class="sfs-column-left" style="width:120px;">
							New flight number
						</div>
						<input type="text" name="returnflight" value="" style="width:130px;" />
					</div>			
					<div class="sfs-row">
						<div class="sfs-column-left" style="width:120px;">
							New flight date
						</div>
						<?php
							$endDateList 	= SfsHelperDate::getSearchDate('end','class="inputbox"','returnflightdate');
							echo $endDateList; 
						?>
					</div>
				</div>
				<?php endif; ?>
				
				<div class="airplus-block">
					<div><h3>Transfer to Hotel</h3></div>
	                <div style="margin-top: 15px;">
	                    <?php if((int)$airplusparams['taxi_enabled'] == 1):?>
	                        <div style="width: 20%; float: left">
	                            <div class="ui toggle checkbox">
	                                <input name="ap-taxi" type="checkbox" id="ap-taxi" >
	                                <input name="ap-taxi-response" type="hidden" id="ap-taxi-response" value="">
	                            </div>
	                        </div>
	                        <div style="width: 80%; float: left">
	                            Distance to airport <?php echo $distance->distance." ".$distance->distance_unit;?><br/>
	                            Estimated taxi costs return trip <?php echo $ap_taxi_value?> Euro<br/>
                                Expiry date <span id="return_taxi"></span>
	                            <p>
	                            <a style="display:none;" id="ViewCardTransferRequest" href="javascript:void(0);" class="small-button" style="margin-top:0;width: 50px;">View</a>
	                           	</p>
	                        </div>
	                    <?php else:?>
	                        <div style="width: 20%; float: left">
	                            <div class="ui toggle checkbox disabled">
	                                <input name="ap-taxi" type="checkbox" id="ap-taxi" >
	                            </div>
	                        </div>
	                        <div style="width: 80%; float: left; opacity: 0.35;">
                                Distance to airport <?php echo $distance->distance." ".$distance->distance_unit;?><br/>
                                Estimated taxi costs return trip <?php echo $ap_taxi_value?> Euro<br/>
                                Expiry date <span id="return_taxi"></span>
	                            <p>
	                            <a style="display:none;" id="ViewCardTransferRequest" href="javascript:void(0);" class="small-button" style="margin-top:0;width: 50px;">View</a>
	                           	</p>
	                        </div>
	                    <?php endif;?>
	                </div>
	                <div><h3>Meal plan voucher</h3></div>
		                <div style="margin-top: 15px;">
		                    <?php if((int)$airplusparams['meal_enabled'] == 1):?>
                                <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                                    <div style="width: 20%; float: left;margin-top: 15px">
                                        <div class="ui toggle checkbox">
                                            <input name="ap-meal-first" type="checkbox" id="ap-meal-first">
                                        </div>
                                    </div>
                                    <div style="width: 80%; float: left; margin-top: 15px">
                                        Issue <?php echo $ap_meal_first_value?> Euro mealplan voucher per person <br/>
                                        Expiry date <span id="return_meal_first"></span>
                                    </div>
                                <?php endif;?>
                                <?php if($ap_meal_second_value && $ap_meal_second_value >0 ):?>
                                    <div style="width: 20%; float: left; margin-top: 15px">
                                        <div class="ui toggle checkbox">
                                            <input name="ap-meal-second" type="checkbox" id="ap-meal-second">
                                        </div>
                                    </div>
                                    <div style="width: 80%; float: left; margin-top: 15px">
                                        Issue <?php echo $ap_meal_second_value?> Euro mealplan voucher per person <br/>
                                        Expiry date <span id="return_meal_second"></span>
                                    </div>
                                <?php endif;?>
                            <?php else:?>
                                <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                                    <div style="width: 20%; float: left; margin-top: 15px">
                                        <div class="ui toggle checkbox disabled">
                                            <input name="ap-meal-first" type="checkbox" id="ap-meal-first">
                                        </div>
                                    </div>
                                    <div style="width: 80%; float: left; opacity: 0.35; margin-top: 15px">
                                        Issue <?php echo $ap_meal_first_value?> Euro mealplan voucher per person <br/>
                                        Expiry date <span id="return_meal_first"></span>
                                    </div>
                                <?php endif;?>
                                <?php if($ap_meal_first_value && $ap_meal_first_value >0 ):?>
                                    <div style="width: 20%; float: left; margin-top: 15px">
                                        <div class="ui toggle checkbox disabled">
                                            <input name="ap-meal-second" type="checkbox" id="ap-meal-second">
                                        </div>
                                    </div>
                                    <div style="width: 80%; float: left; opacity: 0.35;">
                                        Issue <?php echo $ap_meal_second_value?> Euro mealplan voucher per person <br/>
                                        Expiry date <span id="return_meal_second"></span>
                                    </div>
                                <?php endif;?>
		                    <?php endif;?>
		                </div>
				</div>
				
				<div class="sfs-white-wrapper floatbox">				
					<div id="emailFormResult" style="color:red;font-size:12px;"></div>
					<div class=""><?php echo JText::_('COM_SFS_MATCH_VOUCHER_PRINT_BOX_TITLE');?></div>
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr valign="middle">
					    	<td style="padding-bottom:7px;"><input type="text" name="email" value="@" class="validate-email" style="width:160px;" /></td>
					        <td style="padding-bottom:7px; padding-left:5px">				       						       		
					       		<div class="mid-button float-right" >
							    	<button type="button" id="emailRequest" style="width:152px;">
							        	Email<br/>hotelvoucher
							        </button>
						        </div>		
						        <div id="emailRequestSpinner" class="float-right"></div>	
					        </td>
					    </tr>
						<tr>
					    	<td>
					    		<input type="text" name="vouchercode" 
                                value="<?php echo $this->voucher_groups->code;///echo $this->voucher->code;?>" 
                                readonly="readonly" style="width:160px;" />
								<input type="hidden" name="voucher_id" value="<?php echo JRequest::getVar('voucher_id');///echo $this->voucher->id?>" />
					    	</td>
					        <td style="padding-left:5px">				        	
					        	<div class="mid-button float-right" >
					        		<button type="button" id="printRequest" style="width:152px;">
										Print<br/>Groupvoucher
									</button>
						        </div>	
						        <div id="printRequestSpinner" class="float-right"></div>				        	
		        			</td>
						</tr>
						<tr>
							<td></td>
							<td style="padding-left:5px">
								<div class="mid-button float-right" >
									<button type="button" id="printRequest2" style="width:152px;">
										Individual<br/>vouchers
									</button>
								</div>
								<div id="printRequestSpinner2" class="float-right"></div>
							</td>
						</tr>
					</table>
					
					<?php if( !$allowGroupTransport ) : ?>
						<button type="button" id="closeGroupVoucherForm" class="btn orange sm" style="margin-top:10px;">Close</button>
					<?php endif;?>
					
				</div>
				
				<input type="hidden" name="printtype" value="" />
				<input type="hidden" name="separatevoucher" value="0" />
				<input type="hidden" name="option" value="com_sfs" />
				<input type="hidden" name="format" value="raw" />
				<input type="hidden" name="task" value="match.processPrintVoucher" />
				<?php echo JHtml::_('form.token'); ?>				
			</form>
		</div>

			<?php 
			if( $allowGroupTransport ) :			
				echo $this->loadTemplate('busbooking');							
			?>		
				<div class="floatbox clearfix" style="clear:both;margin-top:15px; margin-bottom:15px; margin-right:50px;">
					<button type="button" id="closeGroupVoucherForm" class="btn orange sm" style="margin-top:5px;">Close</button>
				</div>
			<?php endif;?>
	
	
</div>



<?php 
//echo $this->loadTemplate('selectrooms');
echo $this->loadTemplate('insertnames');
?>

