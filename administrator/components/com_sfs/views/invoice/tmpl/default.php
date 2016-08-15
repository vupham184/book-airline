<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

$date_start  = JRequest::getVar('date_start');
$date_end  = JRequest::getVar('date_end');

$hotelTax = $this->hotel->getTaxes();
$mealplanTax  = $this->hotel->getMealPlan();

$currency_name = $hotelTax->currency_symbol;

$this->tax = number_format($hotelTax->percent_total_taxes);

?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'invoice.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div class="width-100 fltlft">

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=invoice'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

	<fieldset>
	<table style="width: auto;">
		<tr>
			<td>Period:</td>
			<td><?php echo JHtml::_('calendar',$date_start,'date_start','date_start');?></td>
			<td style="padding-left: 10px;padding-right:10px;">until</td>
			<td><?php echo JHtml::_('calendar',$date_end,'date_end','date_end');?></td>
			<td style="padding-left: 10px;padding-right:10px;">
				<button onClick="Joomla.submitbutton('invoice.generate')" type="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none; font-size:12px;">Generate Invoice</button>
			</td>
		</tr>
	</table>
	</fieldset>



<?php if (count($this->reservations) ) : ?>

<table class="tableinvoice" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="bottom" class="header">
	<td style="padding-bottom:10px;" class="tdblue">Blockcode</td>
	<td class="tdblue">Status</td>
	<td class="tdblue"># Rooms</td>
	<td class="tdblue">Gross Price</td>
	<td class="tdblue">Net Price based on <?php echo $this->tax?>%</td>
	<td class="tdblue">Merchant Fee %</td>
	<td class="tdblue">Total Merchant Fee Room</td>

	<td class="tdgreen"># pax  BFST</td>
	<td class="tdgreen">Gross Price BFST</td>
	<td class="tdgreen">Net Price based on <?php echo $mealplanTax->bf_tax?>%</td>
	<td class="tdgreen">Merchant Fee %</td>
	<td class="tdgreen">Total Merchant Fee BFST</td>

	<td class="tdgreen"># pax Lunch</td>
	<td class="tdgreen">Gross Price Lunch</td>
	<td class="tdgreen">Net Price based on <?php echo $mealplanTax->lunch_tax?>%</td>
	<td class="tdgreen">Merchant Fee %</td>
	<td class="tdgreen">Total Merchant Fee Lunch</td>

	<td class="tdgreen"># pax Dinner</td>
	<td class="tdgreen">Gross Price Dinner</td>
	<td class="tdgreen">Net Price based on <?php echo $mealplanTax->tax?>%</td>
	<td class="tdgreen">Merchant Fee %</td>
	<td class="tdgreen">Total Merchant Fee Dinner</td>
	<td style="background:#e26b0a;color:#fff;">Grand Total Amount</td>

</tr>
<?php
$totalMerchantFeeRoomOfResv = 0;
$totalMerchantFeeBreakfastResv = 0;
$totalMerchantFeeLunchOfResv = 0;
$totalMerchantFeeDinnerOfResv = 0;

$grandTotalFinal = 0;

foreach ($this->reservations as $reservation):?>

<tr>
	<td><?php echo $reservation->blockcode;?></td>
	<td><?php echo $reservation->status;?></td>
	<td>
		<?php
		$totalRooms = $reservation->totalRooms;
		echo $totalRooms;
		?>
	</td>
	<td><?php echo $currency_name.' '.$reservation->sd_rate;?></td>
	<td>
		<?php
			$b = number_format($reservation->room_net_price,2);
			echo $currency_name.' '. $b;
		?>
	</td>
	<td>
		<?php echo number_format($this->merchantFee->merchant_fee);?> <?php echo $this->merchantFee->merchant_fee_type==1?'%':$currency_name;?>
	</td>
	<td style="background:#8db4e2;">
		<?php
		$totalMerchatFee = $reservation->totalMerchatFeeRoom;
		$totalMerchantFeeRoomOfResv += $totalMerchatFee;

		echo $currency_name.' '.number_format($totalMerchatFee,3);
		?>
	</td>

	<td>
		<?php
			$totalBreakfast = $reservation->calculateTotalBreakfast();
			echo $totalBreakfast;
		?>
	</td>

	<td>
		<?php echo $currency_name.' '. $reservation->breakfast;?>
	</td>

	<td>
		<?php
			$breakfastNetPrice = $reservation->breakfast_net_price;
			$breakfastNetPrice = number_format($breakfastNetPrice,2);
			echo $currency_name.' '.$breakfastNetPrice;
		?>
	</td>
	<td>
		<?php echo number_format($this->merchantFee->breakfast_merchant_fee);?> %
	</td>
	<td style="background: #92d050">
		<?php
		$totalBFSTMerchatFee = $reservation->totalMerchatFeeBreakfast;
		$totalMerchantFeeBreakfastResv += $totalBFSTMerchatFee;

		echo $currency_name.' '.number_format($totalBFSTMerchatFee,3);
		?>
	</td>


	<td>
		<?php
			$totalLunch = $reservation->calculateTotalLunch();
			echo $totalLunch;
		?>
	</td>

	<td>
		<?php echo $currency_name.' '. $reservation->lunch;?>
	</td>

	<td>
		<?php
			$lunchNetPrice = $reservation->lunch_net_price;
			$lunchNetPrice = number_format($lunchNetPrice,2);
			echo $currency_name.' '.$lunchNetPrice;
		?>
	</td>
	<td>
		<?php echo number_format($this->merchantFee->lunch_merchant_fee);?> %
	</td>
	<td style="background: #92d050">
		<?php
		$totalMerchantFeeLunchOfResv += $reservation->totalMerchatFeeLunch;
		echo $currency_name.' '.number_format($reservation->totalMerchatFeeLunch,3);
		?>
	</td>


	<?php $totalDinner = (int)$reservation->calculateTotalMealplan(); ?>
	<td <?php if(!$totalDinner) echo 'style="background:#ccc"';?>>
		<?php
			echo $totalDinner;
		?>
	</td>

	<td <?php if(!$totalDinner) echo 'style="background:#ccc"';?>>
		<?php
		if($totalDinner)
			echo $currency_name.' '. $reservation->mealplan;
		?>
	</td>

	<td <?php if(!$totalDinner) echo 'style="background:#ccc"';?>>
		<?php
		if($totalDinner)
			echo $currency_name.' '. number_format($reservation->dinner_net_price,2);
		?>
	</td>
	<td <?php if(!$totalDinner) echo 'style="background:#ccc"';?>>
		<?php if($totalDinner) echo number_format($this->merchantFee->dinner_merchant_fee).' %';?>
	</td>
	<td style="background: #92d050">
		<?php
		if($totalDinner) {
			$totalMerchantFeeDinnerOfResv += $reservation->totalMerchatFeeDinner;
			echo $currency_name.' '.number_format($reservation->totalMerchatFeeDinner,3);
		}
		?>
	</td>

	<td style="background:#e26b0a;">
		<?php
		echo $currency_name.' '.number_format($reservation->grandTotal,2);
		$grandTotalFinal += $reservation->grandTotal;
		?>
	</td>

</tr>

<?php endforeach;?>
<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td><?php echo number_format($totalMerchantFeeRoomOfResv,3);?></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td><?php echo number_format($totalMerchantFeeBreakfastResv,3)?></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td><?php echo number_format($totalMerchantFeeLunchOfResv,3)?></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td><?php echo number_format($totalMerchantFeeDinnerOfResv,3)?></td>
	<td><?php echo number_format($grandTotalFinal,2)?></td>
</tr>
</table>


	<div style="margin-top: 15px;">
		<button onClick="Joomla.submitbutton('invoice.export')" type="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none; font-size:12px;">
			Export to Excel
		</button>
	</div>


<?php endif;?>


	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hotel_id" value="<?php echo JRequest::getInt('hotel_id')?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>

</div>

