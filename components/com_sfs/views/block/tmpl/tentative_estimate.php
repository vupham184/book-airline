<?php
defined('_JEXEC') or die;
?>
<div class="blockcode-information floatbox" data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('blockcode_information', $text, 'hotel'); ?>">
	<div class="floatbox clear">
	    <span class="l-title">Roomblock code:</span><span><?php echo $this->reservation->blockcode;?></span>
    </div>
	<div class="floatbox clear">
	    <span class="l-title">Roomblock Status:</span><span><?php echo SFSCore::$blockStatus[$this->reservation->status];?></span>
    </div>
	<div class="floatbox clear">
    	<span class="l-title">Date</span><span><?php echo JHTML::_('date', $this->reservation->booked_date, JText::_('DATE_FORMAT_LC3') );?></span>
    </div>
</div>

<?php if($this->reservation->payment_type == 'passenger' ):?>
<div style="padding-bottom:10px">
	<strong>Below charges have been paid directly by passenger to Hotel.</strong>
</div>
<?php endif;?>

<div class="estimate-information" data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('estimate_information', $text, 'hotel'); ?>">

    <div class="estimate-title">Estimated charges</div>

    <div class="estimate-detail floatbox">
        <div class="floatbox clear">
            <span class="l-title">Estimated total gross room charge:</span><span><?php echo $this->hotel->currency; ?> <?php echo $this->total_room_charge; ?></span>
        </div>
        <div class="floatbox clear">
            <span class="l-title">Estimated total gross mealplan charge:</span><span><?php echo $this->hotel->currency; ?> <?php echo $this->total_mealplan_charge ;?></span>
        </div>
        <div class="floatbox clear">
            <span class="l-title">Estimated total gross invoice charge:</span><span><?php echo $this->hotel->currency; ?> <?php echo $this->total_invoice_charge ;?></span>
        </div>
    </div>
</div>

<table cellpadding="0" cellspacing="0" width="100%" class="blocked-rooms" data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('mealplan_list', $text, 'hotel'); ?>">
	<tr>
		<th class="br-c1">Mealplans</th>
        <th class="br-c2">Gross rates</th>
        <th class="br-c3">Picked up mealplans</th>
    </tr>
    <tr class="first">
    	<td>Breakfast price:</td>
        <td>
        	<?php if($this->reservation->breakfast):?>
        	<?php echo $this->hotel->currency ;?> <?php echo $this->reservation->breakfast ;?>
        	<?php else:?>
        		N/A
        	<?php endif;?>
        </td>
        <td><span class="v-pd">
	        <?php 
	       	if($this->reservation->breakfast):
	        	echo $this->picked_breakfasts ;
	        else :
	        	echo 'N/A';
	        endif;	
	        ?>        	
        </span></td>
    </tr>
    <tr>
    	<td>Lunch price:</td>
        <td>
        	<?php if($this->reservation->lunch):?>
        	<?php echo $this->hotel->currency ;?> <?php echo $this->reservation->lunch ;?>
        	<?php else:?>
        		N/A
        	<?php endif;?>
        </td>        
        <td><span class="v-pd">
        <?php 
       	if($this->reservation->lunch):
        	echo $this->picked_lunchs ;
        else :
        	echo 'N/A';
        endif;	
        ?>
        </span></td>
    </tr>
	<?php if( (int)$this->reservation->course_type > 0) : ?>
    <tr>
    	<td>Dinner price:</td>
        <td><?php echo $this->hotel->currency.' '.$this->reservation->mealplan;?></td>        
        <td><span class="v-pd"><?php echo $this->picked_mealplans ;?></span></td>
    </tr>
    <?php else :?>
    <tr>
    	<td>Dinner price:</td>
        <td>N/A</td>
        <td><span class="v-pd">N/A</span></td>                
    </tr>
    <?php endif;?>      
</table>