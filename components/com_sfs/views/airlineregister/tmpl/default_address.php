<?php
defined('_JEXEC') or die;
?>
<fieldset>
    <legend><span class="text_legend"><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_LOCAL_OFFICE_DETAILS')?></span></legend>        
    <div class="col w80 pull-left p20">
        <div data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('address_field', $text, 'airline'); ?>">
        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_ADDRESS')?></label>
            <div class="col w60">
                <div class="input-group">
                    <input type="text" name="address" id="office_address" class="required" value="" size="40"  />                
                    <input type="text" name="address2" id="office_address2" size="40" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_CITY')?></label>
            <div class="col w60">
                <input type="text" name="city" id="office_city" class="required" value="" />
            </div>
        </div>

        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_ZIP_CODE')?></label>
            <div class="col w60">
                <input type="text" name="zipcode" id="office_zipcode" class="required" value="" />
            </div>
        </div>

        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_COUNTRY')?></label>
            <div class="col w60">
                <?php echo $this->options['office_country']; ?>
            </div>
        </div>
      	                        
        <div class="form-group">
            <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?></label>
            <div class="col w60">
                <div class="row r10 clearfix">
                    <div class="col w20">
                        <input name="phone_code" type="text" class="validate-numeric required smaller-size" value="" />
                    </div>
                    <div class="col w80">
                        <input type="text" name="phone_number" class="validate-numeric required short-size" value=""  />
                    </div>
                </div>                
                <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE');?></small>
            </div>
        </div>
        </div>
        
        <div class="form-group">            
            <div class="col w60 pull-left p40">
                <a href="javascript: void(0)" class="btn orange lg" id="copy_address"  data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_copy_address', $text, 'airline'); ?>">
                	<?php echo JText::_('COM_SFS_COPY_ADDRESS')?>
                </a>
            </div>
        </div>
    </div>    
</fieldset>