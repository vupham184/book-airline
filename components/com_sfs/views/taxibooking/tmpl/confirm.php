<?php
defined('_JEXEC') or die;
//index.php?option=com_sfs&view=taxi&layout=voucher&tmpl=component&is_return=0&taxi_id=5&taxi_voucher_id=11&transportation=1
?>
<?php if($this->taxiReservation):
$printUrl = JURI::base().'index.php?option=com_sfs&view=taxi&layout=voucher&tmpl=component&transportation=1';
$printUrl = $printUrl.'&taxi_id='.$this->taxiReservation->taxi_id;
$printUrl = $printUrl.'&taxi_voucher_id='.$this->taxiReservation->id;
$printTaxiUrl  = $printUrl.'&is_return=0&reprint=1';
$printTaxiUrl2 = $printUrl.'&is_return=1&reprint=1'; 
?>
<script type="text/javascript">
window.addEvent('domready', function(){	
	var transportBookingForm = document.id('taxiBookingForm');
	
	var printTaxiUrl = '<?php echo $printTaxiUrl;?>';
	var printTaxiUrl2 = '<?php echo $printTaxiUrl2;?>';

	var printVoucherFormRequest = new Form.Request(transportBookingForm, $('testre'),  {					
	    requestOptions: {
	    	useSpinner: false
	    },	
	    onComplete: function(responseText){		    	
	    },
	    resetForm : false				    
	});
	
	<?php if($this->taxiReservation->transfer_type == 1 || $this->taxiReservation->transfer_type == 3) : ?>
	$('outgoingButton').addEvent('click',function(e){	
		e.stop();	
			
		transportBookingForm.printtype.value = 'outgoing';
		printVoucherFormRequest.setTarget($('testre'));
		printVoucherFormRequest.send();	
		
		taxicomment = document.getElementById("taxicomment").value;
		taxicomment = encodeURIComponent(taxicomment);
		
		printTaxiUrlPP = printTaxiUrl+'&taxicomment='+taxicomment;
		
		winpp = sfsPopupCenter(printTaxiUrlPP, 'PrintTaxiVoucher',845,690);			     				         
	});
	<?php endif;?>
	
	<?php if($this->taxiReservation->transfer_type == 2 || $this->taxiReservation->transfer_type == 3) : ?>
	$('returnTripButton').addEvent('click',function(e){
		e.stop();			
		transportBookingForm.printtype.value = 'returntrip';
		printVoucherFormRequest.setTarget($('testre'));
		printVoucherFormRequest.send();		

		taxicomment = document.getElementById("taxireturncomment").value;
		taxicomment = encodeURIComponent(taxicomment);
		
		printTaxiUrlPP = printTaxiUrl2+'&taxireturncomment='+taxicomment;
		
		winpp = sfsPopupCenter(printTaxiUrlPP, 'PrintTaxiVoucher',845,690);	     				        
	});
	<?php endif;?>

	$('cancelButton').addEvent('click',function(){
		transportBookingForm.task.value='taxibooking.cancelVoucher';
		transportBookingForm.format.value='html';
		transportBookingForm.submit();			     				        
	});
	
});
</script>
<div id="testre"></div>
<div class="heading-block clearfix">
	<div class="heading-block-wrap">
		<h3>Taxi Transportation</h3>	
	</div>
</div>
<form action="<?php echo JRoute::_('index.php')?>" method="post" name="taxiBookingForm" id="taxiBookingForm">
	<div class="main">		
			<h4>Confirmation</h4>
			<p>You have booked taxi transportation for <?php echo count($this->taxiReservation->passengers); ?> persons</p>				
			<?php
			$terminalName='';
			foreach ($this->terminals  as $terminal ) {
				if( (int)$terminal->id == (int)$this->taxiReservation->departure )
				{
					$terminalName = $terminal->name; 
					break;
				}
			} 
			$requestedTime = 'as soon as possible';
			if( $this->taxiReservation->requested_time != '0' ) {
				$requestedTime = 'at '.$this->taxiReservation->requested_time.' hours';
			} 
			?>		
			<p>Pick up at <?php echo $terminalName?> <?php echo $requestedTime?></p>
			
			<?php if($this->taxiReservation->hotel):
				$hotel = & $this->taxiReservation->hotel;
			?>
			<p>
				<strong>Your accommodation</strong>:<br />
				<?php echo $hotel->name.'<br />'.$hotel->address.'<br />'.$hotel->zipcode.', '.$hotel->city;?><br /><?php echo 'Tel: '.$hotel->telephone?>
			</p>
			<?php endif;?>
			
			<p>
			<strong>Passenger Names</strong>:<br/>
			<?php foreach ($this->taxiReservation->passengers as $p) : ?>
				<?php
				echo $p->first_name.' '.$p->last_name;
				if($p->phone_number)
				{
					echo ', Phone: '.$p->phone_number;
				}
				echo '<br />';
				?>
			<?php endforeach;?>			
			</p>
					
			<p>
				<strong>Taxi Contact details</strong>:<br/>
				Taxi Company: <?php echo $this->taxiReservation->taxi_name;?><br />
				Phone: <?php echo $this->taxiReservation->taxi_telephone;?>
			</p>
			
			<p>Your reference number is <strong><?php echo $this->taxiReservation->reference_number;?></strong></p>
						
			<?php if($this->taxiReservation->transfer_type == 1 || $this->taxiReservation->transfer_type == 3) : ?>
			<div class="form-group clearfix">
				<div class="sfs-column-left2" style="width:160px; padding: 20px 0 0 10px;">Add comment on the outgoing taxi voucher to accommodation:</div>
				
				<div class="sfs-column-left2" style="width:340px;">
					<textarea name="comment" id="taxicomment" class="inputbox-gray" style="width: 96%; height:150px;padding:5px;"><?php echo $this->taxiReservation->comment?></textarea>
				</div>
				<div class="sfs-column-left2" style="">					
						<button type="button" id="outgoingButton" class="btn orange xl">Print voucher</button>					
				</div>
			</div>
			<?php endif;?>
			
			<?php if($this->taxiReservation->transfer_type == 2 || $this->taxiReservation->transfer_type == 3) : ?>
			<div class="sfs-row largemarginbottom" id="returnCommentWrapper">
				<div class="sfs-column-left2" style="width:160px;padding: 20px 0 0 10px;">Add comment on the return trip taxi voucher:</div>
				
				<div class="sfs-column-left2" style="width:340px;">					
					<?php if($this->taxiReservation->return_comment):?>
					<textarea name="return_comment" id="taxireturncomment" class="inputbox-gray" style="width: 96%; height:150px; padding:5px;"><?php echo $this->taxiReservation->return_comment?></textarea>
					<?php else:?>
					<textarea name="return_comment" id="taxireturncomment" class="inputbox-gray" style="width: 96%; height:150px; padding:5px;">Please contact <?php echo $this->taxiReservation->taxi_name;?> taxi to schedule  your return transfer, per phone on Tel. <?php echo trim($this->taxiReservation->taxi_telephone);?> Or if possible, through the front desk of your accommodation.</textarea>
					<?php endif;?>
				</div>
				<div class="sfs-column-left2" style="">
					<div class="mid-button" style="float:right; margin-top:60px;">
						<button type="button" id="returnTripButton" style="text-indent: 22px;">Print return voucher</button>
					</div>
				</div>
			</div>
			<?php endif;?>
			
			<div class="form-group">				
				<button type="button" id="cancelButton" class="btn orange sm">Cancel and add other Taxi Transportation</button>									
			</div>
	</div>

	<input type="hidden" name="taxi_reservation_id" value="<?php echo $this->taxiReservation->id;?>" />
	<input type="hidden" name="task" value="taxibooking.printVoucher" />	
	<input type="hidden" name="printtype" value="" />	
	<input type="hidden" name="format" value="raw" />
	<input type="hidden" name="option" value="com_sfs" />	
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
  	<?php echo JHtml::_('form.token'); ?>
  	    
</form>
<?php endif;?>

