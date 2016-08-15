<?php
defined('_JEXEC') or die;
$airline = SFactory::getAirline();
?>
<script type="text/javascript">
<!--
window.addEvent('domready', function() {
	
	var transportBookingForm = document.id('transportBookingForm'),
	formResult = document.id('bookTransportAjaxRepsonse');


	new Form.Validator(transportBookingForm);
	  
	new Form.Request(transportBookingForm, formResult, {
	    requestOptions: {
	    	'useSpinner': false 
	    },
	    resetForm:false,
	    onSend: function(){
	    	$('ajax-Spinner').addClass('ajax-Spinner');
	    },
	    onComplete: function(responseText, responseXML){		   
	    	$('ajax-Spinner').removeClass('ajax-Spinner');	    	 
	    }
	});
	
	$('requested_time').addEvent('change',function(){	     
	     if( $('requested_time').checked ) {
		    $('requested_time_text').setStyle('display','inline-block');
		    $('request_inputs').setStyle('display','none');
	     } else {
	    	$('requested_time_text').setStyle('display','none');
	    	$('request_inputs').setStyle('display','inline-block');	    	 
	     }     
	});
});
-->	
</script>
<div class="sfs-main-wrapper sfs-voucher-print-box sfs-print-voucher-column">

	<form action="<?php echo JRoute::_('index.php')?>" method="post" name="transportBookingForm" id="transportBookingForm">
		<div class="sfs-white-wrapper floatbox midmarginbottom" style="padding: 2px 15px;min-height:10px;">
			<div class="voucher-taxi-icon">
				Group Transport
			</div>	
		</div>
		
		<div class="sfs-white-wrapper floatbox">
			
			<div class="sfs-row largemarginbottom">
				Add comment for the Bus company:<br />
				<textarea id="buscomment" name="comment" style="width:98%;height:123px;"></textarea>
			</div>
		
			<div class="sfs-row largemarginbottom">				
				<div class="sfs-column-left">Bus needed for departure at</div>
				<select name="departure" class="inputbox-gray required validate-custom-required emptyValue:0" style="width:130px;float:right">
					<option value="0">Select one</option>
					<?php
					if(count($this->terminals)):
					foreach ($this->terminals as $terminal): 
					?>
					<option value="<?php echo $terminal->id?>"><?php echo $terminal->name?></option>
					<?php
					endforeach;
					endif;
					?>
				</select>
			</div> 
			<div class="sfs-row largemarginbottom">
				<div class="sfs-column-left2">Requested departure time:</div>
				<input type="checkbox" id="requested_time" name="requested_time" value="1" checked="checked" /> <span id="requested_time_text">as soon as possible</span>
				<span id="request_inputs" style="display:none;">
					<select name="requested_time_h" id="requested_time_h" style="width:50px;">
						<?php
						$currentH = SfsHelperDate::getDate('now','H');
						for($i=0;$i<=23;$i++) {
							if( $currentH == $i ) {
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}		
							echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>' ;
						}
						?>
					</select>
					<select name="requested_time_m" id="requested_time_m" style="width:50px;">
						<option value="00">00</option>
						<option value="15">15</option>
						<option value="30">30</option>
						<option value="45">45</option>
					</select>
				</span>
			</div>
			
		   <div class="sfs-row largemarginbottom">
				<div class="sfs-column-left2" >Bus company contact details for special requests:</div> Phone: <?php echo $this->transportCompany->telephone;?> 
			</div>
			
			<div class="sfs-row largemarginbottom">
				<div class="sfs-column-left2" >Bus transfer will be operated by:</div> <?php echo $this->transportCompany->name;?> 
			</div>
				
			<div id="bookTransportAjaxRepsonse"></div>
						
			<div style="overflow: hidden; margin: 10px 10px 10px 10px;">								
				<div class="mid-button float-right" >					
					<button type="submit" id="bookGroupTransport" style="text-indent:22px;">
			        	Book group transport
			        </button>
		        </div>
		        <div id="ajax-Spinner" class="float-right"></div>	
			</div>	
		</div>
		
		<input type="hidden" name="total_passengers" value="<?php echo $this->voucher->seats;?>" />
		<input type="hidden" name="flight_number" value="<?php echo $this->voucher->flight_code;?>" />
		<input type="hidden" name="vouchercode" value="<?php echo $this->voucher->code;?>" />
		<input type="hidden" name="transport_company_id" value="<?php echo $this->transportCompany->id;?>" />
		<input type="hidden" name="option" value="com_sfs" />
		<input type="hidden" name="format" value="raw" />
		<input type="hidden" name="task" value="match.bookBusTransport" />        
  	    <?php echo JHtml::_('form.token'); ?>
  	    
	</form>	

</div>