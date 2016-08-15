<?php
defined('_JEXEC') or die; 
global $custom_comma_decimal;
$v_bf_s = ( $custom_comma_decimal->bf_standard_price != '') ? $custom_comma_decimal->bf_standard_price : ".";
$v_bf_l = ( $custom_comma_decimal->bf_layover_price != '') ? $custom_comma_decimal->bf_layover_price : ".";
$v_bf_tax = ( $custom_comma_decimal->bf_tax != '') ? $custom_comma_decimal->bf_tax : ".";

$listCur = SfsHelper::getCurrencyMulti();                
$curGlobal = "";
foreach ($listCur as $value) {
    if($value->id == $this->hotel->currency_id){
        $curGlobal = $value->code; 
    }                          
}
?>
<legend><span class="text_legend">Breakfast information</span></legend>
<div class="col w80 pull-left p20">
    <div class="form-group" style="margin-left: -16px;">
        <div class="row r10">
            <div class="radio">
                <label><input type="checkbox" onchange="showHideInfo(3)" id="checkBox_3" 

                    <?php echo (int) $this->mealplan->status_break === 1 ? "checked='checked'" : "" ?>
                            class="breakfastBox" name="bf_no_breakfast"  value="<?php echo (int) $this->mealplan->status_break === 1 ? 1 : 0 ?>" />
                    Breakfast available</label>

            </div>
        </div>
    </div>
</div>

<div class="col w80 pull-left p20" id="main_3" style="<?php echo (int) $this->mealplan->status_break === 1 ? "display:block;" : "display:none;" ?>">

    <div class="form-group">
        <label style="width: 420px;">
            When offering roomrate including breakfast you can enter 0 <?php echo $curGlobal; ?>                     
        </label>
    </div>

    <div class="form-group">
        <label style="width: 220px;">Discounted layover breakfast price:            
            <span style="font-size:10px;">
                Based on full american breakfast
            </span>
        </label>
        <div class="col w20">
            <input type="text" name="bf_layover_price" class="required validate-digits smaller-size" value="<?php echo is_object($this->mealplan) ? str_replace(".", $v_bf_l, $this->mealplan->bf_layover_price ) : ''; ?>" />
        </div>
        <div class="col w40">
            <small class="help-block"><?php echo JText::sprintf('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE', $curGlobal); ?></small>
        </div>
    </div>

    <div class="form-group">
        <div class="row r10">
            <div class="col w40">
                <label>
                    Food taxes (%)
                </label>
            </div>
            <div class="col w20">
                <input style="margin-left: -10px;width:105px;" name="bf_tax" type="text" class="validate-digits smaller-size required" value="<?php echo is_object($this->mealplan) ? str_replace(".", $v_bf_tax, $this->mealplan->bf_tax ) : ''; ?>" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row r10">
            <div class="col w60 label-modified">
                <label>Regular opening hours for the buffet breakfast service:</label>
            </div>
            <div class="form-group">
                <div class="wrap-col">
                    <div class="row r10">
                        <div class="radio">
                            <label><input type="radio" class="numeric smaller-size" name="bf_service_hour" value="2" <?php echo is_object($this->mealplan) && $this->mealplan->bf_service_hour==2 ? 'checked="checked"' : 'checked="checked"'; ?> />
                                From:
                                <?php
                                if( is_object($this->mealplan) && $this->mealplan->bf_opentime) {
                                    $sst_array = explode(':', $this->mealplan->bf_opentime );
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
                                } else {
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField('6','00');
                                }
                                ?></label>
                        </div>
                     </div>
                    <div class="form-group" style="margin-left: 25px">
                        <div class="col w20">
                            <div class="label-modified"><label>HH:</label></div>
                            <div class="wrap-col"><select name="bf_opentime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select></div>
                        </div>
                        <div class="col w20">
                            <label>MM:</label>
                            <div class="wrap-col">
                                <select name="bf_opentime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>
                            </div>
                        </div>
                        <div class="col w10">
                               <p style="text-align: center; margin-top: 40px; margin-bottom: 0;"><strong>Till</strong> <i class="fa fa-arrow-right"></i></p>
                        </div>
                        <div class="col w20">
                            <label>
                            <?php
                                if( is_object($this->mealplan) && $this->mealplan->bf_closetime) {
                                    $sst_array = explode(':', $this->mealplan->bf_closetime );
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
                                } else {
                                    $selectTimeArray = SfsHelperDate::getSelect24TimeField('10','00');
                                }
                            ?>HH:</label>
                            <div class="wrap-col">
                                <select name="bf_closetime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select>
                            </div>
                        </div>
                        <div class="col w20">
                            <label>MM:</label>
                            <div class="wrap-col">
                                <select name="bf_closetime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <div class="row r10">
                        <div class="radio">
                            <label><input type="radio" class="numeric smaller-size" name="bf_service_hour" value="1" <?php echo is_object($this->mealplan) && $this->mealplan->bf_service_hour==1 ? 'checked="checked"' : ''; ?> />
                                24 Hrs</label>
                        </div>
                    </div>
                </div>     -->            
            </div>
        </div>
    </div>
</div>


