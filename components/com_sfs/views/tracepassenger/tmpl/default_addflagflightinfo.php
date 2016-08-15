<?php 
$link_Img = JURI::root().'media/media/images/select-pass-icons/';
$passenger = $this->item;
?>

<div class="content-add-flag-fi" style="display:none">
	<div class="btn-clode-add-flag">CLOSE</div>
	<div style="clear:both;"></div>
	<div class="list-info-flag-fi">
		<p><div class="btn-add-flag-fi-icon"></div><div class="list-info-flag-fi-title">Click Flags to add them to this flight</div></p>
		<div style="clear:both;"></div>
		<div class="info-flag-fi">
			<div class="info-flag-fi-left">
				<div class="flag-item-fi" onclick="add_flag_fi('INSU',<?php echo $passenger->rebook[0]->id  ?>)" style="margin-top:20px;cursor: pointer;">INSU</div>
				<div style="clear:both;"></div>
				<div class="flag-item-fi" onclick="add_flag_fi('FWD',<?php echo $passenger->rebook[0]->id;?>)" style="margin-top:20px;cursor: pointer;">FWD</div>
			</div>
			<div class="info-flag-fi-right">
				<p style="padding-top: 5px;">            							
            							From: 
            							<span style="margin-left: 50px">
            								<span style="color:#f00; ">
            									<?php echo $passenger->rebook[0]->dep; ?>
            								</span>
            								<!--<span style="color:#000; ">
            									<?php //echo (isset($passenger->dep) && $passenger->dep != '' ) ? '->' . $passenger->dep . ' ->' : ''; ?>
            								</span>-->

            								<?php echo ($passenger->rebook[0]->arr) ? '->'.$passenger->rebook[0]->arr : '';?>

            								<?php 
            								echo $passenger->airport_code . ' ' . $passenger->distance . ' ' . $passenger->distance_unit;?>

            							</span>
            							<br />
            					
            							Flightnumber: <?php echo $passenger->carrier;?>
            							<?php echo $passenger->flight_no;?>
            							<br />
            							Dep scheduled:<?php echo $passenger->std?><br />
            							Arr scheduled:<?php echo $passenger->etd?> 
            							<div style="clear:both;"></div>
            						</p>
			</div>
		</div>
		<div style="clear:both;"></div>
		<p><div class="title-fi-second">Please select all other flights with the same Aircraft that you would like to add the insurance or forwarded flag to for this day</div></p>
		<div style="clear:both;"></div>
		<div class="list-aircraft">
			<?php if($this->list_aircraft){?>
				<table class="tb-list-aircraft">
				<tr>
					<th></th>
					<th></th>
					<th style="text-align:left;">Aircraft</th>
					<th style="text-align:left;">Flightno</th>
					<th style="text-align:left;">From</th>
					<th style="text-align:left;">To</th>
					<th style="text-align:left;">STD</th>
					<th style="text-align:left;">ETD</th>
				</tr>
				<?php
				foreach ($this->list_aircraft as $key => $value) {
				?>
				<tr>
					<td><div class="flag-item-fi" style="margin-top:0px!important;cursor:pointer;" onclick="add_flag_fi('INSU',<?php echo $value->id; ?>)">INSU</div></td>
					<td><div class="flag-item-fi" onclick="add_flag_fi('FWD',<?php echo $value->id; ?>)" style="margin-top:0px!important;cursor:pointer;">FWD</div></td>
					<td  style="text-align:left;" valign="middle" ><?php echo $value->registration ?></td>
					<td style="text-align:left;" onclick="add_flag_fi(<?php echo $value->id; ?>)" valign="middle"><?php echo $value->flight_no ?></td>
					<td style="text-align:left;" valign="middle"><?php echo $value->dep ?></td>
					<td style="text-align:left;" valign="middle"><?php echo $value->arr ?></td>
					<td style="text-align:left;" valign="middle"><?php echo date("Y-m-d H:i", strtotime(str_replace("T"," ",$value->std))) ?></td>
					<td style="text-align:left;" valign="middle"><?php echo date("Y-m-d H:i", strtotime(str_replace("T"," ",$value->etd))) ?></td>
				</tr>
				<?php
					}
				?>
				</table>
				<?php
				} ?>

		</div>
	</div>
	<div style="clear:both;"></div>
</div>
<script type="text/javascript">
	function add_flag_fi(flag,flight_id) {
		var fi_id =flight_id;
		jQuery.ajax({
                url :"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.updateNameFlagFi&format=raw';?>",
                type:"POST",
                data:{
                    name_flag: flag,
                    fi_id: fi_id
                },
                success:function(response){
                    if(response == "1")
                    {
                        alert('Update success');
                    }
                    else
                        alert("ERROR!");
                }
            })
	}
</script>