<?php
defined('_JEXEC') or die();
$nowDay = SfsHelperDate::getDate($this->state->get('filter.date_start'),'N');
?>
<style>
div.form-group label{
	width: 100% !important;
}
.help-block{
	margin-left: 22px;
}

</style>
<span class="r-heading">Mealplan</span>
<?php if(empty($this->item->wsData)) : 
//lchung
$bf_layover_price = $this->item->bf_layover_price;
if( $this->item->isContractedRate && $this->item->contracted_breakfast > 0 ) {
	$bf_layover_price = $this->item->contracted_breakfast;
}
//End lchung
?>
<div class="form-group">
<?php if((int)$this->item->status_break === 1) : ?>
	<label for="breakfast<?php echo $this->item->id?>"><input type="checkbox" name="breakfast" id="breakfast<?php echo $this->item->id?>" value="1" class="checkbox breakfast<?php echo $this->item->id?>" />Breakfast <?php echo $this->item->currency_symbol.$bf_layover_price;?></label>
	<?php
	if($this->item->bf_service_hour==1){
		echo '<div class="help-block">24-24</div>';
	} else if($this->item->bf_service_hour==2){
		echo '<div class="help-block">'.str_replace(':','h',$this->item->bf_opentime).'-'.str_replace(':','h',$this->item->bf_closetime).'</div>';
	} 
	?>
<?php else: ?>
	<div class="form-group">
		<label style="width: 170px"><input type="checkbox" class="mealplan" style="visibility: hidden" />Breakfast</label>
		<div class="meal-not-available">not available</div>
	</div>
<?php endif; ?>
</div>

<?php 
$lunchAvailable = 1;
if( ! empty($this->item->lunch_available_days) ) {
	$lunch_available_days = explode(',', $this->item->lunch_available_days);
	JArrayHelper::toInteger($lunch_available_days);
	if( count($lunch_available_days)  ){						
		if( !in_array($nowDay, $lunch_available_days) ){
			$lunchAvailable=0;
		}
	} else {
		$lunchAvailable = 0;
	}
} else {
	$lunchAvailable = 0;
}
if( (int)$this->item->lunch_service_hour > 0 && $lunchAvailable == 1 ): 

//lchung
$lunch_standard_price = $this->item->lunch_standard_price;
if( $this->item->isContractedRate && $this->item->contracted_lunch > 0 ) {
	$lunch_standard_price = $this->item->contracted_lunch;
}
//End lchung

?>

<div class="form-group">
<?php if((int)$this->item->status_lunch === 1) : ?>
	<label for="lunch<?php echo $this->item->id?>"><input type="checkbox" name="lunch" id="lunch<?php echo $this->item->id?>" value="1" class="checkbox lunch<?php echo $this->item->id?>"/>Lunch <?php echo $this->item->currency_symbol.$lunch_standard_price;?></label>

	<?php
	if($this->item->lunch_service_hour==1){
		echo '<div class="help-block">24-24</div>';
	} else if($this->item->lunch_service_hour==2){
		echo '<div class="help-block">'.str_replace(':','h',$this->item->lunch_opentime).'-'.str_replace(':','h',$this->item->lunch_closetime).'</div>';
	}
	?>
<?php else: ?>	
	<div class="form-group">
		<label style="width: 170px"><input type="checkbox" class="mealplan" style="visibility: hidden" />Lunch</label>
		<div class="meal-not-available">not available</div>
	</div>
<?php endif; ?>
</div>	

<?php else :?>				
<div class="form-group">
	<label style="width: 170px"><input type="checkbox" class="mealplan" style="visibility: hidden" />Lunch</label>
	<div class="meal-not-available">not available</div>
</div>
<input type="hidden" name="lunch" value="0" />
<?php endif;?>		
	
<?php
$mealplan_count = 0;
$mealplan_count += 	$this->item->course_1 ? 1 : 0;
$mealplan_count += 	$this->item->course_2 ? 1 : 0;

$mealplan_count += 	$this->item->course_3 ? 1 : 0;

if( ! empty($this->item->available_days) ) {
	$available_days = explode(',', $this->item->available_days);
	JArrayHelper::toInteger($available_days);
	if( count($available_days)  ){						
		if( !in_array($nowDay, $available_days) ){
			$mealplan_count=0;
		}
	} else {
		$mealplan_count = 0;
	}
} else {
	$mealplan_count = 0;
}
										
if( $mealplan_count > 0 ) : ?>
<div class="form-group">	
<?php 
$show = false;
for($i=1;$i<=3;$i++) :
				$course_t = 'course_'.$i;
				
				//lchung
				$vcourse_t = $this->item->$course_t;
				if( $this->item->isContractedRate && $this->item->contracted_dinner > 0 && $i==1 ) {
					$vcourse_t = $this->item->contracted_dinner;
				}
				if( $this->item->isContractedRate && $this->item->contracted_two_course_dinner > 0 && $i==2 ) {
					$vcourse_t = $this->item->contracted_two_course_dinner;
				}
				if( $this->item->isContractedRate && $this->item->contracted_three_course_dinner > 0 && $i==3 ) {
					$vcourse_t = $this->item->contracted_three_course_dinner;
				}
				if($vcourse_t>0){
					$show = true;
					break;
				}
				//End lchung
endfor;				

if((int)$this->item->status_dinner === 1 && $show == true) : ?>									
	<label for="dinner<?php echo $this->item->id;?>">
		<input type="checkbox" name="mealplan" id="dinner<?php echo $this->item->id;?>" value="<?php echo $this->item->id;?>" class="mealplan dinner<?php echo $this->item->id;?>" />Dinner
	</label>
	<?php
		if((int)$this->item->stop_selling_time==24){
			echo '<div class="help-block">24-24</div>';
		} else {
			echo '<div class="help-block">'.str_replace(':','h',$this->item->stop_selling_time).'</div>';
		} 
	?>
    <style>
        .sfs-form.form-vertical .form-group label {
            float: none !important;
            display: inline !important;
        }
    </style>
	<div id="mealplan<?php echo $this->item->id;?>" class="mealplan-<?php echo $this->item->id;?>" style="overflow:hidden; display: none">							
		<ul class="noStyle">
			<?php 
			//print_r($this->item);die;
			for($i=1;$i<=3;$i++) :
				$course = 'course_'.$i;
				
				//lchung
				$vcourse = $this->item->$course;
				if( $this->item->isContractedRate && $this->item->contracted_dinner > 0 && $i==1 ) {
					$vcourse = $this->item->contracted_dinner;
				}
				if( $this->item->isContractedRate && $this->item->contracted_two_course_dinner > 0 && $i==2 ) {
					$vcourse = $this->item->contracted_two_course_dinner;
				}
				if( $this->item->isContractedRate && $this->item->contracted_three_course_dinner > 0 && $i==3 ) {
					$vcourse = $this->item->contracted_three_course_dinner;
				}
				//End lchung

				if ($vcourse > 0) :  //$this->item->$course
			?>
				<li>				
					<input type="radio" value="<?php echo $i;?>" id="course<?php echo $i;?>" name="course"<?php if( $i==1 ) {echo ' checked="checked"';}  ?>>
					<label for="course<?php echo $i;?>"><?php echo $i;?>-course &nbsp;<?php echo $this->item->currency_symbol.$vcourse;//echo $this->item->currency_symbol.$vcourse * $i;//$this->item->$course;?></label>
				</li>
			<?php
				endif; 
			    endfor;
			?>
		</ul>
	</div>
	<?php else : ?>
		<div class="form-group">
			<label style="width: 170px"><input type="checkbox" class="mealplan" style="visibility: hidden" />Dinner</label>
			<div class="meal-not-available">not available</div>
		</div>
		<input type="hidden" name="mealplan" value="0" />
	<?php endif; ?>

<?php else: ?>
<?php endif; ?>
</div>
<?php endif;?>
