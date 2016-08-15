<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
$this->hotel->currency_name = $this->hotel->getTaxes()->currency_name;
?>
<div class="registration<?php echo $this->pageclass_sfx?>">
<div class="com-hotel">
<div id="form-signup">

	<div class="sfs-above-main sfs-hotel-title">
        <h2><?php echo $this->hotel->name; ?>: <?php echo JText::_('COM_SFS_LABLE_FB'); ?></h2>
    </div>
    
    <div class="sfs-main-wrapper-none">
        <div class="sfs-orange-wrapper hotel-form">

<!-- BEGIN -->        
            <div class="hotel-area">
                <div class="hotel-management hotel-form">	
                
                	<div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">		
                    <fieldset>
                    
                        <h3><?php echo JText::_('COM_SFS_LABLE_LUNCH_DINNER_INFO'); ?></h3>
                        <div id="formmealplans_lunchdinner">
                            <div class="label">
                                <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 1); ?>
                                <br />
                                <span style="font-size:10px;"><?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?></span>        
                            </div>
                            <div style="width:70px; float:left"><?php echo $this->mealplan->course_1.' '. $this->hotel->currency_name; ?></div>	
                            <span style="font-size:10px;"><?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?></span>	        
                        </div>
                        <div id="formmealplans_lunchdinner">
                            <div class="label">
                                <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 2); ?>
                                <br />
                                <span style="font-size:10px;"><?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 1); ?></span>        
                            </div>
                            <div style="width:70px; float:left"><?php echo $this->mealplan->course_2.' '. $this->hotel->currency_name; ?></div>	
                            <span style="font-size:10px;"><?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?></span>	        
                        </div>
                        <div id="formmealplans_lunchdinner">
                            <div class="label">
                                <?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE', 3); ?>
                                <br />
                                <span style="font-size:10px;"><?php echo JText::sprintf('COM_SFS_LABLE_PRICE_LAYOVER_MEALS_COURSE_INCLUDE', 2); ?></span>        
                            </div>
                            <div style="width:70px; float:left"><?php echo $this->mealplan->course_3.' '. $this->hotel->currency_name; ?></div>	
                            <span style="font-size:10px;"><?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?></span>	        
                        </div>
                        
                        <div id="formmealplans_lunchdinner_new">
							<div class="label" style="width:80%">
								The above menu prices are net prices (prices without taxes).
							</div>                            
                        </div>
                        <div id="formmealplans_lunchdinner_new">
                            <div class="label">
                                Food taxes (%) that are applicable for the above menus:
                            </div>
                            <div class="formmealplans_lunchdinner-right">
                                <?php echo $this->mealplan->tax; ?> &nbsp; <button type="button" class="button hasTip" title="loram ipsur samed" style="float:none;padding:3px 5px;">?</button>
                            </div>
                        </div>                   
                        <div id="formmealplans_lunchdinner_new">
                            <div class="label">
                                Stop selling time for the restaurant:
                            </div>
                            <div class="formmealplans_lunchdinner-right">
                                <?php echo (int)$this->mealplan->stop_selling_time==24 ? '24-24 for stranded ' : $this->mealplan->stop_selling_time;?>
                                <?php echo SfsHelper::getArticle(103, 1, 1); ?>
                            </div>
                        </div>
                        
                    </fieldset>
                    </div>
                    
                    <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
                    <fieldset>
                    
                        <h3>Roomservice information</h3>
                        <div id="formmealplans_lunchdinner">
                            <div class="label_short">
                                Room service hours:       
                            </div>
                            <div class="formmealplans_lunchdinner-right">
                                <?php
                                    if($this->mealplan->service_hour==0) {
                                        echo 'Not available';
                                    } elseif ($this->mealplan->service_hour==1) {
                                        echo '24-24 for stranded';
                                    }else {
                                        echo 'From <strong>'.$this->mealplan->service_opentime.'</strong> till <strong>'.$this->mealplan->service_closetime.'</strong>';
                                    }
                                ?>
                            </div>  
                        </div>
                        <?php if($this->mealplan->service_hour!=0) : ?>
                        <div id="formmealplans_lunchdinner_new">
                            <div class="label">
                                Number of meals that can be (room) serviced outside open hours restaurant:
                            </div>
                            <?php echo $this->mealplan->service_outside; ?>&nbsp;&nbsp;meals
                            <span style="font-size:11px;"><?php echo SfsHelper::getArticle(104, 1, 1); ?></span>
                        </div>
                        <?php endif; ?>
                    </fieldset>
                    </div>
                    
                    <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
                    <fieldset>      
                    
                    	<h3>Breakfast information</h3>
                        <div id="formmealplans_lunchdinner">
                            <div class="label">
                                Standard price breakfast per person:
                                <br />
                                <span style="font-size:10px;">Must be full american breakfast</span>        
                            </div>
                            <div style="width:70px; float:left"><?php echo $this->mealplan->bf_standard_price.' '.$this->hotel->currency_name; ?></div>	
                            <span style="font-size:10px;"><?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?></span>	        
                        </div>
                        <div id="formmealplans_lunchdinner">
                            <div class="label">
                                Price layover breakfast per person:
                                <br />
                                <span style="font-size:10px;">Must be full american breakfast</span>        
                            </div>
                            <div style="width:70px; float:left"><?php echo $this->mealplan->bf_layover_price.' '.$this->hotel->currency_name; ?></div>	
                            <span style="font-size:10px;"><?php echo JText::_('COM_SFS_LABLE_SERVICE_CHARGE_INCLUDE_NO'); ?></span>	        
                        </div>
                        <div id="formmealplans_lunchdinner_new">
                            <span style="font-size:16px;">Food taxes (%) that are applicable for the above menus:</span> &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $this->mealplan->bf_tax; ?>
                        </div>
                        <div id="formmealplans_lunchdinner_new">
                            <span style="font-size:16px;">Regular opening hours for the buffet breakfast service:</span> &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $this->mealplan->bf_service_hour == 1 ? '24 Hrs': $this->mealplan->bf_opentime.' till '.$this->mealplan->bf_closetime ; ?><br /><?php echo SfsHelper::getArticle(103, 1, 1); ?>
                        </div>
                        <div id="formmealplans_lunchdinner_new">
                            <span style="font-size:16px;">Outside regular openings hours breakfast service:</span> &nbsp; &nbsp; &nbsp; &nbsp; 
                            <span style="font-size:11px; padding-left:30px; padding-top:4px;">
                                <?php
                                $breakfast = array( 1 => 'Continental prearranged room service breakfast will be offered at same price',
                                                    2 => 'Plated continental breakfast service will be offered in the restaurant at same price',
                                                    3 => 'Breakfast box upon group check out',
                                                    0 => 'No Breakfast can be offered');
                        
                                foreach($breakfast as $key => $value) {
                                    if( (int)$key == (int) $this->mealplan->bf_outside ) {
                                        echo $value;
                                        break;
                                    }
                                }
                                ?>    
                            </span>
                        </div>

                    </fieldset>    
                    </div>  
                    
                        
                </div>
            </div>
<!-- END -->        
            
        </div>
    </div>
	<div class="sfs-below-main">       
		<div class="s-button float-left">
			<a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile') );?>" class="s-button">
				<?php echo JText::_('COM_SFS_BACK');?>
			</a>
		</div>
		<div class="s-button float-right">
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid='.JRequest::getInt('Itemid'));?>" class="s-button"><?php echo JText::_('COM_SFS_EDIT');?></a>	 			    		
        </div>    

    </div>    
	<div class="clear"></div>
</div>
</div>
</div>
