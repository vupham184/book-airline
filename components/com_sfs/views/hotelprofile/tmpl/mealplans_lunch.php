<?php
defined('_JEXEC') or die;
$listCur = SfsHelper::getCurrencyMulti();                
$curGlobal = "";
foreach ($listCur as $value) {
    if($value->id == $this->hotel->currency_id){
        $curGlobal = $value->code; 
    }                          
}
?>

<div class="fs-16 midmarginbottom">
	Lunch information
</div>

<?php if((int)$this->mealplan->status_lunch === 1) : ?>

<div class="sfs-row">
	<div class="sfs-column-left">
		Standard price lunch per person: <br /> 
		<span class="fs-12">
			<?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?>
		</span>
	</div>
	<div style="width: 70px; float: left">
		<?php echo $this->mealplan->lunch_standard_price.' '.$curGlobal;  ?>
	</div>
	<span class="fs-12">
		<?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?>
	</span>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">
		Food taxes (%) that are applicable for the above menus:			
	</div>
	<?php echo $this->mealplan->lunch_tax; ?>
	&nbsp;
	<button type="button" class="button hasTip" title="above rates include these taxes"	style="float: none; padding: 3px 5px;">?</button>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">
		Regular opening hours for the lunch	service:
	</div>	 
	<?php echo $this->mealplan->lunch_service_hour == 1 ? '24 Hrs': $this->mealplan->lunch_opentime.' till '.$this->mealplan->lunch_closetime ; ?>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">Restaurant is open on days:</div>
	
	<?php
	if( ! empty($this->mealplan->lunch_available_days) ){
		$day_array = array(
		1 => JText::_('MON'),
		2 => JText::_('TUE'),
		3 => JText::_('WED'),
		4 => JText::_('THU'),
		5 => JText::_('FRI'),
		6 => JText::_('SAT'),
		7 => JText::_('SUN')
		);
		$available_days = explode(',', $this->mealplan->lunch_available_days);
		JArrayHelper::toInteger($available_days);
		foreach ($available_days as $day){
			echo $day_array[$day].' ';
		}

	} else {
		echo 'Not available all days';
	}
	?>
	
</div>

<?php else: ?>
	<div class="sfs-row">
		No lunch available
	</div>	
<?php endif; ?>