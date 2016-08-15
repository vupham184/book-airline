<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$app = JFactory::getApplication();
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$airplus_id = (int)$this->item->airplus_id;
$hotel_id = (int)$this->item->hotel_id;
?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>


<script type="text/javascript">
jQuery.noConflict();
jQuery(function ($) {
    $(document).ready(function() {
        $("#update").on("click", function(){
            var mealplan    = $("#airplus_mealplan").is(':checked') ? 1 : 0;
            var taxi        = $("#airplus_taxi").is(':checked') ? 1 : 0;
            var cash        = $("#airplus_cash").is(':checked') ? 1 : 0;
            $.ajax({
                url :"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.updateAirplusServicesIndividual&format=raw';?>",
                type:"POST",
                data:{
                    airplus_id: <?php echo $airplus_id;?>,
                    hotel_id: <?php echo $hotel_id;?>,
                    'ap-meal': mealplan,
                    'ap-taxi': taxi,
                    'ap-cash': cash
                },
                success:function(response){
                    if(response == "1")
                    {
                        alert("Updated successfully!");
                        parent.jQuery.fancybox.close();
                        window.parent.location.reload();
                    }
                    else
                        alert("ERROR!");
                }
            })

        });
        jQuery(".ui.checkbox").checkbox();
    })
})
</script>
<h2>Add Additional Services</h2>
	<table cellpadding="5" cellspacing="5" style="text-align: center">
        <?php if($airplusparams['meal_enabled']):?>
		<tr>
	    	<td>
                <img src="<?php echo JURI::base(true)."/media/system/images/airplus/mealplan-icon.png"?>">
	    	</td>
	        <td>
                <div class="ui toggle checkbox">
                    <input name="airplus_mealplan" type="checkbox" id="airplus_mealplan" <?php echo ((int)$this->item->airplus_mealplan)? 'checked=checked':'';?>>
                </div>
	        </td>
	    </tr>
        <?php endif;?>
        <?php if($airplusparams['taxi_enabled']):?>
		<tr>
	    	<td>
                <img src="<?php echo JURI::base(true)."/media/system/images/airplus/taxi-icon.png"?>">
	    	</td>
	        <td>
                <div class="ui toggle checkbox">
                    <input name="airplus_taxi" type="checkbox" id="airplus_taxi" <?php echo ((int)$this->item->airplus_taxi)? 'checked=checked':'';?>>
                </div>
	        </td>
	    </tr>
        <?php endif;?>
        <?php if($airplusparams['cashreim_enabled']):?>
        <tr>
            <td>
                <img src="<?php echo JURI::base(true)."/media/system/images/airplus/cash-icon.png"?>">
            </td>
            <td>
                <div class="ui toggle checkbox">
                    <input name="airplus_cash" type="checkbox" id="airplus_cash" <?php echo ((int)$this->item->airplus_cash)? 'checked=checked':'';?>>
                </div>
            </td>
        </tr>
        <?php endif;?>
        <?php if($airplusparams['telcard_enabled']):?>
        <tr>
            <td>
                <img src="<?php echo JURI::base(true)."/media/system/images/airplus/telephone-icon.png"?>">
            </td>
            <td>
                <div class="ui toggle checkbox">
                    <input name="airplus_phone" type="checkbox" id="airplus_phone" <?php echo ((int)$this->item->airplus_phone)? 'checked=checked':'';?>>
                </div>
            </td>
        </tr>
        <?php endif;?>
	    <tr>
	    	<td>
				<a style="margin-top:20px;" class="small-button" onclick="parent.jQuery.fancybox.close();">Close</a>
	    	</td>
            <td>
                <button style="margin-top:20px;" class="small-button" type="button" id="update" >Update</button>
            </td>
	    </tr>

	</table>
	<?php echo JHtml::_( 'form.token' ); ?>


