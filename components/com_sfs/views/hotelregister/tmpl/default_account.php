<?php
defined('_JEXEC') or die;
?>
<legend><span class="text_legend"><?php echo JText::_('COM_SFS_ACCOUNT_INFO'); ?></span></legend>
<div class="col w80 pull-left p20">

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_PREFERRED_USERNAME'); ?>
        </label>
        <div class="col w60">
            <input type="text" size="30" class="validate-username required<?php echo count($this->data) && empty($this->data['username']) ? ' validation-failed' : '';?>" value="<?php echo count($this->data) ? $this->data['username'] : '';?>" id="contact_username" name="contact[username]" />
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_PASSWORD'); ?>
        </label>
        <div class="col w60">
            <input type="password" size="30" class="validate-password required" value="" id="contact_password" name="contact[password]" />
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_RETTYPE_PASSWORD');?>
        </label> 
        <div class="col w60">       
            <input type="password" size="30" class="required validate-match matchInput:'contact_password'" name="contact[password2]" />        
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_EMAIL1'); ?>
        </label>
        <div class="col w60">
            <input type="text" size="30" class="validate-email required<?php echo count($this->data) && empty($this->data['email']) ? ' validation-failed' : '';?>" value="<?php echo count($this->data) ? $this->data['email'] : '';?>" id="contact_email" name="contact[email]" />
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::_('COM_SFS_EMAIL2'); ?>
        </label>
        <div class="col w60">
            <input type="text" size="30" class="required validate-match matchInput:'contact_email'" value="<?php echo count($this->data) ? $this->data['email2'] : '';?>" id="contact_email2" name="contact[email2]" />
        </div>
    </div>
</div>	

