<?php
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
?>
<div id="sfs-wrapper" class="fs-14">
    <div class="heading-block clearfix">
        <div class="heading-block-wrap">
            <h3><?php echo JText::_('COM_SFS_AIRLINE_CONTACT_PERSONS');?></h3>
        </div>
    </div>
    <div class="main airlineprofile-contacts airline-contact-details-top">
    	<div class="contact-block">
	    	<div class="fs-16 midmarginbottom">
	    		<h3><?php echo JText::_('COM_SFS_AIRLINE_CONTACT_DETAILS');?></h3>
	    	</div>
	        <div>
		       	<?php echo JText::_('COM_SFS_AIRLINE_CONTACT_DETAILS_DESC');?>
	        </div>
   		</div>
        <div class="sfs-orange-wrapper">
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
            	<?php echo JText::_('COM_SFS_AIRLINE_CONTACT_PERSONS_DESC');?>
            </div>
            
            <?php
            foreach ( $this->contacts as $contact )  : ?>
            
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
                       
                <fieldset>
                    <div class="fieldset-title float-left">
                        <div class="fs-16">
                        <?php echo ( $contact->is_admin == 1  ) ? 'Main contact' : 'Your team members' ; ?>
                        </div>
                    </div>
                    
                    <div class="fieldset-fields float-left">
                    
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_JOB_TITLE');?>:</label>
                            <?php echo $contact->job_title;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>
                            <?php echo $contact->name;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_SURNAME');?>:</label>
                            <?php echo $contact->surname;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_GENDER')?></label>
                            <?php echo $contact->gender;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_EMAIL')?></label>
                            <?php echo $contact->email;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:</label>
                            <?php echo $contact->telephone;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_DIRECT_FAX')?>:</label>
                             <?php echo $contact->fax;?>
                        </div>
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_MOBILE')?>:</label>
                             <?php echo $contact->mobile;?>
                        </div>
                                                                                                                                                                                                                                                 
                    </div>
                </fieldset>
            </div>
            <?php endforeach; ?>
            
        </div>
    </div>


	<div class="floatbox sfs-below-main">
    	<div class="s-button">
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
        </div>
    	<div class="s-button float-right">
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=editcontacts&Itemid='.JRequest::getInt('Itemid'));?>" class="s-button">
            	<?php echo JText::_('COM_SFS_EDIT')?>
            </a>
        </div>
	</div>


</div>
