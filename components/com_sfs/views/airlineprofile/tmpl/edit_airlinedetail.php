<?php
defined('_JEXEC') or die;?>
<div class="register-field clear floatbox">
    <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_2LETTER_CODE');?></label>
    <?php echo $this->airline->code; ?>
</div>                    	


<div class="register-field clear floatbox">
    <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_COMPANY_NAME');?></label>
    <div id="company_name"><?php echo $this->airline->name; ?></div>
</div>                    	


<div class="register-field clear floatbox">
    <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AFFILIATION_CODE');?></label>
    <input type="text" name="affiliation_code" id="affiliation_code" class="smaller-size" value="<?php echo $this->airline->affiliation_code; ?>" />
</div>                    	


<div class="register-field clear floatbox">
    <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_ALLIANCE');?></label>
    <select name="airline_alliance" id="airline_alliance" >
        <option value="Sky Team"<?php if(trim($this->airline->airline_alliance) == 'Sky Team') echo ' selected="selected"' ?>>
        <?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_SKY_TEAM');?>
            </option>
            <option value="One World"<?php if(trim($this->airline->airline_alliance) == 'One World') echo ' selected="selected"' ?>>
                <?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_ONE_WORLD');?>
            </option>
            <option value="Star Alliance"<?php if(trim($this->airline->airline_alliance) == 'Star Alliance') echo ' selected="selected"' ?>>
                <?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_STAR_ALLIANCE');?>
            </option>
            <option value="No Alliance"<?php if(trim($this->airline->airline_alliance) == 'No Alliance') echo ' selected="selected"' ?>>
            <?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_NO_ALLIANCE');?>
            </option>
        </select>                            
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