<?php
defined('_JEXEC') or die;?>
<div class="fieldset-title float-left">
    <span style="line-height:120%;" class="fs-16"><?php echo JText::_("COM_SFS_CONTACT_PERSONS");?></span> 
</div>                                      

<div class="fieldset-fields float-left fs-14" style="width:584px; padding-top:35px;">

	<?php
    foreach ($this->contacts as $contact) :
	
        if ( $contact->is_admin == 1 ) continue;
    ?>
        <div class="register-field clear floatbox">
            <div class="fbold fs-14">
            <?php
            if( $contact->contact_type==1 ){
                echo 'Hotel General Manager';
            }else if(  $contact->contact_type==2){
                echo 'Main Revenue contact';			
            }else  if( $contact->contact_type==3){
                echo 'Main Front Office contact';			
            }else {
                echo 'Other contact';			
            }
            ?>
            </div>       
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_JOB_TITLE');?>:</label>
            <?php echo $contact->job_title;?>
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_NAME');?>:</label>
            <?php echo $contact->name;?>
        </div>
        <div class="register-field clear floatbox">
            <label><?php echo JText::_('COM_SFS_SURNAME');?>:</label>
            <?php echo $contact->surname;?>
        </div>
        <div class="register-field clear floatbox">
            <label>Title:</label>
            <?php echo  $contact->gender ;?>              
        </div>
        <div class="register-field clear floatbox">
            <label>Email:</label><?php echo $contact->email;?>
        </div>        
        <div class="register-field clear floatbox">
            <label for=""><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE'); ?>:</label>
            <?php echo $contact->telephone;?>    
            
        </div>
        <div class="register-field clear floatbox">
            <label for=""><?php echo JText::_('COM_SFS_DIRECT_FAX'); ?>:</label>
            <?php echo $contact->fax;?>    
        </div>
        <div class="register-field clear floatbox">
            <label for=""><?php echo JText::_('COM_SFS_MOBILE'); ?>:</label>              
            <?php echo $contact->mobile;?>                                
        </div>         
    <?php endforeach; ?>

</div>