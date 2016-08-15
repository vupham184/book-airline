<?php
defined('_JEXEC') or die;
//JHtml::_('behavior.mootools');
JHtml::_('behavior.framework');
JHtml::_('behavior.keepalive');

?>
<style type="text/css">
.EditRowFixed{
	cursor: pointer;
	color: #1da9b8;
	font-size: 12px;
	font-weight: bold;
}
.EditRowFixed:hover{
	color: #ccc;
	font-size: 12px;
	font-weight: bold;
}
.showEdit{display:none;}
ul li{list-style: none;}
.titleTable{float:left; width: 100%;background: #eee; padding: 7px 5px; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc; font-weight: 700;color:#666;}
.title_id{float: left;width: 5%;}
.title_name{float: left;width: 20%;}
.title_info{float: left;width: 15%;}
.title_rate{float: left;width: 45%;}
.infoTable{float: left; width: 100%; font-size: 12px; color: #666; padding: 8px 3px;border-bottom: 1px solid #ccc; }
.le{background: #ebf1f2;}
.title_info_{float:left; width: 18%;}
.title_info__{float:left; width: 15%;}

ul.list_rate li{float: left; width: 100%; height: 30px;}
.airport_from{float: left; width:40%;}
.airport_rate{float: left; width:20%;}
.airport_from_edit{float: left; width:30%;}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">/*jquery*/</script>
<script type="text/javascript">
	jQuery(function($){
		$( document ).ready(function() {
		    
		});	
	
	});

	function editRow_(val){
		var id 			= $(".form_id_" + val).val(),
			name 		= $(".form_name_" + val).val(),
			seats 		= $(".form_seats_" + val).val(),
			hidd 		= $(".hidden_count_" + val).val(),
			group_id 	= $(".form_group_id_" + val).val();

		var arrRateFixed = [];

		for(var i = 0; i < hidd; i++){
			var from 	= $(".form_airport_from_" + i).val(),
				to 		= $(".form_airport_to_" + i).val(),
				rate 	= $(".form_rate_" + i).val();

			arrRateFixed.push({"airport_from": from, "airport_to": to, "rate": rate});
		}

		$.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=grouptransport.saveRateFixededit'; ?>",
            type:"POST",  
            data: {"id": id, "name": name, "seats": seats,"group_id": group_id, "rate_fixed": arrRateFixed },              
            dataType: 'json',                
            success:function(data){                     
                if(data.status == "ok"){
                	location.reload();
                }     
            }
        }); 
	}

	function EditRowFixed_(index, id){

    	jQuery('.sub_table_'+index).slideToggle(500);
    }	
</script>
<div>	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" id="groupTransportTypeForm" name="groupTransportTypeForm">
	
		<fieldset>
			<div class="fltrt">				
				<button onclick="window.parent.location.href=window.parent.location.href;window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				<?php
				$title = '';; 				
				if($this->groupTransport){
					$title .= $this->groupTransport->name.': Types';	
				} 
				echo $title;			
				?>
			</div>
		</fieldset>
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
	
			<div style="margin-bottom:20px;" id="updateResult">
			<?php if( count($this->groupTransportFixed->types) ) : ?>
				
				<div class="titleTable">
					<div class="title_id">ID</div>
					<div class="title_name">Name</div>
					<div class="title_info">Seats</div>
					<div class="title_info">Airport From</div>
					<div class="title_info">Airport To</div>
					<div class="title_info">Rate (km)</div>
					<div class="title_info"></div>
				</div>
				<?php $count = 0; ?>
				<?php foreach ($this->groupTransportFixed->types as $type) : ?>					
					<div class="infoTable <?php echo $count%2 == 0 ? "chan" : "le"; ?>">
						<div class="title_id"><?php echo $type->id;?></div>
						<div class="title_name"><?php echo $type->name;?></div>
						<div class="title_info"><?php echo $type->seats;?></div>
						<div class="title_rate">
							<ul class="list_rate">
								<?php $rates = json_decode($type->rate_fixed); foreach ($rates as $krate => $rate) : ?>
									<li>
										<div class="airport_from"><?php echo $rate->airport_from; ?></div>
										<div class="airport_from"><?php echo $rate->airport_to; ?></div>
										<div class="airport_rate"><?php echo $rate->rate; ?></div>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
						
						<div class="title_info">
							<div class="EditRowFixed" onclick="EditRowFixed_(<?php echo $count;?>,<?php echo $type->id;?>)">Edit</div>
						</div>
					</div>
					<div class="sub_table_<?php echo $count; ?>" style="display:none;float:left; width: 100%; padding: 20px 5px; background: #dfdfdf;">
						<input type="hidden" name="id" class="form_id_<?php echo $count; ?>" value="<?php echo $type->id;?>">
						<input type="hidden" name="group_id" class="form_group_id_<?php echo $count; ?>" value="<?php echo $type->group_transportation_id;?>">
						<input type="hidden" name="stt" class="form_arr" value="<?php echo $k;?>">
						<div class="title_info_">
							<input type="text" name="name" class="form_name_<?php echo $count; ?>" style="width:90px"  value="<?php echo $type->name;?>">
						</div>
						<div class="title_info_">
							<input type="text" name="seats" class="form_seats_<?php echo $count; ?>" style="width:90px" value="<?php echo $type->seats;?>">
						</div>
						<div class="title_rate">
							<ul class="list_rate">
								<?php $rates = json_decode($type->rate_fixed); foreach ($rates as $krate => $rate) : ?>
									<li>
										<div class="airport_from_edit">										
											<select name="" class="form_airport_from_<?php echo $krate; ?>">
												<?php foreach ($this->listAirportTo as $key => $value) : 
													if ($value->code == $rate->airport_from):
												 ?>
													<option value="<?php echo $value->code; ?>" selected><?php echo $value->code; ?></option>
												<?php else: ?>
													<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>
												<?php endif; endforeach; ?>
											</select>
										</div>
										<div class="airport_from_edit">
											<select name="airport_to" class="form_airport_to_<?php echo $krate; ?>">
												<?php foreach ($this->listAirport as $key => $value) : 
													if ($value->code == $rate->airport_to):
												?>
													<option value="<?php echo $value->code; ?>" selected><?php echo $value->code; ?></option>
												<?php else: ?>
													<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>
												<?php endif; endforeach; ?>
											</select>
										</div>
										<div class="airport_rate">											
											<input type="text" name="rate" class="form_rate_<?php echo $krate; ?>" value="<?php echo $rate->rate;?>" style="width:90px">	
										</div>
									</li>
								<?php endforeach; ?>
							</ul>							
						</div>
						<div class="title_info__">
						<input type="hidden" value="<?php echo count(json_decode($type->rate_fixed)); ?>" class="hidden_count_<?php echo $count; ?>">
							<button id="editRow" type="button" onclick="editRow_(<?php echo $count; ?>)">Save</button>
						</div>
					</div>					
				<?php $count++; endforeach; ?>
		
			<?php endif;?>
			</div>
			
			<table>			
				<tr>
					<td>
						Name: <input type="text" name="name" value="" class="inputbox required">
					</td>
					<td>
						Seats: <input type="text" name="seats" value="" class="inputbox required">
					</td>
					<td>
						From: 
						<select name="airport_from">
							<?php foreach ($this->listAirportTo as $key => $value) : ?>
								<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>
							<?php endforeach; ?>
						</select>
						
					</td>
					<td>
						To: 
						<select name="airport_to">
							<?php foreach ($this->listAirport as $key => $value) : ?>
								<option value="<?php echo $value->code; ?>"><?php echo $value->code; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						Rate: <input type="text" name="rate" value="" class="inputbox required">
					</td>									
				</tr>
				<tr style="height: 20px;">&#160;</tr>
				<tr>
					<td colspan="5">
						<button type="submit" id="addGroupType">Add</button>
					</td>
				</tr>
			</table>			
							
		</div>
		
		<!-- <input type="hidden" name="task" value="ajax.saveTranportTypes" />  -->
		<input type="hidden" name="task" value="grouptransport.addTypeFixed" /> 
		<input type="hidden" name="option" value="com_sfs" />
		<!-- <input type="hidden" name="format" value="raw" />		 -->
		<input type="hidden" name="id" value="<?php echo JRequest::getInt('id')?>" />
				
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>



