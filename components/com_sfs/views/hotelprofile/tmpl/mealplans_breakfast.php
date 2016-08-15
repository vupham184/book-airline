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
	Breakfast information
</div>

<?php if((int)$this->mealplan->status_break === 1) : ?>

<div class="sfs-row">
	<div class="sfs-column-left">
		Standard price breakfast per person: <br /> 
		<span class="fs-12">Must be full american breakfast</span>
	</div>
	<div style="width: 70px; float: left">
		<?php echo $this->mealplan->bf_standard_price.' '.$curGlobal; ?>
	</div>
	<span class="fs-12">
		<?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?>
	</span>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">
		Price layover breakfast per person: <br /> 
		<span class="fs-12">
			Must be full american breakfast
		</span>
	</div>
	<div style="width: 70px; float: left">
		<?php echo $this->mealplan->bf_layover_price.' '.$curGlobal; ?>
	</div>
	<span class="fs-12">
		<?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?>
	</span>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">
	Food taxes (%) that are applicable for the above menus:
	</div>
	<?php echo $this->mealplan->bf_tax; ?>
	&nbsp;
	<button type="button" class="button hasTip" title="above rates include these taxes"	style="float: none; padding: 3px 5px;">?</button>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">
		Regular opening hours for the buffet breakfast service:
 	</div>
	<?php echo $this->mealplan->bf_service_hour == 1 ? '24 Hrs': $this->mealplan->bf_opentime.' till '.$this->mealplan->bf_closetime ; ?>
	<br />
	<?php echo SfsHelper::getArticle(103, 1, 1); ?>
</div>

<div class="sfs-row">
	<div class="sfs-column-left">Outside regular openings hours breakfast
		service:
	</div>
	<?php
	$breakfast = array( 1 => 'Continental prearranged room service breakfast will be offered at same price',
	2 => 'Plated continental breakfast service will be offered in the restaurant at same price',
	3 => 'Breakfast box upon group check out',
	0 => 'No Breakfast can be offered');

	foreach($breakfast as $key => $value) {
		if( (int)$key == (int) $this->mealplan->bf_outside ) {
			echo $value;
			break;
		}
	}
	?> 
</div>

<?php else: ?>
	<div class="sfs-row">
		No breakfast available
	</div>	
<?php endif; ?>