<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
if( isset($this->mealplan->stop_selling_time) && $this->mealplan->stop_selling_time !='24' ) {
	$sst_array = explode(':', $this->mealplan->stop_selling_time);
	$selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
} else {
	$selectTimeArray = SfsHelperDate::getSelect24TimeField('22','35');	
}
global $custom_comma_decimal;
$v_1 = ( $custom_comma_decimal->course_1 != '' ) ? $custom_comma_decimal->course_1 : ".";
$v_2 = ( $custom_comma_decimal->course_2 != '' ) ? $custom_comma_decimal->course_2 : ".";
$v_3 = ( $custom_comma_decimal->course_3 != '' ) ? $custom_comma_decimal->course_3 : ".";
$v_tax = ( $custom_comma_decimal->tax != '' ) ? $custom_comma_decimal->tax : ".";

$listCur = SfsHelper::getCurrencyMulti();                
$curGlobal = "";
foreach ($listCur as $value) {
    if($value->id == $this->hotel->currency_id){
        $curGlobal = $value->code; 
    }                          
}

?>

<legend><span class="text_legend"><?php echo JText::_('COM_SFS_LABLE_LUNCH_DINNER_INFO'); ?></span></legend>
<div class="col w80 pull-left p20">
    <div class="form-group" style="margin-left: -16px;">
        <div class="row r10">
            <div class="radio">
               
                <label><input type="checkbox" onchange="showHideInfo(1)" id="checkBox_1" 
                    <?php echo (int) $this->mealplan->status_dinner === 1 ? "checked='checked'" : "" ?>
                            class="dinnerBox" name="no_dinner"  value="<?php echo (int) $this->mealplan->status_dinner === 1 ? 1 : 0 ?>" />
                    Dinner available</label>

            </div>
        </div>
    </div>
</div>

<div class="col w80 pull-left p20" id="main_1" style="<?php echo (int) $this->mealplan->status_dinner === 1 ? "display:block;" : "display:none;" ?>">
    <div class="form-group">
        <label>
            <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 1); ?>        
            <span style="font-size:10px;">
                <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?>
            </span>        
        </label>        
        <div class="col w20">
            <input type="text" name="course_1" class="smaller-size validate-digits " value="<?php echo ( is_object($this->mealplan)) ? str_replace(".", $v_1, $this->mealplan->course_1 ) : ''; ?>" />            
        </div>	        
        <div class="col w40">
            <small class="help-block"><?php echo JText::sprintf('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE', $curGlobal); ?></small>         
        </div>
    </div>

    <div class="form-group">
        <label>
            <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 2); ?>
         
            <span style="font-size:10px;">
                <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?>
            </span>        
        </label>
        <div class="col w20">
            <input type="text" name="course_2" class="validate-digits smaller-size " value="<?php echo ( is_object($this->mealplan)) ? str_replace(".", $v_2, $this->mealplan->course_2 ) : ''; ?>" />            
        </div>	       
        <div class="col w40">
            <small class="help-block"><?php echo JText::sprintf('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE', $curGlobal); ?></small>            
        </div> 
    </div>
    <div class="form-group">
        <label>
            <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 3); ?>            
            <span style="font-size:10px;">
                <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 2); ?>
            </span>        
        </label>
        <div class="col w20">
            <input type="text" name="course_3" class="validate-digits smaller-size " value="<?php echo ( is_object($this->mealplan)) ? str_replace(".", $v_3, $this->mealplan->course_3 ) : ''; ?>" />            
        </div>  
        <div class="col w40">
            <small class="help-block"><?php echo JText::sprintf('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE', $curGlobal); ?></small>          
        </div>
    </div>    
    <hr>
   
    <div class="form-group">
        <div class="row r10" style="margin: 20px 0 0 1px;">            
            <label>Food taxes (%):</label>            
            <div class="col w20">   
                <input name="tax" value="<?php echo ( is_object($this->mealplan)) ? str_replace(".", $v_tax, $this->mealplan->tax) : '' ; ?>" type="text" class="validate-digits smaller-size required" />            
            </div>
            <div class="col w20">
                <button type="button" class="button hasTip" title="<?php echo SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_13')); ?>" style="padding: 2px 6px; margin-top: 8px">?</button>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row r10">
            <div class="col w40 label-modified">
                <label>Stop selling time for the restaurant:</label>
            </div>
            <div class="col w60">            
                <div class="col w50">
                    <div class="row">
                        <div class="form-group">
                            <div class="radio">
                                <label><input type="radio" name="stop_selling_time" class="ld_selling_time smaller-size" value="<?php echo $this->mealplan->stop_selling_time!='24' ? $this->mealplan->stop_selling_time : ''; ?>" <?php echo ( is_object($this->mealplan) && $this->mealplan->stop_selling_time!='24' ) ? 'checked="checked"' : 'checked="checked"'; ?>/><strong>HH:</strong></label>
                            </div>
                            <div class="col w40">
                                <select name="stop_selling_time_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col w50">
                    <div class="form-group">
                        <div class="col w40 label-modified">
                            <label>MM:</label>
                        </div>
                        <div class="col w40">                    
                            <select name="stop_selling_time_m" class="smaller-size" style="width: 60px;"><?php echo $selectTimeArray[1]->html;?></select>        
                        </div>
                    </div>            
                </div>
                <div class="wrap-col"> 
                    <div class="row r10">
                        <div class="radio">
                            <label><input type="radio" name="stop_selling_time" class="smaller-size" value="24" <?php echo ( is_object($this->mealplan) && $this->mealplan->stop_selling_time=='24' ) ? 'checked="checked"' : ''; ?> style="padding: 0" />
                        24-24 for stranded</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Restaurant is open on days:</label>
        <div class="col w60">
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
            		if( !empty($this->mealplan->available_days) ){
            			$available_days = explode(',', $this->mealplan->available_days);
            			JArrayHelper::toInteger($available_days);
            		}
            		for ($i=1;$i<=7;$i++) :        		
            		?>        	
            		<td>
            			<input type="checkbox" name="week[]" value="<?php echo $i;?>" <?php echo in_array($i, $available_days)? 'checked="checked"':''?> checked="checked" >
            		</td>	
            		<?php endfor;?>
            	</tr>
            </table>
        </div>
    </div>
</div>


