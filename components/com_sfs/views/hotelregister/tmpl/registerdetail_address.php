<?php
defined('_JEXEC') or die;?>

<legend><span class="text_legend"><?php echo JText::_("COM_SFS_ADDRESS_INFOMATION");?></span></legend>

<div class="col w80 pull-left p20">
	<div class="form-group">    	
    	<label for="web_address"><?php echo JText::_('COM_SFS_WEB_ADDRESS');?> :</label>
		<div class="col w60">	
        	<input class="required" type="text" value="<?php echo $this->hotel->web_address; ?>" name="web_address" value="" />			
			<small class="help-block">Example:  www.testhotel.com</small>			
		</div>
    </div>   

	<div class="form-group">
		<label for="telephone_code">
			<?php echo JText::_('COM_SFS_MAIN_TELEPHONE_NUMBER');?>:
		</label>
		<div class="col w60">
			<div class="row r10 clearfix">
		        <div class="col w20">
		       		<input type="text" class="validate-numeric required smaller-size" name="tel_code" value="<?php echo SfsHelper::formatPhone( $this->hotel->telephone,1)  ;?>" />
		        </div>
		        <div class="col w80">
					<input type="text" class="validate-numeric required short-size" name="tel_number" value="<?php echo SfsHelper::formatPhone( $this->hotel->telephone,2)  ;?>" />
		        </div>
			</div>
	        <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
        </div>
	</div>

	<div class="form-group">
		<label for="fax_code">
			<?php echo JText::_('COM_SFS_MAIN_FAX_NUMBER');?>:<br />
			<span style="font-size: 11px;"><?php echo JText::_("COM_SFS_24H_MONITORED");?></span>
		</label>
		<div class="col w60">
		    <div class="row r10 clearfix">
		        <div class="col w20">
					<input type="text" class="validate-numeric required smaller-size" name="fax_code" value="<?php echo SfsHelper::formatPhone( $this->hotel->fax, 1)  ;?>" />
		        </div>
		        <div class="col w80">
					<input type="text" class="validate-numeric required short-size" name="fax_number" value="<?php echo SfsHelper::formatPhone( $this->hotel->fax, 2)  ;?>" />
		        </div>
		    </div>
	        <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
        </div>
	</div>
	
    
    <div class="form-group">
    	<label><?php echo JText::_('COM_SFS_MAIN_EMAIL_ADDRESS');?> :<br />
        <span style="font-size: 11px;"><?php echo JText::_("COM_SFS_24H_EMAIL_ADDRESS_MONITORED");?></span>
        </label>
    	<div class="col w60">
    		<div class="form-group">
        		<input type="text" value="<?php echo $this->hotel->main_24h_address; ?>" name="main_24h_address" id="main_24h_address" class="required" />
        	</div>
        </div>
    </div>
    
    
	<div class="form-group">
    	<label><?php echo JText::_('COM_SFS_ADDRESS');?> :</label>
    	<div class="col w60">
    		<div class="form-group">
        		<input type="text" value="<?php echo $this->hotel->address; ?>" name="address" id="address" class="required" />
        	</div>
        	<div class="form-group">
        		<input type="text" value="<?php echo $this->hotel->address1; ?>" name="address1" id="address1">
        	</div>
        	<div class="form-group">
        		<input type="text" value="<?php echo $this->hotel->address2; ?>" name="address2" id="address2">
        	</div>
        </div>
    </div>


	<div class="form-group">
    	<label for="city"><?php echo JText::_('COM_SFS_CITY');?> :</label>
    	<div class="col w60">
        	<input type="text" class="required" value="<?php echo $this->hotel->city; ?>" name="city" id="city">
        </div>
    </div>

	<div class="form-group">
    	<label for="zipcode"><?php echo JText::_('COM_SFS_ZIP_CODE');?>:</label>
    	<div class="col w60">
        	<input type="text" class="required" value="<?php echo $this->hotel->zipcode; ?>" name="zipcode" id="zipcode">
        </div>
    </div>

	<div class="form-group">
    	<label for="country"><?php echo JText::_('COM_SFS_COUNTRY');?> :</label>
    	<div class="col w60">
			<?php echo SfsHelperField::getCountryField('country_id',$this->hotel->country_id); ?>
		</div>
	</div>

	<div class="form-group">
    	<label for="star"><?php echo JText::_('COM_SFS_STAR_RATING');?> :</label>
    	<div class="col w60">
			<select name="star">
				<option value="0"<?php echo ($this->hotel->star==0)?" selected":""; ?>><?php echo JText::_('COM_SFS_NO_RATING');?></option>
				<option value="1"<?php echo ($this->hotel->star==1)?" selected":""; ?>>1 <?php echo JText::_("STAR");?></option>
				<option value="2"<?php echo ($this->hotel->star==2)?" selected":""; ?>>2 <?php echo JText::_("STARS");?></option>
				<option value="3"<?php echo ($this->hotel->star==3)?" selected":""; ?>>3 <?php echo JText::_("STARS");?></option>
				<option value="4"<?php echo ($this->hotel->star==4)?" selected":""; ?>>4 <?php echo JText::_("STARS");?></option>
				<option value="5"<?php echo ($this->hotel->star==5)?" selected":""; ?>>5 <?php echo JText::_("STARS");?></option>
			</select>
		</div>
    </div>

	<div class="form-group">
    	<label for="time_zone"><?php echo JText::_('COM_SFS_TIME_ZONE');?> :</label>
    	<div class="col w60">
			<?php echo SfsHelperField::getTimeZone( $this->hotel->time_zone );?>
		</div>
	</div>

    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_YOUR_CURRENT_TIME_NOW');?></label>
        <div class="col w60">        	
        	<div id="time_display" class="float-left">
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
        	</div>
        	<div class="checkbox clearfix">
        		<label><input type="checkbox" <?php if($this->hotel->step_completed >= 2) echo 'checked="checked"';?> class="validate-timezone-check" />
        	<?php echo JText::_('COM_SFS_CONFIRM');?></label>
        	</div>
        	<small class="help-block"><?php echo JText::_('COM_SFS_CHANGE_TIME_ZONE');?></small>      	
        	<div style="display:none; color:red;" class="clear floatbox" id="timezone-error-msg"><?php echo JText::_('COM_SFS_FIELD_REQUIRED');?></div>
        </div>
    </div>

	<div class="form-group" id="copy_address">
        <button type="button" class="btn orange sm pull-left p40"><?php echo JText::_('COM_SFS_COPY_ADDRESS');?></button>            		
    </div>

</div>
