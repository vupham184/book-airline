<?php
defined('_JEXEC') or die;
?>

<legend><span class="text_legend"><?php echo JText::_('COM_SFS_MAIN_CONTACT'); ?></span></legend>
<div class="col w80 pull-left p20">
    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_JOB_TITLE'); ?>
        </label>
        <div class="col w60">
            <input type="text" size="30" class="required" value="<?php echo count($this->data) ? $this->data['job_title'] : '';?>" id="contact_job_title" name="contact[job_title]" />
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_NAME'); ?>
        </label>
        <div class="col w60">
            <input type="text" size="30" class="required" value="<?php echo count($this->data) ? $this->data['name'] : '';?>" id="contact_first_name" name="contact[name]" />
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_SURNAME'); ?>
        </label>
        <div class="col w60">
            <input type="text" size="30" class="inputbox" value="<?php echo count($this->data) ? $this->data['surname'] : '';?>" id="contact_last_name" name="contact[surname]" />
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_TITLE'); ?>
        </label>
        <div class="col w60">
            <select name="contact[gender]">
                <option value="<?php echo JText::_('COM_SFS_GENDER_MR');?>"><?php echo JText::_('COM_SFS_GENDER_MR');?></option>
                <option value="<?php echo JText::_('COM_SFS_GENDER_MRS');?>"><?php echo JText::_('COM_SFS_GENDER_MRS');?></option>
                <option value="<?php echo JText::_('COM_SFS_GENDER_MS');?>"><?php echo JText::_('COM_SFS_GENDER_MS');?></option>
            </select>
        </div>
    </div>
    
    <div class="form-group">        
        <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE'); ?></label>
        <div class="col w60">
            <div class="row r10 clearfix">
                <div class="col w20">
                    <input type="text" class="validate-numeric required smaller-size" value="<?php echo count($this->data) ? $this->data['tel_code'] : '';?>" name="contact[tel_code]">
                </div>
                <div class="col w80">
                    <input type="text" class="validate-numeric required short-size" value="<?php echo count($this->data) ? $this->data['tel_number'] : '';?>" name="contact[tel_number]" />                                                
                </div>
            </div>
            <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
        </div>
    </div>    
    
    <div class="form-group" data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('direct_fax_field', $text, 'hotel'); ?>">        
        <label><?php echo JText::_('COM_SFS_DIRECT_FAX'); ?></label>
        <div class="col w60">
            <div class="row r10 clearfix">
                <div class="col w20">
                    <input type="text" class="validate-numeric required smaller-size" value="<?php echo count($this->data) ? $this->data['fax_code'] : '';?>" name="contact[fax_code]" />
                </div>
                <div class="col w80">
                    <input type="text" class="validate-numeric required short-size" value="<?php echo count($this->data) ? $this->data['fax_number'] : '';?>" name="contact[fax_number]" />            
                </div>
            </div>
            <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>  
        </div>
    </div>    
    
    <div class="form-group">                
        <label><?php echo JText::_('COM_SFS_MOBILE'); ?></label>
        <div class="col w60">
            <div class="row r10 clearfix">
                <div class="col w20">
                    <input type="text" class="validate-numeric smaller-size" value="<?php echo count($this->data) ? $this->data['mobile_code'] : '';?>" name="contact[mobile_code]" />
                </div>
                <div class="col w80">
                    <input type="text" class="validate-numeric short-size" value="<?php echo count($this->data) ? $this->data['mobile_number'] : '';?>" name="contact[mobile_number]" />            
                </div>
            </div>
            <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
        </div>
    </div>    
</div>

