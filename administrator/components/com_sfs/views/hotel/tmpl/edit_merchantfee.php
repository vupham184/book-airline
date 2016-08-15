<?php
// No direct access.
defined('_JEXEC') or die;
$merchantFee = $this->merchantFee;
$params = JComponentHelper::getParams('com_sfs');
if( empty($merchantFee) )
{	
	$merchantFee->monthly_fee 			= $params->get('merchant_fixed_fee');
	$merchantFee->merchant_fee 			= $params->get('room_merchant_fee');
	$merchantFee->merchant_fee_type 	= $params->get('merchant_fee_type');
	$merchantFee->breakfast_merchant_fee 	= $params->get('breakfast_merchant_fee');
	$merchantFee->lunch_merchant_fee 		= $params->get('lunch_merchant_fee');
	$merchantFee->dinner_merchant_fee 		= $params->get('dinner_merchant_fee');
}
$approveNote='';
if( empty($this->adminSetting) ) {
	$this->adminSetting->merchant_register_note = $params->get('merchant_register_note');
	$approveNote = 'Warning! Save all the settings first before approve hotel';
}
?>
<?php if( isset($this->createdUser) && (int)$this->createdUser->block == 1 && $this->createdUser->activation ) : ?>
	<div style="overflow:hidden;width:100%">
		<button type="button" onclick="Joomla.submitbutton('hotel.activate')" style="float:right;text-shadow: none;background:#009966; border-radius:0; border: none; color:#fff; font-size:14px; text-transform:uppercase; padding:5px 10px;">
			Approve Hotel
		</button>
		<div style="color:red;">
			<?php
			if( $approveNote ) {
				echo $approveNote;	
			} 
			?>
		</div>
	</div>
<?php endif; ?>
<legend>Merchant Fee</legend>
<ul class="adminformlist">	
	<li>
		<label>Merchant Enable</label>
		<select name="merchant_fixed_fee_enable">
			<option value="1"<?php echo (int)$this->adminSetting->merchant_fixed_fee_enable == 1 ? ' selected="selected"':'';?>>Enable</option>
			<option value="0"<?php echo (int)$this->adminSetting->merchant_fixed_fee_enable == 0 ? ' selected="selected"':'';?>>Disable</option>			
		</select> 									
	</li>	
	<li>
		<label>Personal note field for the hotel during registration:</label>		
		<input type="text" size="100" class="inputbox" value="<?php echo $this->adminSetting->merchant_register_note?>" name="merchant_register_note">			
	</li>
	<li>
		<label>Comment by the client during registration:</label>		
		<?php echo $merchantFee->comment;?>			
	</li>
	<li>
		<label for="">Fixed Fee:</label>
		<input type="text" size="10" class="inputbox" value="<?php echo !empty($merchantFee)? $merchantFee->monthly_fee : '';?>" id="monthly_fee" name="merchantfee[monthly_fee]"> <?php echo $this->taxes->currency_name;?>		
	</li>
	
	<li>
		<hr class="clr" />
	</li>
	
	<li>
		<label for="">Room Merchant Fee:</label>
		<input type="text" size="10" class="inputbox" value="<?php echo !empty($merchantFee)? $merchantFee->merchant_fee : '';?>" id="merchant_fee" name="merchantfee[merchant_fee]">
		<select class="inputbox required" name="merchantfee[merchant_fee_type]">
			<option value="1"<?php echo ( !empty($merchantFee) && $merchantFee->merchant_fee_type==1 ) ?' selected="selected"':'';?>>Percent of room price</option>
			<option value="2"<?php echo ( !empty($merchantFee) && $merchantFee->merchant_fee_type==2 ) ?' selected="selected"':'';?>>Fixed price per room</option>
		</select>
	</li>
						
	<li>
		<label for="">Breakfast Merchant Fee:</label>
		<input type="text" size="10" class="inputbox" value="<?php echo !empty($merchantFee)?$merchantFee->breakfast_merchant_fee:'';?>" id="breakfast_merchant_fee" name="merchantfee[breakfast_merchant_fee]">
	</li>
	
	<li>
		<label for="">Lunch Merchant Fee:</label>
		<input type="text" size="10" class="inputbox" value="<?php echo !empty($merchantFee)? $merchantFee->lunch_merchant_fee : '';?>" id="lunch_merchant_fee" name="merchantfee[lunch_merchant_fee]">
	</li>
	
	<li>
		<label for="">Dinner Merchant Fee:</label>
		<input type="text" size="10" class="inputbox" value="<?php echo !empty($merchantFee)? $merchantFee->dinner_merchant_fee : '';?>" id="dinner_merchant_fee" name="merchantfee[dinner_merchant_fee]">
	</li>
							
																																		
</ul>
<div class="clr"></div>	

