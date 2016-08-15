<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$app = JFactory::getApplication();
$passenger = $this->trace_passenger;
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$ap_voucher_id = (int)$this->Item->airplus_meal_id;
$info_of_card_ap_meal = json_decode($this->Item->info_of_card_ap_meal);
$value = (int)$info_of_card_ap_meal[0]->value;

?>

<script type="text/javascript">
jQuery(function ($) {
    $(document).ready(function() {
        $("#update").on("click", function(){
            $.ajax({
                url :"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.updateMealplanService&format=raw';?>",
                type:"POST",
                data:{
                    ap_voucher_id: <?php echo $ap_voucher_id;?>
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
    })
})
</script>
<!--<h2>Update Airplus Mealplan Voucher</h2>-->
	<p>
    	You currently have issued an mealplan credit card of 5.00 Euro.
        If the delay has extended you can update the total amount of the credit card to <?php echo $value+10?> EUR by adding 10 EUR here.
    </p>
	<table cellpadding="5" cellspacing="5" style="text-align: center; width:100%" >
        <?php if($airplusparams['meal_enabled']):?>
		<tr>
	    	<td align="right">
                <img src="<?php echo JURI::base(true)."/media/system/images/airplus/mealplan-icon.png"?>">
	    	</td>
	        <td align="left">
                 <strong style="margin-top:-10px;">+10 EURO</strong>
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


