<?php
defined('_JEXEC') or die;
$hotel = SFactory::getHotel();
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Hotel Management</h3>        
    </div>
</div>

<div class="main">    
    <p>Welcome <?php echo $this->user->name;?>,</p>                
    <div data-step="3" data-intro="<?php echo SfsHelper::getTooltipTextEsc('support_info', $text, 'hotel'); ?>">
        <?php echo SfsHelper::getIntroTextOfArticle($this->params->get('article_page_4_02')); ?>
    </div>    
    <div class="form-group btn-group">                
        <?php if ( $hotel->isRegisterComplete() ) :?>        
            <?php if(SFSAccess::check($this->user, 'h.admin')) :?>
                <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=roomloading&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg" data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_room_management', $text, 'hotel'); ?>">Rooms management</a>
            <?php endif;?>
            <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=block&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange lg" data-step="5" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_block_overview', $text, 'hotel'); ?>">Block Overview</a>
            <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=rooming&Itemid='.JRequest::getInt('Itemid'));?>" class="btn orange lg" data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_rooming_list_loading', $text, 'hotel'); ?>">Rooming list loading</a>        
        <?php endif;?>

        <?php if(SFSAccess::check($this->user, 'h.admin')) :?>        
            <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&Itemid='.JRequest::getInt('Itemid'))?>" class="btn orange lg" data-step="7" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_hotel_data', $text, 'hotel'); ?>">Hotel data</a>        
        <?php endif;?>
    </div>
</div>