<?php
defined('_JEXEC') or die;

$billing = $this->airline->getBillingDetail();

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	// The elements used.
	var airlineRegisterForm = document.id('airlineRegisterForm');
	  // 	Labels over the inputs.
	airlineRegisterForm.getElements('[type=text], select').each(function(el){
    	new OverText(el);
	});
	// Validation.
	new Form.Validator(airlineRegisterForm); 
});
</script>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_AIRLINE_DATA');?></h3>
    </div>
</div>
<div id="sfs-wrapper" class="main">

<div id="airline-registration">
        
    <form id="airlineRegisterForm" name="airlineRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" class="form-validate">
        <div class="sfs-main-wrapper-none">        
	        <div class="sfs-orange-wrapper">
				<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
	            	<fieldset>                
						<div class="fieldset-title float-left">
		                    <span><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_YOUR_DETAILS');?></span> 
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
	                                                                                                                                                 
	                </fieldset>
	            </div>
	            
	            
	            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
	            	<fieldset>                
	                	
						<div class="fieldset-title float-left">
		                    <span><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_LOCAL_OFFICE_DETAILS');?></span> 
	                    </div>                      
	                    
	                    <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">	
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
	                            <div class="float-left">
	                            	<div style="margin-bottom:7px">
		                            	<input type="text" name="address" id="address" class="required" value="<?php echo $this->airline->address; ?>" size="40"  />
	                                </div>
	                                <input type="text" name="address2" id="address2" size="40" value="<?php echo $this->airline->address2; ?>" />
	                            </div>
	                        </div>     
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_CITY');?></label>
	                            <input type="text" name="city" id="city" class="required" value="<?php echo $this->airline->city; ?>" />
	                        </div>     
	                       
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_ZIP_CODE');?></label>
								<input type="text" name="zipcode" id="zipcode" class="required" value="<?php echo $this->airline->zipcode; ?>" />
	                        </div>     
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
	                            <?php echo SfsHelperField::getCountryField( 'country_id' , $this->airline->country_id ); ?>
	                        </div>     
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?></label>
	                            <div class="float-left">
	                                <input name="phone_code" type="text" class="validate-numeric required smaller-size" value="<?php echo SfsHelper::formatPhone( $this->airline->telephone , 1)?>" />&nbsp;<input type="text" name="phone_number" class="validate-numeric required short-size" value="<?php echo SfsHelper::formatPhone( $this->airline->telephone , 2)?>"  />
	                                <br />
	                                <div class="clear" style="padding:2px 0 2px;"><span class="field-note"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></span></div>                                 
	                            </div>
	                        </div>                                                                                                                                               	
	                    </div>
	                    
	                </fieldset>
	                
	                
	            	<fieldset>                
	                	
						<div class="fieldset-title float-left">
		                    <span><?php echo JText::_('COM_SFS_BILLING_DETAILS');?></span> 
	                    </div>                   
	                    
	                    <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_REGISTERED_NAME');?></label>
	                            <input type="text" name="billing[name]" id="billing_name"  class="required" value="<?php echo $billing->name; ?>" />
	                        </div>     
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_ADDRESS');?></label>
	                            <div class="float-left">
	                           		<div style="margin-bottom:7px;">
		                            	<input type="text" name="billing[address]" id="billing_address" size="40" class="required" value="<?php echo $billing->address; ?>"  />
	                                </div>    
									<input type="text" name="billing[address1]" id="billing_address1" size="40" value="<?php echo $billing->address1; ?>" />
	                            </div>
	                        </div>     
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_CITY');?></label>
	                            <input type="text" name="billing[city]" id="billing_city" class="required" value="<?php echo $billing->city; ?>" />
	                        </div>     
	                       
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_ZIP_CODE');?></label>
	                            <input type="text" name="billing[zipcode]" id="billing_zipcode" class="required" value="<?php echo $billing->zipcode; ?>" />
	                        </div>     
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_COUNTRY');?></label>
                                <?php echo SfsHelperField::getCountryField( 'billing[country_id]' , $billing->country_id ); ?>
	                        </div> 
	                        <div class="register-field clear floatbox">
	                            <label><?php echo JText::_('COM_SFS_TVA_NUMBER');?></label>
	                            <input type="text" name="billing[tva_number]" id="billing_tvanumber" class="required" value="<?php echo $billing->tva_number; ?>" />
	                        </div>                                                                                                                                                                         	
	                    </div>
	                    
	                </fieldset>
	                
	            </div>  
	            
            		<?php
                		if( $this->airline->grouptype==2 ) : 
                			echo $this->loadTemplate('airlinecomment');
                		else :
                			echo $this->loadTemplate('ghcomments');
                		endif;		
                	?>  
	            
	        </div>        
	            
        </div>        
    
        <div class="sfs-below-main">
  			<div class="s-button float-right">
	        	<input type="submit" class="validate s-button" value="<?php echo JText::_('JSAVE');?>">
            </div>           
        </div>
    	<input type="hidden" name="task" value="airlineprofile.save" />
    	<input type="hidden" name="option" value="com_sfs" />        
    	<input type="hidden" name="id" value="<?php echo $this->airline->id; ?>" />
    	<input type="hidden" name="billing_id" value="<?php echo $this->airline->billing_id;?>" />        
        
       	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />        
        <?php echo JHtml::_('form.token'); ?>            
    </form>
       
</div>


</div>