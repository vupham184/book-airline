<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<style type="text/css">

.classname {position:absolute; left: -28px; -webkit-transform:  rotate(90deg);-moz-transform:  rotate(90deg);-o-transform:  rotate(90deg);writing-mode: lr-tb;top: 45px;}

#alt-toolbar-sfs.sticky-tools{
	top: 58px!important;
	background: #ddd!important;
}
.table-header-rotated td{
	padding:2px 3px;
}
.input-title{
	width:28px;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">/*jquery*/</script>
<form action="" method="post" name="adminForm" id="adminForm" class="max-list-h">
	<div class="d-title" style="padding-top:5px;">
		<label>Filter on airport code</label>
		<input type="text" name="airport_code" id="airport_code" value="<?php echo $this->state->get('filter.airport_code'); ?>">
		<button type="submit">Filter</button>
		<button onclick="jQuery('#airport_code').val('');
            this.form.submit();
            " type="button">Clear</button>		
		<div id="alt-toolbar-sfs">
			<table style="width: 460px;">
				
			</table>
			<table style="width: 460px;">
			<tr style="height: 20px;">
					<th  style="position: relative;" class="input-title"><label>Default</label></th>
					<th  style="position: relative; width:30px" ><label style="width:90px;">&nbsp</label></th>
					<th  style="position: relative;width:30px" class="input-title" ><label  style="width:90px">&nbsp</label></th>
					<?php foreach($this->services as $s): ?>
						<th style="position: relative;text-align:right;" class="input-title">
							<input type="checkbox" id="service-<?php echo $s->id; ?>" name="service-default[]" value="<?php echo $s->id; ?>"  data-airport=""/>
						</th>
					<?php endforeach;?>
				</tr>
			<tr style="height: 95px;">
				<th  style="position: relative;width:35px;"><label>Airport</label></th>
				<th  style="position: relative;width:36px;"><label class="classname" style="width:90px">All</label></th>
				<th  style="position: relative;width:36px;"><label class="classname" style="width:90px">Default</label></th>
				<?php foreach($this->services as $s): ?>
					<th style="position: relative;" class="input-title">
						<label class="classname" style="width:87px"><?php echo $s->name_service; ?></label>
					</th>
				<?php endforeach;?>
			</tr>
			</table>
		</div>
		
		<table class="table table-header-rotated" style="width: 460px;">
			
			<?php 
				$list_airport_empty = array();
				$d_row = 0;
				foreach($this->airportcodes as $a): 
					$d_row++;
					$flag=true;
				?>
				<tr class="data-row<?php echo $d_row; ?>">
					<td ><div style="width:41px;">
						<label><?php echo $a->code ?></label>
					</div></td>
					<td style="text-align:right"><input type="checkbox" class="checkAll" data-row="<?php echo $d_row;?>" data-airport="<?php echo $a->code.'_'.$a->id;?>" /></td>
					<td  style="text-align:right"><input type="checkbox" name="checkDefault" class="checkDefault"  data-airport="<?php echo $a->code.'_'.$a->id;?>"/></td>
					<?php foreach($this->services as $s): 					
							$selected='';
							if($this->services_selected){
								foreach($this->services_selected[$a->id] as $ss){
									if($ss==$s->id){
										$selected='checked';
										$flag = false;
										break;
									}
								}
							}
					?>
					<td class="service"  style="text-align:right">
						<input name="airports_code[<?php echo $a->code.'_'.$a->id;?>][]" class="services" value="<?php echo $s->id;?>" type="checkbox" class="padding-b2" <?php echo $selected; ?> data-airport="<?php echo $a->code.'_'.$a->id;?>" />
					</td>
				<?php 
				if($flag==true)
					$list_airport_empty[$a->code.'_'.$a->id] = $a->code.'_'.$a->id;
				endforeach;?>
				</tr>
			<?php endforeach;?>
		</table>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
	var airport_empty = [<?php echo '"'.implode('","',$list_airport_empty).'"'; ?>];
	var airport_default='';
	jQuery(function($){
		$('.checkAll').click(function(e) {
			var d_row = $(this).attr("data-row");
			var code_airport_current = $(this).attr("data-airport");
            if( $(this).is(":checked") ){    
     //        	if( $('input[name=checkDefault]:checked').is(':checked') ){
     //        		var code_airport = $('input[name=checkDefault]:checked').attr("data-airport");
     //        		$("input[name='airports_code["+code_airport+"][]']:checked").each(function () {
     //        				var val = $(this).val();
     //        			$("input[name='airports_code["+code_airport_current+"][]']").each(function (){
     //        				if($(this).val()==val){
     //        					$(this).prop( "checked", true );
     //        				}
     //        			});
					// });
            		
     //        	}   
     //        	else{
            		$('.data-row' + d_row + ' .service').find("input").prop( "checked", true );
            		removeairport(code_airport_current);	
            	// }   
			}
			else {
				$('.data-row' + d_row + ' .service').find("input").prop( "checked", false );
				addairport(code_airport_current);
			}
        });
        $('.checkDefault').click(function(e){			
        	if( $(this).is(":checked") ){				        		
				// $('.checkDefault').not(this).prop('checked', false);  
				// $(this).attr('checked', true);
				if($("input[name='service-default[]']:checked").length>0){
					var code_airport = $(this).attr("data-airport");
					$("input[name='service-default[]']:checked").each(function(){
						var chk_df = $(this).val();
						$("input[name='airports_code["+code_airport+"][]']").each(function(){
							if($(this).val() == chk_df){
								$(this).prop('checked', true);
								$(this).attr('checked', true);
							}
						});
					});
				}
				
				//airport_default = airport_empty;
				//airport_empty = [];
				/*$("input[name='airports_code["+code_airport+"][]']:checked").each(function(){
					var val_check = $(this).val();
					$.each(airport_default, function( index, value ) {
						$("input[name='airports_code["+value+"][]']").each(function(){
							if($(this).val() == val_check){
								$(this).prop('checked', true);
								$(this).attr('checked', true);
							}
						});
										  	
					});
					
				});*/

        	}  else{
        		if($("input[name='service-default[]']:checked").length>0){
	        		var code_airport = $(this).attr("data-airport");
	        		$("input[name='airports_code["+code_airport+"][]']").each(function(){
	        			$(this).prop('checked', false);
						$(this).attr('checked', false);
	        		});	
        		}
        		
        		/*if(airport_default.length>0){
        			$.each(airport_default, function( index, value ) {
        				$("input[name='airports_code["+value+"][]']").prop( "checked", false );
        				$("input[name='airports_code["+value+"][]']").attr( "checked", false );
        				
        				addairport(value);
        			});
					airport_default = [];					
        		}*/
        	}  	 
        });
        $('.services').click(function(e){
        	if( $(this).is(":checked") ){
        		if($("input[name='airports_code["+$(this).attr("data-airport")+"][]']").is(':checked')){
					removeairport($(this).attr("data-airport"));
	        	}
        	}else{        		
        		addairport($(this).attr("data-airport"));
        	}
        	
        });
	});
	function removeairport(airport_code){		
		if($("input[name='airports_code["+airport_code+"][]']").is(':checked')){
			airport_empty = jQuery.grep(airport_empty, function(value) {
			  return value != airport_code;
			});
			//console.log(airport_empty);
		}
	}
	function addairport(airport_code){
		var flag = false;		
		$.each(airport_empty, function( index, value ) {
			if(value == airport_code){
				flag = true;
			}					  	
		});
		if(flag == false){
			airport_empty.push(airport_code);
		}
		//console.log(airport_empty);
	}
	
</script>
