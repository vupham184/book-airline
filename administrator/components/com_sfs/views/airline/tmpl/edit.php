<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');

$zone_show_permision = SfsHelper::checkIsGroupAdmin();
?>
<style>
	.name-airline{
		color: #838383;
	}
    fieldset.airplus li > label{
        min-width: 120px;
    }
    fieldset.airplus .width-50{
        width: 46%;
        margin-left: 3%;
        box-sizing: border-box;
        float: left;
    }
    fieldset.airplus fieldset.radio label{
        width: 50px;
        padding-right: 0;
    }
    fieldset.airplus li:after{
    	content: ' ';
    	display: block;
    	clear: both;
    }
    fieldset.airplus h3.field-heading{
    	font-size: 1.2em;
    	text-transform: uppercase;
    	font-weight: 600;
    	padding-top: 1em;
    	padding-left: .3em;
    	padding-bottom: .3em;
    	clear: both;
    	color: #444;
    	background-color: #ddd;
    }
    fieldset.airplus div.note{
        color: #aaa; font-style: italic;
        clear: both; font-size: .9em;
    }
    fieldset.airplus input[type="text"]{
        width: 200px;
    }
    fieldset.airplus textarea{
    	width: 240px; height: 50px;
    	font-family: Arial;
    	font-size: .9em;
    }
</style>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'airline.cancel' || document.formvalidator.isValid(document.id('airline-form'))) {			
			Joomla.submitform(task, document.getElementById('airline-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>


<fieldset>
    <div class="fltrt">
        <a style="color:#06F; text-decoration:underline;" href="index.php?option=com_sfs&view=airline&id=<?php echo $this->item->id?>&layout=copyairline&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 450,y: 350}, onClose: function() {}}">
            <button type="button">Copy New Airline</button>
        </a>
    </div>
    <div class="configuration"></div>
</fieldset>

<?php if(!$this->item->approved):?>

<a href="index.php?option=com_sfs&view=airline&tmpl=component&layout=approve&id=<?php echo $this->item->id;?>" class="modal" style="display:block;width:170px;background:green;float:right;color:#fff;font-size:12px; text-align:center;padding:5px 0;" rel="{handler: 'iframe', size: {x:220, y: 120}, onClose: function() {}}">Click here to approve</a>

<?php endif;

$jsonUrl  = JURI::base().'index.php?option=com_sfs&task=contact.generatesk&format=json';
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	var jsonuri = '<?php echo $jsonUrl ?>';
	$('generateKey').addEvent('click', function(e){
		e.stop();		
		new Request.JSON({url: jsonuri, onSuccess: function(result){			    
			if(result.secretkey) {
				$('unique_token').set('value',result.secretkey);
			}	    		    			   
		}}).send();
		
	});		
});

jQuery(function($){
	$('#jform_userairportairline').change(function(e) {
		var user_id = $(this).val();
		var url = '<?php echo 'index.php?option=com_sfs&task=airline.getAirportEdit'; ?>';
        $.get(url,{'user_id':user_id, 'airline_id':'<?php echo $_GET['id'];?>'},function( data ){
			$('#jform_airport').html("").html( data.data );
		}, 'JSON');
    });
});
</script>


<div class="clr"></div>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=airline&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="airline-form" class="form-validate">
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend>Airline Details</legend>
			<ul class="adminformlist">	
				<li><h3 class="name-airline"><b><?php echo $this->item->airline_name;?></b></h3></li>
				<li>
					<?php echo $this->form->getLabel('logo'); ?>
					<?php echo $this->form->getInput('logo'); ?>
				</li>

                <li>
                    <label for="">Airline Template:</label>
                    <?php
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query->select($db->quoteName(array('id', 'template')));
                        $query->from($db->quoteName('#__template_styles'));
                        $query->where($db->quoteName('template') . "LIKE  '%sfs_j16_%'");
                        $db->setQuery($query);
                        $results = $db->loadObjectList();
                    ?>
                    <select name="airlineparams[template_id]">
                        <option value="0">Default</option>
                        <?php if (count($results) > 0) {?>
                        <?php foreach ($results as $value) {?>
                                <option value="<?php echo $value->id; ?>" <?php echo (int)$this->item->params['template_id']==(int)$value->id ? 'selected':'';?>>
                                    <?php echo $value->template; ?>
                                </option>
                        <?php }?>
                        <?php }?>
                    </select>
                </li>

				<li>
					<label title="" class="hasTip required" for="">Airline code:</label>
					<input type="text" readonly="readonly" class="readonly" size="22" value="<?php echo $this->item->airline_code;?>">
					<?php echo $this->form->getInput('iatacode_id'); ?>
				</li>
				<li>
					<label for="">Airline Name:</label>
					<input type="text" readonly="readonly" class="readonly" size="22" value="<?php echo $this->item->airline_name;?>">
				</li>
				<li><?php echo $this->form->getLabel('affiliation_code'); ?>
				<?php echo $this->form->getInput('affiliation_code'); ?></li>
				

				<li><?php echo $this->form->getLabel('airline_alliance'); ?>
				<?php echo $this->form->getInput('airline_alliance'); ?></li>

				
                <li>
					<label id="jform_userairportairline-lbl" class="required" for="jform_userairportairline" 
                    aria-invalid="false">User of airline airports</label>
					<select id="jform_userairportairline" name="jform[user_id]">
					<?php
					 $user_id = 0;
					 foreach ($this->admins as $admin) :  
					 if ( $user_id == 0 ){
						 $user_id = $admin->user_id;
					 }
					 ?>
                    <option value="<?php echo $admin->user_id?>"><?php echo $admin->username;?></option>
					<?php endforeach;?> 
                     <?php foreach ($this->stationUsers as $stationUser) :
					 if ( $user_id == 0 ){
						 $user_id = $stationUser->user_id;
					 }
					 ?>
                    <option value="<?php echo $stationUser->user_id?>"><?php echo $stationUser->username;?></option>
                    <?php endforeach;?>                   	
                    </select>
               </li>
                    
				<li><?php // echo $this->form->getLabel('airport'); ?>
                	<select id="jform_airport" class="inputbox required" multiple="multiple" size="15" name="jform[airport][]" aria-required="true" required="required" aria-invalid="false">
                    
					<?php 
					$airline_id = $_GET['id'];
					$model= $this->getModel('Airline'); 
					$str = $model->getAirportEdit( $user_id, $airline_id );
					echo $str;
					//echo $this->form->getInput('airport'); 
					?>
                    </select>
                    </li>

                <li><?php //print_r( $this->item->params['default_sort_order'] );?>
                
                    <label class="" for="airlineparams_default_sort_order" id="airlineparams_default_sort_order-lbl" aria-invalid="false">Default sort order</label>
                    <select id="airlineparams_default_sort_order" name="airlineparams[default_sort_order]" >
                    <option value="0">Select</option>
                    <option value="1" <?php echo ( isset( $this->item->params['default_sort_order'] ) && $this->item->params['default_sort_order'] == 1 ) ? 'selected="selected"' :'';?>  >Star</option>
                    <option value="2" <?php echo ( isset( $this->item->params['default_sort_order'] ) && $this->item->params['default_sort_order'] == 2 ) ? 'selected="selected"' :'';?>>Price of hotel</option>
                    <option value="3" <?php echo ( isset( $this->item->params['default_sort_order'] ) && $this->item->params['default_sort_order'] == 3 ) ? 'selected="selected"' :'';?>>Distance to airport</option>
                    <option value="4" <?php echo ( isset( $this->item->params['default_sort_order'] ) && $this->item->params['default_sort_order'] == 4 ) ? 'selected="selected"' :'';?>>Hotel shuttle available</option>
                    <option value="5" <?php echo ( isset( $this->item->params['default_sort_order'] ) && $this->item->params['default_sort_order'] == 5 ) ? 'selected="selected"' :'';?>>Total calculated price</option>
                </select>
                </li>
                
                <li>
                    <?php echo $this->form->getLabel('partner_limit_for_extra_search'); ?>
                    <?php 
					echo $this->form->getInput('partner_limit_for_extra_search'); 
					?>
                </li>

				<li>
					<?php echo $this->form->getLabel('time_zone'); ?>
					<?php echo $this->form->getInput('time_zone'); ?>
				</li>

				<li>
					<?php echo $this->form->getLabel('created_date'); ?>
					<?php echo $this->form->getInput('created_date'); ?>
				</li>
                <li>
                    <?php echo $this->form->getLabel('airport_ring_1_mile'); ?>
                    <?php echo $this->form->getInput('airport_ring_1_mile'); ?>
                </li>
                <li>
                    <?php echo $this->form->getLabel('airport_ring_2_mile'); ?>
                    <?php echo $this->form->getInput('airport_ring_2_mile'); ?>
                </li>
                <li>
                    <?php echo $this->form->getLabel('gh_airline'); ?>
                    <?php echo $this->form->getInput('gh_airline'); ?>
                </li>
							
																															
			</ul>
			<div class="clr"></div>				
			<div style="border-top:solid 1px #CCCCCC;padding-top:10px; padding-bottom: 10px">
				<div style="margin-bottom: 10px;font-size: 1.091em;">
					Airline Admins: 
					<?php
					$i = 0; 
					foreach ($this->admins as $admin) : 
					?>
					<a href="index.php?option=com_users&task=user.edit&id=<?php echo $admin->user_id?>"><?php echo $admin->username;?></a>						
					<?php
					if( $i < (count($this->admins) - 1) ) echo ' , '; 
					$i++;
					endforeach;
					?>
				</div>
				<div class="clr"></div>		
				<a rel="{handler: 'iframe', size: {x: 600, y: 440}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_sfs&view=airline&layout=newadmin&tmpl=component&id='.$this->item->id);?>" class="modal icon-16-user" style="background-position: left 50%;background-repeat: no-repeat;color: #333333; padding-left: 25px;">Add New Airline Admin</a>
			</div>
            <div style="border-top:solid 1px #CCCCCC;padding-top:10px;">
                <div style="margin-bottom: 10px;font-size: 1.091em;">
                    Station Users:
                    <?php
                    $i = 0;
                    foreach ($this->stationUsers as $stationUsers) :
                        ?>
                        <a href="index.php?option=com_users&task=user.edit&id=<?php echo $stationUsers->user_id?>"><?php echo $stationUsers->username;?></a>
                        <?php
                        if( $i < (count($this->stationUsers) - 1) ) echo ' , ';
                        $i++;
                    endforeach;
                    ?>
                </div>
                <div class="clr"></div>
                <a rel="{handler: 'iframe', size: {x: 600, y: 440}, onClose: function() {}}" href="<?php echo JRoute::_('index.php?option=com_sfs&view=airline&layout=newstationuser&tmpl=component&id='.$this->item->id);?>" class="modal icon-16-user" style="background-position: left 50%;background-repeat: no-repeat;color: #333333; padding-left: 25px;">Add New Station User</a>
            </div>
        </fieldset>
		
		<fieldset class="adminform">
			<legend>Local offive visiting details</legend>
			<ul class="adminformlist">								
				<li><?php echo $this->form->getLabel('address'); ?>
				<?php echo $this->form->getInput('address'); ?></li>
				
				<li><?php echo $this->form->getLabel('address2'); ?>				
				<?php echo $this->form->getInput('address2'); ?></li>
				
				<li><?php echo $this->form->getLabel('city'); ?>
				<?php echo $this->form->getInput('city'); ?></li>
				
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				
				<li><?php echo $this->form->getLabel('zipcode'); ?>
				<?php echo $this->form->getInput('zipcode'); ?></li>
				
				<li><?php echo $this->form->getLabel('country_id'); ?>
				<?php echo $this->form->getInput('country_id'); ?></li>
				
				<li><?php echo $this->form->getLabel('telephone'); ?>
				<?php echo $this->form->getInput('telephone'); ?></li>
																																					
			</ul>
			<div class="clr"></div>	
		</fieldset>		
		
	
		<fieldset class="adminform">
			<legend>Billing Details</legend>
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('billing_details') as $field): ?>
				<?php if ($field->hidden): ?>
					<?php echo $field->input; ?>
				<?php else: ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		</fieldset>
		
		<fieldset class="adminform">
			<legend>Airline Admin</legend>
			<?php $contacts = $this->item->contacts;?>
			<ul class="adminformlist">
				<li>
					<label for="">Job title:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->job_title;?>" readonly="readonly" class="readonly">
				</li>		
				<li>
					<label for="">Gender:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->gender;?>" readonly="readonly" class="readonly">
				</li>										
				<li>
					<label for="">Name:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->name;?>" readonly="readonly" class="readonly">
				</li>
				<li>
					<label for="">Surname:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->surname;?>" readonly="readonly" class="readonly">
				</li>		
				<li>
					<label for="">Email:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->email;?>" readonly="readonly" class="readonly">
				</li>	
				<li>
					<label for="">Direct office telephone:</label>					
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->telephone;?>" readonly="readonly" class="readonly">
				</li>
				<li>
					<label for="">Direct fax:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->fax;?>" readonly="readonly" class="readonly">
				</li>
				<li>
					<label for="">Mobile:</label>
					<input type="text" size="30" value="<?php echo $contacts[(int)$this->item->created_by]->mobile;?>" readonly="readonly" class="readonly">
				</li>																																														
			</ul>
			
			<?php 						
			if( count($contacts) ) : 
			?>			
				<div class="clr"></div>
				<hr />
				<a href="index.php?option=com_sfs&view=airline&layout=contacts&id=<?php echo $this->item->id;?>&tmpl=component" rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}" class="modal">Click here to see more contacts</a>
			<?php endif;?>
	
		</fieldset>			
		
	</div>
	<div class="width-50 fltrt">		
		<fieldset class="adminform">
			<legend>Global Params</legend>
			<ul class="adminformlist" style="float:left; width:50%; margin-right:10px;">
				<li>
                    <label>Enable payment by passenger:</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->params['enable_passenger_payment']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[enable_passenger_payment]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->params['enable_passenger_payment']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[enable_passenger_payment]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>Enable invite hotels to load rooms:</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->params['enable_invite_load_rooms']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[enable_invite_load_rooms]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->params['enable_invite_load_rooms']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[enable_invite_load_rooms]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>Enable add hotels:</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->params['enable_add_hotel']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[enable_add_hotel]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->params['enable_add_hotel']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[enable_add_hotel]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <?php if($zone_show_permision) : ?>
	                <li>
	                    <label for="">Sender Title:</label>
	                    <input type="text" size="30" value="<?php echo isset($this->item->params['sender_title']) ? $this->item->params['sender_title'] : ''; ?>" name="airlineparams[sender_title]">
	                </li>
	                <li>
	                    <label>Enable send SMS message:</label>
	                    <fieldset class="radio">
	                        <input type="radio" <?php echo (int)$this->item->params['send_sms_message']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[send_sms_message]">
	                        <label>No</label>
	                        <input type="radio" <?php echo (int)$this->item->params['send_sms_message']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[send_sms_message]">
	                        <label>Yes</label>
	                    </fieldset>
	                </li>
            	<?php endif ?>
				<li>
					<label>Enable general comment:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['show_general_comment']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[show_general_comment]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['show_general_comment']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[show_general_comment]">
						<label>Yes</label>
					</fieldset>
				</li>
				<li>
					<label for="">Show comment on flight form:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['show_addpassengercomment']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[show_addpassengercomment]">
						<label>Hide</label>
						<input type="radio" <?php echo (int)$this->item->params['show_addpassengercomment']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[show_addpassengercomment]">
						<label>Show</label>
					</fieldset>
				</li>
				<li>
					<label for="">Show comment on match page:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['show_vouchercomment']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[show_vouchercomment]">
						<label>Hide</label>
						<input type="radio" <?php echo (int)$this->item->params['show_vouchercomment']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[show_vouchercomment]">
						<label>Show</label>
					</fieldset>
				</li>	
				<li>
					<label>Voucher format:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['voucher_format']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[voucher_format]">
						<label>Image</label>
						<input type="radio" <?php echo (int)$this->item->params['voucher_format']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[voucher_format]">
						<label>HTML</label>
					</fieldset>
				</li>							
				<li>
					<label>VAT comment line on Voucher:</label>		
					<textarea name="airlineparams[voucher_vat_comment_line]"><?php echo isset($this->item->params['voucher_vat_comment_line']) ? $this->item->params['voucher_vat_comment_line'] :'';?></textarea>
				</li>	
				<li>
					<label for="">Return flight details on match page and voucher:</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['enable_return_flight']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[enable_return_flight]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['enable_return_flight']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[enable_return_flight]">
						<label>Yes</label>
					</fieldset>
				</li>
				<?php if($zone_show_permision) : ?>
					<li>
						<label for="">Enable group (bus) transport for Airline:</label>		
						<fieldset class="radio">			
							<input type="radio" <?php echo (int)$this->item->params['enable_group_transport']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[enable_group_transport]">
							<label>No</label>
							<input type="radio" <?php echo (int)$this->item->params['enable_group_transport']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[enable_group_transport]">
							<label>Yes</label>
						</fieldset>
					</li>
	                
	                <li>
						<label for="">Enable Invite hotel for registration:</label>		
						<fieldset class="radio">			
							<input type="radio" <?php echo (int)$this->item->params['invite_hotel_for_registration']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[invite_hotel_for_registration]">
							<label>No</label>
							<input type="radio" <?php echo (int)$this->item->params['invite_hotel_for_registration']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[invite_hotel_for_registration]">
							<label>Yes</label>
						</fieldset>
					</li>	
	                
					
					<li>
						<label for="">Update block status after X days: </label>		
						<input type="text" name="airlineparams[number_day_update_status]" value="<?php echo isset($this->item->params['number_day_update_status']) ? $this->item->params['number_day_update_status'] :'';?>">
					</li>	
				<?php endif ?>		
			</ul>	
            
            <ul class="adminformlist" style="float:left; width:40%; margin-right:10px;">
            	<?php if($zone_show_permision) : ?>
            		<li>
	                    <label>Enable extra data on voucher:</label>
	                    <fieldset class="radio">
	                        <input type="radio" <?php echo (int)$this->item->params['enable_extra_data_on_voucher']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[enable_extra_data_on_voucher]">
	                        <label>No</label>
	                        <input type="radio" <?php echo (int)$this->item->params['enable_extra_data_on_voucher']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[enable_extra_data_on_voucher]">
	                        <label>Yes</label>
	                    </fieldset>
                	</li>
                	 <li>
	                    <label>Extra data on voucher title:</label>
	                        <input type="text" value="<?php echo (isset($this->item->params['extra_data_on_voucher_title'])) ? $this->item->params['extra_data_on_voucher_title'] : '';?>" name="airlineparams[extra_data_on_voucher_title]">
	                </li>
            	<?php endif; ?>				                              
                
                <li>
                    <label>Airline WS Enabled:</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->params['airline_ws_status']==0 && isset($this->item->params['airline_ws_status'] )? 'checked="checked"':'';?> value="0" name="airlineparams[airline_ws_status]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->params['airline_ws_status']==1 || !isset( $this->item->params['airline_ws_status'] )  ? 'checked="checked"':'';?> value="1" name="airlineparams[airline_ws_status]">
                        <label>Yes</label>
                        <?php if((int)$this->item->params['airline_ws_status']==1){
                        ?>                        
                        	<a href="<?php echo JURI::base(); ?>index.php?option=com_sfs&view=airportservices">Service per Airport</a>
                        <?php	
                        } ?>
                        
                    </fieldset>

                </li>
                
                <?php if($zone_show_permision) : ?>
	                <li>
	                    <label>Extra data on voucher title:</label>
	                        <input type="text" value="<?php echo (isset($this->item->params['extra_data_on_voucher_title'])) ? $this->item->params['extra_data_on_voucher_title'] : '';?>" name="airlineparams[extra_data_on_voucher_title]">
	                </li>
	                
	                <fieldset class="adminform">
	                    <legend>Unique Token</legend>
	                    <div>
	                        <p style="margin-bottom: 10px;padding-bottom:0;">
	                            <input type="text" name="jform[unique_token]" id="unique_token" value="<?php echo (isset($this->item->unique_token)) ? $this->item->unique_token : '';?>" style="width:90%" readonly="readonly">
	                        </p>			
	                        <button type="button" id="generateKey">Generate</button>				
	                    </div>
	                </fieldset>
        		<?php endif; ?>
                
                <fieldset class="adminform">
                    <legend>API Enabled</legend>
                            <label style="display:inline;"> No
                            <input style="display:inline;" type="radio" value="0" 
							<?php echo (isset($this->item->params['api_enabled'] ) && $this->item->params['api_enabled'] == '0' ) ? 'checked="checked"' : '';?> name="airlineparams[api_enabled]">
                            </label>
                            <label style="display:inline;">Yes
                            <input style="display:inline;" type="radio" value="1" <?php echo (isset($this->item->params['api_enabled'] ) && $this->item->params['api_enabled'] == '1' ) ? 'checked="checked"' : '';?> name="airlineparams[api_enabled]"></label>
                            
                </fieldset>
                <fieldset class="adminform">
                    <legend>Communication Enabled</legend>
                            <label style="display:inline;"> No
                            <input style="display:inline;" type="radio" value="0" 
							<?php echo (isset($this->item->params['communication_enabled'] ) && $this->item->params['communication_enabled'] == '0' ) ? 'checked="checked"' : '';?> name="airlineparams[communication_enabled]">
                            </label>
                            <label style="display:inline;">Yes
                            <input style="display:inline;" type="radio" value="1" <?php echo (isset($this->item->params['communication_enabled'] ) && $this->item->params['communication_enabled'] == '1' ) ? 'checked="checked"' : '';?> name="airlineparams[communication_enabled]"></label>
                            
                </fieldset>
                <fieldset class="adminform">
                    <legend>Specific Services Enabled</legend>
                            <label style="display:inline;"> No
                            <input style="display:inline;" type="radio" value="0" 
							<?php echo (isset($this->item->params['specific_services_enabled'] ) && $this->item->params['specific_services_enabled'] == '0' ) ? 'checked="checked"' : '';?> name="airlineparams[specific_services_enabled]" checked>
                            </label>
                            <label style="display:inline;">Yes
                            <input style="display:inline;" type="radio" value="1" <?php echo (isset($this->item->params['specific_services_enabled'] ) && $this->item->params['specific_services_enabled'] == '1' ) ? 'checked="checked"' : '';?> name="airlineparams[specific_services_enabled]"></label>
                            
                </fieldset>
           	</ul>	
		</fieldset>	
		<?php if($zone_show_permision) : ?>
		<fieldset class="adminform">
			<legend>Taxi Params</legend>
			
			<ul class="adminformlist">
				<li style="margin-bottom:10px;overflow:hidden;">
					<label>Enable taxi voucher for the airline</label>
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['enable_taxi_voucher']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[enable_taxi_voucher]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['enable_taxi_voucher']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[enable_taxi_voucher]">
						<label>Yes</label>
					</fieldset>
				</li>
				<li style="margin-bottom:10px;overflow:hidden;">
					<label>Airline will be able to edit taxi details/ prices in front end</label>		
					<fieldset class="radio">			
						<input type="radio" <?php echo (int)$this->item->params['can_edit_taxi']==0 ? 'checked="checked"':'';?> value="0" name="airlineparams[can_edit_taxi]">
						<label>No</label>
						<input type="radio" <?php echo (int)$this->item->params['can_edit_taxi']==1 ? 'checked="checked"':'';?> value="1" name="airlineparams[can_edit_taxi]">
						<label>Yes</label>
					</fieldset>
				</li>
			</ul>
			<div class="clr"></div>
			<?php if(count($this->taxiCompanies)):?>
			<strong>Selection of taxi companies available for the airline</strong>			
			<ul class="admsinformlist">				
			<?php foreach ($this->taxiCompanies as $taxi):
				$disabled = '';
				if( $taxi->profile_type=='taxi' )
					$disabled = 'disabled="disabled"';				
			?>					
				<li>													
					<input type="checkbox" value="<?php echo $taxi->id?>" <?php echo $disabled?> name="available_taxi[<?php echo $taxi->id?>]" <?php echo (int)$taxi->published==1 ? 'checked="checked"':'';?>> <?php echo $taxi->name;?>										
				</li>
			<?php endforeach;?>
			</ul>
			<?php endif;?>
			<div class="clr"></div>
			
			<?php if((int)$this->item->params['enable_taxi_voucher']==1 && $this->taxiDetails):?>
				<div style="background:#fff;padding:10px;">
					<strong>For services and inquiries</strong>
					<div style="background:#fff;padding:10px;">
						<table>
							<tr>
								<td><?php echo JText::_('Address');?></td><td><?php echo $this->taxiDetails->address; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('City');?></td><td><?php echo $this->taxiDetails->city; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('Zipcode');?></td><td><?php echo $this->taxiDetails->zipcode; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('Country');?></td><td><?php echo $this->taxiDetails->country_name; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('Telephone');?></td><td><?php echo $this->taxiDetails->telephone; ?></td>
							</tr>
							
						</table>
					</div>
				
					<strong>Billing details for taxi company</strong>
					<div style="background:#fff;padding:10px;">
						<table>
							<tr>
								<td><?php echo JText::_('Registered name');?></td><td><?php echo $this->taxiDetails->billing_registed_name; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('Address');?></td><td><?php echo $this->taxiDetails->billing_address; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('City');?></td><td><?php echo $this->taxiDetails->billing_city; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('Zipcode');?></td><td><?php echo $this->taxiDetails->billing_zipcode; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('Country');?></td><td><?php echo $this->taxiDetails->billing_country_name; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('Vat number ');?></td><td><?php echo $this->taxiDetails->billing_vat_number ; ?></td>
							</tr>
							
						</table>
					</div>
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=taxi&layout=edit&airline_id='.$this->item->id.'&tmpl=component');?>" class="modal" rel="{handler: 'iframe', size: {x: 800, y: 550}, onClose: function() {}}">
						Edit Taxi Details
					</a>
				</div>
				
			<?php endif;?>
			
		</fieldset>		

        <fieldset class="adminform airplus">
            <legend>Airplus Configuration</legend>
            <ul class="adminformlist width-50 fltlft">
                <li><h3 class="field-heading">Airplus DBI</h3></li>
                <li>
                    <label>AE (Accounting Unit):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_ae']?$this->item->airplusparams['dbi_ae']:'|airportcode|';?>"  name="airplusparams[dbi_ae]">
                    <div class="note">Can use |airportcode| as variable</div>
                </li>
                <li>
                    <label>AU (Order Number):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_au']?$this->item->airplusparams['dbi_au']:'|airplusblockcode|';?>"  name="airplusparams[dbi_au]">
                    <div class="note">Can use |airplusblockcode| as variable</div>
                </li>
                <li>
                    <label>BD (Departure Date):</label>
                    <input type="text" placeholder="Date 01JUL15" value="<?php echo $this->item->airplusparams['dbi_bd']?$this->item->airplusparams['dbi_bd']:'|startdate|';?>"  name="airplusparams[dbi_bd]">
                    <div class="note">Can use |startdate| as variable</div>
                </li>
                <li>
                    <label>DS (Department Code):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_ds'];?>"  name="airplusparams[dbi_ds]">
                    <div class="note">Can use static value example "SFS"</div>
                </li>
                <li>
                    <label>IK (Internal Account):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_ik']?$this->item->airplusparams['dbi_ik']:'|originalflightnumber|';?>"  name="airplusparams[dbi_ik]">
                    <div class="note">Can use |originalflightnumber| as variable</div>
                </li>
                <li>
                    <label>KS (Cost Center):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_ks'];?>"  name="airplusparams[dbi_ks]">
                    <div class="note">Static cost per creadit card</div>
                </li>
                <li>
                    <label>PK (Employee Number):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_pk']?$this->item->airplusparams['dbi_pk']:'|userID|';?>"  name="airplusparams[dbi_pk]">
                    <div class="note">Can use |userID| as variable</div>
                </li>
                <li>
                    <label>PR ( Project Number):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_pr']?$this->item->airplusparams['dbi_pr']:'|PNR|';?>"  name="airplusparams[dbi_pr]">
                    <div class="note">Can use |PNR| as variable</div>
                </li>
                <li>
                    <label>RZ (Destination):</label>
                    <input type="text" placeholder="Alphanumeric 1-17 chars" value="<?php echo $this->item->airplusparams['dbi_rz']?$this->item->airplusparams['dbi_rz']:'|destinationIATA|';?>"  name="airplusparams[dbi_rz]">
                    <div class="note">Can use |destinationIATA| as variable</div>
                </li>
                <li>
                    <label style="padding-top:10px; font-size: 10px">
                        Transaction Currency control (optional): EUR
                    </label>
                </li>

                <!-- Other used by Airline-->
                <li>
                    <h3 class="field-heading">Other used by Airline</h3>
                </li>
                <li>
                    <label>NM (Name):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['other_nm']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[other_nm]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['other_nm']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[other_nm]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>FT (Free Text):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['other_ft']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[other_ft]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['other_ft']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[other_ft]">
                        <label>Yes</label>
                    </fieldset>
                </li>

                <!-- Cash Reimbursement-->
                <li>
                    <h3 class="field-heading">Cash Reimbursement</h3>
                </li>
                <li>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['cashreim_enabled']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[cashreim_enabled]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['cashreim_enabled']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[cashreim_enabled]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Enabled / disabled issue cash reimbursements vouchers</div>
                </li>
                <li>
                    <label>Transaction Fee (Transfee):</label>
                    <input type="text" placeholder="Numeric 1234.56" value="<?php echo $this->item->airplusparams['cashreim_fee'];?>"  name="airplusparams[cashreim_fee]">
                    <div class="note">Required if enabled</div>
                </li>
                <li>
                    <label>NM (Name):</label>
                    <input type="text" placeholder="Alphanumeric 1-45 chars" value="<?php echo $this->item->airplusparams['cashreim_nm'];?>"  name="airplusparams[cashreim_nm]">
                    <div class="note">Name on vouchers</div>
                </li>
                <li>
                    <label>FT (Free Text):</label>
                    <textarea  placeholder="Alphanumeric 1-25 chars (once compiled)" type="text" name="airplusparams[cashreim_ft]" rows="2"><?php echo $this->item->airplusparams['cashreim_ft'];?></textarea>
                    <div class="note">Can use |firstname|, |lastname|, |blockcode| as variable </div>
                </li>

                <!-- Taxi -->
                <li>
                    <h3 class="field-heading">Taxi</h3>
                </li>
                <li>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['taxi_enabled']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[taxi_enabled]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['taxi_enabled']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[taxi_enabled]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Enabled / disabled issue taxi vouchers</div>
                </li>
                <li>
                    <label>Transaction Fee(Transfee):</label>
                    <input type="text" placeholder="Numeric 1234.56" value="<?php echo $this->item->airplusparams['taxi_fee'];?>"  name="airplusparams[taxi_fee]">
                    <div class="note">Required if enabled</div>
                </li>
                <li>
                    <label>NM (Name):</label>
                    <input type="text" placeholder="Alphanumeric 1-45 chars" value="<?php echo $this->item->airplusparams['taxi_nm'];?>"  name="airplusparams[taxi_nm]">
                    <div class="note">Name on vouchers</div>
                </li>
                <li>
                    <label>FT (Free Text):</label>
                    <textarea  placeholder="Alphanumeric 1-25 chars (once compiled)" type="text" name="airplusparams[taxi_ft]" rows="2"><?php echo $this->item->airplusparams['taxi_ft'];?></textarea>
                    <div class="note">Can use |firstname|, |lastname|, |blockcode| as variable </div>
                </li>

                <!-- Meal plan Vouchers -->
                <li>
                    <h3 class="field-heading">Meal plan Vouchers</h3>
                </li>
                <li>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['meal_enabled']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[meal_enabled]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['meal_enabled']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[meal_enabled]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Enabled / disabled issue meal plan vouchers</div>
                </li>
                <li>
                    <label>Transaction Fee(Transfee):</label>
                    <input type="text" placeholder="Numeric 1234.56" value="<?php echo $this->item->airplusparams['meal_fee'];?>"  name="airplusparams[meal_fee]">
                    <div class="note">Required if enabled</div>
                </li>
                <li>
                    <label>1.5 hours Meal Plan VelocityControl Cummulative Limit:</label>
                    <input type="text" placeholder="Numeric 1234.56" value="<?php echo $this->item->airplusparams['meal_first_limit'];?>"  name="airplusparams[meal_first_limit]">
                    <div class="note">Standard amount if the passenger delay 1,5 hours</div>
                </li>
                <li>
                    <label>5 hours Meal Plan VelocityControl Cummulative Limit:</label>
                    <input type="text" placeholder="Numeric 1234.56" value="<?php echo $this->item->airplusparams['meal_second_limit'];?>"  name="airplusparams[meal_second_limit]">
                    <div class="note">Standard amount if the passenger delay after 5 hours</div>
                </li>
                <li>
                    <label>NM (Name):</label>
                    <input type="text" placeholder="Alphanumeric 1-45 chars" value="<?php echo $this->item->airplusparams['meal_nm'];?>"  name="airplusparams[meal_nm]">
                    <div class="note">Name on vouchers</div>
                </li>
                <li>
                    <label>FT (Free Text):</label>
                    <textarea  placeholder="Alphanumeric 1-25 chars (once compiled)" type="text" name="airplusparams[meal_ft]" rows="2"><?php echo $this->item->airplusparams['meal_ft'];?></textarea>
                    <div class="note">Can use |firstname|, |lastname|, |blockcode| as variable </div>
                </li>
                <li>
                    <label>Values for issue vouchers:</label>
                    <input type="text" placeholder="Values for issue vouchers" value="<?php echo $this->item->airplusparams['meal_values'];?>" name="airplusparams[meal_values]"/>
                    <div class="note">Can use firstvalue; secondvalue; thirdvalue</div>
                </li>


            </ul>

            <ul class="adminformlist width-50 fltrt">

                <!-- Hotel used by Airline-->
                <li>
                    <h3 class="field-heading">Hotel used by Airline</h3>
                </li>
                <li>
                    <label>GN (Guest name):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_gn']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[hotel_gn]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_gn']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[hotel_gn]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Guest name</div>
                </li>
                <li>
                    <label>CID (Check-in-date):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_cid']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[hotel_cid]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_cid']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[hotel_cid]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Date. Ex 01JUN07</div>
                </li>
                <li>
                    <label>COD (Check-out-date):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_cod']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[hotel_cod]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_cod']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[hotel_cod]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Date. Ex 01JUN07</div>
                </li>
                <li>
                    <label>RN (Roomnights):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_rn']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[hotel_rn]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['hotel_rn']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[hotel_rn]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Numeric 1-4 digits</div>
                </li>

                <!-- Rail used by Airline-->
                <li>
                    <h3 class="field-heading">Rail used by Airline</h3>
                </li>
                <li>
                    <label>PN (Passenger name):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_pn']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[rail_pn]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_pn']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[rail_pn]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>DD (Departure date):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_dd']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[rail_dd]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_dd']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[rail_dd]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>DPTC (Departure city):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_dptc']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[rail_dptc]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_dptc']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[rail_dptc]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>DTC (Destination city):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_dtc']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[rail_dtc]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_dtc']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[rail_dtc]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>SC (Service class):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_sc']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[rail_sc]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['rail_sc']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[rail_sc]">
                        <label>Yes</label>
                    </fieldset>
                </li>

                <!-- Car rental used by Airline-->
                <li>
                    <h3 class="field-heading">Car rental used by Airline</h3>
                </li>
                <li>
                    <label>DR (Driver):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_dr']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[car_dr]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_dr']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[car_dr]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>PD (Pick-up date):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_pd']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[car_pd]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_pd']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[car_pd]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>PL (Pick-up location):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_pl']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[car_pl]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_pl']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[car_pl]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>DL (Drop-off location):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_dl']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[car_dl]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_dl']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[car_dl]">
                        <label>Yes</label>
                    </fieldset>
                </li>
                <li>
                    <label>DC (Day count):</label>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_dc']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[car_dc]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['car_dc']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[car_dc]">
                        <label>Yes</label>
                    </fieldset>
                </li>

                <!-- Bus - Group transport used by Airline-->
                <li>
                    <h3 class="field-heading">Bus - Group transport </h3>
                </li>
                <li>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['bus_enabled']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[bus_enabled]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['bus_enabled']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[bus_enabled]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Enabled / disabled issue bus-group transport vouchers</div>
                </li>
                <li>
                    <label>Transaction Fee(Transfee):</label>
                    <input type="text" placeholder="Numeric 1234.56" value="<?php echo $this->item->airplusparams['bus_fee'];?>"  name="airplusparams[bus_fee]">
                    <div class="note">Required if enabled</div>
                </li>
                <li>
                    <label>NM (Name):</label>
                    <input type="text" placeholder="Alphanumeric 1-45 chars" value="<?php echo $this->item->airplusparams['bus_nm'];?>"  name="airplusparams[bus_nm]">
                    <div class="note">Name on vouchers</div>
                </li>
                <li>
                    <label>FT (Free Text):</label>
                    <textarea placeholder="Alphanumeric 1-25 chars (once compiled)" type="text" name="airplusparams[bus_ft]" rows="2"><?php echo $this->item->airplusparams['bus_ft'];?></textarea>
                    <div class="note">Can use |firstname|, |lastname|, |blockcode| as variable </div>
                </li>
                <!-- Telephone cards used by Airline-->
                <li>
                	<h3 class="field-heading">Telephone cards </h3>
                </li>
                <li>
                    <fieldset class="radio">
                        <input type="radio" <?php echo (int)$this->item->airplusparams['telcard_enabled']==0 ? 'checked="checked"':'';?> value="0" name="airplusparams[telcard_enabled]">
                        <label>No</label>
                        <input type="radio" <?php echo (int)$this->item->airplusparams['telcard_enabled']==1 ? 'checked="checked"':'';?> value="1" name="airplusparams[telcard_enabled]">
                        <label>Yes</label>
                    </fieldset>
                    <div class="note">Enabled / disabled issue telephone card vouchers</div>
                </li>
                <li>
                    <label>Transaction Fee(Transfee):</label>
                    <input type="text" placeholder="Numeric 1234.56" value="<?php echo $this->item->airplusparams['telcard_fee'];?>"  name="airplusparams[telcard_fee]">
                    <div class="note">Required if enabled</div>
                </li>
                <li>
                    <label>NM (Name):</label>
                    <input type="text" placeholder="Alphanumeric 1-45 chars" value="<?php echo $this->item->airplusparams['telcard_nm'];?>"  name="airplusparams[telcard_nm]">
                    <div class="note">Name on vouchers</div>
                </li>
                <li>
                    <label>FT (Free Text):</label>
                    <textarea  placeholder="Alphanumeric 1-25 chars (once compiled)" type="text" name="airplusparams[telcard_ft]" rows="2"><?php echo $this->item->airplusparams['telcard_ft'];?></textarea>
                    <div class="note">Can use |firstname|, |lastname|, |blockcode| as variable </div>
                </li>

            </ul>
        </fieldset>
		<?php endif ?>
		<fieldset class="adminform">
			<legend>Latest Reservations</legend>
			<?php if(count($this->reservations)):?>
			<table class="adminlist">
				<thead>
					<tr>
						<th><strong>Blockcode</strong></th>
						<th><strong>Hotel</strong></th>
						<th><strong>Initial</strong></th>
						<th><strong>Claimed</strong></th>
						<th><strong>SD Rate</strong></th>
						<th><strong>T Rate</strong></th>
						<th><strong>Revenue</strong></th>
						<th width="5"><strong></strong></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->reservations as $value) : ?>
					<tr>
						<td>
							<a href="index.php?option=com_sfs&view=reservation&id=<?php echo $value->id;?>">
								<?php echo $value->blockcode;?>
							</a>
						</td>
						<td>
							<a href="index.php?option=com_sfs&view=hotel&layout=edit&id=<?php echo $value->hotel_id;?>" target="_blank">
								<?php echo $value->hotel_name;?>
							</a>
						</td>
						<td><?php echo $value->sd_room+$value->t_room;?></td>
						<td><?php echo $value->claimed_rooms?></td>
						<td><?php echo $value->currency.$value->sd_rate?></td>	
						<td><?php echo $value->currency.$value->t_rate?></td>
						<td>
							<?php 
							if( $value->status == 'A' || $value->status =='R' ) {
								echo $value->currency.$value->revenue_booked;
							}
							?>
						</td>						
						<td><?php echo $value->status?></td>							
					</tr>		
					<?php endforeach;?>
				</tbody>
			</table>
			<p style="text-align:right;">
				<br />
				<a href="index.php?option=com_sfs&view=reservations&rtype=airline&gid=<?php echo $this->item->id;?>">More Reservations</a>
			</p>		
			<?php else :?>
				<h3>No Reservations</h3>
			<?php endif;?>		
		</fieldset>				
		
	</div>	

	<div class="clr"></div>
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
</form>

