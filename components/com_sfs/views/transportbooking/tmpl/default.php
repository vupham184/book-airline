<?php
defined('_JEXEC') or die;?>
<?php 
if($this->transportCompany): 
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	// The elements used.
	var transportBookingForm = document.id('transportBookingForm');
	// Validation.
	var transportBookingFormValidator = new Form.Validator(transportBookingForm);

	$('requested_time').addEvent('change',function(){	     
	     if( $('requested_time').checked ) {
		    $('requested_time_text').setStyle('display','inline-block');
		    $('request_inputs').setStyle('display','none');
	     } else {
	    	$('requested_time_text').setStyle('display','none');
	    	$('request_inputs').setStyle('display','inline-block');	    	 
	     }     
	});
	
	$('departure').addEvent('change',function(){	     
		var departure = $('departure').getSelected().get('value').toString();   
		elementRuleArray = departure.split(',');
		if(elementRuleArray[0]=='a'){
			$('hotelaccommodation').setStyle('display','block');
			$('terminalaccommodation').setStyle('display','none');
		}
		if(elementRuleArray[0]=='h'){
			$('hotelaccommodation').setStyle('display','none');
			$('terminalaccommodation').setStyle('display','block');
		}
	});	

});
</script>
<?php endif;?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Group Transportation</h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 main">
	<div class="sfs-main-wrapper-none">
	<div class="sfs-orange-wrapper">
	<div class="sfs-white-wrapper floatbox">
		
		<?php if($this->transportCompany): ?>
		<form action="<?php echo JRoute::_('index.php')?>" method="post" name="transportBookingForm" id="transportBookingForm">
			
			<div class="sfs-row largemarginbottom">
				<div class="sfs-column-left2">Need transportation for</div>
				<input type="text" name="total_passengers" id="total_passengers" value="" style="width:40px" class="inputbox-gray required validate-integer" /> passengers
			</div>
			
			<div class="sfs-row largemarginbottom">
				<div class="sfs-column-left2" >Flight number of flight which caused the delay</div>
				<input type="text" name="flight_number" id="flight_number" value="" class="inputbox-gray required" />
			</div>
			
			<div class="sfs-row largemarginbottom">
				<div class="float-left">				
					<div class="sfs-column-left2">Bus needed for departure at</div>
					<select id="departure" name="departure" class="inputbox-gray required validate-custom-required emptyValue:0" >
						<option value="0">Select one</option>
						<optgroup label="Airport Terminals">
							<?php
							if(count($this->terminals)):
							foreach ($this->terminals as $terminal): 
							?>
							<option value="a,<?php echo $terminal->id?>"><?php echo $terminal->name?></option>
							<?php
							endforeach;
							endif;
							?>
						</optgroup>
						<optgroup label="Hotels">
						<?php
						if(count($this->hotels)):
						foreach ($this->hotels as $hotel): 
							$selected = '';		
							$testname = $hotel->name;
							$testname = JString::strtolower($testname);
							$testPos  = JString::strpos($testname, 'test');
							if( is_int($testPos) ){
								continue;
							}												
						?>
							<option value="h,<?php echo $hotel->id?>"<?php echo $selected?>><?php echo $hotel->name?></option>
						<?php
						endforeach;
						endif;
						?>
						</optgroup>
					</select>
				</div> 
				<div class="float-left verybigpaddingleft">
					<div class="sfs-column-left2">Requested departure time:</div>
					<input type="checkbox" id="requested_time" name="requested_time" value="1" checked="checked" /> <span id="requested_time_text">as soon as possible</span>
					<span id="request_inputs" style="display:none;">
						<?php
						echo SfsHelperDate::getSearchDate('start','style="width:100px;"','departure_date','d-M-y'); 
						?>
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
			</div>
			
			<div class="sfs-row largemarginbottom" id="hotelaccommodation">
				<div class="sfs-column-left2">To Hotel</div>
				<select name="hotel_id" class="inputbox-gray required validate-custom-required emptyValue:0" >					
					<?php
					if(count($this->hotels)):
					foreach ($this->hotels as $hotel): 
						$selected = '';		
						$testname = $hotel->name;
						$testname = JString::strtolower($testname);
						$testPos  = JString::strpos($testname, 'test');
						if( is_int($testPos) ){
							continue;
						}											
					?>
						<option value="<?php echo $hotel->id?>"<?php echo $selected?>><?php echo $hotel->name?></option>
					<?php
					endforeach;
					endif;
					?>
				</select>
			</div>
			
			<div class="sfs-row largemarginbottom" id="terminalaccommodation" style="display:none">
				<div class="sfs-column-left2">To Airport</div>
				<select name="terminal_id" class="inputbox-gray required validate-custom-required emptyValue:0" >					
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
				<div class="sfs-column-left2" >Comments for the bus company:</div>
				<textarea name="comment" class="inputbox-gray group-booking-comment"></textarea>
			</div>
			
			<div class="sfs-row largemarginbottom">
				<div class="sfs-column-left2" >Bus company contact details for special requests:</div> Phone: <?php echo $this->transportCompany->telephone;?> 
			</div>
			
			<div class="sfs-row largemarginbottom">
				<div class="sfs-column-left2" >Bus transfer will be operated by:</div> <?php echo $this->transportCompany->name;?> 
			</div>
			
			<div class="sfs-row largemarginbottom">
				<div class="mid-button" style="margin-right: 10px;float:right;">
					<button type="submit" style="text-indent: 22px;">Book Bus Transportation</button>
				</div>
			</div>
			
			<input type="hidden" name="transport_company_id" value="<?php echo $this->transportCompany->id;?>" />
			
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="transportbooking.booking" />        
	  	    <?php echo JHtml::_('form.token'); ?>
	  	    
		</form>
		<?php else : ?>
		It has no bus company available for your account.
		<?php endif;?>			
				
	</div>
	</div>
	</div>		
</div>