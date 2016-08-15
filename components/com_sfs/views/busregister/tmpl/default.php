<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$listCur = SfsHelper::getCurrencyMulti();
$listAirport = SfsHelper::getListAirport();

?>
<script type="text/javascript">
<!--
window.addEvent('domready', function(){
	$('copy_address').addEvent('click',function(){
		
		$('billing_name').set('value', $('company').get('value') );
		$('billing_address').set('value', $('address').get('value') );
		$('billing_city').set('value', $('city').get('value') );

		$('billing_phone_code').set('value', $('phone_code').get('value') );
		$('billing_phone_number').set('value', $('phone_number').get('value') );
		$('billing_fax_code').set('value', $('fax_code').get('value') );
		$('billing_fax_number').set('value', $('fax_number').get('value') );
		
		var country = $('busdetailscountry_id').getSelected().get('value');
		for (var i = 0; i < $('busdetailsbilling_country_id').options.length; i++)
		if ($('busdetailsbilling_country_id').options[ i ].value == country) {
			$('busdetailsbilling_country_id').options[ i ].selected='selected';
			break;
		}
	});
});
//-->
</script>
<style type="text/css">
.phone_first{
	float: left; width: 120px;
}
.phone_second{
	float: left; width:227px; margin-left: 10px;
}
.ex{float:left; width: 140px; margin-top: -20px;}
</style>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Bus Sign up</h3>        
    </div>
</div>
<div class="main">
	<div class="info-block bg light-blue">
	    <?php echo SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_01'));	?>
	</div>
	
	<form id="busRegisterForm" name="busRegisterForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=busregister');?>"	method="post" class="form-validate sfs-form form-vertical register-form">
	    <div class="block-group">	        
            <div class="block border orange">
                <fieldset>
				    <legend><span class="text_legend">Your details</span></legend>

				    <div class="col w80 pull-left p20">
				        <div class="form-group">
				            <label>Company name</label>
				            <div class="col w60">
				            	<input type="text" name="busdetails[name]" id="company" value="<?php echo !empty($this->busdetails['name']) ? $this->busdetails['name']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Address</label>
				            <div class="col w60">
				            	<input type="text" name="busdetails[address]" id="address" value="<?php echo !empty($this->busdetails['address']) ? $this->busdetails['address']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>City</label>
				            <div class="col w60">
				            	<input type="text" name="busdetails[city]" id="city" value="<?php echo !empty($this->busdetails['city']) ? $this->busdetails['city']:''?>" />
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
								<div class="input-group" style="float: left;">
							       	<input type="text" class="validate-numeric required smaller-size phone_first" name="busdetails[phone_code]" id="phone_code" value="<?php echo !empty($this->busdetails['phone_code']) ? $this->busdetails['phone_code']:''?>" />
									<input type="text" class="validate-numeric required short-size phone_second" name="busdetails[phone_number]" id="phone_number" value="<?php echo !empty($this->busdetails['phone_number']) ? $this->busdetails['phone_number']:''?>"  />
								</div>
								<span class="ex">
						        	<small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
						        </span>
					        </div>
						</div>
						
						<div class="form-group">
							<label>Direct fax</label>
							<div class="col w60">
						        <div class="input-group" style="float: left;">
						       		<input type="text" class="validate-numeric required smaller-size phone_first" name="busdetails[fax_code]" id="fax_code" value="<?php echo !empty($this->busdetails['fax_code']) ? $this->busdetails['fax_code']:''?>" />						        						    
									<input type="text" class="validate-numeric required short-size phone_second" name="busdetails[fax_number]" id="fax_number" value="<?php echo !empty($this->busdetails['fax_number']) ? $this->busdetails['fax_number']:''?>" />
						        </div>
						        <span class="ex">
					        		<small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
					        	</span>
					        </div>
						</div>
						
						<div class="form-group">
							<label>Mobile</label>
							<div class="col w60">
						        <div class="input-group" style="float: left">
						       		<input type="text" class="validate-numeric required smaller-size phone_first" name="busdetails[mobile_code]" value="<?php echo !empty($this->busdetails['mobile_code']) ? $this->busdetails['mobile_code']:''?>" />						        
									<input type="text" class="validate-numeric required short-size phone_second" name="busdetails[mobile_number]" value="<?php echo !empty($this->busdetails['mobile_number']) ? $this->busdetails['mobile_number']:''?>" />
						        </div>
						        <span class="ex">
						        	<small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
						        </span>
					        </div>
						</div>
						
						<div class="form-group">				            
				            <div class="col w60 pull-left p40">
				                <a href="javascript: void(0)" class="btn orange lg" id="copy_address">
				                	<span><?php echo JText::_('COM_SFS_COPY_ADDRESS')?></span>
				                </a>
				            </div>
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
				            	<input type="text" name="busdetails[billing_name]" id="billing_name" value="<?php echo !empty($this->busdetails['billing_name']) ? $this->busdetails['billing_name']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Address</label>
				            <div class="col w60">
				            	<input type="text" name="busdetails[billing_address]" id="billing_address" value="<?php echo !empty($this->busdetails['billing_address']) ? $this->busdetails['billing_address']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>City</label>
				            <div class="col w60">
				            	<input type="text" name="busdetails[billing_city]" id="billing_city" value="<?php echo !empty($this->busdetails['billing_city']) ? $this->busdetails['billing_city']:''?>" />
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Country</label>
				            <div class="col w60">
				            	<?php echo $this->options['billing_country_id']; ?>
				            </div>
				        </div>
				        <div class="form-group">
				            <label>Currency</label>
				            <div class="col w60">
				            	<select style="width: 360px;" name="busdetails[currency]">
			                        <option value="0">-- Choose currency --</option>
			                        <?php 

			                            foreach ($listCur as $value) {
			                                if($this->hotel->currency_id == 0){
			                                    if($value->id == 2){
			                                        echo "<option value='" .$value->id. "' selected='selected'>";
			                                        echo $value->name ." (". $value->code . ")";
			                                        echo "</option>";
			                                    }else{
			                                        echo "<option value='" .$value->id. "'>";
			                                        echo $value->name ." (". $value->code . ")";
			                                        echo "</option>";
			                                    }
			                                }else{
			                                    if($value->id == $this->hotel->currency_id){
			                                        echo "<option value='" .$value->id. "' selected='selected'>";
			                                        echo $value->name ." (". $value->code . ")";
			                                        echo "</option>";
			                                    }else{
			                                        echo "<option value='" .$value->id. "'>";
			                                        echo $value->name ." (". $value->code . ")";
			                                        echo "</option>";
			                                    }
			                                }                            		                            
			                            }
			                        ?> 
			                    </select>
				            </div>
				        </div>
				        <div class="form-group">
				            <label>Airport</label>
				            <div class="col w60">
				            	<select style="width: 360px;" name="busdetails[airport]">
			                        <option value="0">-- Choose Airport --</option>
			                        <?php 

			                            foreach ($listAirport as $value) {
		                                    if($value->id == $this->hotel->currency_id){
		                                        echo "<option value='" .$value->id. "' selected='selected'>";
		                                        echo $value->name ." (". $value->code . ")";
		                                        echo "</option>";
		                                    }else{
		                                        echo "<option value='" .$value->id. "'>";
		                                        echo $value->name ." (". $value->code . ")";
		                                        echo "</option>";
		                                    }                            
			                            }
			                        ?> 
			                    </select>
				            </div>
				        </div>
				        <div class="form-group">
							<label>Telephone</label>
							<div class="col w60">
						        <div class="input-group" style="float: left;">
						       		<input type="text" class="validate-numeric required smaller-size phone_first" name="busdetails[billing_phone_code]" id="billing_phone_code" value="<?php echo !empty($this->busdetails['billing_phone_code']) ? $this->busdetails['billing_phone_code']:''?>" />
									<input type="text" class="validate-numeric required short-size
									phone_second" name="busdetails[billing_phone_number]" id="billing_phone_number" value="<?php echo !empty($this->busdetails['billing_phone_number']) ? $this->busdetails['billing_phone_number']:''?>" />
						        </div>
						        <span class="ex">
						        	<small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
						        </span>
						        
					        </div>
						</div>
						
						<div class="form-group">
							<label>Fax</label>
							<div class="col w60">
						        <div class="input-group" style="float: left;">
						       		<input type="text" class="validate-numeric required smaller-size phone_first" name="busdetails[billing_fax_code]" id="billing_fax_code" value="<?php echo !empty($this->busdetails['billing_fax_code']) ? $this->busdetails['billing_fax_code']:''?>" />						        
									<input type="text" class="validate-numeric required short-size
									phone_second" name="busdetails[billing_fax_number]" id="billing_fax_number" value="<?php echo !empty($this->busdetails['billing_fax_number']) ? $this->busdetails['billing_fax_number']:''?>" />
						        </div>
						        <span class="ex">
						        	<small class="help-block"><?php echo JText::_('COM_SFS_INT_CODE_EXAMPLE')?></small>
						        </span>
					        </div>
						</div>

						<div class="form-group">
				            <label>VAT number</label>
				            <div class="col w60">
				            	<input type="text" name="busdetails[billing_tva_number]" id="billing_tva_number" value="<?php echo !empty($this->busdetails['billing_tva_number']) ? $this->busdetails['billing_tva_number']:''?>" />
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
				            	<input class="validate-password required" id="password" name="account[password]" type="password">
				            </div>
				        </div>
				        
				        <div class="form-group">
				            <label>Retype Password </label>
				            <div class="col w60">
				            	<input class="required validate-match matchInput:'password'" name="account[password2]" type="password">
				            </div>
				        </div>
				    </div>
				</fieldset>
            </div>
	    </div>
	    <div class="form-group">
	        <button type="submit" class="validate btn orange lg pull-right"><?php echo JText::_('COM_SFS_SAVE')?></button>
	    </div>
	    <input type="hidden" name="task" value="busregister.validate" />
	    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
	    <?php echo JHtml::_('form.token'); ?>
	</form>
	
</div>