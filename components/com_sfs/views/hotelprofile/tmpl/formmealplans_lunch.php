<?php
defined('_JEXEC') or die; 

global $custom_comma_decimal;
$v_lunch_s = ( $custom_comma_decimal->lunch_standard_price != '') ? $custom_comma_decimal->lunch_standard_price : ".";
$v_lunch_tax = ( $custom_comma_decimal->lunch_tax != '' ) ? $custom_comma_decimal->lunch_tax : ".";

$listCur = SfsHelper::getCurrencyMulti();                
$curGlobal = "";
foreach ($listCur as $value) {
    if($value->id == $this->hotel->currency_id){
        $curGlobal = $value->code; 
    }                          
}
?>
<legend><span class="text_legend">Lunch information</span></legend>
<div class="col w80 pull-left p20">
    <div class="form-group" style="margin-left: -16px;">
        <div class="row r10">
            <div class="radio">
                <label><input type="checkbox" onchange="showHideInfo(2)" id="checkBox_2" 

                    <?php echo (int) $this->mealplan->status_lunch === 1 ? "checked='checked'" : "" ?>
                                class="lunchBox" name="no_lunch"  value="<?php echo (int) $this->mealplan->status_lunch === 1 ? 1 : 0 ?>" />
                    Lunch available</label>

            </div>
        </div>
    </div>
</div>

<div class="col w80 pull-left p20" id="main_2" style="<?php echo (int) $this->mealplan->status_lunch === 1 ? "display:block;" : "display:none;" ?>">

    <div class="form-group">
        <label>
            Standard price lunch per person:  
            <br />
            <span style="font-size:10px;">
                <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?>
            </span>              
        </label>
        <div class="col w20">
            <input type="text" name="lunch_standard_price" class="required validate-digits smaller-size" value="<?php echo  is_object($this->mealplan) ?  str_replace(".", $v_lunch_s, $this->mealplan->lunch_standard_price ) : ''; ?>" />            
        </div>
        <div class="col w40">
            <small class="help-block"><?php echo JText::sprintf('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE', $curGlobal); ?></small>
        </div>
    </div>

    <div class="form-group">
        <div class="row r10">
            <div class="col w40 label-modified">
                <label>Food taxes (%) </label>
            </div>
            <div class="col w20">
                <input name="lunch_tax" style="margin-left: -30px;width:105px;" type="text" class="validate-digits smaller-size required" value="<?php echo is_object($this->mealplan) ? str_replace(".", $v_lunch_tax, $this->mealplan->lunch_tax ) : ''; ?>" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row r10">
            <div class="col w60 label-modified">
                <label> Regular opening hours for the lunch service:</label>
            </div>
            <div class="form-group">
                <div class="wrap-col">
                    <div class="row r10">
                        <div class="radio">
                            <label> <input type="radio" class="numeric smaller-size" name="lunch_service_hour" value="2" <?php echo is_object($this->mealplan) && $this->mealplan->lunch_service_hour==2 ? 'checked="checked"' : 'checked="checked"'; ?> />
                                From:
                                <?php
                                if( is_object($this->mealplan) && $this->mealplan->lunch_opentime) {
                                    $sst_array = explode(':', $this->mealplan->lunch_opentime );
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
                                } else {
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField('11','00');
                                }
                                ?></label>
                        </div>
                    </div>
                    <div class="form-group" style="margin-left: 25px">
                        <div class="col w20">
                            <div class="label-modified"><label>HH:</label></div>
                            <div class="wrap-col"><select name="lunch_opentime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select></div>
                        </div>
                        <div class="col w20">
                            <label>MM:</label>
                            <div class="wrap-col">
                                <select name="lunch_opentime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>
                            </div>
                        </div>
                        <div class="col w10">
                            <p style="text-align: center; margin-top: 40px; margin-bottom: 0;"><strong>Till</strong> <i class="fa fa-arrow-right"></i></p>
                        </div>
                        <div class="col w20">
                            <label>
                                <?php
                                if( is_object($this->mealplan) && $this->mealplan->lunch_closetime) {
                                    $sst_array = explode(':', $this->mealplan->lunch_closetime );
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
                                } else {
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField('14','00');
                                }
                                ?>
                                HH:
                            </label>
                            <div class="wrap-col">
                                <select name="lunch_closetime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select>
                            </div>
                        </div>
                        <div class="col w20">
                            <label>MM:</label>
                            <div class="wrap-col">
                                <select name="lunch_closetime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row r10">
                        <div class="radio">
                            <label><input type="radio" class="numeric smaller-size" name="lunch_service_hour" value="1" <?php echo is_object($this->mealplan) && $this->mealplan->lunch_service_hour==1 ? 'checked="checked"' : ''; ?> />
                                24 Hrs</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Restaurant is open on days:</label>
        <div class="formmealplans_lunchdinner-right">
            <table cellspacing="0" cellpadding="0" border="0">
            	<tr>
            		<td style="padding-right:5px"><?php echo JText::_('MON')?></td>
            		<td style="padding-right:5px"><?php echo JText::_('TUE')?></td>
            		<td style="padding-right:5px"><?php echo JText::_('WED')?></td>
            		<td style="padding-right:5px"><?php echo JText::_('THU')?></td>
            		<td style="padding-right:5px"><?php echo JText::_('FRI')?></td>
            		<td style="padding-right:5px"><?php echo JText::_('SAT')?></td>
            		<td style="padding-right:5px"><?php echo JText::_('SUN')?></td>
            	</tr>
            	<tr>
            		<?php
            		$available_days = array();
            		if( !empty($this->mealplan->lunch_available_days) ){
            			$available_days = explode(',', $this->mealplan->lunch_available_days);
            			JArrayHelper::toInteger($available_days);
            		}
            		for ($i=1;$i<=7;$i++) :        		
            		?>        	
            		<td>
            			<input type="checkbox" name="lunchweek[]" value="<?php echo $i;?>" <?php echo in_array($i, $available_days)? 'checked="checked"':''?> checked="checked" >
            		</td>	
            		<?php endfor;?>
            	</tr>
            </table>
        </div>
    </div>
</div>
