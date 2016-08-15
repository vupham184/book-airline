<?php
defined('_JEXEC') or die();
$nowDay = SfsHelperDate::getDate($this->state->get('filter.date_start'),'N');
?>
<style>
div.form-group label{
	width: 100% !important;
}

</style>
<span class="r-heading">Mealplan</span>
<div class="form-group">
    <?php if((int)$this->item->bf_layover_price > 0):?>
	    <label for="breakfast<?php echo $this->item->id?>">Breakfast <?php echo $this->item->currency_symbol.$this->item->bf_layover_price;?></label>
    <?php else:?>
        <label style="width: 170px">Lunch</label>
        <div>not available</div>
    <?php endif;?>
</div>

<div class="form-group">
    <?php if((int)$this->item->lunch_standard_price > 0): ?>
    	<label for="lunch<?php echo $this->item->id?>">Lunch <?php echo $this->item->currency_symbol.$this->item->lunch_standard_price;?></label>
    <?php else :?>
        <label style="width: 170px">Lunch</label>
        <div>not available</div>
    <?php endif;?>
</div>

<div class="form-group">
    <?php if((int)$this->item->course_1 > 0): ?>
        <label for="course<?php echo $this->item->id?>">Dinner <?php echo $this->item->currency_symbol.$this->item->course_1;?></label>
    <?php else :?>
        <label style="width: 170px">Dinner</label>
        <div>not available</div>
    <?php endif;?>
</div>


