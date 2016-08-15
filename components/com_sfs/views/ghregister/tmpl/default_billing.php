<?php
defined('_JEXEC') or die;?>
<fieldset>
    <legend><span class="text_legend"><?php echo JText::_('COM_SFS_BILLING_DETAILS')?></span></legend>
  
    <div class="col w80 pull-left p20">
      <div class="form-group">        
        <label><?php echo JText::_('COM_SFS_REGISTERED_NAME')?></label>
        <div class="col w60">
          <input type="text" name="billing[name]" id="billing_name"  class="required" value="" />
        </div>
      </div>

      <div class="form-group">        
        <label><?php echo JText::_('COM_SFS_ADDRESS')?></label>
        <div class="col w60">
            <div class="input-group">
              <input type="text" name="billing[address]" id="billing_address" size="40" class="required" value=""  />          
              <input type="text" name="billing[address1]" id="billing_address1" size="40" />
            </div>    
        </div>
      </div>

      <div class="form-group">        
        <label><?php echo JText::_('COM_SFS_CITY')?></label>
        <div class="col w60">
          <input type="text" name="billing[city]" id="billing_city" class="required" value="" />
        </div>
      </div>

      <div class="form-group">        
        <label><?php echo JText::_('COM_SFS_ZIP_CODE')?></label>
        <div class="col w60">
          <input type="text" name="billing[zipcode]" id="billing_zipcode" class="required" value="" />
        </div>
      </div>

      <div class="form-group">
        <label><?php echo JText::_('COM_SFS_COUNTRY')?></label>
        <div class="col w60">
          <?php echo $this->options['billing_country']; ?> 
        </div>
      </div>
      
      <div class="form-group">
        <label><?php echo JText::_('COM_SFS_TVA_NUMBER')?></label>
        <div class="col w60">
          <input type="text" name="billing[tva_number]" id="billing_tvanumber" class="required" value="" />
          <small class="help-block"><?php echo JText::_('COM_SFS_TVA_NUMBER_DESC')?></small>
        </div>
      </div>
    </div>
</fieldset>