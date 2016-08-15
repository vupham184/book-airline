<?php
defined('_JEXEC') or die;
$hotelSetting   = $this->item->getBackendSetting();
$loaded_airlines = array();
?>
<?php if(count($this->contractedRates)):?>

<?php foreach ($this->contractedRates as $airline_id=>$contractedRate):
	$loaded_airlines[] = (int) $airline_id;
	
	$t = 0;
	
	$default_srate = null; 
	$default_sdrate = null;
	$default_trate = null;
	$default_qrate = null;
	
	$default_breakfast = null;
	$default_lunch = null;
	$default_dinner = null;
	$default_two_course_dinner = null;
	$default_three_course_dinner = null;
?>
<style>
.floatbox, .roomloading-rate{
	/*padding-top:5px;
	padding-bottom:5px;*/
	margin-bottom:4px;
}
#roomLoadingForm .roomloading .roomloading-rate{
	margin-bottom:1px;
	padding-top:0px;
}
#roomLoadingForm .roomloading .roomloading-rate p{	
	padding-bottom:0px;
}

div.roomloading input.inputbox, div.roomloading div.inputbox{
	height:24px !important;
}
.add-form{
	height: 25px;
	line-height: 20px;
}
</style>
<p class="clr" style="padding:10px;"><hr></p>
<form class="fedit<?php echo $airline_id;?>" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotel');?>" method="post">
<div class="roomloading">
	<div class="clear floatbox">
		<div style="padding-left:296px;padding-bottom:10px;font-size:15px;">
			<strong>Contracted rates for: <?php echo $contractedRate->airline_name?></strong>
			<input type="hidden" name="airline_id" value="<?php echo $airline_id;?>" />			
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="roomloading-left float-left">
		<div style="padding-right:15px;">			
			<?php 
			$height = 100;
			if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) :
				$height += 20; 
			?>
			<div class="roomloading-rate add-form" style="padding: 0;">
				<strong>Single rate</strong>
			</div>
			<?php endif;?>	
			<div class="roomloading-rate add-form" style="padding: 0;">
				<strong>Single/Double rate</strong>
			</div>
			<div class="roomloading-rate add-form" style="padding: 0;">
				<strong>Triple rate</strong>
			</div>
			<?php 
			if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) :
			$height += 20; 
			?>
			<div class="roomloading-rate add-form" style="padding: 0;">
				<strong>Quad rate</strong>
			</div>
			<?php endif;?>	
            
            <!--lchung add  more-->
            <?php $height += 100; ?>
            <div style="padding-top:30px; clear:both;">
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>Breakfast</strong>
                </div>
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>Lunch</strong>
                </div>
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>1 course Dinner</strong>
                </div>
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>2 course Dinner</strong>
                </div>
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>3 course Dinner</strong>
                </div>
            </div><!--End lchung add  more-->
            			
		</div>
	</div>
	
	<div class="roomloading-middle float-left">
		<div class="roomtable floatbox" style="height:345px; width:69%; float:left;">
			<div style="margin:0 2px 6px 2px;">
				<table cellpadding="0" cellspacing="0" border="0" class="roomloading">
				<tr valign="top">
				<?php
				$i = 0;
				$last = 'inputbox-last';
				if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ){
					$last = '';
				}
				foreach ( $this->rooms_prices as $key => $value ) :
					$srate = null; 
					$sdrate = null;
					$trate = null;
					$qrate = null;
					
					$breakfast = null;
					$lunch = null;
					$dinner = null;
					$two_course_dinner = null;
					$three_course_dinner = null;
					// echo '<pre>';
					// print_r($contractedRate);
					// echo '<pre/>';
					// die;
					if( $contractedRate->rates[$key] )
					{
						if ( $contractedRate->rates[$key]->custom_comma_decimal != '' ) {
							$custom_comma_decimal = json_decode( $contractedRate->rates[$key]->custom_comma_decimal );
							$comma_s_rate = $custom_comma_decimal->s_rate;
							$contractedRate->rates[$key]->s_rate = str_replace(".", ($comma_s_rate == "") ? "." : $comma_s_rate, $contractedRate->rates[$key]->s_rate);
							
							$comma_sd_rate = $custom_comma_decimal->sd_rate;
							$contractedRate->rates[$key]->sd_rate = str_replace(".", ($comma_sd_rate == "") ? "." : $comma_sd_rate, $contractedRate->rates[$key]->sd_rate);
							
							$comma_t_rate = $custom_comma_decimal->t_rate;
							$contractedRate->rates[$key]->t_rate = str_replace(".", ($comma_t_rate == "") ? "." : $comma_t_rate, $contractedRate->rates[$key]->t_rate);
							
							$comma_q_rate = $custom_comma_decimal->q_rate;
							$contractedRate->rates[$key]->q_rate = str_replace(".", ($comma_q_rate == "") ? "." : $comma_q_rate, $contractedRate->rates[$key]->q_rate);
							
							$comma_breakfast = $custom_comma_decimal->breakfast;
							$contractedRate->rates[$key]->breakfast = str_replace(".", ($comma_breakfast == "") ? "." : $comma_breakfast, $contractedRate->rates[$key]->breakfast);
							
							$comma_lunch = $custom_comma_decimal->lunch;
							$contractedRate->rates[$key]->lunch = str_replace(".", ($comma_lunch == "") ? "." : $comma_lunch, $contractedRate->rates[$key]->lunch);
							
							$comma_dinner = $custom_comma_decimal->dinner;
							$contractedRate->rates[$key]->dinner = str_replace(".", ($comma_dinner == "") ? "." : $comma_dinner, $contractedRate->rates[$key]->dinner);
							
						}
						
						$srate = $contractedRate->rates[$key]->s_rate;
						$sdrate = $contractedRate->rates[$key]->sd_rate;
						$trate  = $contractedRate->rates[$key]->t_rate;
						$qrate = $contractedRate->rates[$key]->q_rate;
						
						$breakfast = $contractedRate->rates[$key]->breakfast;
						$lunch = $contractedRate->rates[$key]->lunch;
						$dinner = $contractedRate->rates[$key]->dinner;
						$two_course_dinner = $contractedRate->rates[$key]->two_course_dinner;
						$three_course_dinner = $contractedRate->rates[$key]->three_course_dinner;
						if ( $t == 0 ) {
							$t = 1;
							$default_srate = $srate; 
							$default_sdrate = $sdrate;
							$default_trate = $trate;
							$default_qrate = $qrate;
							
							$default_breakfast = $breakfast;
							$default_lunch = $lunch;
							$default_dinner = $dinner;
							$default_two_course_dinner = $two_course_dinner;
							$default_three_course_dinner = $three_course_dinner;
						}
					}
				?>			
				<td nowrap="nowrap">					
					<div class="date floatbox" style="margin-bottom:5px;">
						<?php echo date('d-',strtotime($key)).substr(date( 'F' , strtotime($key) ),0,3).'-'.date( JText::_('y') , strtotime($key) );?>
					</div>
					<?php if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) : ?>
						<div class="clear floatbox">														
							<input type="text" class="inputbox crooms_<?php echo $i;?>_srate_<?php echo $airline_id;?>" name="crooms[<?php echo $i;?>][srate]" value="<?php echo !empty($srate) ? $srate : ''?>" />
						</div>
					<?php endif;?>
					<div class="clear floatbox">														
						<input type="text" class="inputbox crooms_<?php echo $i;?>_sdrate_<?php echo $airline_id;?>" name="crooms[<?php echo $i;?>][sdrate]" value="<?php echo !empty($sdrate) ? $sdrate : ''?>" />											
					</div>
					<div class="clear floatbox">														
						<input type="text" class="inputbox crooms_<?php echo $i;?>_trate_<?php echo $airline_id;?> <?php echo $last?>" name="crooms[<?php echo $i;?>][trate]" value="<?php echo !empty($trate) ? $trate : ''?>" />											
					</div>	
					<?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) : ?>
						<div class="clear floatbox">														
							<input type="text" class="inputbox crooms_<?php echo $i;?>_qrate_<?php echo $airline_id;?> inputbox-last" name="crooms[<?php echo $i;?>][qrate]" value="<?php echo !empty($qrate) ? $qrate : ''?>" />											
						</div>
					<?php endif;?>	
                    
                    
                    <!--lchung add more-->	
                    <div style="padding-top:20px; clear:both;">
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_breakfast_<?php echo $airline_id;?>" name="crooms[<?php echo $i;?>][breakfast]" value="<?php echo !empty($breakfast) ? $breakfast : ''?>" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_lunch_<?php echo $airline_id;?>" name="crooms[<?php echo $i;?>][lunch]" value="<?php echo !empty($lunch) ? $lunch : ''?>" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_dinner_<?php echo $airline_id;?> inputbox-last" name="crooms[<?php echo $i;?>][dinner]" value="<?php echo !empty($dinner) ? $dinner : ''?>" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_two_course_dinner_<?php echo $airline_id;?> inputbox-last" name="crooms[<?php echo $i;?>][two_course_dinner]" value="<?php echo !empty($two_course_dinner) ? $two_course_dinner : ''?>" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_three_course_dinner_<?php echo $airline_id;?> inputbox-last" name="crooms[<?php echo $i;?>][three_course_dinner]" value="<?php echo !empty($three_course_dinner) ? $three_course_dinner : ''?>" />											
                        </div>
                    </div>
                    <!--End lchung add more-->
                    
					<input type="hidden" name="crooms[<?php echo $i;?>][rdate]" value="<?php echo $key; ?>" />
					<?php
					if($i==0) : 
					?>
					<input type="hidden" name="start_date" value="<?php echo $key; ?>" />
					<?php endif;?>														
				</td>					
				<?php 
				$i++;
				endforeach; ?>
				</tr>
				</table>
			</div>
		</div>
        
        <!--lchung add more chua su dung-->
        <div class="roomloading-left float-left" style="width:15%;" >
            <div style="padding-right:15px;">		
                <?php 
                $height = 110;
                if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) :
                    $height += 20; 
                ?>
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>Single rate</strong>
                </div>
                <?php endif;?>	
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>Single/Double rate</strong>
                </div>
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>Triple rate</strong>
                </div>
                <?php 
                if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) :
                $height += 20; 
                ?>
                <div class="roomloading-rate add-form" style="padding: 0;">
                    <strong>Quad rate</strong>
                </div>
                <?php endif;?>                
                <div style="padding-top:30px; clear:both;">
                    <div class="roomloading-rate add-form" style="padding: 0;">
                        <strong>Breakfast</strong>
                    </div>
                    <div class="roomloading-rate add-form" style="padding: 0;">
                        <strong>Lunch</strong>
                    </div>
                    <div class="roomloading-rate add-form" style="padding: 0;">
                        <strong>1 course Dinner</strong>
                    </div>
                    <div class="roomloading-rate add-form" style="padding: 0;">
                        <strong>2 course Dinner</strong>
                    </div>
                    <div class="roomloading-rate add-form" style="padding: 0;">
                        <strong>3 course Dinner</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="roomloading-left float-left" style="width:120px; padding-top:10px; float: left;">
        	<span style="float: left;">Default</span>
        	<span style="float: right">Max rate</span>
            <div style="padding-right:15px;">	
           		<?php  
           			
           			$dateNow = date("Y-m-d");
           			$maxRate = json_decode($contractedRate->rates[$dateNow]->max_rate);

           			//print_r(json_decode($maxRate)->srate);
           		?>

           		
                <?php if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) : ?>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-single-srate-<?php echo $airline_id;?>" data-airline_id="<?php echo $airline_id;?>" name="default[single_srate]" value="<?php echo !empty($default_srate) ? $default_srate : ''?>" />											
                    </div><input type="checkbox" onclick="ChangeSrate(<?php echo $airline_id;?>)"  id="srateId-<?php echo $airline_id;?>" name="checkBox[srate]" <?php echo $maxRate->srate == "true" ? "checked" : "" ?> >
                <?php endif;?>
                <div class="clear floatbox">														
                    <input type="text" class="inputbox default-single-double-sdrate-<?php echo $airline_id;?>" data-airline_id="<?php echo $airline_id;?>" name="default[single_double_sdrate]" value="<?php echo !empty($default_sdrate) ? $default_sdrate : ''?>" />	
                    <input type="checkbox" onclick="ChangeSdrate(<?php echo $airline_id;?>)"  id="sdrateId-<?php echo $airline_id;?>" name="checkBox[sdrate]" <?php echo $maxRate->sdrate == "true" ? "checked" : "" ?> >										
                </div>
                <div class="clear floatbox">														
                    <input type="text" class="inputbox default-triple-trate-<?php echo $airline_id;?> 
					<?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 0 ) : ?>
                    inputbox-last" <?php endif;?> name="default[triple_trate]" data-airline_id="<?php echo $airline_id;?>" value="<?php echo !empty($default_trate) ? $default_trate : ''?>" />	
                    <input type="checkbox" onclick="ChangeTrate(<?php echo $airline_id;?>)"  id="trateId-<?php echo $airline_id;?>" name="checkBox[trate]" <?php echo $maxRate->trate == "true" ? "checked" : "" ?> >										
                </div>
                <?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) : ?>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-quad-qrate-<?php echo $airline_id;?> inputbox-last" data-airline_id="<?php echo $airline_id;?>" name="default[quad_qrate]" value="<?php echo !empty($default_qrate) ? $default_qrate : ''?>" />											
                    </div>
                    <input type="checkbox" onclick="ChangeQrate(<?php echo $airline_id;?>)"  id="qrateId-<?php echo $airline_id;?>" name="checkBox[qrate]" <?php echo $maxRate->qrate == "true" ? "checked" : "" ?> >
                <?php endif;?>
                <!--lchung add more-->	
                <div style="padding-top:20px; clear:both;">
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-breakfast-<?php echo $airline_id;?>" data-airline_id="<?php echo $airline_id;?>" name="default[breakfast]" value="<?php echo !empty($default_breakfast) ? $default_breakfast : ''?>" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-lunch-<?php echo $airline_id;?>" data-airline_id="<?php echo $airline_id;?>" name="default[lunch]" value="<?php echo !empty($default_lunch) ? $default_lunch : ''?>" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-dinner-<?php echo $airline_id;?> inputbox-last" data-airline_id="<?php echo $airline_id;?>" name="default[dinner]" value="<?php echo !empty($default_dinner) ? $default_dinner : ''?>" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-two-course-dinner-<?php echo $airline_id;?> inputbox-last" data-airline_id="<?php echo $airline_id;?>" name="default[two_course_dinner]" value="<?php echo !empty($default_two_course_dinner) ? $default_two_course_dinner : ''?>" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-three-course-dinner-<?php echo $airline_id;?> inputbox-last" data-airline_id="<?php echo $airline_id;?>" name="default[default_three_course_dinner]" value="<?php echo !empty($default_three_course_dinner) ? $default_three_course_dinner : ''?>" />											
                    </div>
                </div>
                <!--End lchung add more-->	
            </div>
            
        </div><br style="clear:both;" />
        <!--End lchung add more-->
		<br />
        
		<input type="submit" value="Save Prices" class="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none; font-size:12px;" />		
		
	</div>
	
	
</div>
<input type="hidden" name="task" value="hotel.saveContractedRates" />
<input type="hidden" name="id" value="<?php echo $this->item->id;?>" />
<?php echo JHtml::_('form.token'); ?>	
</form>

<?php endforeach;?>
<?php endif;?>

<?php if(count($loaded_airlines) < count($this->airlines) ) :?>

<script type="text/javascript">
<!--
window.addEvent('domready', function(){
	$('addContractPrize').addEvent('click', function(e){
		setVal0();
		e.stop();
		$('newContractedForm').setStyle('display','block');
	});
});
//-->
</script>

<div id="newContractedForm" style="display:none;">
<p class="clr" style="padding:10px;"><hr></p>

<form class="faddnew" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotel');?>" method="post">

<div class="roomloading">

	<div class="clear floatbox">
		<div style="padding-left:296px;padding-bottom:10px;font-size:15px;">
			<strong>Contracted rates for &nbsp;&nbsp;&nbsp;&nbsp;Select Airline</strong>
			<select name="airline_id">
				<?php foreach ($this->airlines as $airline): 
					if( in_array((int)$airline->id, $loaded_airlines) ) continue;
				?>
					<option value="<?php echo $airline->id?>"><?php echo !empty($airline->airline_name) ? $airline->airline_name : $airline->company_name; ?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="roomloading-left float-left">
		<div style="padding-right:15px;">		
			<?php 
			$height = 100;
			if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) :
				$height += 20; 
			?>
			<div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
				<strong>Single rate</strong>
			</div>
			<?php endif;?>	
			<div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
				<strong>Single/Double rate</strong>
			</div>
			<div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
				<strong>Triple rate</strong>
			</div>
			<?php 
			if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) :
			$height += 20; 
			?>
			<div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
				<strong>Quad rate</strong>
			</div>
			<?php endif;?>
            <!--lchung add  more-->
            <?php $height += 100; ?>
            <div style="padding-top:30px; clear:both;">
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>Breakfast</strong>
                </div>
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>Lunch</strong>
                </div>
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>1 course Dinner</strong>
                </div>
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>2 course Dinner</strong>
                </div>
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>3 course Dinner</strong>
                </div>
            </div><!--End lchung add  more-->
                
		</div>
	</div>
	
	<div class="roomloading-middle float-left">
		<div class="roomtable floatbox float-left" style="height:350px; width:69%;">
			<div style="margin:0 2px 6px 2px;">
				<table cellpadding="0" cellspacing="0" border="0" class="roomloading">
				<tr valign="top">
				<?php
				$total = count( $this->rooms_prices );
				$i = 0;
				$last = 'inputbox-last';
				if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ){
					$last = '';
				}
				foreach ( $this->rooms_prices as $key => $value ) : 
				?>				
				<td nowrap="nowrap">					
					<div class="date floatbox" style="margin-bottom:5px;">
						<?php echo date('d-',strtotime($key)).substr(date( 'F' , strtotime($key) ),0,3).'-'.date( JText::_('y') , strtotime($key) );?>
					</div>
					<?php if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) : ?>
						<div class="clear floatbox">														
							<input type="text" class="inputbox crooms_<?php echo $i;?>_srate" name="crooms[<?php echo $i;?>][srate]" value="" />											
						</div>
					<?php endif;?>
					<div class="clear floatbox">														
						<input type="text" class="inputbox crooms_<?php echo $i;?>_sdrate" name="crooms[<?php echo $i;?>][sdrate]" value="" />											
					</div>
					<div class="clear floatbox">														
						<input type="text" class="inputbox crooms_<?php echo $i;?>_trate <?php echo $last?>" name="crooms[<?php echo $i;?>][trate]" value="" />											
					</div>
					<?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) : ?>
						<div class="clear floatbox">														
							<input type="text" class="inputbox crooms_<?php echo $i;?>_qrate" name="crooms[<?php echo $i;?>][qrate]" value="" />											
						</div>
					<?php endif;?>
                    
                    
                    
                    <!--lchung add more-->	
                    <div style="padding-top:20px; clear:both;">
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_breakfast" name="crooms[<?php echo $i;?>][breakfast]" value="" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_lunch" name="crooms[<?php echo $i;?>][lunch]" value="" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_dinner inputbox-last" name="crooms[<?php echo $i;?>][dinner]" value="" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_two_course_dinner inputbox-last" name="crooms[<?php echo $i;?>][two_course_dinner]" value="" />											
                        </div>
                        <div class="clear floatbox">														
                            <input type="text" class="inputbox crooms_<?php echo $i;?>_three_course_dinner inputbox-last" name="crooms[<?php echo $i;?>][three_course_dinner]" value="" />											
                        </div>
                    </div>
                    <!--End lchung add more-->
                    
                    	
					<input type="hidden" name="crooms[<?php echo $i;?>][rdate]" value="<?php echo $key; ?>" />
					<?php
					if($i==0) : 
					?>
					<input type="hidden" name="start_date" value="<?php echo $key; ?>" />
					<?php endif;?>	                            
				</td>					
				<?php 
				$i++;
				endforeach;?>
				</tr>
				</table>
			</div>
		</div>
        
        <!--lchung add more-->
        <div class="roomloading-left float-left" style="width:15%;">
            <div style="padding-right:15px;">		
                <?php 
                $height = 100;
                if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) :
                    $height += 20; 
                ?>
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>Single rate</strong>
                </div>
                <?php endif;?>	
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>Single/Double rate</strong>
                </div>
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>Triple rate</strong>
                </div>
                <?php 
                if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) :
                $height += 20; 
                ?>
                <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                    <strong>Quad rate</strong>
                </div>
                <?php endif;?>                
                <div style="padding-top:30px; clear:both;">
                    <div class="roomloading-rate" style="padding: 0;">
                        <strong>Breakfast</strong>
                    </div>
                    <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                        <strong>Lunch</strong>
                    </div>
                    <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                        <strong>1 course Dinner</strong>
                    </div>
                    <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                        <strong>2 course Dinner</strong>
                    </div>
                    <div class="roomloading-rate" style="padding: 0;margin-bottom:3px;">
                        <strong>3 course Dinner</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="roomloading-left float-left" style="width:auto; padding-top:10px;">
        	<span style="text-align:center; display:block;">Default</span>
            <div style="padding-right:15px;">		
                <?php if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) : ?>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-single-srate" name="default[single_srate]" value="0" />											
                    </div>
                <?php endif;?>
                <div class="clear floatbox">														
                    <input type="text" class="inputbox default-single-double-sdrate" name="default[single_double_sdrate]" value="0" />											
                </div>
                <div class="clear floatbox">														
                    <input type="text" class="inputbox default-triple-trate 
					<?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 0 ) : ?>
                    inputbox-last" <?php endif;?> name="default[triple_trate]" value="0" />											
                </div>
                <?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) : ?>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-quad-qrate inputbox-last" name="default[quad_qrate]" value="" />											
                    </div>
                <?php endif;?>
                <!--lchung add more-->	
                <div style="padding-top:20px; clear:both;">
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-breakfast" name="default[breakfast]" value="0" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-lunch" name="default[lunch]" value="0" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-dinner inputbox-last" name="default[dinner]" value="0" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-two-course-dinner inputbox-last" name="default[two_course_dinner]" value="0" />											
                    </div>
                    <div class="clear floatbox">														
                        <input type="text" class="inputbox default-three-course-dinner inputbox-last" name="default[three_course_dinner]" value="0" />											
                    </div>
                </div>
                <!--End lchung add more-->	
            </div>
            
        </div><br style="clear:both;" />
        <!--End lchung add more-->
		<br />
		<input type="submit" value="Save Prices" class="button" style="margin-left:5px;background:green;color:white;padding:5px 20px;border:none; font-size:12px;" />		
		
	</div>

</div>

<input type="hidden" name="task" value="hotel.saveContractedRates" />
<input type="hidden" name="id" value="<?php echo $this->item->id;?>" />
<?php echo JHtml::_('form.token'); ?>

</form>
</div>

<p class="clr" style="padding:10px;"><hr></p>
<div id="addContractPrize">Add contracted hotel prizes</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
jQuery.noConflict();
jQuery(function($){
	//lchung set value default
	var total = <?php echo $total;?>;
	var default_srate = $('.default-single-srate').val();
	var default_single_double_sdrate = $('.default-single-double-sdrate').val();
	var default_triple_trate = $('.default-triple-trate').val();
	var default_quad_qrate = $('.default-quad-qrate').val();
	
	var default_breakfast = $('.default-breakfast').val();
	var default_lunch = $('.default-lunch').val();
	var default_dinner = $('.default-dinner').val();
	var default_two_course_dinner = $('.default-two-course-dinner').val();
	var default_three_course_dinner = $('.default-three-course-dinner').val();
	
	for ( var i = 0; i < total; i++) {
		
		if ( default_srate != undefined ) {
			$('.faddnew .crooms_' + i + '_srate').val( default_srate );
		}
		
		if ( default_single_double_sdrate != undefined ) {
			$('.faddnew .crooms_' + i + '_sdrate').val( default_single_double_sdrate );
		}
		
		if ( default_triple_trate != undefined ) {
			$('.faddnew .crooms_' + i + '_trate').val( default_triple_trate );
		}
		
		if ( default_quad_qrate != undefined ) {
			$('.faddnew .crooms_' + i + '_qrate').val( default_quad_qrate );
		}
		
		if ( default_breakfast != undefined ) {
			$('.faddnew .crooms_' + i + '_breakfast').val( default_breakfast );
		}
		
		if ( default_lunch != undefined ) {
			$('.faddnew .crooms_' + i + '_lunch').val( default_lunch );
		}
		
		if ( default_dinner != undefined ) {
			$('.faddnew .crooms_' + i + '_dinner').val( default_dinner );
		}
		
		if ( default_two_course_dinner != undefined ) {
			$('.faddnew .crooms_' + i + '_two_course_dinner').val( default_dinner );
		}
		if ( default_three_course_dinner != undefined ) {
			$('.faddnew .crooms_' + i + '_three_course_dinner').val( default_dinner );
		}
       //$('input[name="crooms[0][srate]]').val( vsrate );
    };
	
	$('.default-single-srate').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'srate', total);
    });
	
	$('.default-single-double-sdrate').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'sdrate', total);
    });
	
	$('.default-triple-trate').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'trate', total);
    });
	
	$('.default-quad-qrate').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'qrate', total);
    });
	
	$('.default-breakfast').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'breakfast', total);
    });
	
	$('.default-lunch').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'lunch', total);
    });
	
	$('.default-dinner').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'dinner', total);
    });
    $('.default-two-course-dinner').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'two_course_dinner', total);
    });
	$('.default-three-course-dinner').keyup(function(e) {
		var v = $(this).val();
		var neW = '';
		for(var i = 0; i < v.length; i++) {
			if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
				neW += v[i];
			}
		}
		$(this).val( neW );
        setVal( $(this).val(), 'three_course_dinner', total);
    });

	

  //   $('.change-srate').click(function(e) {
  //   	var checkVal = document.getElementById('srateId').checked;
		// if(checkVal == true){
		// 	var curValue = $(".default-single-srate").val();
			
		// 	$(this).val( curValue );
	 //        setVal( $(this).val(), 'srate', total);
		// }		
  //   });
	
});

function ChangeSrate(id){
	var total = <?php echo $total;?>;
	var checkVal = document.getElementById('srateId-' + id).checked;
	var curValue = jQuery(".default-single-srate-" + id).val();
	if(checkVal == true){
		setVal_( curValue, 'srate', total, id);
	}	
}
function ChangeSdrate(id){
	var total = <?php echo $total;?>;
	var checkVal = document.getElementById('sdrateId-' + id).checked;
	var curValue = jQuery(".default-single-double-sdrate-" + id).val();
	if(checkVal == true){
		setVal_( curValue, 'sdrate', total, id);
	}	
}
function ChangeTrate(id){
	var total = <?php echo $total;?>;
	var checkVal = document.getElementById('trateId-' + id).checked;
	var curValue = jQuery(".default-triple-trate-" + id).val();
	if(checkVal == true){
		setVal_( curValue, 'trate', total, id);
	}	
}
function ChangeQrate(id){
	var total = <?php echo $total;?>;
	var checkVal = document.getElementById('qrateId-' + id).checked;
	var curValue = jQuery(".default-quad-qrate-" + id).val();
	if(checkVal == true){
		setVal_( curValue, 'qrate', total, id);
	}	
}

function setVal_( val, obj, total, id){
	for ( var i = 0; i < total; i++) {
		if ( val != undefined ) {
			jQuery('.crooms_' + i + '_' + obj + '_' + id).val( val );
		}
	}
}

function setVal( val, obj, total){
	for ( var i = 0; i < total; i++) {
		if ( val != undefined ) {
			jQuery('.faddnew .crooms_' + i + '_' + obj).val( val );
		}
	}
}

function setValEditForm( val, obj_form, obj, total, id){
	for ( var i = 0; i < total; i++) {
		if ( val != undefined ) {
			jQuery('.' + obj_form + ' .crooms_' + i + '_' + obj + '_' + id).val( val );
		}
	}
}

function setVal0( ){
	var total = '<?php echo $total;?>';
	jQuery('.default-single-srate').val( 0 );
	jQuery('.default-single-double-sdrate').val(0);
	jQuery('.default-triple-trate').val(0);
	jQuery('.default-quad-qrate').val(0);
	jQuery('.default-breakfast').val(0);
	jQuery('.default-lunch').val(0);
	jQuery('.default-dinner').val(0);
	jQuery('.default-two-course-dinner').val(0);
	jQuery('.default-three-course-dinner').val(0);
	
	setVal(0, 'srate', total);
	setVal(0, 'sdrate', total);
	setVal(0, 'trate', total);
	setVal(0, 'qrate', total);	
	setVal(0, 'breakfast', total);
	setVal(0, 'lunch', total);
	setVal(0, 'dinner', total);
	setVal(0, 'two_course_dinner', total);
	setVal(0, 'three_course_dinner', total);
}

function creaeEvn(){
	var total = '<?php echo $total;?>';
	<?php if( count( $loaded_airlines ) > 0 ): ?>
		<?php foreach( $loaded_airlines as $vk => $v ) :?>
		
		jQuery('.default-single-srate-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'srate', total,'<?php echo $v;?>');
		});
		
		jQuery('.default-single-double-sdrate-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'sdrate', total,'<?php echo $v;?>');
		});
		
		jQuery('.default-triple-trate-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'trate', total,'<?php echo $v;?>');
		});
		
		jQuery('.default-quad-qrate-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'qrate', total,'<?php echo $v;?>');
		});
		
		jQuery('.default-breakfast-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'breakfast', total,'<?php echo $v;?>');
		});
		
		jQuery('.default-lunch-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'lunch', total,'<?php echo $v;?>');
		});
		
		jQuery('.default-dinner-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'dinner', total,'<?php echo $v;?>');
		});
		jQuery('.default-two-course-dinner-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'two_course_dinner', total,'<?php echo $v;?>');
		});
		jQuery('.default-three-course-dinner-<?php echo $v;?>').keyup(function(e) {
			var v = jQuery(this).val();
			var neW = '';
			for(var i = 0; i < v.length; i++) {
				if( !isNaN( v[i] ) || v[i] == "," || v[i] == "." ){
					neW += v[i];
				}
			}
			jQuery(this).val( neW );
			setValEditForm( jQuery(this).val(), 'fedit<?php echo $v;?>', 'three_course_dinner', total,'<?php echo $v;?>');
		});
		<?php endforeach;?>
	<?php endif; ?>
}

creaeEvn();
</script>
<?php endif;?>


