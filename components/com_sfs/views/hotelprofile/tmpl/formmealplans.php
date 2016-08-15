<?php
defined('_JEXEC') or die;
$tax = $this->hotel->getTaxes();
$this->hotel->currency_name = $tax->currency_name;
global $custom_comma_decimal;
$custom_comma_decimal = json_decode( $this->mealplan->custom_comma_decimal );
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

<script type="text/javascript">
    function showHideInfo(id){
        var count = 1;
        document.getElementById("checkBox_" + id).value = count;
        var valBreak = document.getElementById("checkBox_" + id).checked;
        if(valBreak){
            document.getElementById('main_' + id).style.display = 'block';
        }else{
            document.getElementById('main_' + id).style.display = 'none';
            document.getElementById('checkBox_' + id).value = 0;
        }

        count++;
    }    
</script>

<div class="sfs-above-main sfs-hotel-title">
    		<?php if($this->hotel->step_completed < 9) : ?>
					<?php $title = JText::sprintf('COM_SFS_STEP', 3) . JText::_('COM_SFS_LABLE_FB'); ?>
            <?php else : ?>
                <?php $title = $this->hotel->name.' - '.JText::_('COM_SFS_LABLE_FB'); ?>
             <?php endif; ?>
        </div>

<?php if( ! $this->hotel->isRegisterComplete()) :?>
        	<?php
        	$text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_11'));
        	$text = empty($text) ? JText::_('COM_SFS_HOTEL_FB_DESC') : $text;
        	?>
    <?php endif; ?>

<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $title?></h3>
        <p class="descript-txt"><?php echo @$text?></p>
    </div>
</div>

<div class="main">
	<div id="hotel-registraion">

	    <?php if( ! $this->hotel->isRegisterComplete()) :?>
	        <h1 class="page-title" style="text-align:center"><?php echo $this->hotel->name; ?></h1>

	        <?php echo $this->progressBar(2); ?>

	        <div class="clear"></div>

	        <p>
	        	<?php
	        	$text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_11'));
	        	echo empty($text) ? JText::_('COM_SFS_HOTEL_FB_DESC') : $text;
	        	?>
			</p>

	        <div class="clear"></div>
	    <?php endif; ?>

	    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post" name="FBForm" id="FBForm" class="form-validate sfs-form form-vertical register-form">
	        <div class="block-group">
	        	<div class="block border orange clearfix">
	            	<?php echo $this->loadTemplate('breakfast');?>
	            </div>
	            <div class="block border orange clearfix">
	                <?php echo $this->loadTemplate('lunch');?>
				</div>
	            <div class="block border orange clearfix">
	            	<?php echo $this->loadTemplate('lunchdinner');?>
	            </div>
	            	           
	        </div>

		    <div class="wrap-col">
                <div class="form-group">
                    <div class="col w50">
                        <?php
                         $text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_12'));
                         echo empty($text) ? JText::_('COM_SFS_HOTEL_FB_NOTE') : $text;
                         ?>
                    </div>
                    <div class="col w50">
                        <div class="form-group btn-group">
                            <?php if($this->hotel->step_completed < 9) : ?>
                                <button type="submit" class="btn orange lg pull-right" name="save_next">Save and next &gt;&gt;</button>
                            <?php else : ?>
                                <button type="submit" class="btn orange lg pull-right" name="save_close"><?php echo JText::_('COM_SFS_SAVE_AND_CLOSE');?></button>
                            <?php endif; ?>
                        </div>
                    </div>
		        </div>
		    </div>

		    <input type="hidden" name="id" value="<?php echo is_object($this->mealplan) ? $this->mealplan->id : 0 ;?>" />
		    <input type="hidden" name="task" value="hotelprofile.saveMealplan" />
            <input type="hidden" name="save_next" value="1" />
		    <?php echo JHtml::_('form.token'); ?>
	    </form>
	</div>
</div>
