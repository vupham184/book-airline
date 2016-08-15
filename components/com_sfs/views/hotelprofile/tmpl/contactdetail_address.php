<?php
defined('_JEXEC') or die;?>

<div class="fieldset-title float-left">
    <span style="line-height:120%;" class="fs-16"><?php echo JText::_("COM_SFS_ADDRESS_INFOMATION");?></span> 
</div>    

<div class="fieldset-fields float-left fs-14" style="width:584px; padding-top:35px;">
	<div class="register-field clear floatbox">
    	<?php if( !$this->hotel->web_address ) $this->hotel->web_address = 'http://'; ?>
    	<label for="web_address"><?php echo JText::_('COM_SFS_WEB_ADDRESS');?> :</label>
        <?php echo $this->hotel->web_address; ?>
    </div>
    
	<div class="register-field clear floatbox">
		<label for="telephone_code">
			<?php echo JText::_('COM_SFS_MAIN_TELEPHONE_NUMBER'); ?>:
		</label>
		<?php echo $this->hotel->telephone;?>
	</div>
    
	<div class="register-field clear floatbox">
		<label for="fax_code">
			<?php echo JText::_('COM_SFS_MAIN_FAX_NUMBER'); ?>:<br />
			<span class="fs-12"><?php echo JText::_("COM_SFS_24H_MONITORED");?></span>			
		</label>
		<?php echo $this->hotel->fax;?>
	</div>
    
	<div class="register-field clear floatbox">
    	<label for="address"><?php echo JText::_('COM_SFS_ADDRESS');?> :</label>
        <?php echo $this->hotel->address; ?>
    </div>
    
	<div class="register-field clear floatbox">
    	<label for="zip"><?php echo JText::_('COM_SFS_ZIP_CODE');?> :</label>
        <?php echo $this->hotel->zipcode; ?>
    </div>
    
	<div class="register-field clear floatbox">
    	<label for="city"><?php echo JText::_('COM_SFS_CITY');?> :</label>
        <?php echo $this->hotel->city; ?>
    </div>
    
	<div class="register-field clear floatbox">
    	<label for="country"><?php echo JText::_('COM_SFS_COUNTRY');?> :</label>
		<?php echo $this->hotel->country_name; ?>
	</div>
      
	<div class="register-field clear floatbox">
    	<label for="star"><?php echo JText::_('COM_SFS_STAR_RATING');?> :</label>
		<?php 		
		if( (int) $this->hotel->star==0 ) {
			echo 'no rating';
		} else {
			echo $this->hotel->star.' stars';
		}
		?>
    </div>
    

	<div class="register-field clear floatbox">
    
		<div class="register-field clear floatbox">
	        <label><?php echo JText::_("COM_SFS_GEO_LOCATION");?>:</label>
            <div style="width:200px; float:left;">
				<span style="padding-top:5px; margin-bottom:5px;" class="fs-12"><?php echo JText::_("COM_SFS_LATITUDE");?></span>&nbsp; <?php echo $this->hotel->geo_location_latitude; ?><br />
				<span style="padding-top:5px; clear:both" class="fs-12"><?php echo JText::_("COM_SFS_LONGITUDE");?></span>&nbsp; <?php echo $this->hotel->geo_location_longitude; ?>
            </div>
			<span style="width:140px; float:right; line-height:150%;" class="fs-12">Visit http://mygeoposton.com to find your location</span>            
        </div>
        
	</div>
    
</div>
