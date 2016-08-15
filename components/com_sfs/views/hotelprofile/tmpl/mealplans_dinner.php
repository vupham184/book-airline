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
<?php echo JText::_('COM_SFS_LABLE_LUNCH_DINNER_INFO'); ?>
</div>

<?php if((int)$this->mealplan->status_dinner === 1) : ?>

<div class="sfs-row">
	<div class="sfs-column-left">
		<?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 1); ?>
		<br /> 
		<span class="fs-12">
			<?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?>
		</span>
	</div>
	<div style="width: 70px; float: left">
		<?php echo $this->mealplan->course_1.' '.$curGlobal; ?>
	</div>
	<span class="fs-12">
		<?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?>
	</span>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">
		<?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 2); ?>
		<br /> 
		<span class="fs-12">
			<?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?>
		</span>
	</div>
	<div style="width: 70px; float: left">
		<?php echo $this->mealplan->course_2.' '.$curGlobal; ?>
	</div>
	<span class="fs-12">
		<?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?>
	</span>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">
		<?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 3); ?>
		<br /> 
		<span class="fs-12">
			<?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 2); ?>
		</span>
	</div>
	<div style="width: 70px; float: left">
		<?php echo $this->mealplan->course_3.' '.$curGlobal; ?>
	</div>
	<span class="fs-12">
		<?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?>
	</span>
</div>

<div class="sfs-row midmargintop">
	The above menu prices are net prices (prices without taxes).
</div>

<div class="sfs-row">

	<div class="sfs-column-left">
		Food taxes (%) that are applicable for the above menus:
	</div>
	
	<div class="formmealplans_lunchdinner-right">
		<?php echo $this->mealplan->tax; ?>
		&nbsp;
		<button type="button" class="button hasTip"	title="above rates include these taxes"	style="float: none; padding: 3px 5px;">?</button>
	</div>
	
</div>

<div class="sfs-row">

	<div class="sfs-column-left">Stop selling time for the restaurant:</div>
	
	<?php echo (int)$this->mealplan->stop_selling_time==24 ? '24-24 for stranded ' : $this->mealplan->stop_selling_time;?>
	<?php echo SfsHelper::getArticle(103, 1, 1); ?>
	
</div>

<div class="sfs-row">

	<div class="sfs-column-left">Restaurant is open on days:</div>
	
	
	<?php
	if( ! empty($this->mealplan->available_days) ){
		$day_array = array(
		1 => JText::_('MON'),
		2 => JText::_('TUE'),
		3 => JText::_('WED'),
		4 => JText::_('THU'),
		5 => JText::_('FRI'),
		6 => JText::_('SAT'),
		7 => JText::_('SUN')
		);
		$available_days = explode(',', $this->mealplan->available_days);
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
		No dinner available
	</div>	
<?php endif; ?>
