<style>
	.tooltip-custom .tip{
		text-align: center;
	}
</style>
<?php
defined('_JEXEC') or die;

$airline = SFactory::getAirline();

$issued_array = array();
$issued_array[1] = $issued_array[2] = $issued_array[3] = $issued_array[4] = 0;

$startDate  = JFactory::getDate($this->night)->format('d F');
$endDate	= SfsHelperDate::getNextDate('d F Y', $this->night);

$reservationid  = JRequest::getInt('reservationid');
$association_id = JRequest::getInt('association_id');
?>

<h4 data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('create_voucher_date', $text, 'airline'); ?>">Created Voucher(s) for the night starting <?php echo $startDate;?> ending <?php echo $endDate;?></h4>

<?php if(count($this->reservations)) :
$url_blockcode  = 'index.php?option=com_sfs&view=match&layout=vouchers&&nightdate='.$this->night.'&Itemid=120&association_id='.$association_id.'&reservationid=';
$url_hotel 		= 'index.php?option=com_sfs&view=match&layout=vouchers&&nightdate='.$this->night.'&Itemid=120&association_id='.$association_id.'&reservationid=';
?>
<div data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('select_voucher_code', $text, 'airline'); ?>">
	Select blockcode
	<select name="ordering" onchange="location.href='<?php echo JURI::base().$url_blockcode;?>' + this.options[this.selectedIndex].value;">
		<option value="">All blocks</option>
		<?php foreach ($this->reservations as $r):?>
			<option value="<?php echo $r->id?>"<?php echo $reservationid==$r->id?' selected="selected"':''?>><?php echo $r->blockcode?></option>
		<?php endforeach;?>
	</select>
</div>

<div>
	Select hotel
	<select name="ordering" onchange="location.href='<?php echo JURI::base().$url_hotel;?>' + this.options[this.selectedIndex].value;">
		<option value="">All hotels</option>
		<?php foreach ($this->reservations as $r):?>
			<option value="<?php echo $r->id?>"<?php echo $reservationid==$r->id?' selected="selected"':''?>><?php echo $r->name?></option>
		<?php endforeach;?>
	</select>
</div>
<?php endif;?>

<div class="floatbox sfs-white-wrapper voucher-match-table">		
	<form id="voucherListForm" name="voucherListForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=match'); ?>" method="post">
		<table class="airblocktable" width="100%">
			<tr>
				<th>Flight<br/>number</th>
				<th>Block code<br/>Voucher number</th>
				<th>Passengers</th>
				<th>Mealplan</th>
				<?php
				if( isset($airline->params['enable_passenger_payment']) && (int)$airline->params['enable_passenger_payment'] == 1 ) :
				?>
				<th>Paid by</th>
				<?php endif;?>
				<th>Creation Rep</th>
				<th>Room Type</th>
				<th>Group</th>
				<?php if( $airline->allowTaxiVoucher() ) : ?>
				<th>Taxi<br />vouchers</th>
				<?php endif;?>
				<th>Hotel</th>
				<th>Creation<br/>Print/Email</th>
				<th>&nbsp;</th>
			</tr>
			<?php
//			var_dump($this->vouchers);exit;
			foreach ($this->vouchers as $item) :
			if($item->status==1 || $item->status==2||$item->status==0) :

				$item->vgroup = (int) $item->vgroup ;
				$issued_array[1] = $issued_array[1] + $item->sroom;
				$issued_array[2] = $issued_array[2] + $item->sdroom;
				$issued_array[3] = $issued_array[3] + $item->troom;
				$issued_array[4] = $issued_array[4] + $item->qroom;
				$this->item = $item;

				if( $this->item->vgroup && count($this->item->individualVouchers) )
				{
					echo $this->loadTemplate('created_item_individual');
				} else {
					echo $this->loadTemplate('created_item');
				}
				?>
			

			<?php
			else:
				$this->cancel_count++;
			endif;
			endforeach;?>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="" />
  		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
  		<?php echo JHtml::_('form.token'); ?>
	</form>
	
	<?php if( count($this->vouchers) ) : ?>
	
	<div class="midpaddingtop" data-step="16" data-intro="<?php echo SfsHelper::getTooltipTextEsc('total_info', $text, 'airline'); ?>">
		Total issued vouchers for <?php echo $issued_array[1];?> singles, <?php echo $issued_array[2];?> doubles, <?php echo $issued_array[3];?> triple and <?php echo $issued_array[4];?> quad rooms
	</div>
	<?php endif;?>
</div>
	