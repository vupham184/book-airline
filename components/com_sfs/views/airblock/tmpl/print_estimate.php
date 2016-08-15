<?php
defined('_JEXEC') or die;
//if($this->reservation->status == 'T' ) $this->reservation->status = 'C';
?>

<table cellpadding="0" cellspacing="0" border="0">
    <tr valign="top">
        <td width="180" class="smallpaddingbottom">Flight Number:</td>
        <td class="smallpaddingbottom">
            <?php echo $this->reservation->_vouchers[0]->flight_code;?>
        </td>
    </tr>
	<tr valign="top">
    	<td width="180" class="smallpaddingbottom">Roomblock code:</td>
        <td class="smallpaddingbottom">
        	<?php echo $this->reservation->blockcode;?>
        </td>
    </tr>
	<tr valign="top">
    	<td class="smallpaddingbottom">Roomblock Status:</td>
        <td>
     	   <?php echo SFSCore::$blockStatus[$this->reservation->status];?>
        </td>
    </tr>
	<tr valign="top">
    	<td class="smallpaddingbottom">Date:</td>
        <td class="smallpaddingbottom">
        	<?php echo JHTML::_('date', $this->reservation->booked_date, JText::_('DATE_FORMAT_LC3') );?>
        </td>
    </tr>        
</table>



<div class="estimated-charges">

<?php if($this->reservation->payment_type == 'passenger' ):?>
<div>
	<strong>Below charges have been paid directly by passenger to Hotel.</strong>
</div>
<?php endif;?>

<div style="font-weight:bold" class="smallpaddingbottom">Estimated charges</div>

<table cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
    	<td class="smallpaddingbottom">Estimated total gross room charge:</td>
        <td class="smallpaddingbottom hugepaddingleft">
        	<?php echo $this->hotel->currency; ?> <?php echo $this->total_room_charge; ?>
        </td>
    </tr>
	<tr valign="top">
    	<td class="smallpaddingbottom">Estimated total gross mealplan charge:</td>
        <td class="smallpaddingbottom hugepaddingleft">
     	  	<?php echo $this->hotel->currency; ?> <?php echo $this->total_mealplan_charge ;?>
        </td>
    </tr>
	<tr valign="top">
    	<td class="smallpaddingbottom">Estimated total gross invoice charge:</td>
        <td class="smallpaddingbottom hugepaddingleft">
        	<?php echo $this->hotel->currency; ?> <?php echo $this->total_invoice_charge ;?>
        </td>
    </tr>        
</table>    
</div>
 
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td class="smallpaddingbottom">Mealplans</td>
        <td class="smallpaddingbottom">Gross rates</td>
        <td class="smallpaddingbottom">Picked up mealplans</td>
    </tr>
    <tr>
    	<td class="smallpaddingbottom">Breakfast price:</td>
        <td class="smallpaddingbottom">
       		<?php if($this->reservation->breakfast):?>
        	<?php echo $this->hotel->currency ;?> <?php echo $this->reservation->breakfast ;?>
        	<?php else:?>
        		N/A
        	<?php endif;?> 
        </td>        
        <td class="smallpaddingbottom"><span class="v-pd">
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
    	<td class="smallpaddingbottom">Lunch price:</td>
        <td class="smallpaddingbottom">
        	<?php if($this->reservation->lunch):?>
        	<?php echo $this->hotel->currency ;?> <?php echo $this->reservation->lunch ;?>
        	<?php else:?>
        		N/A
        	<?php endif;?>
        </td>        
        <td class="smallpaddingbottom"><span class="v-pd">
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
    	<td class="smallpaddingbottom">Dinner price:</td>
        <td class="smallpaddingbottom"><?php echo $this->hotel->currency.' '.$this->reservation->mealplan;?></td>        
        <td class="smallpaddingbottom"><span class="v-pd"><?php echo $this->picked_mealplans ;?></span></td>
    </tr>   
    <?php else :?>
    <tr>
    	<td class="smallpaddingbottom">Dinner price:</td>
        <td class="smallpaddingbottom">N/A</td>
        <td class="smallpaddingbottom">N/A</td>                
    </tr>
    <?php endif;?>  
      
</table>  