<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$params = JComponentHelper::getParams('com_sfs');
$systemCurrency = $params->get('sfs_system_currency');

$listAirport = SfsHelper::getListAirport();
?>
<style type="text/css">
	th{
		background: #dfdfdf;
		border-bottom: 1px solid #b1b0ae;
		border-top: 1px solid #b1b0ae;
		padding: 5px 0;
	}
	table.floatbox {
    	border-collapse: collapse;
    	float: left;
    	width: 450px;
	}
	table.floatbox, tr.test td{
	    border: 1px solid #e4e9ec;
	}

	td{padding: 5px; text-align: center;}
	
	.showDetailRate{float: left; width: 100%}
	.fs-16{border-bottom: 3px solid #e4e9ec; padding-bottom: 20px;}
	.editrate{float: left;width: 70px;text-align: center; background: #ff8806; padding: 5px 0; margin-left: 50px; margin-bottom: 0;}
	.loopListRate{padding-left: 100px; float: left; border-bottom: 3px solid #e4e9ec;padding-bottom: 30px;}
	.loopListFixedDetail{padding-left: 100px; float: left; border-bottom: 3px solid #e4e9ec;padding-bottom: 30px;}
	.sfs-below-main{margin-top: 20px;}
	.noteview{
		float:left; width: 400px; margin-left: 40px;
		background: #ffefcf; border-radius: 7px;
	}
	.noteimg{ float: left; }
	.noteinfo{float: left; width: 360px; padding: 15px 10px;}
	.controllRate{float: left; width: 100%;}
	.controllRate{margin-top: 20px;}
	.removeadd, .removeaddFixed, .back{float: left; width: 49%;}
	
	.btDistance{float:left; width: 48%;}
	#addFixedRate, #addDistanceRate{
		width: 250px;margin-left:100px;background-color: #ff8806;border:none;color: #FFFFFF;font-weight: 600;
	}
	.addRateDistanceFixed{float:left;width:100%;margin-top:40px;}
</style>

<script type="text/javascript">
jQuery(function($){
	var arrFixed = []; var arrDis = [];
	$("#addDistanceRate").on('click', function(event) {
		<?php foreach ($this->profiles as $ke => $valDis): ?>
			<?php if(!empty($valDis->rate) ): ?>
				arrDis.push(<?php echo $ke ?>);	
			<?php endif; ?>
		<?php endforeach; ?>

		if(parseInt(arrDis.length) > 0){
			$('.loopListRate').css("display","block");
			$('.removeadd').css("display","block");
			$('.back').css("display","block");
			$('.addRateDistanceFixed').css("display","none");
		}else{
			$(".controllRate").css('display','block');
			$('.addRateDistanceFixed').css("display","none");
		}	
		
	});

	
	$("#addFixedRate").on('click', function(event) {
		<?php foreach ($this->profiles as $key => $value): ?>
			<?php if(!empty($value->rate_fixed) ): ?>
				arrFixed.push(<?php echo $key ?>);	
			<?php endif; ?>
		<?php endforeach; ?>
		
		
		if(parseInt(arrFixed.length) == 0){
			$('.loopListFixed').css("display","block");				
			$('.addRateDistanceFixed').css("display","none");
		}else{
			$('.loopListFixedDetail').css("display","block");	
			$('.removeaddFixed').css("display","block");
			$('.back').css("display","block");	
			$('.addRateDistanceFixed').css("display","none");
		}

		
	});
});
</script>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Rates</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main fs-14">
	<div class="sfs-main-wrapper-none">        
    <div class="sfs-orange-wrapper">
    
	    <div class="sfs-white-wrapper floatbox">
			
			<div class="fs-16">
				 Your Bus Profiles 
				<div class="fs-14 smallpaddingtop greycolor">
					Rates are based on the distance of a one way trip from or to the airport. <br />
					You can add or edit any profiles but you can only disable an profile if there are existing bookings made for them.
				</div>
			</div>				
			<div class="addRateDistanceFixed">
				<div class="btDistance">
					<input type="button" id="addFixedRate" value="ADD FIXED RATES" >
				</div>
				<div class="btDistance">
					<input type="button"  id="addDistanceRate" value="ADD DISTANCE RATES" >
				</div>
			</div>

			
			

			<?php if( count($this->profiles) ) : ?>
				<?php foreach ($this->profiles as $key=>$profile ) :
					$link = 'index.php?option=com_sfs&view=bus&tmpl=component&layout=rate&profile_id='.$profile->id;
				?>
				<?php if($profile->rate != ""): ?>
					<div class="loopListRate" style="display:none;">
						<div class="showDetailRate">
							<div class="register-field clear floatbox largemargintop">
								<label>Profile name</label> 
								<?php echo $profile->name; ?>
							</div>
							<div class="register-field clear floatbox largemargintop">
								<label>Number of seats / capacity</label> 
								<?php echo $profile->seats; ?>
							</div>
						</div>
						<div class="showDetailRate" style="margin-left: 30px;">
							<table class="floatbox" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td>Distance from (km)</td>
									<td>Distance until (km)</td>
									<td>Rate</td>
								</tr>
								<?php $rates = json_decode($profile->rate);
									foreach ($rates as $key => $rate): 
								?>
									<tr class="test">
										<td><?php echo $rate->rate_first; ?></td>
										<td><?php echo $rate->rate_second; ?></td>
										<td><?php echo $rate->rate_three; ?>   EUR</td>
									</tr>
								<?php endforeach; ?>
							</table>
							<div class="editrate">
								<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=editprofile&Itemid='. $profile->id) ?>" style="color:#ffffff;"><?php echo JText::_('COM_SFS_EDIT');?></a>
							</div>  
						        
					        
						</div>
					</div>
				<?php else: ?>
					<div class="loopListFixedDetail" style="display:none;">
						<div class="showDetailRate">
							<div class="register-field clear floatbox largemargintop">
								<label>Profile name</label> 
								<?php echo $profile->name; ?>
							</div>
							<div class="register-field clear floatbox largemargintop">
								<label>Number of seats / capacity</label> 
								<?php echo $profile->seats; ?>
							</div>
						</div>
						<div class="showDetailRate" style="margin-left: 30px;">
							<table class="floatbox" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td>From</td>
									<td>To</td>
									<td>Rate</td>
								</tr>
								<?php $rates_fixed = json_decode($profile->rate_fixed);
									foreach ($rates_fixed as $key => $rate): 
								?>
									<tr class="test">
										<td><?php echo $rate->airport_from; ?></td>
										<td><?php echo $rate->airport_to; ?></td>
										<td><?php echo $rate->rate; ?>   EUR</td>
									</tr>
								<?php endforeach; ?>
							</table>
							<div class="editrate">
								<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=bus&layout=editprofile&Itemid='. $profile->id) ?>" style="color:#ffffff;"><?php echo JText::_('COM_SFS_EDIT');?></a>
							</div>  
						        
					        
						</div>
					</div>
				<?php endif; ?>
				<?php endforeach;?>
			<?php endif; ?>

			<div class="loopListFixed" style="display:none;">
				<form method="post" action="">
			        <div class="formAddrate" style="float: left; width: 640px;">
						<div class="register-field clear floatbox largemargintop">
							<label>Profile name</label> 
							<input type="text" name="profiles[0][name]" value="" class="required" />
						</div>
						<div class="register-field clear floatbox">
							<label class="textindent25">Number of seats / capacity</label> 
							<input type="text" name="profiles[0][seats]" value="" class="required" />
						</div>
						<div class="register-field clear floatbox">
							<table class="addrate">
								<tr>
									<td width="130px">&#160;</td>
									<td width="130px">&#160;</td>
									<td width="130px">Rate</td>
								</tr>
								<tr>
									<td width="170px">
										From
										<?php if( count($this->busAirport) > 1 ) : ?>
											<select name="airport_from_0" style="width:120px;">				
											<?php foreach ($this->busAirport as $kbus => $dataBus) : ?>
												<option value="<?php echo $dataBus->code; ?>"><?php echo $dataBus->code; ?></option>
											<?php endforeach; ?>
										<?php else: ?>
											<input type="text" name="airport_from_0" style="width: 120px;"  value="<?php echo $this->busAirport[0]->code; ?>" readonly>
										<?php endif; ?>
										
									</td>
									<td width="150px">
										To
										<select name="airport_to_0" style="width:120px;">
											<option value="0">--Airport--</option>
											<?php foreach ($listAirport as $key => $value):?>
												<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>
											<?php endforeach; ?>											
										</select>
									</td>
									<td width="160px">
										<input type="text" name="rate_0" style="width: 120px;" class="required">
										<span class="currency">EUR</span>
									</td>
								</tr>					
							</table>
							<table>
								<div class="viewAddrate">
									<input type="hidden" name="count" class="countRow" value="0" >
								</div>
							</table>
							<div class="clear"></div>
							<div class="s-button" style="margin-top: 20px;">
								<a class="s-button" id="newAddrateFixed">Add Rate</a>	    
							</div>
						</div>	
					</div>
					
					<div style="float: left; width: 100%;">
						<div class="s-button float-right" >
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
		</div>    	
	        
    </div>
    </div>    
    
    <div class="sfs-below-main">
    	<!-- <div class="s-button">
	        <a href="<?php //echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid')) ?>" class="s-button"><?php //echo JText::_('COM_SFS_BACK');?></a>
        </div> -->
        <div class="controllRate" style="display: none;">
        <form method="post" action="">
	        <div class="formAddrate" style="float: left; width: 440px;">
				<div class="register-field clear floatbox largemargintop">
					<label>Profile name</label> 
					<input type="text" name="profiles[0][name]" value="" class="required" />
				</div>
				<div class="register-field clear floatbox">
					<label class="textindent25">Number of seats / capacity</label> 
					<input type="text" name="profiles[0][seats]" value="" class="required" />
				</div>
				<div class="register-field clear floatbox">
					<table class="addrate">
						<tr>
							<td width="130px">Distance from</td>
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
							<input type="hidden" name="count" class="countRow" value="0" >
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
			<div style="float: left; width: 100%;">
				<div class="s-button float-right" >
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
		<script type="text/javascript">
			var count = 0;
			

			jQuery("#newAddrate").click(function(){
				count++;
				var html = '';
				
				jQuery(".countRow").remove();
				html += '<tr style="float: left; margin: 10px 0; width: 100%">';
				html += '<td width="134px"><input type="text" name="rate_first_'+count+'" style="width: 120px;" class="required"></td>';
				html += '<td width="134px"><input type="text" name="rate_second_'+count+'" style="width: 120px;" class="required"></td>';
				html += '<td width="160px"><input type="text" name="rate_three_'+count+'" style="width: 120px;" class="required"><span class="currency"> EUR</span></td>';
				html += '</tr>';
				html += '<input type="hidden" name="count" class="countRow" value="'+count+'" >';
				
				jQuery(".viewAddrate").append(html);

			});
			jQuery("#newAddrateFixed").click(function(){
				count++;
				var html = '';
				
				jQuery(".countRow").remove();
				html += '<tr style="float: left; margin: 10px 0; width: 100%">';
				html += '<td width="170px">&#160<select name="airport_from_'+count+'" style="width:120px;float:right;">';
				<?php if( count($this->busAirport) > 1 ) : ?>
					<?php foreach ($this->busAirport as $kb => $data_bus) : ?>
						html +='<option value="<?php echo $data_bus->code; ?>"><?php echo $data_bus->code; ?></option>';
					<?php endforeach; ?>
				<?php endif; ?>
				html += '</select></td>';
				html += '<td width="150px">';
					
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
		</div>

		<div class="back" style="display:none;">
			<div class="s-button" style="float:left; width:100px;"><a class="s-button" id="back">Back</a></div>
		</div>
        <div class="s-button removeadd" style="display:none;">
			<a class="s-button" id="newProfileButton">+ Additional bus profile</a>	    
		</div>
		<div class="removeaddFixed" style="display:none;">			
			<div class="s-button" style="float:right; width:120;"><a class="s-button" id="newProfileButtonFixed">+ Additional bus profile</a>	    </div>
		</div>



    </div>
            
    </div>
	<div class="clear"></div>
	
	
</div>

<script type="text/javascript">
	jQuery("#newProfileButton").click(function(){
		jQuery(".controllRate").show();
		jQuery(".removeadd").remove();
	});

	jQuery("#newProfileButtonFixed").click(function(){
		jQuery(".loopListFixed").show();
		jQuery(".removeaddFixed").remove();
	});

	jQuery("#back").click(function(){
		history.back(1);
	});
</script>