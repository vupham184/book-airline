<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$app = JFactory::getApplication();
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
?>
<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/css/checkbox.css" type="text/css" />
<script src="<?php echo JURI::base() ?>/templates/<?php echo $app->getTemplate();?>/js/checkbox.js" type="text/javascript"></script>


<script type="text/javascript">
jQuery(function ($) {
    $(document).ready(function() {
        $("#update").on("click", function(){
            var taxi        = $("#taxi").is(':checked') ? 1 : 0;
            $.ajax({
                url :"<?php echo JURI::base().'index.php?option=com_sfs&task=individualpassengerpage.updateServiceTracePassenger';?>",
                type:"POST",
                data:{
                    passenger_id: <?php echo $this->Item->id;?>,
                    taxi: taxi
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
<h2>Update Airplus Transfer to hotel</h2>
	<table cellpadding="5" cellspacing="5" style="text-align: center; width:100%">
        <?php if($airplusparams['taxi_enabled']):?>
		<tr>
	    	<td align="right">
                <img src="<?php echo JURI::base(true)."/media/system/images/airplus/taxi-icon.png"?>">
	    	</td>
	        <td align="left">
                <div class="ui toggle checkbox">
                    <input name="taxi" type="checkbox" id="taxi" <?php echo ((int)$this->Item->airplus_taxi)? 'checked=checked':'';?>>
                </div>
	        </td>
	    </tr>
        <?php endif;?>
       
	    <tr>
	    	<!--<td>
				<a style="margin-top:20px;" class="small-button" onclick="parent.jQuery.fancybox.close();">Close</a>
	    	</td>-->
            <td colspan="2" align="center">
                <button style="margin-top:20px;" class="small-button" type="button" id="update" >Update</button>
            </td>
	    </tr>

	</table>
	<?php echo JHtml::_( 'form.token' ); ?>


