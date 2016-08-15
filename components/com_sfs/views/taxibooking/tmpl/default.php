<?php
defined('_JEXEC') or die;?>
<?php if($this->taxiCompanies): ?>
<script type="text/javascript">
window.addEvent('domready', function(){
	var transportBookingForm = document.id('taxiBookingForm');
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

	$$('.transertype').addEvent('change',function(){	     
	     if( this.checked ) {
		    ttvalue = this.get('value');
		    if( ttvalue == 1 ) {
			    $('commentWrapper').setStyle('display','block');
			    $('returnCommentWrapper').setStyle('display','none');
		    } else if( ttvalue == 2 ) {
		    	$('commentWrapper').setStyle('display','none');
			    $('returnCommentWrapper').setStyle('display','block');
		    } else {
		    	$('commentWrapper').setStyle('display','block');
		    	$('returnCommentWrapper').setStyle('display','block');
		    }
	     }    
	});
	
	$('outgoingButton').addEvent('click',function(){
		if(transportBookingFormValidator.validate()){
			transportBookingForm.submit();
		}	     				         
	});
});
</script>
<?php endif;?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Taxi Transportation</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">
	<div class="sfs-main-wrapper-none">
	<div class="sfs-orange-wrapper">
	<div class="sfs-white-wrapper floatbox">
		
		<?php if($this->taxiCompanies): ?>
		<form action="<?php echo JRoute::_('index.php')?>" method="post" name="taxiBookingForm" id="taxiBookingForm" class="sfs-form form-vertical">
			
			<div class="form-group clearfix">
				<div class="sfs-column-left2">Taxi company:</div>
				<?php if( count($this->taxiCompanies) > 1 ):?>
					<select name="taxi_id">
						<?php foreach ($this->taxiCompanies as $taxiCompany):
							$selected = '';
							if($this->taxiReservation && $this->taxiReservation->taxi_id==$taxiCompany->id)
							{
								$selected = ' selected="selected"';	
							}
						?>
						<option value="<?php echo $taxiCompany->id?>"<?php echo $selected?>><?php echo $taxiCompany->name;?></option>
						<?php endforeach;?>
					</select>
				<?php else: ?>
					<?php foreach ($this->taxiCompanies as $taxiCompany):?>
						<input type="hidden" name="taxi_id" value="<?php echo $taxiCompany->id?>" />
						<?php 
						echo $taxiCompany->name;
						break;
						?>
					<?php endforeach;?>
				<?php endif;?>
			</div>
			
			<div class="form-group clearfix">
				<label>Flight number of flight which caused the delay</label>
				<input type="text" name="flight_number" id="flight_number" value="<?php echo !empty($this->taxiReservation) ? $this->taxiReservation->flight_number : '';?>" class="inputbox-gray required" />
			</div>
			
			<div class="form-group clearfix">
				<div class="pull-left">				
					<label>Book a taxi from</label>
					<select name="departure" class="inputbox-gray required validate-custom-required emptyValue:0" >
						<option value="0">Select one</option>
						<?php
						if(count($this->terminals)):
						foreach ($this->terminals as $terminal): 
							$selected = '';							
							if( !empty($this->taxiReservation) && $this->taxiReservation->departure == $terminal->id )
							{
								$selected = ' selected="selected"';
							}
						?>
						<option value="<?php echo $terminal->id?>"<?php echo $selected?>><?php echo $terminal->name?></option>
						<?php
						endforeach;
						endif;
						?>
					</select>
				</div> 

				<div class="pull-left verybigpaddingleft">
					<label>Requested departure time:</label>
					<div class="checkbox">
						<?php
						$checked = 'checked="checked"'; 
						if($this->taxiReservation && $this->taxiReservation->requested_time != '0' )
						{
							$checked = '';
						}  
						?>
						<input type="checkbox" id="requested_time" name="requested_time" value="1" <?php echo $checked;?> /> 
						<span id="requested_time_text" <?php echo strlen($checked)==0?'style="display:none;"':''?>>as soon as possible</span>
						<span id="request_inputs" <?php echo strlen($checked)!=0?'style="display:none;"':''?>>
							<select name="requested_time_h" id="requested_time_h" style="width:50px;">
								<?php
								$currentH = SfsHelperDate::getDate('now','H');
								$currentM = '00';
								if( $this->taxiReservation && $this->taxiReservation->requested_time!='0' )
								{
									$rt = explode(':', $this->taxiReservation->requested_time);
									$currentH = $rt[0];
									$currentM = $rt[1];
								}
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
								<option <?php echo $currentM == '00'?'selected="selected"':''?> value="00">00</option>
								<option <?php echo $currentM == '15'?'selected="selected"':''?> value="15">15</option>
								<option <?php echo $currentM == '30'?'selected="selected"':''?> value="30">30</option>
								<option <?php echo $currentM == '45'?'selected="selected"':''?> value="45">45</option>
							</select> Hours
						</span>
					</div>
				</div>
			</div>
			
			<div class="form-group clearfix">
			    <label>To accommodation</label>
				<select name="hotel_id" class="inputbox-gray required validate-custom-required emptyValue:0" >
					<option value="0">Select one</option>
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
						if( !empty($this->taxiReservation) && $this->taxiReservation->hotel_id == $hotel->id )
						{
							$selected = ' selected="selected"';
						}
					?>
						<option value="<?php echo $hotel->id?>"<?php echo $selected?>><?php echo $hotel->name?></option>
					<?php
					endforeach;
					endif;
					?>
				</select>
			</div>
			
			<div class="form-group clearfix">
				<?php if(empty($this->taxiReservation)) : ?>
				<div style="float:left; display:inline-block;padding-right:50px;">
					<input type="radio" name="transfer_type" class="transertype" checked="checked" id="transfer_type1" value="1"> <label for="transfer_type1">Taxi transfer from airport to hotel</label>
				</div>
				<div style="float:left; display:inline-block;padding-right:50px;">
					<input type="radio" name="transfer_type" class="transertype" id="transfer_type2" value="2"> <label for="transfer_type2">Taxi transfer from hotel to airport</label>
				</div>
				<div style="float:left; display:inline-block;">
					<input type="radio" name="transfer_type" class="transertype" id="transfer_type3" value="3"> <label for="transfer_type3">Both taxi transfers</label>
				</div>
				<?php else : ?>
				<div style="float:left; display:inline-block;padding-right:50px;">
					<input type="radio" <?php echo !empty($this->taxiReservation) && $this->taxiReservation->transfer_type==1 ? 'checked="checked"':'';?> name="transfer_type" class="transertype" id="transfer_type1" value="1"> <label for="transfer_type1">Taxi transfer from airport to hotel</label>
				</div>
				<div style="float:left; display:inline-block;padding-right:50px;">
					<input type="radio" <?php echo !empty($this->taxiReservation) && $this->taxiReservation->transfer_type==2 ? 'checked="checked"':'';?> name="transfer_type" class="transertype" id="transfer_type2" value="2"> <label for="transfer_type2">Taxi transfer from hotel to airport</label>
				</div>
				<div style="float:left; display:inline-block;">
					<input type="radio" <?php echo !empty($this->taxiReservation) && $this->taxiReservation->transfer_type==3 ? 'checked="checked"':'';?> name="transfer_type" class="transertype" id="transfer_type3" value="3"> <label for="transfer_type3">Both taxi transfers</label>
				</div>
				<?php endif;?>
			</div>
			
			<div class="form-group clearfix">
				<table class="taxibookingpassengertable">
					<thead>
						<tr>
							<th></th>
							<th>First name</th>
							<th>Last name</th>							
							<th style="padding-left:60px;">Mobile phone number (at least 1 required per group)</th>
						</tr>
					</thead>
					<tbody>
						<?php for( $i=1; $i<=3; $i++ ) : 
						$passengers = array();
						if($this->taxiReservation->passengers)
						{
							$passengers = $this->taxiReservation->passengers;	
						}
						?>
						<tr>
							<td>Passenger <?php echo $i;?></td>
							<td>
								<input type="text" name="passengers[<?php echo $i;?>][firstname]" value="<?php echo isset($passengers[$i-1]) ? $passengers[$i-1]->first_name:'';?>" class="inputbox-gray" style="width: 120px;" />
							</td>
							<td>
								<input type="text" name="passengers[<?php echo $i;?>][lastname]" value="<?php echo isset($passengers[$i-1]) ? $passengers[$i-1]->last_name:'';?>" class="inputbox-gray" style="width: 150px;"  />
							</td>
							<td style="padding-left:60px;">
								<input type="text" name="passengers[<?php echo $i;?>][phonenumber]" value="<?php echo isset($passengers[$i-1]) ? $passengers[$i-1]->phone_number:'';?>" class="inputbox-gray" style="width: 150px;"  />
							</td>
						</tr>
						<?php endfor;?>
					</tbody>
				</table>
			</div>
			
			<div class="form-group" id="commentWrapper">
				<div class="sfs-column-left2" style="width:160px; padding: 20px 0 0 10px;">Add comment on the outgoing taxi voucher to accommodation:</div>
				
				<div class="sfs-column-left2" style="width:340px;">
					<textarea name="comment" class="inputbox-gray" style="width: 96%; height:150px;padding:5px;"></textarea>
				</div>				
			</div>
			
			<div class="form-group" id="returnCommentWrapper" style="display:none;">
				<div class="sfs-column-left2" style="width:160px;padding: 20px 0 0 10px;">Add comment on the return trip taxi voucher:</div>
				
				<div class="sfs-column-left2" style="width:340px;">
					<textarea name="return_comment" class="inputbox-gray" style="width: 96%; height:150px; padding:5px;"></textarea>
				</div>				
			</div>
			<div class="clear"></div>
			<div class="form-group clearfix">
				<button type="button" id="outgoingButton" class="btn orange sm pull-right" >Book Taxi Transportation</button>
			</div>
		
			
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
			<input type="hidden" name="option" value="com_sfs" />
			<input type="hidden" name="task" value="taxibooking.booking" />			
	  	    <?php echo JHtml::_('form.token'); ?>
	  	    
		</form>
		<?php else : ?>
		It has no taxi company available for your account.
		<?php endif;?>			
				
	</div>
	</div>
	</div>		
</div>