<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<script type="text/javascript">
<!--
window.addEvent('domready', function(){
	$('copy_address').addEvent('click',function(){
		
		$('billing_name').set('value', $('company').get('value') );
		$('billing_address').set('value', $('address').get('value') );
		$('billing_city').set('value', $('city').get('value') );
		$('billing_zipcode').set('value', $('zipcode').get('value') );
		$('billing_phone_code').set('value', $('phone_code').get('value') );
		$('billing_phone_number').set('value', $('phone_number').get('value') );
		$('billing_fax_code').set('value', $('fax_code').get('value') );
		$('billing_fax_number').set('value', $('fax_number').get('value') );
		
		var country = $('taxidetailscountry_id').getSelected().get('value');
		for (var i = 0; i < $('taxidetailsbilling_country_id').options.length; i++)
		if ($('taxidetailsbilling_country_id').options[ i ].value == country) {
			$('taxidetailsbilling_country_id').options[ i ].selected='selected';
			break;
		}
	});
});
//-->
</script>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Taxi Sign up</h3>        
    </div>
</div>
<div class="main">
	<div class="info-block bg light-blue">
	    <?php echo SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_01'));	?>
	</div>
	<form id="taxiRegisterForm" name="taxiRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=taxiregister');?>"	method="post" class="form-validate sfs-form form-vertical register-form">
	    <div class="block-group">	        
            <div class="block border orange">
                <fieldset>
				    <legend><span class="text_legend">Your details</span></legend>
				    <div class="col w80 pull-left p20">
				        <div class="form-group">
				            <label>Company name</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[name]" id="company" value="<?php echo !empty($this->taxidetails['name']) ? $this->taxidetails['name']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Address</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[address]" id="address" value="<?php echo !empty($this->taxidetails['address']) ? $this->taxidetails['address']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>City</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[city]" id="city" value="<?php echo !empty($this->taxidetails['city']) ? $this->taxidetails['city']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Zipcode</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[zipcode]" id="zipcode" value="<?php echo !empty($this->taxidetails['zipcode']) ? $this->taxidetails['zipcode']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Country</label>
				            <div class="col w60">
				            	<?php echo $this->options['country_id']; ?>
				            </div>
				        </div>
				        
				        <div class="form-group">
							<label>Direct office telephone</label>
							<div class="col w60">
						        <div class="input-group">
						       		<input type="text" class="validate-numeric required smaller-size" name="taxidetails[phone_code]" id="phone_code" value="<?php echo !empty($this->taxidetails['phone_code']) ? $this->taxidetails['phone_code']:''?>" />						        
									<input type="text" class="validate-numeric required short-size" name="taxidetails[phone_number]" id="phone_number" value="<?php echo !empty($this->taxidetails['phone_number']) ? $this->taxidetails['phone_number']:''?>" />
						        </div>
						        <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
					        </div>
						</div>
						
						<div class="form-group">
							<label>Direct fax</label>
							<div class="col w60">
						        <div class="input-group">
						       		<input type="text" class="validate-numeric required smaller-size" name="taxidetails[fax_code]" id="fax_code" value="<?php echo !empty($this->taxidetails['fax_code']) ? $this->taxidetails['fax_code']:''?>" />						        
									<input type="text" class="validate-numeric required short-size" name="taxidetails[fax_number]" id="fax_number" value="<?php echo !empty($this->taxidetails['fax_number']) ? $this->taxidetails['fax_number']:''?>" />
						        </div>
						        <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
					        </div>
						</div>
						
						<div class="form-group">
							<label>Mobile</label>
							<div class="col w60">
						        <div class="input-group">
						       		<input type="text" class="validate-numeric required smaller-size" name="taxidetails[mobile_code]" value="<?php echo !empty($this->taxidetails['mobile_code']) ? $this->taxidetails['mobile_code']:''?>" />						        
									<input type="text" class="validate-numeric required short-size" name="taxidetails[mobile_number]" value="<?php echo !empty($this->taxidetails['mobile_number']) ? $this->taxidetails['mobile_number']:''?>" />
						        </div>
						        <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
					        </div>
						</div>
						
						<div class="form-group">				            				            
				            <a href="javascript: void(0)" class="btn orange lg" id="copy_address"><span><?php echo JText::_('COM_SFS_COPY_ADDRESS')?></span></a>				            
				        </div>				            
				    </div>
				</fieldset>				    
            </div>

            <div class="block border orange">
                <fieldset>
				    <legend><span class="text_legend">Billing details</span></legend>
				    <div class="col w80 pull-left p20">
				        <div class="form-group">
				            <label>Name of head of accounting</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[billing_registed_name]" id="billing_name" value="<?php echo !empty($this->taxidetails['billing_registed_name']) ? $this->taxidetails['billing_registed_name']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Address</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[billing_address]" id="billing_address" value="<?php echo !empty($this->taxidetails['billing_address']) ? $this->taxidetails['billing_address']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>City</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[billing_city]" id="billing_city" value="<?php echo !empty($this->taxidetails['billing_city']) ? $this->taxidetails['billing_city']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Zipcode</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[billing_zipcode]" id="billing_zipcode" value="<?php echo !empty($this->taxidetails['billing_zipcode']) ? $this->taxidetails['billing_zipcode']:''?>" />
				            </div>
				        </div>
				        
				        
				        <div class="form-group">
				            <label>Country</label>
				            <div class="col w60">
				            	<?php echo $this->options['billing_country_id']; ?>
				            </div>
				        </div>
				        
				        <div class="form-group">
							<label>Telephone</label>
							<div class="col w60">
						        <div class="input-group">
						       		<input type="text" class="validate-numeric required smaller-size" name="taxidetails[billing_phone_code]" id="billing_phone_code" value="<?php echo !empty($this->taxidetails['billing_phone_code']) ? $this->taxidetails['billing_phone_code']:''?>" />						        
									<input type="text" class="validate-numeric required short-size" name="taxidetails[billing_phone_number]" id="billing_phone_number"  value="<?php echo !empty($this->taxidetails['billing_phone_number']) ? $this->taxidetails['billing_phone_number']:''?>" />
						        </div>
						        <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
					        </div>
						</div>
						
						<div class="form-group">
							<label>Fax</label>
							<div class="col w60">
						        <div class="input-group">
						       		<input type="text" class="validate-numeric required smaller-size" name="taxidetails[billing_fax_code]" id="billing_fax_code" value="<?php echo !empty($this->taxidetails['billing_fax_code']) ? $this->taxidetails['billing_fax_code']:''?>" />						        
									<input type="text" class="validate-numeric required short-size" name="taxidetails[billing_fax_number]" id="billing_fax_number" value="<?php echo !empty($this->taxidetails['billing_fax_number']) ? $this->taxidetails['billing_fax_number']:''?>" />
						        </div>
						        <small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
					        </div>
						</div>
						<div class="form-group">
				            <label>VAT number</label>
				            <div class="col w60">
				            	<input type="text" name="taxidetails[billing_vat_number]" id="billing_tva_number" value="<?php echo !empty($this->taxidetails['billing_vat_number']) ? $this->taxidetails['billing_vat_number']:''?>" />
				            </div>
				        </div>
				    </div>
				</fieldset>
            </div>

            <div class="block border orange">
				<fieldset>
				    <legend><span class="text_legend">Account details</span></legend>

				    <div class="col w80 pull-left p20">
				        <div class="form-group">
				            <label>First name</label>
				            <div class="col w60">
				            	<input type="text" name="account[first_name]" id="first_name" class="required" value="<?php echo !empty($this->accountdetails['first_name']) ? $this->accountdetails['first_name']:''?>" />
				            </div>
				        </div>
				        
				         <div class="form-group">
				            <label>Last name</label>
				            <div class="col w60">
				            	<input type="text" name="account[last_name]" id="last_name"class="required"  value="<?php echo !empty($this->accountdetails['last_name']) ? $this->accountdetails['last_name']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Email</label>
				            <div class="col w60">
				            	<input type="text" name="account[email]" id="email" class="validate-email required" value="<?php echo !empty($this->accountdetails['email']) ? $this->accountdetails['email']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Username</label>
				            <div class="col w60">
				            	<input type="text" name="account[username]" id="username" class="required" value="<?php echo !empty($this->accountdetails['username']) ? $this->accountdetails['username']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Password</label>
				            <div class="col w60">
				            	<input class="validate-password required" id="password" name="account[password]" type="password" value="">
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Retype Password </label>
				            <div class="col w60">
				            	<input class="required validate-match matchInput:'password'" name="account[password2]" type="password" value="">
				            </div>
				        </div>
				    </div>
				</fieldset>
            </div>	        
	    </div>
	    <div class="form-group">	        	           
	        <button type="submit" class="validate btn orange lg pull-right"><?php echo JText::_('COM_SFS_SAVE')?></button>	              
	    </div>
	    <input type="hidden" name="task" value="taxiregister.validate" />
	    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
	    <?php echo JHtml::_('form.token'); ?>
	</form>
	
</div>