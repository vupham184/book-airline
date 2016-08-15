<?php
defined('_JEXEC') or die;?>

<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_GH_COMPANY_NAME');?>
	</label>
	<div>
		<input type="text" name="company_name" value="<?php echo $this->airline->name; ?>" />
	</div>
</div>
<div class="register-field clear floatbox">
	<label><?php echo JText::_('COM_SFS_GH_SERVICING_AIRLINES');?></label>
	<div>
		<?php 
		$servicingAirlines = $this->airline->getServicingAirlines();
		if (count ($servicingAirlines)  ) :
			$i=0;
			foreach ($servicingAirlines AS $a) :
				if($i==0) :
					echo $a->code;
				else :
					echo ', '.$a->code; 		
				endif;			
				$i++;
			endforeach;
		endif; 
		?>
	</div>
</div>

<div class="register-field clear floatbox">
    <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRPORT_LOCATION_CODE');?></label>
    <?php echo SfsHelperField::getAirportField( 'airport_id' , $this->airline->airport_id ); ?>
</div>                    	
<div class="register-field clear floatbox">
    <label><?php echo JText::_('COM_SFS_TIME_ZONE');?></label>
    <?php 	   
        echo SfsHelperField::getTimeZone( $this->airline->time_zone );                         	                            
    ?>
</div>                    	
<div class="register-field clear floatbox">
    <label><?php echo JText::_('COM_SFS_YOUR_CURRENT_TIME_NOW');?></label>
    <span id="time_display"><?php echo date('h:i');  ?></span><span style="margin:0 10px;"><input type="checkbox" checked="checked" name="correct" id="correct" class="numeric smaller-size" /></span><?php echo JText::_('COM_SFS_CONFIRM');?><span style="margin:0 10px; font-size:10px;"><?php echo JText::_('COM_SFS_CHANGE_TIME_ZONE');?></span>
</div>  