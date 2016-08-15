<?php
defined('_JEXEC') or die; 
?>
<div style="padding-bottom:20px;"><h3>Roomservice information</h3></div>                                      

<div id="formmealplans_lunchdinner">
    <div class="label_short">
        Room service hours:       
    </div>
    <div class="formmealplans_lunchdinner-right">
        <input onclick="checkServiceAvailable()" type="radio" class="numeric smaller-size" value="1" name="service_hour"<?php echo ( is_object($this->mealplan) && $this->mealplan->service_hour==1 ) ? ' checked="checked"' : ' checked="checked"'; ?> />
		24-24 for stranded
		<div>
            <input onclick="checkServiceAvailable()" type="radio" class="numeric smaller-size" name="service_hour" value="2" <?php echo ( is_object($this->mealplan) && $this->mealplan->service_hour==2 ) ? 'checked="checked"' : ''; ?> />
            From:
                <?php
                    if( is_object($this->mealplan) && $this->mealplan->service_opentime) {
                        $sst_array = explode(':', $this->mealplan->service_opentime );					
                        $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);						
                    } else {
                        $selectTimeArray = SfsHelperDate::getSelect24TimeField('6','00');						
                    }
                ?>
                HH:<select name="service_opentime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select> 
                MM:<select name="service_opentime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>                    
            &nbsp;till:
				<?php
                    if(is_object($this->mealplan) && $this->mealplan->service_closetime) {
                        $sst_array = explode(':', $this->mealplan->service_closetime );					
                        $selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);						
                    } else {
                        $selectTimeArray = SfsHelperDate::getSelect24TimeField('23','35');			
                    }
                ?>                
                HH:<select name="service_closetime_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select> 
                MM:<select name="service_closetime_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>                                
        </div>
		<input onclick="checkServiceAvailable()" type="radio" class="numeric smaller-size" name="service_hour" value="0" <?php echo  is_object($this->mealplan) && $this->mealplan->service_hour==0 ? 'checked="checked"' : ''; ?> />
		Not available        
    </div>  
</div>

<div id="formmealplans_lunchdinner_new">
    <div class="label">
        Number of meals that can be (room) serviced outside open hours restaurant:
    </div>
    <input type="text" class="validate-integer smaller-size" value="<?php echo  is_object($this->mealplan) ?  $this->mealplan->service_outside : ''; ?>" name="service_outside" id="service_outside"<?php if(is_object($this->mealplan) && $this->mealplan->service_hour==0) echo 'readonly="readonly"';?> />
    &nbsp;&nbsp;meals
</div>
