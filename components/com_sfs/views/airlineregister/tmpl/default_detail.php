<?php
defined('_JEXEC') or die;?>
<fieldset>

    <legend><span class="text_legend"><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_YOUR_DETAILS')?></span></legend>
                         
    <div class="col w80 pull-left p20">    
        <div data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('airline_code_field', $text, 'airline'); ?>">
        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_2LETTER_CODE')?></label>
            <div class="col w60">
                <?php echo $this->options['airline_code']; ?>
            </div>
        </div>                    	
        </div>


        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_COMPANY_NAME')?></label>
            <div class="col w60">
                <div id="company_name"></div>
            </div>
        </div>                    	


        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AFFILIATION_CODE')?></label>
            <div class="col w60">
                <input type="text" name="affiliation_code" id="affiliation_code" class="smaller-size" />
            </div>
        </div>                    	


        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_ALLIANCE')?></label>
            <div class="col w60">
                <select name="airline_alliance" id="airline_alliance" >
                    <option value="<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_SKY_TEAM')?>"><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_SKY_TEAM')?></option>
                    <option value="<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_ONE_WORLD')?>"><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_ONE_WORLD')?></option>
                    <option value="<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_STAR_ALLIANCE')?>"><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_STAR_ALLIANCE')?></option>
                    <option value="<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_NO_ALLIANCE')?>"><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRLINE_NO_ALLIANCE')?></option>
                </select>
            </div>
        </div>                    	

   
        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_AIRPORT_LOCATION_CODE')?></label>
            <div class="col w60">                
                <?php echo $this->options['airport_code']; ?>                
                <div id="airport-location-loading" class="float-left" style="margin-left:10px; margin-top:3px;"></div>
                <div style="display:none;">
                	<div id="air-error-msg">
                		<?php echo JText::_('COM_SFS_AIRLINE_REGISTER_STATIS_ALREADY_ACTIVE')?>
                		<button type="button" class="button" onClick="window.SqueezeBox.close();"><?php echo JText::_('COM_SFS_CLOSE')?></button>
                	</div>
                </div>
            </div>
        </div>       
        <div data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('timezone_field', $text, 'airline'); ?>">
        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_TIME_ZONE')?></label>
            <div class="col w60">
                <?php echo SfsHelperField::getTimeZone();?>
            </div>
        </div>                    	
        
        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_YOUR_CURRENT_TIME_NOW')?></label>
            <div class="col w60" style="padding-top: 10px;">                                	
	            <div id="time_display" class="pull-left">
                    <strong>
    		            <?php 
        		            $params = JComponentHelper::getParams('com_sfs');
        					$sfs_system_timezone = $params->get('sfs_system_timezone');
        					if($sfs_system_timezone)
        					{
        						echo JHtml::_('date',JFactory::getDate()->toSql(), 'H:i',$sfs_system_timezone);
        					} else {
        						echo JHtml::_('date',JFactory::getDate()->toSql(), 'H:i');	
        					} 
    		            ?>
                    </strong>
	            </div>
                <div class="checkbox clearfix">
	               <label><input type="checkbox" name="correct" id="correct" class="validate-timezone-check" /><?php echo JText::_('COM_SFS_CONFIRM')?></label>                   
                   <div style="display:none; color:red;" id="timezone-error-msg">
                       <small><?php echo JText::_('COM_SFS_FIELD_REQUIRED')?></small>
                   </div>
                </div>                
   	            <small class="help-block"><?php echo JText::_('COM_SFS_CHANGE_TIME_ZONE')?></small>                
            </div>
        </div>                    	    
        </div>
</div>
</fieldset>