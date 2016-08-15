<?php
defined('_JEXEC') or die;
$session = JFactory::getSession();
$tmpHotelContact = $session->get('tmpHotelContact');

if( isset($this->contacts) && count($this->contacts) ) {
	$order = count($this->contacts);
} else{
	$order = 3;	
}

foreach ($tmpHotelContact as $contact) :
$order++; 
?>		
<legend><span class="text_legend">
<?php echo JText::_('COM_SFS_ADDITIONAL_CONTACT');?></span>
</legend>

<div class="col w80 pull-left p20">
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_JOB_TITLE');?>:</label>
        <div class="col w60">
            <?php
                $job_title = $contact['job_title'];                     
            ?>
            <input type="text" name="contacts[<?php echo $order;?>][job_title]" value="<?php echo $job_title;?>" />
        </div>        
    </div>
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_NAME');?>:</label>
        <div class="col w60">           
            <input type="text" value="<?php echo $contact['name'];?>" name="contacts[<?php echo $order;?>][name]" class="required"  />
        </div>        
    </div>
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_SURNAME');?>:</label>
        <div class="col w60">           
            <input type="text" value="<?php echo $contact['surname'];?>" name="contacts[<?php echo $order;?>][surname]" class="required" />
        </div>        
    </div>
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_TITLE');?>:</label>
        <div class="col w60">  
            <select size="1" class="inputbox" name="contacts[<?php echo $order;?>][gender]">
                <option value="<?php echo JText::_('COM_SFS_GENDER_MR');?>"><?php echo JText::_('COM_SFS_GENDER_MR');?></option>
                <option value="<?php echo JText::_('COM_SFS_GENDER_MRS');?>"><?php echo JText::_('COM_SFS_GENDER_MRS');?></option>
                <option value="<?php echo JText::_('COM_SFS_GENDER_MS');?>"><?php echo JText::_('COM_SFS_GENDER_MS');?></option>
            </select>  
        </div>            
    </div>
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_EMAIL');?>:</label>
        <div class="col w60">           
            <input type="text" value="<?php echo $contact['email'];?>" name="contacts[<?php echo $order;?>][email]" class="required validate-email" />
        </div>        
    </div>
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE'); ?>:</label>
        <div class="col w60">  
            <div class="row r10 clearfix">
                <div class="col w20">
                    <input type="text" class="validate-numeric required smaller-size" value="<?php echo SfsHelper::formatPhone($contact['telephone'],1);?>" name="contacts[<?php echo $order;?>][tel_int]" />                   
                </div>
                <div class="col w80">
                    <input type="text" class="validate-numeric required short-size" value="<?php echo SfsHelper::formatPhone($contact['telephone'],2);?>" name="contacts[<?php echo $order;?>][tel_num]">
                </div>                
            </div>  
            <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>                   
        </div>        
    </div>
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_DIRECT_FAX'); ?>:</label>
        <div class="col w60"> 
            <div class="row r10 clearfix">
                <div class="col w20">
                    <input type="text" class="validate-numeric smaller-size" value="<?php echo SfsHelper::formatPhone($contact['fax'],1);?>" name="contacts[<?php echo $order;?>][fax_int]">                              
                </div>
                <div class="col w80">
                    <input type="text" class="validate-numeric short-size" value="<?php echo SfsHelper::formatPhone($contact['fax'],2);?>" name="contacts[<?php echo $order;?>][fax_num]">  
                </div>                
            </div>  
            <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>                      
        </div>        
    </div>
    <div class="form-group">
        <label><?php echo JText::_('COM_SFS_MOBILE'); ?>:</label>
        <div class="col w60"> 
            <div class="row r10 clearfix">
                <div class="col w20">
                    <input type="text" class="validate-numeric smaller-size" value="<?php echo SfsHelper::formatPhone($contact['mobile'],1);?>" name="contacts[<?php echo $order;?>][mobile_int]">                              
                </div>
                <div class="col w80">
                    <input type="text" class="validate-numeric short-size" value="<?php echo SfsHelper::formatPhone($contact['mobile'],2);?>" name="contacts[<?php echo $order;?>][mobile_num]">
                </div>                
            </div>  
            <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>                       
        </div>        
    </div>
</div>

<input type="hidden" name="contacts[<?php echo $order;?>][contact_type]" value="4" />
<input type="hidden" value="0" name="contacts[<?php echo $order;?>][user_id]">
<input type="hidden" value="0" name="contacts[<?php echo $order;?>][id]">
<?php endforeach;?>