<?php
defined('_JEXEC') or die;
$currency = SfsHelper::getCurrency();
$listCur = SfsHelper::getCurrencyMulti();
$backendParams = $this->hotel->getBackendSetting();
?>

<?php if( ! $this->hotel->isRegisterComplete()) :?>

        <?php $title = JText::_('COM_SFS_HOTEL_SIGN_UP_STEP_3')?>

    <?php else : ?>
    	<?php $title = $this->hotel->name.' - '.JText::_('COM_SFS_LABLE_TAXES')?>
    <?php endif; ?>
    
    
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <!-- <h3><?php echo $title?></h3>  -->       
        <h3>Step 1: Finance</h3>
    </div>
</div>
    
<div class="main">
    <div id="hotel-registraion">
        <?php if( ! $this->hotel->isRegisterComplete()) :?>
            <h1 class="page-title" style="text-align:center;"><?php echo $this->hotel->name; ?></h1>
            <?php echo $this->progressBar(0); ?>            
        <?php else : ?>
        <?php endif; ?>
        <form id="taxForm" name="taxForm" action="" method="post" class="form-validate sfs-form form-vertical register-form">
            <div class="block-group">                
                <div class="block border orange" style="text-align: center;">
                    <legend><span class="text_legend"><?php echo JText::_('COM_SFS_LABLE_CURRENCY'); ?></span></legend>
                       	<?php
                       		echo SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_08'));
                       	?>
                    <!-- <p class="text-center">Currency in <strong><?php //echo $currency?></strong></p> -->
                    Currency in 
                    
                    <select style="width: 180px;" name="currency_val">
                        <option value="0">-- Choose currency --</option>
                        <?php 

                            foreach ($listCur as $value) {
                                if($this->hotel->currency_id == 0){
                                    if($value->id == 2){
                                        echo "<option value='" .$value->id. "' selected='selected'>";
                                        echo $value->name ." (". $value->code . ")";
                                        echo "</option>";
                                    }else{
                                        echo "<option value='" .$value->id. "'>";
                                        echo $value->name ." (". $value->code . ")";
                                        echo "</option>";
                                    }
                                }else{
                                    if($value->id == $this->hotel->currency_id){
                                        echo "<option value='" .$value->id. "' selected='selected'>";
                                        echo $value->name ." (". $value->code . ")";
                                        echo "</option>";
                                    }else{
                                        echo "<option value='" .$value->id. "'>";
                                        echo $value->name ." (". $value->code . ")";
                                        echo "</option>";
                                    }
                                }                            		                            
                            }
                        ?> 
                    </select>
                                                 
                </div>

                <div class="block border orange clearfix">
                    <legend><span class="text_legend"><?php echo JText::_('COM_SFS_LABLE_TAXES'); ?></span></legend>
                    <div data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('taxes_field', $text, 'hotel'); ?>">
                        
                        <div class="col w80 pull-left p20">
                            <div class="form-group" style="margin-left:-12px">  
                                <div class="col w55">
                                    <label style="width:100%">
                                        The percentage of taxes per roomnight for your hotel    
                                    </label>                                    
                                </div>                      
                                <div class="col w10">
                                    <input type="text" value="<?php echo is_object($this->taxes) ? $this->taxes->percent_total_taxes : '0';?>" name="percent_total_taxes" class="required number numeric hasTip" title="percent total taxes per night::<?php echo JText::_('COM_SFS_TAXES_PERCENT_TOTAL_TAXES_PER_NIGHT_DESC'); ?>" />
                                </div>  
                                <div class="col w5"><label>%</label></div>                              
                            </div> 
                            <div class="form-group">
                                <label>Add Comment</label>
                                <div class="col w60">
                                    <textarea name="comment"  rows="10"><?php echo $this->merchant_fee->comment;?></textarea>
                                </div>
                            </div>                             
                        </div>
                        <p><?php echo SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_10')); ?></p>
                    </div>
                </div>
               
                <div class="block border orange clearfix">
                	<legend><span class="text_legend"><?php echo JText::_('COM_SFS_MERCHANT_FEE'); ?></span></legend>
                    <div class="col w80 pull-left p20"> 
                            <div data-step="2" data-intro="<?php echo SfsHelper::getTooltipTextEsc('merchant_fee_field', $text, 'hotel'); ?>">
                        		<div class="form-group">
                                    <label><?php echo JText::_('COM_SFS_COMMISION_PERCENTAGE_ROOMS')?></label>                                
                                    <?php
                                    if( is_object($this->merchant_fee) && $this->merchant_fee->merchant_fee ) {
                                    	$merchant_fee = $this->merchant_fee->merchant_fee;
                                    	$merchant_fee_type = (int) $this->merchant_fee->merchant_fee_type;
                                    	if( (int) $this->merchant_fee->agree == 1 ){
                                    		$checked=' checked="checked" ';//style="display:none;"
                                    	} else {
                                    		$checked='';
                                    	}
                                    } else {
                                    	$merchant_fee = $this->params->get('room_merchant_fee');
                                    	$merchant_fee_type = (int)$this->params->get('merchant_fee_type');
                                    	$checked='';
                                    }
                                    $merchant_fee_type_text = $merchant_fee_type == 1 ? '%':$this->params->get('sfs_system_currency');
                                    ?>
                                    <div class="col w60">
                                        <label>
                                        <!--<input type="checkbox" <?php echo $checked?> value="<?php echo $merchant_fee;?>" name="merchant_fee" class="required" />-->
                                        <input style="width:50%;" type="text" value="<?php echo $merchant_fee;?>" name="merchant_fee" />
										<?php //echo (int)$merchant_fee?> <?php echo $merchant_fee_type_text?>
                                        </label>
                                        <input type="hidden" name="merchant_fee_type" value="<?php echo $merchant_fee_type?>" />
                                    </div>    							
                                </div>
                                <div class="form-group">
                                    <label><?php echo JText::_('COM_SFS_COMMISION_PERCENTAGE_FB')?></label>                                
                                    <?php
                                    if( is_object($this->merchant_fee) && $this->merchant_fee->dinner_merchant_fee ) {
                                    	$dinner_merchant_fee = $this->merchant_fee->dinner_merchant_fee;
                                    	if( (int) $this->merchant_fee->agree == 1 ){
                                    		$checked=' checked="checked" ';//style="display:none;"
                                    	} else {
                                    		$checked='';
                                    	}
                                    } else {
                                    	$dinner_merchant_fee = $this->params->get('dinner_merchant_fee');
                                    	$checked='';
                                    }
                                    ?>
                                    <div class="col w60">
                                        <label>
                                        <input style="width:50%;" type="text" value="<?php echo $dinner_merchant_fee;?>" name="dinner_merchant_fee" />
                                        <!--<input type="checkbox" <?php echo $checked?> value="<?php echo $dinner_merchant_fee;?>" name="dinner_merchant_fee" class="required" /> --><?php //echo (int)$dinner_merchant_fee?> %
                                        </label>
                                    </div>
                                    <div class="col w60">
                                        <label style="width:300px; margin-left:-10px;">Contracted rates are excluded from merchant free</label>
                                    </div>
                                </div>                            
                                <?php if( isset($backendParams) && (int)$backendParams->merchant_fixed_fee_enable == 1 ) : ?>
                                <div class="form-group">
                                    <label><?php echo JText::_('COM_SFS_NET_MONTHLY_FEE')?></label>                                
                                    <?php
                                    if( is_object($this->merchant_fee) && $this->merchant_fee->monthly_fee ) {
                                    	$merchant_fixed_fee = $this->merchant_fee->monthly_fee;
                                    	if( (int) $this->merchant_fee->agree == 1 ){
                                    		$checked=' checked="checked" style="display:none;"';
                                    	} else {
                                    		$checked='';
                                    	}
                                    } else {
                                    	$merchant_fixed_fee = $this->params->get('merchant_fixed_fee');
                                    	$checked='';
                                    }
                                    
                                    ?>
                                    <div class="col w60">
                                        <label><input type="checkbox" <?php echo $checked?> value="<?php echo $merchant_fixed_fee;?>" name="monthly_fee" class="required" /> <?php echo (int)$merchant_fixed_fee?> <?php echo $this->params->get('sfs_system_currency')?></label>
                                    </div>
                                </div>
                                <?php endif;?>                                                        
                                <?php if( isset($backendParams) && $backendParams->merchant_register_note ) : ?>
                                <div class="form-group">
                                    <label><?php echo JText::_('Comment from your account manager:')?></label>
                                    <div class="col w60">
                                        <p><?php echo $backendParams->merchant_register_note;?></p>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="form-group">
                                <label>Add Comment</label>
                                <div class="col w60">
                                    <textarea name="comment"  rows="10"><?php echo $this->merchant_fee->comment;?></textarea>
                                </div>
                            </div>                        
                    </div>
                </div>
                                    
                <div class="block border orange">
                	<legend><span class="text_legend"><?php echo JText::_('COM_SFS_FREE_RELEASE_POLICY'); ?></span></legend>
                	<div data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('free_release_policy', $text, 'hotel'); ?>">
    					<div class="form-group">
        					<?php if( !$this->hotel->isRegisterComplete() ): ?>
        						<p>
                                    <?php echo JText::_('COM_SFS_PERCENTAGE_FREE_RELEASE_POLICY')?>*
<!--                                <input style="width: 40px" type="text" value="--><?php //echo is_object($this->taxes) ? floatval($this->taxes->percent_release_policy) : '0';?><!--" name="percent_release_policy" class="required number numeric" />-->
                                    <input style="width: 40px" type="text" value="20" name="percent_release_policy" class="required number numeric" />
                                    %
                                </p>
        					<?php else : ?>
        					   <p><?php echo JText::_('COM_SFS_PERCENTAGE_FREE_RELEASE_POLICY')?>
        					   <strong><?php echo floatval($this->taxes->percent_release_policy)?> %</strong></p>
        					<?php endif;?>                        
                			<p><?php echo JText::_('COM_SFS_PERCENTAGE_FREE_RELEASE_POLICY_NOTE'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrap-col clearfix">                
            	<div class="pull-left">
            		<?php echo JText::_('COM_SFS_HOTEL_TAXES_NOTE');?>
            	</div>                
                <div class="form-group btn-group text-right pull-right" id="appendSaveClose">
            		<?php if( $this->hotel->isRegisterComplete()) :?>            		
    				    <button type="button" onclick="myFunction()" class="btn orange lg" name="save_close">Save and close</button>            		
            		<?php endif;?>
            		   <button type="submit" class="btn orange lg" name="save_next">Next step &gt;&gt;</button>            	
                </div>                
            </div>
            <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>">
            <input type="hidden" name="id" value="<?php echo is_object($this->taxes) ? $this->taxes->id : '0';?>" />
            <input type="hidden" name="task" value="hotelprofile.taxes" />
            <input type="hidden" name="save_next" value="1" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>

<script type="text/javascript">
    function myFunction() {
        var div = document.createElement('div');
        div.className = 'row';
        div.innerHTML = '<input type="hidden" name="save_close" value="2">';

        document.getElementById('appendSaveClose').appendChild(div);
        document.getElementById("taxForm").submit();
    }
</script>
