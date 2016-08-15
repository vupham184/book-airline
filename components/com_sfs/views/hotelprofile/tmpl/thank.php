<?php
defined('_JEXEC') or die;
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::sprintf('COM_SFS_STEP', 7); ?> Done</h3>
    </div>
</div>

<div id="sfs-wrapper" class="fs-14">
    <div class="main">    
        <h1 class="page-title" style="text-align:center"><?php echo $this->hotel->name; ?></h1>
        
        <?php echo $this->progressBar(6); ?>

        <div class="clear"></div>
                
        <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post">
           
            <div class="sfs-main-wrapper-none">
    	        <div class="sfs-orange-wrapper">
    	            <div class="sfs-white-wrapper floatbox">
    					<?php echo JText::sprintf('COM_SFS_HOTEL_THANK_CONTENT',$this->user->name)?>	                    
    	            </div>        	
    	        </div>
            </div>
            <div class="sfs-below-main">
            	<div class="s-button float-right">
            	    <input type="submit" class="s-button" value="Finish">
            	</div>
            </div>
            
            <input type="hidden" name="task" value="hotelprofile.finish" />                
            <input type="hidden" name="hotel_id" value="<?php echo $this->hotel->id;?>" />                
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>