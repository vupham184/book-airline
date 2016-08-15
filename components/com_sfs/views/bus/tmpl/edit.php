<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>

<script type="text/javascript">
<!--
window.addEvent('domready', function(){

	$('addEmailField').addEvent('click', function(e){
		e.stop();
		var inputEmailField = new Element('input', {
		    type: 'text',		
		    name: 'notifications[email][]'		    		   
		});
		inputEmailField.inject( $('notification-emails') );
	});
	
	$('addFaxField').addEvent('click', function(e){
		e.stop();
		var inputFaxField = new Element('input', {
		    type: 'text',		
		    name: 'notifications[fax][]'		    		   
		});
		inputFaxField.inject( $('notification-faxes') );
	});
	
	$('addMobileField').addEvent('click', function(e){
		e.stop();
		var inputMobileField = new Element('input', {
		    type: 'text',		
		    name: 'notifications[mobile][]'		    		   
		});
		inputMobileField.inject( $('notification-mobiles') );
	});
	
});
//-->
</script>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Company Data</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">

<form id="busEditForm" name="busEditForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=edit');?>"	method="post" class="form-validate">
<div class="sfs-main-wrapper-none">
<div class="sfs-orange-wrapper">

    <div class="sfs-white-wrapper floatbox" style="margin-bottom: 25px;">
        <fieldset>                
            <div class="fieldset-title float-left fs-16">
                <span>Your details</span> 
            </div>
            <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                <div class="register-field clear floatbox">
                    <label>Company name</label>
                    <input type="text" name="busdetails[name]" id="company" value="<?php echo !empty($this->bus->name) ? $this->bus->name : ''?>" />					            
                </div> 
                
                <div class="register-field clear floatbox">
                    <label>Address</label>
                    <input type="text" name="busdetails[address]" id="address" value="<?php echo !empty($this->bus->address) ? $this->bus->address:''?>" />					            
                </div> 
                
                <div class="register-field clear floatbox">
                    <label>City</label>
                    <input type="text" name="busdetails[city]" id="city" value="<?php echo !empty($this->bus->city) ? $this->bus->city:''?>" />					            
                </div>
                
                <div class="register-field clear floatbox">
                    <label>Country</label>
                    <?php echo $this->options['country_id']; ?>
                    <?php echo SfsHelperField::getCountryField( 'busdetails[country_id]' , $this->bus->country_id ); ?>
                </div>
                
                <div class="register-field clear floatbox">
                    <label>Direct office telephone</label>
                    <input type="text" name="busdetails[telephone]" id="telephone" value="<?php echo !empty($this->bus->telephone) ? $this->bus->telephone:''?>" />
                </div>
                
                <div class="register-field clear floatbox">
                    <label>Direct fax</label>
                    <input type="text" name="busdetails[fax]" id="fax" value="<?php echo !empty($this->bus->fax) ? $this->bus->fax:''?>" />
                </div>                
            </div> 
        </fieldset>             
    </div>
    
    <div class="sfs-white-wrapper floatbox"	style="margin-bottom: 25px;">
        <fieldset>                
            <div class="fieldset-title float-left fs-16">
                <span>Billing details</span> 
            </div>
            <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                <div class="register-field clear floatbox">
                    <label>Name of head of accounting</label>
                    <input type="text" name="busdetails[billing_name]" id="billing_name" value="<?php echo !empty($this->bus->billing_name) ? $this->bus->billing_name:''?>" />					            
                </div> 
                
                <div class="register-field clear floatbox">
                    <label>Address</label>
                    <input type="text" name="busdetails[billing_address]" id="billing_address" value="<?php echo !empty($this->bus->billing_address) ? $this->bus->billing_address:''?>" />					            
                </div> 
                
                <div class="register-field clear floatbox">
                    <label>City</label>
                    <input type="text" name="busdetails[billing_city]" id="billing_city" value="<?php echo !empty($this->bus->billing_city) ? $this->bus->billing_city:''?>" />					            
                </div>
                
                <div class="register-field clear floatbox">
                    <label>Country</label>
                    <?php echo SfsHelperField::getCountryField( 'busdetails[billing_country_id]' , $this->bus->country_id ); ?>					            
                </div>
                
                <div class="register-field clear floatbox">
                    <label>Telephone</label>
                    <input type="text" name="busdetails[billing_telephone]" id="billing_telephone" value="<?php echo !empty($this->bus->billing_telephone) ? $this->bus->billing_telephone:''?>" />
                </div>
                
                <div class="register-field clear floatbox">
                    <label>Fax</label>
                    <input type="text" name="busdetails[billing_fax]" id="billing_fax" value="<?php echo !empty($this->bus->billing_fax) ? $this->bus->billing_fax:''?>" />
                </div>	
                <div class="register-field clear floatbox">
                    <label>VAT number</label>
                    <input type="text" name="busdetails[billing_tva_number]" id="billing_tva_number" value="<?php echo !empty($this->bus->billing_tva_number) ? $this->bus->billing_tva_number:''?>" />					            
                </div>				            
            </div> 
        </fieldset>  
    </div>
    
	    
	<div class="sfs-white-wrapper floatbox">      
	    <div class="fieldset-title float-left fs-16">
	        <span>Booking notifications</span>                                  
	    </div> 
	    <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
	        <div class="register-field bus-register-field clear floatbox">
	            <label>Email</label>       
	            <div id="notification-emails" class="float-left">             
	            <?php
	                $emails = $this->bus->getNotifications('email');
	                if(count($emails)) :
	                foreach ($emails as $email):
	                ?>      	                	        
	                	<input type="text" name="notifications[email][]" value="<?php echo $email?>" />		                	                   
	                <?php     
	                endforeach;    
	                else : ?>		               
		            	<input type="text" name="notifications[email][]" value="" />		                		
	                <?php 
	                endif;	 
	            ?>	 	            	
	            </div>   
	            	            
	            <div class="float-left mid-button" style="margin-left:10px;">
	            	<button type="button" id="addEmailField" style="text-indent: 22px;">Add Email</button>  
	            </div>        
	        </div>
	        <div class="register-field bus-register-field clear floatbox">
	            <label>Fax</label>	
	            <div id="notification-faxes" class="float-left">              
	            <?php
	                $faxes = $this->bus->getNotifications('fax');
	                if(count($faxes)) :
	                foreach ($faxes as $fax):
	                ?>                	
	                	<input type="text" name="notifications[fax][]" value="<?php echo $fax?>" />	                    
	                <?php     
	                endforeach;    
	                else : ?>
	                	<input type="text" name="notifications[fax][]" value="" />	 
	                <?php 
	                endif;	  
	            ?>
	            </div>
	            <div class="float-left mid-button" style="margin-left:10px;">
	            	<button type="button" id="addFaxField" style="text-indent: 22px;">Add Fax</button>  
	            </div>        
	        </div>  
	        <div class="register-field bus-register-field clear floatbox">
	            <label>24H Mobile number</label>
	            <div id="notification-mobiles" class="float-left"> 
	            <?php
	                $mobiles = $this->bus->getNotifications('mobile');
	                if(count($mobiles)) :
	                foreach ($mobiles as $mobile):
	                ?>                	
	                	<input type="text" name="notifications[mobile][]" value="<?php echo $mobile?>" />	                    
	                <?php     
	                endforeach;    
	                else : ?>
	                	<input type="text" name="notifications[mobile][]" value="" />	 
	                <?php 
	                endif;	                 
	            ?>
	            </div>
	            <div class="float-left mid-button" style="margin-left:10px;">
	            	<button type="button" id="addMobileField" style="text-indent: 22px;">Add Mobile</button>  
	            </div>        
	        </div>  
	    </div> 
	</div>     
    
    
</div>
</div>

<div class="sfs-below-main">
    <div class="s-button float-right">
        <input type="submit" class="validate s-button" value="<?php echo JText::_('JSAVE');?>">
    </div>           
</div>

<input type="hidden" name="task" value="bus.save" />
<input type="hidden" name="option" value="com_sfs" />        
<input type="hidden" name="id" value="<?php echo $this->bus->id; ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        
<?php echo JHtml::_('form.token'); ?> 

</form>
	
</div>