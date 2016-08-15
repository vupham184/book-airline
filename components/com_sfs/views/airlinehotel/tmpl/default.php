<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<script>
    jQuery.noConflict();
    jQuery(function($){
        $(".updatebutton").on("click", function(){
            var id = $(this).attr('data-id');
            var url = "index.php?option=com_sfs&view=airlinehotel&layout=reservation&Itemid=161&tmpl=component&id="+id;
            SqueezeBox.open(url, {handler: 'iframe', size: {x: 800, y: 470} });
        })
    })
</script>



<div class="heading-block clearfix">
    <div class="heading-block-wrap">
    </div>
</div>

<div class="main">
    <div class="sfs-above-main search-results-title">
        <h3 class="pull-left">Your hotel(s)</h3>
    </div>

    <table class="search-result" id="search-result">
        <thead id="search-result-head">
        <tr>
            <th class="main-head" style="width: 50%">Hotel details</th>
            <th class="main-head" style="width: 50%">Mealplan</th>
        </tr>
        </thead>
    <?php foreach($this->hotels as $hotel):
        $this->item = & $hotel;
    ?>
        <tbody action="" method="post" class="sfs-form form-vertical">
        <tr class="hotel-row-space"><td></td></tr>
        <tr class="hotel-row">
            <td style="width: 50%">
                <?php echo $this->loadTemplate('item_information');?>
            </td>

            <td style="width: 50%">
                <?php echo $this->loadTemplate('item_mealplan');?>
            </td>
        </tr>
        <tr class="tr button-row">
            <td class="hotel-row"></td>
            <td class="hotel-row" >
                <button type="button" class="btn orange sm updatebutton button-now pull-right" rel="" data-id="<?php echo $hotel->hotel_id;?>" style="margin-bottom: 30px">Book</button>
            </td>
        </tr>
        </tbody>
    <?php endforeach;?>
    </table>
    <!-- End show results-->
</div>
