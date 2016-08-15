<?php
defined('_JEXEC') or die;
$tax = $this->hotel->getTaxes();
$this->hotel->currency_name = $tax->currency_name;
?>
<script type="text/javascript">
<!--
function checkServiceAvailable() {
	for (i=0; i<document.FBForm.service_hour.length; i++)
	{
		if (document.FBForm.service_hour[i].checked==true) {
			if(document.FBForm.service_hour[i].value==0) {
				document.getElementById('service_outside').value='';
				document.getElementById('service_outside').setAttribute('readonly','readonly');
			} else {
				document.getElementById('service_outside').removeAttribute('readonly');
			}
			break;
		}
	}
}
window.addEvent('domready', function(){
	var FBFormId = document.id('FBForm');
	var FBFormValidator = new Form.Validator(FBFormId);
});
//-->
</script>

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $this->hotel->name; ?></h3>
        <p class="descript-txt"><?php
        	$text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_11'));
        	echo empty($text) ? JText::_('COM_SFS_HOTEL_FB_DESC') : $text;
        	?></p>
    </div>
</div>

<div id="sfs-wrapper" class="main fs-14">

<div id="hotel-registraion">

    <?php if($this->hotel->step_completed < 9) : ?>

        <?php echo $this->progressBar(4); ?>

        <div class="clear"></div>
    <?php endif; ?>

    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post" name="FBForm" id="FBForm">
        <div class="sfs-above-main sfs-hotel-title">
    		<?php if($this->hotel->step_completed < 9) : ?>
                 <h2>
					<?php echo JText::sprintf('COM_SFS_STEP', 5); ?><?php echo JText::_('COM_SFS_LABLE_FB'); ?>
        		 </h2>
            <?php else : ?>
                <h2><?php echo $this->hotel->name.' - '.JText::_('COM_SFS_LABLE_FB'); ?></h2>
             <?php endif; ?>
        </div>
	    <div class="sfs-main-wrapper-none">
	        <div class="sfs-orange-wrapper hotel-form">

	            <div class="sfs-white-wrapper floatbox">
	            	<?php echo $this->loadTemplate('lunchdinner');?>
	            </div>
	            <div class="sfs-white-wrapper sfs-white-wrapper-last floatbox">
	            	<?php echo $this->loadTemplate('breakfast');?>
	            </div>

	        </div>
	    </div>

	    <div class="sfs-below-main">
	    	<div class="float-left" style="width:500px">
        		<?php
        		 $text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_12'));
        		 echo empty($text) ? JText::_('COM_SFS_HOTEL_FB_NOTE') : $text;
        		 ?>
        	</div>
	    	<div class="s-button float-right">
		        <?php if($this->hotel->step_completed < 9) : ?>
		            <input type="submit" class="s-button" name="save_next" value="Save and next &gt;&gt;">
		        <?php else : ?>
		           	<input type="submit" class="s-button" name="save_close" value="<?php echo JText::_('COM_SFS_SAVE_AND_CLOSE');?>">
		        <?php endif; ?>
	        </div>
	    </div>

	    <input type="hidden" name="id" value="<?php echo is_object($this->mealplan) ? $this->mealplan->id : 0 ;?>" />
	    <input type="hidden" name="task" value="hotelprofile.saveMealplan" />
	    <?php echo JHtml::_('form.token'); ?>
    </form>

</div>
</div>
