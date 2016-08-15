<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
$billing = $this->airline->getBillingDetail();
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_AIRLINE_DATA');?></h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">
<div id="airline-registration">
     
    <div class="sfs-main-wrapper">
        <div class="sfs-orange-wrapper">
            
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">      
            	<div class="fieldset-title float-left">
                	<span ><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_YOUR_DETAILS');?></span> 
                </div>                  
                <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                	<?php
                		if( $this->airline->grouptype==2 ) : 
                			echo $this->loadTemplate('airlinedetail');
                		else :
                			echo $this->loadTemplate('ghdetail');
                		endif;		
                	?>                                         
                </div>                                       
            </div>
            
            
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
                                
                    
                    <div class="fieldset-title float-left">
                        <span><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_LOCAL_OFFICE_DETAILS');?></span> 
                    </div>                      
                    
                    <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">	
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
                            <div class="float-left">
                                <div style="margin-bottom:7px">
                                    <?php echo $this->airline->address ;?><br />
                                    <?php echo $this->airline->address2 ;?>
                                </div>
                            </div>
                        </div>     
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_CITY');?></label>
                             <?php echo $this->airline->city ;?>
                        </div>     
                          
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_ZIP_CODE');?></label>
                            <?php echo $this->airline->zipcode ;?>
                        </div>     
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
                            <?php echo $this->airline->country_name ;?>
                        </div>     
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?></label>
                            <?php echo $this->airline->telephone  ;?>
                        </div>                                                                                                                                               	
                    </div>
                    
                	                
            </div>    
            
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
                                
                                        
                    <div class="fieldset-title float-left">
                        <span><?php echo JText::_('COM_SFS_BILLING_DETAILS');?></span> 
                    </div>                   
                    
                    <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_REGISTERED_NAME');?></label>
                            <?php echo $billing->name   ;?>
                        </div>     
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
                            <div class="float-left">
                                <?php echo $billing->address    ;?> <br />
                                <?php echo $billing->address1    ;?>    
                            </div>
                        </div>     
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_CITY');?></label>
                            <?php echo $billing->city ;?>
                        </div>     
                    
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_ZIP_CODE');?></label>
                            <?php echo $billing->zipcode ;?>  
                        </div>     
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
                            <?php echo SfsHelperField::getCountryName( $billing->country_id ) ;?>  
                        </div> 
                        <div class="register-field clear floatbox">
                            <label><?php echo JText::_('COM_SFS_TVA_NUMBER');?></label>
                            <?php echo $billing->tva_number;?>
                        </div>                                                                                                                                                                         	
                    </div>
                    
                
            </div>
            
            <?php
            if( $this->airline->grouptype==2 ) :           
            ?>
            <div class="sfs-white-wrapper floatbox" >      
            	<div class="fieldset-title float-left">
            		<span>General voucher comment</span> 
                </div>                  
                <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                	<?php echo $this->airline->getVoucherComment();?>                                         
                </div>                                       
            </div>
            <?php else : ?>
            	<?php echo $this->loadTemplate('ghcomment');?>
            <?php endif;?>
            
            
        </div>        
            
    </div>        

    <div class="sfs-below-main">
    	<div class="s-button">
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
        </div>
        <div class="s-button float-right">    
	        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=edit&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_EDIT');?></a>
        </div>    
    </div>
	<div class="clear"></div>

</div>
</div>