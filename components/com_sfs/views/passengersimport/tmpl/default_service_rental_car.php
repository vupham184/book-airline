<?php
defined('_JEXEC') or die;
$script_check='';
?>
<style type="text/css">
	
	.frm-rental td{
		border: 2px solid #e1f2fa;
	}
	.rental-location td{
		border: 0px;
	}
	#save-rental{background: #ff8806; padding: 7px 25px; color:#ffffff;float: right;margin-right: 10px;}
	.icon-warning{
		float: left;	
	}
	.message-rental{
		background:#ffefbe ;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		padding:5px;
		margin-bottom: 5px;
		width: 90%;
		margin: 0 auto;
	}	
	
	.info-service-rental.service-active{
		background: #eeeeee;	
	}
	.info-service-rental.service-active .tableInfo{
		background: none;		
	}
	.info-service-header-rental.service-active .tableInfo td{
		border-bottom: 2px solid #eeeeee!important;
	}
	.frm-rental{
		background:#fff;
		width: 99%;
	}
	.info-service-rental.service-active span
	{
		color: #000;
	}
	.rental-location td{
		padding: 0px;
	}
	.rental-location select{
		height: 30px;
	}
	.btn-open:hover{
		cursor: pointer;
	}
	.info-service-header-rental.service-active .tableInfo{
		background: #eeeeee;
	}
</style>

<div class="info-service-header-rental"  style="display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr>			
			<td width="40%">Rental cars</td>
			<td width="10%">
				<img id="rental_car" src="<?php echo JURI::root().'media/media/images/VoucherNOK.png' ?>">
			</td>	
			<td width="40%" class="title-header-rental">Select a rental agency</td>
			<td  width="10%"><div id="btn-rental-car" class="btn-open"><span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/></div></td>
		</tr>							
	</table>
</div>
<div class="info-service-rental" style="display: none;">
	<table class="tableInfo" cellpadding="0" cellspacing="0" border="0">
		<tr id="rental-car-content">	
			<td colspan="4">
				<div class="message-rental">
					<div class="icon-warning">
						<img align="left" style="margin-right:5px" src="<?php echo JURI::root().'media/media/images/warning.png' ?>">	
						You can choose your rental car from either Hertz or Sixt please always phone the rental angency to make sure there is an rental car available
					</div>
					<div style="clear: both;"></div>			
				</div>
				<br/>
				<table class="frm-rental" width="100%" cellpadding="0" cellspacing="0" border="0">
				<?php foreach($this->rentalcars as $ren){
					$script_check.=' $("#rental-toggle-'.$ren->id.'").click(function() {
		        var explode = function(){
		        	if($("#rental-toggle-'.$ren->id.'").hasClass("checked")){
		        		$(".title-header-rental").html($("#name-rental-'.$ren->id.'").val());
		        		$("#rental_car").attr("src", "'.JURI::root().'media/media/images/VoucherOK.png");
		        	}else{
		        		$("#rental_car").attr("src", "'.JURI::root().'media/media/images/VoucherNOK.png");
		        		$(".title-header-rental").html("Select a rental agency");
		        	}		        	
		        	$("#rental-location-'.$ren->id.'").slideToggle( "slow" );	
		        	deleterental('.$ren->id.');
				};
				setTimeout(explode, 200);

		    });';
				?>
				<form id="frm-rental-<?php echo $ren->id ?>" action="" method="POST">
				<tr>
					<td width="20%"  style="border-right:0;vertical-align: top;padding-top: 18px; ">
						<div id="rental-toggle-<?php echo $ren->id; ?>"  class="ui toggle checkbox checked">
					        <input name="rental-<?php echo $ren->id; ?>" type="checkbox" id="rental-<?php echo $ren->id; ?>" value="<?php echo $ren->id; ?>">		

					    </div>
					</td>
					<td width="20%"  style="border-left:0;border-right:0;vertical-align: top;padding-top: 18px; ">
						<img style="width:60px" src="<?php echo JURI::root().$ren->logo ?>">
						<?php ?>
					</td>
					<td width="60%" style="border-left:0; ">
						<p><span>Local office: <?php echo $ren->location_name.' '.$ren->address.','.' '.$ren->address.' '.$ren->zipcode.' '.$ren->city; ?></span>
						<input type="hidden" name="name-rental-<?php echo $ren->id; ?>" id="name-rental-<?php echo $ren->id; ?>" value="<?php echo $ren->company; ?>" />
						</p>
						<br/>
						<p><span>Phone:<?php echo $ren->telephone; ?></span></p>
						<div class="rental-location" id="rental-location-<?php echo $ren->id; ?>" style="display:none;">
						
							<table>
								<tr>
									<td style="border-bottom:0px;">Pick up location: </td>
									<td style="border-bottom:0px;">
										<select onchange="submitfrom(<?php echo $ren->id ?>)" name="pick-up-<?php echo $ren->id ?>" id="pick-up-<?php echo $ren->id ?>">
											<option value="">------Select a location-----</option>
											<?php 
											foreach($ren->rentallocation as $lo){
												if($this->airline->airport_id==$lo->airportcode){


												?>
													<option value="<?php echo $lo->id; ?>" <?php if($lo->name_code==$this->airline->airport_code) echo 'selected'; ?>><?php echo $lo->name_code.' '.$lo->city.' '.$lo->name_airport; ?></option>
												<?php
													}
												}?>
										</select>
									</td>
								</tr>
								<tr>
									<td style="border-bottom:0px;">
										Drop off location:
									</td>
									<td style="border-bottom:0px;">
										<select onchange="submitfrom(<?php echo $ren->id ?>)" name="drop-off-<?php echo $ren->id ?>" id="drop-off-<?php echo $ren->id ?>">
												<option value="">------Select a location-----</option>
											<?php foreach($ren->rentallocation as $lo){
												?>
													<option value="<?php echo $lo->id; ?>"><?php echo $lo->name_code.' '.$lo->city.' '.$lo->name_airport; ?></option>
												<?php
												}?>
										</select>
									</td>
								</tr>
							</table>					
						</div>
					</td>
				</tr>		
				<input type="hidden" name="rental_service_<?php echo $ren->id; ?>" id="rental_service_<?php echo $ren->id; ?>" value="">
				<input type="hidden" name="rental_airportcode_<?php echo $ren->id; ?>" id="rental_airportcode_<?php echo $ren->id; ?>" value="<?php echo $ren->airport_code; ?>">
				<input type="hidden" name="rental_airline_<?php echo $ren->id; ?>" id="rental_airline_<?php echo $ren->id; ?>" value="<?php echo $ren->airline_id; ?>">
				</form>
				<?php
					} ?>	
				</table>
				<br/>
				<div style="clear: both;"></div>
				<div class="btn-save-rental"><a id="save-rental" href="#">SAVE</a></div>
			</td>
		</tr>
	</table>
	<div style="clear: both;"></div>
<script>
    jQuery(function($){
    	<?php echo $script_check; ?>    
    	$( "#btn-rental-car" ).click(function() {
    		if($( "#btn-rental-car span" ).text()=="Open"){    			
    			$( ".info-service-header-rental" ).addClass('service-active');
    			$( ".info-service-rental" ).addClass('service-active');    			
    			$( "#btn-rental-car" ).html('<span>Close<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-up.png' ?>"/>');
    		}else{
    			$( ".info-service-header-rental" ).removeClass('service-active');    			
    			$( ".info-service-rental" ).removeClass('service-active');    			
    			$( "#btn-rental-car" ).html('<span>Open<span><img src="<?php echo JURI::root().'media/media/images/select-pass-icons/arrow-down.png' ?>"/>');
    		}
		  	$(".info-service-rental").slideToggle( "slow" );	
		});	
    });
    function submitfrom(rental_id){    	
    	if(checkempty(rental_id)==true){   		
    		var pas_id_chk=[];	
    		jQuery('.page_passenger_import input[type="checkbox"]').each(function(index, element) {
				if ( jQuery(this).is(':checked') ) {
					pas_id_chk.push(jQuery(this).attr('id'));					
				}
			});
			if(pas_id_chk.length>0){
				jQuery.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.bookServiceRental&format=raw'; ?>",
                type:"POST",
                data:{
                	pass_ids:pas_id_chk,
                    rental_id: rental_id,
                   	group_id:jQuery('#'+pas_id_chk[0]).attr('data-group_id'),
                    pick_up_id:jQuery('#pick-up-'+rental_id).val(),
                    drop_off_id:jQuery('#drop-off-'+rental_id).val(),                    
                    rental_airportcode_id:jQuery('#rental_airportcode_'+rental_id).val(), 
                    group_service:jQuery('#group-service').val(),
                    airline_id:jQuery('#rental_airline_'+rental_id).val()                    
                },
                dataType: 'json',
                success:function(json){                						
                    jQuery('#rental_service_'+rental_id).val(json.id);
                    jQuery('.message-rental').css('display','none');
                    
                }
            	});
			}
    	}
    }
    function checkempty(rental_id){        	
    	if(jQuery('#pick-up-'+rental_id).val()==''||jQuery('#drop-off-'+rental_id).val()==''){
    		return false;
    	}
    	if(jQuery('#pick-up-'+rental_id).val()==jQuery('#drop-off-'+rental_id).val()){
    		//return false;	
    	}
    	return true;
    }
    function deleterental(rental_id){
    	if(checkempty(rental_id)==true){
    		var pas_id_chk=[];	
    		jQuery('.page_passenger_import input[type="checkbox"]').each(function(index, element) {
				if ( jQuery(this).is(':checked') ) {
					pas_id_chk.push(jQuery(this).attr('id'));					
				}
			});
			if(pas_id_chk.length>0){
				jQuery.ajax({
	                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.deletebookServiceRental&format=raw'; ?>",
	                type:"POST",
	                data:{
	                	pass_ids:pas_id_chk,
	                    rental_id: rental_id,
	                   	group_id:jQuery('#'+pas_id_chk[0]).attr('data-group_id'),
	                    pick_up_id:jQuery('#pick-up-'+rental_id).val(),
	                    drop_off_id:jQuery('#drop-off-'+rental_id).val(),                    
	                    rental_airportcode_id:jQuery('#rental_airportcode_'+rental_id).val(), 
	                    group_service:jQuery('#group-service').val(),     
	                    airline_id:jQuery('#rental_airline_'+rental_id).val()                 
	                },
	                dataType: 'text',
	                success:function(response){
	                    
	                }
	            });
	            jQuery("#pick-up-"+rental_id).val('');
	            jQuery('#drop-off-'+rental_id).val('');
	            jQuery('#rental_service_'+rental_id).val('');  
			}
			          
    	}
    }
</script>
</div>