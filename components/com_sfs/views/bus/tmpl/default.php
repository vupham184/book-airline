<?php
defined('_JEXEC') or die;
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Company Data</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">

	<div class="sfs-main-wrapper-none">        
    <div class="sfs-orange-wrapper">
    
    	<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">      
            <div class="fieldset-title float-left fs-16">
                <span>Your details</span> 
            </div>                              
			<div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                <div class="register-field clear floatbox">
                    <label>Company name</label>
                    <?php echo $this->bus->name;?>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
                    <div class="float-left">
                        <?php echo $this->bus->address;?>                        
                    </div>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_CITY');?></label>
                    <?php echo $this->bus->city ;?>
                </div>            
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
                    <?php echo SfsHelperField::getCountryName( $this->bus->country_id ) ;?>  
                </div>  
                <div class="register-field clear floatbox">
                    <label>Direct office telephone</label>
                    <?php echo $this->bus->telephone;?>
                </div>
                <div class="register-field clear floatbox">
                    <label>Direct fax</label>
                    <?php echo $this->bus->fax;?>
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
                    <?php echo $this->bus->billing_name;?>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
                    <div class="float-left">
                        <?php echo $this->bus->billing_address;?>                        
                    </div>
                </div>     
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_CITY');?></label>
                    <?php echo $this->bus->billing_city ;?>
                </div>            
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
                    <?php echo SfsHelperField::getCountryName( $this->bus->billing_country_id ) ;?>  
                </div> 
                <div class="register-field clear floatbox">
                    <label><?php echo JText::_('COM_SFS_TVA_NUMBER');?></label>
                    <?php echo $this->bus->billing_tva_number;?>
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
                    	$emails = $this->bus->getNotifications('email');
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
                    	$faxes = $this->bus->getNotifications('fax');
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
                    	$mobiles = $this->bus->getNotifications('mobile');
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
    </div>    
    
    <div class="sfs-below-main">
    	<div class="s-button">
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
        </div>
        <div class="s-button float-right">    
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=edit&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_EDIT');?></a>
        </div>    
    </div>
	<div class="clear"></div>
	
	
</div>;