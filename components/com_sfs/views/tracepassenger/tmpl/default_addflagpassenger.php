<?php 
$link_Img = JURI::root().'media/media/images/select-pass-icons/';
?>

<div class="content-add-flag-pass" style="display:none;">
	<div class="btn-clode-add-flag">CLOSE</div>
	<div style="clear:both;"></div>
	<div class="list-info-flag-pass">
		<p><div class="btn-add-flag-pass-icon"></div><div class="list-info-flag-pass-title">Click Flags to add them manuallu to the profile of the passenger</div></p>
		<p>
		<div style="clear:both;"></div>
			<table style="width: 100%;">
				<tr>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('INAD')">INAD</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('ERRH')">ERRH</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('FTC')">FTC</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('OPR')">OPR</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('OVB')">OVB</div>	
					</td>
				</tr>
				<tr>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('MISC')">MISC</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('ERRAB')">ERRAB</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" style="cursor:pointer;" onclick="add_flag_pass('OPREQ')">OPREQ</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" onclick="add_flag_pass('ACR')">ACR</div>	
					</td>
					<td width="20%">
						<div class="flag-item-pass" onclick="add_flag_pass('DWN')">DWN</div>	
					</td>
				</tr>
			</table>
			<div style="clear:both;"></div>
		</p>
		<div style="clear:both;"></div>
	</div>
	<div style="clear:both;"></div>
</div>

<script type="text/javascript">
	function add_flag_pass(flag) {
		var pass_id ='<?php echo JRequest::getInt('passenger_id',0); ?>';
		jQuery.ajax({
                url :"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.updateNameFlag&format=raw';?>",
                type:"POST",
                data:{
                    name_flag: flag,
                    pass_id: pass_id
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