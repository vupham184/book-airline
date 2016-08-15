<?php
defined('_JEXEC') or die;
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Company Data</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">

    <div class="sfs-orange-wrapper">
    
    	<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">      
            <div class="fieldset-title float-left fs-16">
                <span>Your details</span> 
            </div>                              
			<div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                <div class="register-field clear floatbox">
                    <label>Company name</label>
                    <?php echo $this->taxi->name;?>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
                    <div class="float-left">
                        <?php echo $this->taxi->address;?>                        
                    </div>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_CITY');?></label>
                    <?php echo $this->taxi->city ;?>
                </div>            
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
                    <?php echo SfsHelperField::getCountryName( $this->taxi->country_id ) ;?>  
                </div>  
                <div class="register-field clear floatbox">
                    <label>Direct office telephone</label>
                    <?php echo $this->taxi->telephone;?>
                </div>
                <div class="register-field clear floatbox">
                    <label>Direct fax</label>
                    <?php echo $this->taxi->fax;?>
                </div>                                                                    
            </div>                                                                                                          
        </div>
        
        <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">      
            <div class="fieldset-title float-left fs-16">
                <span>Billing details</span> 
            </div>                  
            <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                <div class="register-field clear floatbox">
                    <label>Name of head of accounting</label>
                    <?php echo $this->taxi->billing_registed_name;?>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
                    <div class="float-left">
                        <?php echo $this->taxi->billing_address;?>                        
                    </div>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_CITY');?></label>
                    <?php echo $this->taxi->billing_city ;?>
                </div>            
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
                    <?php echo SfsHelperField::getCountryName( $this->taxi->billing_country_id ) ;?>  
                </div> 
                <div class="register-field clear floatbox">
                    <label>Telephone</label>
                    <?php echo $this->taxi->billing_telephone;?>
                </div>
                <div class="register-field clear floatbox">
                    <label>Fax</label>
                    <?php echo $this->taxi->billing_fax;?>
                </div>    
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_TVA_NUMBER');?></label>
                    <?php echo $this->taxi->billing_vat_number;?>
                </div>                                                         
            </div>                                       
        </div>
        
        <div class="sfs-white-wrapper floatbox">      
            <div class="fieldset-title float-left fs-16">
                <span>Booking notifications</span>                                  
            </div> 
            <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
            	<div class="register-field clear floatbox">
                    <label>Email</label>      
                    <div class="float-left">                
                    <?php
                    	$emails = $this->taxi->getNotifications('email');
                    	if(count($emails))
                    	{
                    		echo implode('<br />', $emails);
                    	} 
                    ?>
                    </div>
                </div>
                <div class="register-field clear floatbox">
                    <label>Fax</label>
                    <div class="float-left">
                    <?php
                    	$faxes = $this->taxi->getNotifications('fax');
                    	if(count($faxes))
                    	{
                    		echo implode('<br />', $faxes);
                    	} 
                    ?>
                    </div>
                </div>  
                <div class="register-field clear floatbox">
                    <label>24H Mobile number</label>
                    <div class="float-left">
                    <?php
                    	$mobiles = $this->taxi->getNotifications('mobile');
                    	if(count($mobiles))
                    	{
                    		echo implode('<br />', $mobiles);
                    	} 
                    ?>
                    </div>
                </div>  
           	</div> 
        </div>    
    	
    </div>
    
    <div class="sfs-below-main">
    	<div class="s-button">
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
        </div>
        <div class="s-button float-right">    
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiprofile&layout=edit&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_EDIT');?></a>
        </div>    
    </div>
	<div class="clear"></div>
	
	
</div>