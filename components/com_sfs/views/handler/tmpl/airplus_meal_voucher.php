<script>
    function printDiv(id) {

        //Get the HTML of div
        divID = "sfs-print-wrapper" +id;
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;
        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            divElements + "</body>";
        //Print Page
        window.print();
        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }
</script>
<?php
defined('_JEXEC') or die();
?>
<?php /* @var $wsBooking Ws_Do_Book_Response */?>

<?php
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$i = 0;
$vouchers_json = JRequest::getVar('vouchers');
?>
<?php if ( isset( $vouchers_json) && $vouchers_json != '' ):
        $vouchers = json_decode($vouchers_json);
        foreach($vouchers as $info_of_card_meal):
?>

<div class="heading-buttons">
    <?php
    $rePrint = JRequest::getInt('reprint',0);
    if( $rePrint == 0 ) :
        ?>
        <a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
        <a onclick="printDiv(<?php echo $i;?>);" class="sfs-button float-right">Print</a>
    <?php else :?>
        <a onclick="self.close();" class="sfs-button float-right">Close</a>
        <a onclick="printDiv(<?php echo $i;?>);" class="sfs-button float-right">Print</a>
    <?php endif;?>
</div>
<br clear="all" />
<div id="sfs-print-wrapper<?php echo $i?>" class="fs-14">
    <div class="htmlvoucher">

        <div style="background-color:#fff; border:solid 10px #cc2030; padding:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif;">

            <?php
            if ($airline->logo && (file_exists($airline->logo)))
            {
                ?>
                <img style="float:right;" src="<?php echo $airline->logo?>" height="45px" >
            <?php
            }
            ?>
            <br clear="all" />
            
            <p> Dear <?php echo $info_of_card_meal->PassengerName; ?>,                            
            </p>
            
            With this below Non card present creditcard issued by <?php echo $airline->name;?> <?php echo $airline->country_name ? '('.$airline->country_name.')':'';?> you will be able to pay for and beverage at the airport food and beverage outlets that accept creditcard.            
            <br /><br />
            For your inconvienience we have reserved <strong><?php echo $info_of_card_meal->value?> Euro,</strong>- on this creditcard and it will have limited validity but at least untill your onward travel if applicable.
            <br /><br />
            Please note that this Creditcard can only be used for Food and Beverage and not for other services or items.            
            <br /><br />
            Please <strong>give the numbers</strong> on this voucher <strong>at the counter when you have to pay</strong> for your order so they can enter the creditcard number in the credit card machine.<br />
            If you have not used the entire sum on this creditcard at same vendor you may keep the voucher and use the remaining value at another time but allways before your onward travel as it has a limited validity.
            
            <p align="center" style="color:#666; display:none;">Share your experience on www.strandedexperience.com</p>
            <?php
                $info_of_card_meal = json_encode($info_of_card_meal);
                require JPATH_COMPONENT.'/libraries/emails/card-meal.php';
            ?>
        </div>
    </div>
</div>
<?php
        $i++;
    endforeach;
endif;
?>
