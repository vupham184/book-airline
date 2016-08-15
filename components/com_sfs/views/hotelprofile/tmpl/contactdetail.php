<?php
defined('_JEXEC') or die;
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo JText::_('COM_SFS_HOTEL_TEAM_DETAILS'); ?></h3>
	</div>
</div>
<div id="sfs-wrapper" class="main">
<div id="hotel-registraion" class="registration<?php echo $this->pageclass_sfx?>">

    <p class="fs-14"><?php echo JText::sprintf('COM_SFS_LABEL_WELCOME', $this->user->name); ?></p>
    
    <div class="fs-14 midmarginbottom">
            <?php echo JText::_('COM_SFS_HOTEL_TEAM_DETAILS_TOP_NOTE'); ?>
    </div>    
    
    <div class="sfs-above-main sfs-hotel-title">            
        <h2><?php echo JText::_('COM_SFS_HOTEL_CONTACT_DETAILS'); ?></h2>    
	</div>
        
    <div class="sfs-main-wrapper-none">
        <div class="sfs-orange-wrapper hotel-form">
        
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">
                <div class="fieldset-title float-left">
                    <span style="line-height:120%;" class="fs-16">Hotel</span> 
                </div>                                      
                <div class="fieldset-fields float-left" style="width:584px; padding-top:35px;">
                    <div class="register-field clear floatbox fs-14">
                        <label><?php echo JText::_('COM_SFS_HOTEL_NAME');?> :</label> <?php echo $this->hotel->name; ?>
                    </div>
                    <div class="register-field clear floatbox fs-14">
                        <label><?php echo JText::_('COM_SFS_CHAIN_AFFILIATION');?> :</label> <?php echo strlen((string)$this->hotel->chain_name ) > 0 ? $this->hotel->chain_name : 'Not Listed'; ?> 
                    </div>
                </div>                    
			</div>
            
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">            
	            <?php echo $this->loadTemplate('address'); ?>
            </div>
            
            <div class="sfs-white-wrapper floatbox" style="margin-bottom:25px;">     
            	<?php echo $this->loadTemplate('billing'); ?>
            </div>
            
            <?php if ( count($this->contacts) > 1 ) : ?>
                <div class="sfs-white-wrapper floatbox">             
                	<?php echo $this->loadTemplate('contacts'); ?>                 
                </div>
            <?php endif; ?>

        </div>
    </div>
    
    <div class="sfs-below-main">
    	<div class="float-right">
            <div class="s-button">
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile') );?>" class="s-button"><?php echo JText::_('COM_SFS_BACK');?></a>
            </div>   
            <div class="s-button s-button-separator">
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelregister','registerdetail'));?>" class="s-button"><?php echo JText::_('COM_SFS_EDIT');?></a>
            </div>
        </div>
    </div>    
    <div class="clear"></div>
          
</div>
</div>
