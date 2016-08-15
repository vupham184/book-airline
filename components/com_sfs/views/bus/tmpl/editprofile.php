<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$listAirport = SfsHelper::getListAirport();
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	
	var busProfilesForm = document.id('busProfilesForm');	
	busProfilesForm.getElements('[type=text], select').each(function(el){
    	new OverText(el);
	});

	new Form.Validator(busProfilesForm); 
	
	jQuery('newProfileButton').addEvent('click', function(e){		

		var random1 = Number.random(10, 5000);
		var random2 = Number.random(10, 5000);
		var random3 = Number.random(100, 1000);

		var key = random1 + random2 + random3;

		var fieldDiv    = new Element('div', {'class': 'register-field clear floatbox largemargintop'});
		var fieldLable  = new Element('label');		
		var fieldInput  = new Element('input', {type: 'text', name: 'profiles[t'+key+'][name]', value:''});			
		fieldLable.set('text','Profile name');
		fieldLable.inject(fieldDiv, 'top');
		fieldInput.inject(fieldDiv, 'bottom');		
		fieldDiv.inject($('busprofiles'), 'bottom');

		var emailFieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var emailFieldLable  = new Element('label', {'class': 'textindent25'});		
		var emailFieldInput  = new Element('input', {type: 'text', name: 'profiles[t'+key+'][seats]', value:''});
		emailFieldLable.set('text','Seats');
		emailFieldLable.inject(emailFieldDiv, 'top');
		emailFieldInput.inject(emailFieldDiv, 'bottom');		
		emailFieldDiv.inject($('busprofiles'), 'bottom');

		
		var telephoneFieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var telephoneFieldLable  = new Element('label', {'class': 'textindent25'});		
		var telephoneFieldInput  = new Element('input', {type: 'text', name: 'profiles[t'+key+'][rate_first]', value:''});					
		telephoneFieldLable.set('text','Rate for first 50 km');
		telephoneFieldLable.inject(telephoneFieldDiv, 'top');
		telephoneFieldInput.inject(telephoneFieldDiv, 'bottom');		
		telephoneFieldDiv.inject($('busprofiles'), 'bottom');

		var telephoneFieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var telephoneFieldLable  = new Element('label', {'class': 'textindent25'});		
		var telephoneFieldInput  = new Element('input', {type: 'text', name: 'profiles[t'+key+'][rate_second]', value:''});					
		telephoneFieldLable.set('text','Rate from 50 to 100 (km)');
		telephoneFieldLable.inject(telephoneFieldDiv, 'top');
		telephoneFieldInput.inject(telephoneFieldDiv, 'bottom');		
		telephoneFieldDiv.inject($('busprofiles'), 'bottom');

		var telephoneFieldDiv    = new Element('div', {'class': 'register-field clear floatbox'});
		var telephoneFieldLable  = new Element('label', {'class': 'textindent25'});		
		var telephoneFieldInput  = new Element('input', {type: 'text', name: 'profiles[t'+key+'][rate_three]', value:''});					
		telephoneFieldLable.set('text','Rate from 100 to 150 (km)');
		telephoneFieldLable.inject(telephoneFieldDiv, 'top');
		telephoneFieldInput.inject(telephoneFieldDiv, 'bottom');		
		telephoneFieldDiv.inject($('busprofiles'), 'bottom');

	});
	
});
</script>
<style type="text/css">
	.noteview{
		float:left; width: 400px; margin-left: 40px;
		background: #ffefcf; border-radius: 7px;
	}
	.noteimg{ float: left; }
	.noteinfo{float: left; width: 360px; padding: 15px 10px;}
	.currency{margin-left: 10px;}
	div.rowbutton{margin-left: 60px;}
	.btDistance{float:left; width: 48%;}
</style>
<div id="sfs-wrapper">

<form id="busProfilesForm" name="busProfilesForm" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
<div class="heading-block clearfix">
	<div class="sfs-above-main">
	    <h3>Rates</h3>
	</div>
</div>

<div class="sfs-main-wrapper-none">
<div class="sfs-orange-wrapper">	
<div class="sfs-white-wrapper floatbox">				
<fieldset>
		
	<div class="fs-16 midmarginbottom">
		 Your Bus Profiles 
		<div class="fs-14 smallpaddingtop greycolor">
			<!-- You can add or delete any profile that you have an agreement with -->
			Rates are based on the distance of a one way trip from or to the airport. <br />
					You can add or edit any profiles but you can only disable an profile if there are existing bookings made for them.
		</div>
	</div>	
		
	<div class="fieldset-fields" style="padding-top: 15px;padding-left:230px;" id="busprofiles">
		<?php if( count($this->profiles) ) : ?>
			<?php foreach ($this->profiles as $profile ) : 
				
				$removeLink = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=removeprofile&profile_id='.$profile->id.'&Itemid='.JRequest::getInt('Itemid');
				$removeRate = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=removelinerate&profile_id='.$profile->id.'&Itemid='.JRequest::getInt('Itemid').'&rate_first=';
				$removeLinkFixed = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=removeprofilefixed&profile_id='.$profile->id.'&Itemid='.JRequest::getInt('Itemid').'&name="'.$profile->name.'"';
				$removeRateFixed = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=removelineratefixed&profile_id='.$profile->id.'&Itemid='.JRequest::getInt('Itemid').'&airport_to=';		
			?>	
			<?php if($profile->id == JRequest::getInt('Itemid')) : ?>	
				<?php if(!empty($profile->rate) ) : ?>
					<div style="float: left;margin-left: -220px; margin-bottom: 20px; width: 90%;">
						<div class="register-field clear floatbox largemargintop">
							<div style="float: left;">
								<label>Profile name</label> 
								<?php if($profile->seats):?>					
									<input type="text" name="profiles[<?php echo $profile->id?>][name]" value="<?php echo $profile->name?>" class="required" style="width: 130px;" />					
								<?php endif;?>
							</div>
							<div style="float: left; width: 15%; position: relative; left: 120px;">
											
								<div class="s-button">
									<a class="s-button modal" rel="{handler: 'iframe', size: {x: 420, y: 150}}" href="<?php echo $removeLink?>">
										<?php echo JText::_('COM_SFS_TAXI_REMOVE');?>
									</a>
								</div>					
							</div>	
						</div>
						<div class="register-field clear floatbox largemargintop">
							<label>Seats</label> 
							<input type="text" name="profiles[<?php echo $profile->id?>][seats]" value="<?php echo $profile->seats?>" class="required" style="width: 130px;" />
						</div>
						<div class="register-field clear floatbox">
							<table>
								<tr>
									<td width="130px">Distance from (km)</td>
									<td width="130px">Distance until (km)</td>
									<td width="130px">Rate</td>
									<td>&#160;</td>
								</tr>
								<tr>
								<?php foreach (json_decode($profile->rate) as $key => $rate) : ?>
									<td width="130px">
										<input type="text" value="<?php echo $rate->rate_first; ?>" name="rate_first_<?php echo $key; ?>" style="width: 120px;" class="required">
									</td>
									<td width="130px">
										<input type="text" value="<?php echo $rate->rate_second; ?>" name="rate_second_<?php echo $key; ?>" style="width: 120px;" class="required">
									</td>
									<td width="160px">
										<input type="text" value="<?php echo $rate->rate_three; ?>" name="rate_three_<?php echo $key; ?>" style="width: 120px;" class="required">
										<span class="currency">EUR</span>
									</td>
									<?php if($key > 0): ?>
									<td>
										<div class="s-button rowbutton">
											<a class="s-button modal" rel="{handler: 'iframe', size: {x: 460, y: 100}}" href="<?php echo $removeRate.$rate->rate_first.'&rate_second='.$rate->rate_second.'&rate_three='.$rate->rate_three;?>">
												<?php echo JText::_('COM_SFS_TAXI_REMOVE');?>
											</a>
										</div>
									</td>
									<?php endif; ?>
								</tr>	
								<?php endforeach; ?>				
							</table>
							<table>
								<div class="viewAddrate">
									<!-- <input type="hidden" name="count" class="countRow" value="0" > -->
									<input type="hidden" name="count" class="countRow" id="rowcount" value="<?php echo count(json_decode($profile->rate)) - 1; ?>" >
								</div>
							</table><div class="clear"></div>
							
						</div>									
						<div class="s-button" style="margin-top: 20px;">
							<a class="s-button" id="newAddrate">Add Rate</a>	    
						</div>
						<input type="hidden" name="id" value="<?php echo $profile->id?>" />
					</div>
				<?php else: ?>
					<div style="float: left;margin-left: -220px; margin-bottom: 20px; width: 90%;">
						<div class="register-field clear floatbox largemargintop">
							<div style="float: left;">
								<label>Profile name</label> 
								<?php if($profile->seats):?>					
									<input type="text" name="profiles[<?php echo $profile->id?>][name]" value="<?php echo $profile->name?>" class="required" style="width: 130px;" />					
								<?php endif;?>
							</div>
							<div style="float: left; width: 15%; position: relative; left: 120px;">
											
								<div class="s-button">
									<a class="s-button modal" rel="{handler: 'iframe', size: {x: 420, y: 150}}" href="<?php echo $removeLinkFixed?>">
										<?php echo JText::_('COM_SFS_TAXI_REMOVE');?>
									</a>
								</div>					
							</div>	
						</div>
						<div class="register-field clear floatbox largemargintop">
							<label>Seats</label> 
							<input type="text" name="profiles[<?php echo $profile->id?>][seats]" value="<?php echo $profile->seats?>" class="required" style="width: 130px;" />
						</div>
						<div class="register-field clear floatbox">
							<table>
								<tr>
									<td width="130px"></td>
									<td width="130px"></td>
									<td width="130px">Rate</td>
									<td>&#160;</td>
								</tr>
								<tr>
								<?php foreach (json_decode($profile->rate_fixed) as $key => $rate) : ?>
									<td width="170px">
										From
										<select name="airport_from_<?php echo $key; ?>" style="width:120px;">				
											<?php foreach ($this->busAirport as $kbus => $dataBus) : ?>
												<?php if($dataBus->code == $rate->airport_from) : ?>
													<option value="<?php echo $dataBus->code; ?>" selected><?php echo $dataBus->code; ?></option>
												<?php else: ?>
													<option value="<?php echo $dataBus->code; ?>"><?php echo $dataBus->code; ?></option>
												<?php endif; ?>
												
											<?php endforeach; ?>
										</select>
									</td>
									<td width="160px">
										To
										<select name="airport_to_<?php echo $key; ?>" style="width:120px;">
											<?php foreach ($listAirport as $k => $value):?>
												<?php if($value->code == $rate->airport_to) : ?>
													<option value="<?php echo $value->code; ?>" selected><?php echo $value->code; ?></option>
												<?php else: ?>
													<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>										
									</td>
									<td width="160px">
										<input type="text" value="<?php echo $rate->rate; ?>" name="rate_<?php echo $key; ?>" style="width: 120px;" class="required">
										<span class="currency">EUR</span>
									</td>
									<?php if($key > 0): ?>
									<td>
										<div class="s-button rowbutton">
											<a class="s-button modal" rel="{handler: 'iframe', size: {x: 460, y: 100}}" href="<?php echo $removeRateFixed.$rate->airport_to.'&rate='.$rate->rate;?>">
												<?php echo JText::_('COM_SFS_TAXI_REMOVE');?>
											</a>
										</div>
									</td>
									<?php endif; ?>
								</tr>	
								<?php endforeach; ?>				
							</table>
							<table>
								<div class="viewAddrate">
									<!-- <input type="hidden" name="count" class="countRow" value="0" > -->
									<input type="hidden" name="count" class="countRow" id="rowcount" value="<?php echo count(json_decode($profile->rate_fixed)) - 1; ?>" >
								</div>
							</table><div class="clear"></div>
							
						</div>									
						<div class="s-button" style="margin-top: 20px;">
							<a class="s-button" id="newAddrateFixed">Add Rate</a>	    
						</div>
						<input type="hidden" name="id" value="<?php echo $profile->id?>" />
					</div>
				<?php endif; ?>
			<?php endif; ?>			
			<?php endforeach;?>
			
			<div class="clear"></div>
		
			<!-- <div class="midpaddingtop">	
				<div class="s-button">
					<a class="s-button" id="newProfileButton">+ Additional bus profile</a>	    
				</div>
			</div> -->	
		<?php else : ?>
		<div style="float: left; margin-left: -150px; width: 450px;">
			<div class="register-field clear floatbox largemargintop">
				<label>Profile name</label> 
				<input type="text" name="profiles[0][name]" value="" class="required" />
			</div>
			<div class="register-field clear floatbox">
				<label class="textindent25">Number of seats / capacity</label> 
				<input type="text" name="profiles[0][seats]" value="" class="required" />
			</div>
			<div class="register-field clear floatbox">
				<table>
					<tr>
						<td width="130px">Distance from (km)</td>
						<td width="130px">Distance until (km)</td>
						<td width="130px">Rate</td>
					</tr>
					<tr>
						<td width="130px">
							<input type="text" value="0" name="rate_first_0" style="width: 120px;" class="required">
						</td>
						<td width="130px">
							<input type="text" name="rate_second_0" style="width: 120px;" class="required">
						</td>
						<td width="160px">
							<input type="text" name="rate_three_0" style="width: 120px;" class="required">
							<span class="currency">EUR</span>
						</td>
					</tr>					
				</table>
				<table>
					<div class="viewAddrate">
						<input type="hidden" name="count" class="countRow" id="rowcount" value="0" >
					</div>
				</table><div class="clear"></div>
				<div class="s-button" style="margin-top: 20px;">
					<a class="s-button" id="newAddrate">Add Rate</a>	    
				</div>
			</div>	
		</div>

		<div class="noteview">
			<div class="noteimg">
				<img src="<?php echo JURI::root().'media/media/images/upload_organ.png' ?>" width="25px" style="margin: 10px 0 0 8px;">
			</div>
			<div class="noteinfo">
				Please note:
				Rates are based on the distance of a one way trip from or to the airport. <br />
				And please always start the rate at 0 km and add at least prices until a distance of 350 km.
			</div>
		</div>		
		<?php endif;?>	
		
	</div>
	
	<script type="text/javascript">
		var count = jQuery('input#rowcount').val();		

		jQuery("#newAddrate").click(function(){
			count++;
			var html = '';
			
			jQuery(".countRow").remove();
			html += '<tr style="float: left; margin: 10px 0; width: 100%">';
			html += '<td width="134px"><input type="text" name="rate_first_'+count+'" style="width: 120px;" class="required"></td>';
			html += '<td width="134px"><input type="text" name="rate_second_'+count+'" style="width: 120px;" class="required"></td>';
			html += '<td width="160px"><input type="text" name="rate_three_'+count+'" style="width: 120px;" class="required"><span class="currency">EUR</span></td>';
			html += '</tr>';
			html += '<input type="hidden" name="count" class="countRow" value="'+count+'" >';
			
			jQuery(".viewAddrate").append(html);

		});

		jQuery("#newAddrateFixed").click(function(){
			count++;
			var html = '';
			
			jQuery(".countRow").remove();
			html += '<tr style="float: left; margin: 10px 0; width: 100%">';
			html += '<td width="170px">From<select name="airport_from_'+count+'" style="width:120px;">';
				<?php foreach ($this->busAirport as $kb => $data_bus) : ?>
					html +='<option value="<?php echo $data_bus->code; ?>"><?php echo $data_bus->code; ?></option>';
				<?php endforeach; ?>

			html += '</select></td>';
			html += '<td width="160px">';
				
			html +='To<select name="airport_to_'+count+'" style="width:120px;"><option value="0">--Airport--</option>';
				<?php foreach ($listAirport as $key => $value):?>
				html +=	'<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>';
				<?php endforeach; ?>											
			html +='</select></td>';
			html += '<td width="160px"><input type="text" name="rate_'+count+'" style="width: 120px;" class="required"><span class="currency"> EUR</span></td>';
			html += '</tr>';
			html += '<input type="hidden" name="count" class="countRow" value="'+count+'" >';
			
			jQuery(".viewAddrate").append(html);
		});
	</script>
	

</fieldset>
</div>		        
</div>
</div>

<div class="sfs-below-main">
    <div class="s-button">
        <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=profiles&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
    </div>
    <div class="s-button float-right">
        <input type="submit" class="validate s-button"	value="<?php echo JText::_('JSAVE');?>">
    </div>
</div>

<div>
    <input type="hidden" name="task" value="bus.saveProfiles" /> 
    <input type="hidden" name="option" value="com_sfs" />        
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
    <?php echo JHtml::_('form.token'); ?>
</div>
    
</form>

</div>
