<?php
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$airline = SFactory::getAirline();
$url_code = SfsUtil::getRandomString(5);


if( ! isset($this->reservation) )
{
	return;
}

$closeUrl = JURI::base().'index.php?option=com_sfs&view=search&Itemid=119&rooms=1&date_start='.$this->reservation->blockdate.'&date_end=';
$closeUrl .= SfsHelperDate::getNextDate('Y-m-d', $this->reservation->blockdate);

$printUrl  = $closeUrl.'&reservation_id='.$this->reservation->id.'&print=1';

$wsPrintUrl = $closeUrl.'&reservation_id='. $this->reservation->id . '&singlevoucherpreview=1';

/* @var $wsRoomType Ws_Do_Search_RoomTypeResult */
/* @var $wsPreBook Ws_Do_PreBook_Response */
$wsPreBook = $this->wsPreBook;
?>
<style type="text/css" rel='stylesheet'>
body.contentpane,iframe{
	border:none !important;
	padding: 0 !important;
	margin: 0 !important;	
}
body.contentpane{
	padding: 10px !important;
	background:#82adf1;
	background:#82adf1;
}
.sfs-white-wrapper{
	background:#FFFFFF;
	padding:20px;
	overflow:hidden;
}
#wsRequestSpinner{
	width: 16px;
	height: 16px;
	display: block;
	margin-right: 5px;
}
.singlevoucher-passenger-title{
	width: 60px !important;
}
.ws-booking-loading{
	text-align: center;
	font-weight: bold;
}
.ws-booking-spinner{
	margin: 0 auto;
	display: block;
}
#sbox-window {
    left: 0px !important;
    top:0px !important;
}
input.input-error{
	border-color: #f00;
	background-color: #fdd;
}
</style>


<script type="text/javascript">	
<!--
jQuery(function ($) {
    
	iframeModalAutoSize();
	
});
window.addEvent('domready', function() {

	var printLink = '<?php echo $printUrl;?>';		
	var closeLink = "<?php echo $closeUrl?>";
	var wsPrintLink = "<?php echo $wsPrintUrl?>";
	
	var printVoucherForm  = document.id('voucherPrintForm');

	var validateWSPassengers = function(){
		var ok = true;
		jQuery('input.passenger-firstname,input.passenger-lastname').each(function(){
			var $el = jQuery(this),
				val = jQuery.trim($el.val());
			if(val == '') {
				ok = false;
				$el.addClass('input-error');
			} else {
				$el.removeClass('input-error');
			}
		});
		return ok;
	};

	var validateFlightCode = function(){
		var ok = true;
		var flight_code = $('flight_code').get('value');
		if(!flight_code) {
			ok = false;
		} 
		jQuery('#flight_code').toggleClass('input-error', !flight_code);
		
		var iata_stranged_code = $('iata_stranged_code').get('value');
		if(!iata_stranged_code) {
			ok = false;
		}
		jQuery('#iata_stranged_code').toggleClass('input-error', !iata_stranged_code);
		return ok;
	};
	
	var printVoucherFormRequest = new Form.Request(printVoucherForm, $('testre'),  {					
	    requestOptions: {
	    	useSpinner: false,
	    	evalScripts: true
	    },
	    onComplete: function(responseText){

	    	jQuery('#ws-booking-loading').hide();
			jQuery('#ws-booking-buttons').show();

	    	// show error if has
		    if(jQuery(responseText).find('.uk-alert-danger').length) {
				return;
			}

		    if(printVoucherForm.printtype.value =='ws') {
				window.top.location.href = wsPrintLink;
			}
	    },
	    resetForm : false
	});
   	
	$('wsRequest') && $('wsRequest').addEvent('click', function(e){
		e.stop();

		var ok = validateFlightCode();

		ok = validateWSPassengers() && ok;
		
		if(ok) {
			jQuery('#ws-booking-loading').show();
			jQuery('#ws-booking-buttons').hide();
			printVoucherForm.printtype.value = 'ws';
			printVoucherFormRequest.setTarget($('testre'));
			printVoucherFormRequest.send();

			iframeModalAutoSize();
		}
	});	
	
	$('closeVoucherPrintForm').addEvent('click', function(e){
		e.stop();		
		window.parent.location.href="<?php echo $closeUrl?>";	
		window.parent.SqueezeBox.close();
	});
	
});				
-->	
</script>
<?php
$user = &JFactory::getUser();
$enable_extra_data_on_voucher = 0;
$extra_data_on_voucher_title = '';
if( SFSAccess::isAirline($user) ) {
	$airline = SFactory::getAirline();
	if(isset($airline->params["enable_extra_data_on_voucher"]))
	{
		$enable_extra_data_on_voucher = (int)$airline->params["enable_extra_data_on_voucher"];
		$extra_data_on_voucher_title = $airline->params["extra_data_on_voucher_title"];
	}
}
?>


<div id="sfs-wrapper" class="match">
<div id="sfs-voucher-print-form">
<form id="voucherPrintForm" name="voucherPrintForm" action="<?php echo JRoute::_('index.php')?>" method="post">	
	<?php if ( $enable_extra_data_on_voucher == 1 ) :?>
    <div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="min-height:50px;">		
		<div class="sfs-row">
			<div class="sfs-column-left" style="width:100%;">
				<?php echo $extra_data_on_voucher_title;?>
			</div>
		</div>	
	</div>
    <?php endif;?>
	<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="min-height:50px;">		
		<div class="sfs-row">
			<div class="sfs-column-left" style="width:140px;">
				Flight number *
			</div>
			<input type="text" name="flight_code" id="flight_code" value="" style="width:130px;" />
		</div>			
		<div class="sfs-row">
			<div class="sfs-column-left" style="width:140px;">
				IATA stranded code *
			</div>
			<input type="text" name="iata_stranged_code" id="iata_stranged_code" value="" style="width:130px;" />
            <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=article&id='.$this->params->get('article_delay_code').'&tmpl=component&Itemid='.JRequest::getInt('Itemid'));?>" rel="{handler: 'iframe', size: {x: 350, y: 270}}" class="modal button" style="float:none;text-decoration:none;"><?php echo SfsHelper::htmlTooltip('flight_delay_code', 'help-icon', 'airline');?></a>
		</div>
	</div>
	
	<?php
	if( isset($airline->params['show_vouchercomment']) && (int)$airline->params['show_vouchercomment'] == 1 ) :  
	?>
	<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom">			
		<?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?>:<br />
        <textarea name="comment" id="vouchercomment" style="width:98%;height:90px;border: 1px solid #909bb1"
                  onKeyDown="textCounter(this,500);"
                  onKeyUp="textCounter(this,'comment_length' ,500)"></textarea>
        <p>
            Maximum characters: 500 - You have
            <input style="color:red;font-size:12pt;font-style:italic;width:40px;border: 0px" readonly type="text" id='comment_length' name="comment_length" size="3" maxlength="3" value="500">
            characters left
        </p>
        <script>
            function textCounter(field,cnt, maxlimit)
            {
                var cntfield = document.getElementById(cnt)
                if (field.value.length > maxlimit)
                    field.value = field.value.substring(0, maxlimit);
                else
                    cntfield.value = maxlimit - field.value.length;
            }
        </script>
	</div>
	<?php 
	endif;?>
	
	<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom">
		<div>Insert names *</div>
		<div class="fs-11" style="padding-top: 5px; line-height:13px;">When you insert names it will be easier for you to trace the passengers at a later stage.</div>
		
		<div class="midmargintop">
			<?php foreach($this->wsRoomTypes as $wsRoomType) :

				$adult = (int)$wsRoomType->NumAdultsPerRoom;
				$children = (int)$wsRoomType->NumChildrenPerRoom;
			?>
				<?php for($i = 0; $i< $wsRoomType->NumberOfRooms; $i++) : ?>
				<h3><?php echo $wsRoomType->Name?> - <?php echo ($i + 1)?> (<?php echo $adult + $children;?> person)</h3>
				<table border="0" width="100%" class="match-passengers">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Title</th>
                            <th>First name *</th>
                            <th>Last name *</th>
						</tr>
					</thead>
					<tbody>
					<?php
						for ($j=0;$j< ($adult+$children); $j++):
							if($j < $adult):
					?>
								<tr>
									<td>Adult </td>
									<td>
										<select name="passengers[<?php echo $i?>][<?php echo $j?>][title]" id="title<?php echo $i+1?><?php echo $j+1?>" class="singlevoucher-passenger-title passenger-title">
											<option value="Mr">Mr</option>
											<option value="Mrs">Mrs</option>
											<option value="Ms">Ms</option>
										</select>
									</td>
									<td><input type="text" name="passengers[<?php echo $i?>][<?php echo $j?>][firstname]" id="firstname<?php echo $i+1;?><?php echo $j+1;?>" class="passenger-input match-passenger-firstname passenger-firstname"></td>
									<td><input type="text" name="passengers[<?php echo $i?>][<?php echo $j?>][lastname]" id="lastname<?php echo $i+1;?><?php echo $j+1;?>" class="passenger-input match-passenger-lastname passenger-lastname"></td>
								</tr>
							<?php else:?>
								<tr>
									<td>Child </td>
									<td></td>
									<td><input type="text" name="passengers[<?php echo $i?>][<?php echo $j?>][firstname]" id="firstname<?php echo $i+1;?><?php echo $j+1;?>" class="passenger-input match-passenger-firstname passenger-firstname"></td>
									<td><input type="text" name="passengers[<?php echo $i?>][<?php echo $j?>][lastname]" id="lastname<?php echo $i+1;?><?php echo $j+1;?>" class="passenger-input match-passenger-lastname passenger-lastname"></td>
									<input type="text" name="passengers[<?php echo $i?>][<?php echo $j?>][type]" value="1">
								</tr>
							<?php endif;?>
						<?php endfor;?>
						<tr>
							<td>Phone number</td>
							<td colspan="3">
								country ext <input type="text" name="passenger_mobile_ext[<?php echo $i?>]" class="match-passenger-mobile-ext" style="width: 40px">
								<input type="text" name="passenger_mobile[<?php echo $i?>]" class="match-passenger-mobile">
							</td>
						</tr>
					</tbody>
				</table>
				<?php endfor;?>
			<?php endforeach;?>
			<input type="hidden" name="stranded_seats" value="<?php echo $adult+$children?>" />
		</div>
	</div>	
	
	<?php if( isset($airline->params['enable_return_flight']) && (int)$airline->params['enable_return_flight'] == 1 ) : ?>		
	<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="min-height:50px;">
		<div class="sfs-row">
			<div class="sfs-column-left" style="width:120px;">
				Return flight number
			</div>
			<input type="text" name="returnflight" value="" style="width:130px;" />
		</div>			
		<div class="sfs-row">
			<div class="sfs-column-left" style="width:120px;">
				Return flight date
			</div>
			<?php
				$endDateList 	= SfsHelperDate::getSearchDate('end','class="inputbox"','returnflightdate');
				echo $endDateList; 
			?>
		</div>
	</div>
	<?php endif; ?>
	
	<div id="testre"></div>
	
	<div class="sfs-white-wrapper sfs-voucher-print-box floatbox">				
		<div id="emailFormResult"></div>
		
		<div id="ws-booking-loading" class="ws-booking-loading" style="display: none">
			<p>Just a moment, we are making your reservation</p>
			<p>
				<span class="ws-booking-spinner ajax-Spinner48"></span>
			</p>
		</div>
		<table cellpadding="0" cellspacing="0" width="100%" id="ws-booking-buttons">
			<tr>
				<td><button type="button" id="closeVoucherPrintForm" class="small-button" style="margin-top:10px;">Close</button></td>
				<td align="right"><button type="button" id="wsRequest" class="small-button" style="margin-top:10px;">Book now</button></td>
			</tr>
		</table>
			
	</div>		
		
	<input type="hidden" name="reservation_id" value="<?php echo $this->reservation->id?>" />
	<input type="hidden" name="printtype" value="" />
	<input type="hidden" name="option" value="com_sfs" />
	<input type="hidden" name="format" value="raw" />
	<input type="hidden" name="task" value="match.processPrintWsVoucher" />
	<input type="hidden" name="url_code" value="<?php echo $url_code;?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

</div>
</div>    	   

<div id="voucherUpdateElement"></div> 	