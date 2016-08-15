<?php
defined('_JEXEC') or die;
JHtml::_('behavior.modal', 'input.modal');

$hotelTransport = $this->hotel->getTransportDetail();
$hotelSetting   = $this->hotel->getBackendSetting();
$this->tax 		= $this->hotel->getTaxes();

$listCur = SfsHelper::getCurrencyMulti();
                   
$curGlobal = "";
foreach ($listCur as $value) {
    if($value->id == $this->hotel->currency_id){
        $curGlobal = $value->code; 
    }                          
}

?>

<script type="text/javascript">
    var rFormSubmit = function(){
    	document.roomLoadingForm.submit();
    }
    window.addEvent('domready', function(){

    	var roomLoadingFormId = document.id('roomLoadingForm');
    	var roomLoadingFormValidator = new Form.Validator(roomLoadingFormId);

    	$('save-prices').addEvent('click',function(event){
    		if(roomLoadingFormValidator.validate()) {
    			SqueezeBox.open($('save-price-buttons'), {handler: 'clone',size: {x: 280, y: 80}});          
    		}
    	});

    	$('transportall').addEvent('click',function(event){
    		$$('input.transport').each( function(el){
    			if( $('transportall').checked ) {
    				el.setProperty('checked','checked')
    			} else {
    				el.removeProperty('checked');
    			}
    		});

    	});

    });
    var doc = document.documentElement;
    doc.setAttribute('data-useragent', navigator.userAgent);
</script>

<!--[if IE 11]>
    <style>
        input[type=checkbox]{
            min-height: 18px;
            min-width: 18px;
            border: #dddddd 1px;
        }
    </style>
<![endif]-->

<!--[if lte IE 9]>
    <style>
        input[type=checkbox]{
            min-height: 28px;
            min-width: 28px;
            border: #dddddd 1px;
        }
    </style>
<![endif]-->

<style>
    html[data-useragent*='MSIE 10.0'] input[type=checkbox]{
        min-height: 28px;
        min-width: 28px;
        border: #dddddd 1px;
    }
</style>


<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::sprintf('COM_SFS_ROOMLOADING_PAGE_TITLE',$this->hotel->name)?></h3>
        <div class="descript-txt"><?php echo JText::_('COM_SFS_ROOMLOADING_SHORT_DESC')?></div>
    </div>
</div>

<div class="main">
    <?php
    if(count($this->rooms_prices)) {
                /*foreach ( $this->rooms_prices as $key => $value ) {
                    if( is_object($value)  ) {
                        if((int)$value->modified_by) {
                            $user = JFactory::getUser();
                            if( $value->modified_by==$user->id ) {
                                $modifiedName = $user->name;
                            } else {
                                $mUser = JUser::getInstance($value->modified_by);
                                $modifiedName = $mUser->name;
                            }
                            echo '<p style="text-align:right;">Last change made by '.$modifiedName.' on '.JHtml::_('date',$value->modified,'d/m/Y H:i').' (server time)</p>';
                        }
                        break;
                    }
                }*/
                SFSCore::includePath('log');
                SfsLog::printf('roomloading',$this->hotel);
            }
            ?>
            <form name="roomLoadingForm" id="roomLoadingForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=roomloading');?>" method="post">
                <div class="roomloading-left pull-left">
                <?php if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ): ?>
                      <div class="roomloading-rate" style="margin-top: 5px; margin-bottom: 15px">
                        <strong>Single rooms authorized</strong>
                        <div style="margin-top: 15px;">Gross rate (in <?php echo $curGlobal; ?>)</div>
                    </div>
                <?php endif;?>

                <div class="roomloading-rate" style="margin-top: 5px;">
                    <div data-step="1" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_sd_authorized', $text, 'hotel') ?>">
                        <strong><?php echo JText::_('COM_SFS_ROOMLOADING_SD_ROOMS_AUTHORIZED') ?></strong>                        
                    </div>
                    <div data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_gross_rate', 'Gross rate (in '.$this->tax->currency_name.')', 'hotel') ?>" style="margin-top: 12px">
                        <?php echo JText::_('Gross rate (in '.$curGlobal.')');?>                        
                    </div>                        
                </div>
                
                <div class="roomloading-rate" style="margin-top: 15px;">
                    <strong><?php echo JText::_('COM_SFS_ROOMLOADING_T_ROOMS_AUTHORIZED');?></strong>
                    <div style="margin-top: 15px;">Gross rate (in <?php echo $curGlobal; ?>)</div>
                </div>

                <?php if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ): ?>
                      <div class="roomloading-rate" style="margin-top: 15px;">
                        <strong>Quad rooms authorized</strong>
                        <div style="margin-top: 15px;">Gross rate (in <?php echo $curGlobal; ?>)</div>
                    </div>
                <?php endif;?>
            <?php if( isset($hotelTransport) && (int)$hotelTransport->transport_available > 0 ) : ?>
                <div style="padding-top: 12px;">           		
                    <span data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_transport_included', $text, 'hotel') ?>"><?php echo JText::_('COM_SFS_ROOMLOADING_TRANSPORT_INCLUDED') ?></span>
                </div>
            <?php endif;?>
            <?php
            if( count($this->contractedRates) ):?>
            <div style="padding-top:30px;padding-bottom:5px;"><strong>Contracted rates</strong></div>
            <?php
            foreach ( $this->contractedRates as $contractedRate ):?>
            <div style="padding-top:3px; padding-bottom:3px;">
             <?php echo !empty($contractedRate->airline_name) ? $contractedRate->airline_name : $contractedRate->company_name;?>
         </div>
     <?php endforeach;?>
     
 <?php endif;?>
</div>

<div class="roomloading-middle float-left">
   <div class="roomtable">                
    <?php echo $this->loadTemplate('table');?>
     <?php
        if( count($this->contractedRates) ):
           echo $this->loadTemplate('contractedrates');
       else:
		echo '</tbody></table>';
       endif;
       ?>                                
    <!--<div style="margin:0 2px 6px 2px;">
       
   </div>-->
</div>    		

<a class="fs-12" href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=taxes&Itemid='.JRequest::getInt('Itemid'));?>" data-step="9" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_check_ranking', $text, 'hotel'); ?>">Taxes details</a>
<div class="form-group btn-group">            					
    <button id="save-prices" class="btn orange sm" type="button" data-step="10" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_save', $text, 'hotel'); ?>">Save prices</button>
    
    <div style="display:none">
       <div id="save-price-buttons">
          <div class="save-price-button-wrap">
             <?php echo JText::_('COM_SFS_ROOMLOADING_ARE_YOU_SURE')?><br />
             <ul class="menu-command">
                <li class="float-left"><button type="button" class="btn orange lg" style="float:none;" onclick="rFormSubmit();"><?php echo JText::_('COM_SFS_YES')?></button></li>
                <li class="float-left"><button type="button" class="btn orange lg" style="float:none;" onclick="window.SqueezeBox.close();"><?php echo JText::_('COM_SFS_NO')?></button></li>
            </ul>
        </div>
    </div>
</div>

<?php // echo SfsHelper::htmlTooltip('roomloading_check_ranking', '<button id="check_ranking" class="btn orange sm" type="button" style="float:none;">'.JText::_('COM_SFS_ROOMLOADING_CHECK_RANKING_BUTTON').'</button>' ,'hotel');?>
<button id="check_ranking" class="btn orange sm" data-step="11" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_check_ranking', $text, 'hotel'); ?>"><?php echo JText::_('COM_SFS_ROOMLOADING_CHECK_RANKING_BUTTON') ?></button>

<div style="display:none;">
   <div id="check-ranking-msg">
      <div style="padding:10px;">
         <span style="font-size:14px;"><?php echo JText::_('COM_SFS_ROOMLOADING_PRICE_SAVE_FIRST')?></span>
         <ul class="menu-command">
           <li><button type="button" class="button" style="float:none;" onclick="window.SqueezeBox.close();">Close</button></li>
       </ul>
   </div>
</div>
</div>                
</div>

</div>

<div class="roomloading-right pull-right">
    <?php if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1  && (int)$hotelSetting->quad_room_available == 1 ): ?>
        <div style="margin-top:355px;"  data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_transport_always_included', $text, 'hotel'); ?>">
    <?php elseif((int)$hotelSetting->single_room_available == 1  || (int)$hotelSetting->quad_room_available == 1 ):?>
        <div style="margin-top:271px;"  data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_transport_always_included', $text, 'hotel'); ?>">
    <?php else:?>
        <div style="margin-top:187px;"  data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('roomloading_transport_always_included', $text, 'hotel'); ?>">
    <?php endif;?>

       <div style="width:20px;">
           <?php if( isset($hotelTransport) && (int)$hotelTransport->transport_available > 0 ) :?>
             <input type="checkbox" id="transportall" name="transportall" value="yes" <?php echo $this->tax->transport ? 'checked="checked"' : ''; ?> />
         <?php else : ?>
             <input type="checkbox" id="transportall" name="transportall" value="" style="display:none;" />
         <?php endif;?>
     </div>
     <div style="width:120px;">
      <?php if( isset($hotelTransport) && (int)$hotelTransport->transport_available > 0 ) :?>
       <?php echo SfsHelper::htmlTooltip('roomloading_transport_always_included', JText::_('COM_SFS_ROOMLOADING_TRANSPORT_ALWAYS_INCLUDED') ,'hotel');?>
   <?php endif;?>
</div>
</div>
</div>


<div class="float-right fs-16">
    <?php
            //echo JText::sprintf('COM_SFS_ROOMLOADING_NOTE',$this->params->get('rule25','25').'%');
    ?>
</div>
<div class="main-bottom-block">
 <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid') ) ;?>" class="btn orange sm">Back</a>
</div>

<input type="hidden" name="task" value="roomloading.save" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />

<?php echo JHtml::_('form.token'); ?>
</form>
</div>
