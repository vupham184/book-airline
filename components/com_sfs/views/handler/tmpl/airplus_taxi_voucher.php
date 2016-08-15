<script>
    function printDiv() {

        //Get the HTML of div
        divID = "sfs-print-wrapper";
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
<?php
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$vouchers_json = JRequest::getVar('vouchers');
?>
<?php if ( isset( $vouchers_json ) && $vouchers_json != '' ):
    $info_of_card_taxi = json_decode($vouchers_json);
?>
<div class="fs-14">
    <?php
    $rePrint = JRequest::getInt('reprint',0);
    if( $rePrint == 0 ) :
        ?>
        <a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
        <a onclick="printDiv();" class="sfs-button float-right">Print</a>
    <?php else :?>
        <a onclick="self.close();" class="sfs-button float-right">Close</a>
        <a onclick="printDiv();" class="sfs-button float-right">Print</a>
    <?php endif;?>
</div><br clear="all" />
<div id="sfs-print-wrapper" class="fs-14">
    <div class="htmlvoucher">

        <div style="background-color:#fff; border:solid 10px #cc2030; padding:10px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">

            <?php
            if ($airline->logo && (file_exists($airline->logo)))
            {
                ?>
                <img style="float:right;" src="<?php echo $airline->logo?>" height="45px">
            <?php
            }
            ?>
            <br clear="all" />
            
            <p> Dear <?php echo $info_of_card_taxi->PassengerName; ?>,</p>
            
            With this below Non card present creditcard issued by <?php echo $airline->name;?> <?php echo $airline->country_name ? '('.$airline->country_name.')':'';?> you will be able to pay for the taxi transportation from the <?php echo $airline->name;?> <?php echo $airline->country_name ? '('.$airline->country_name.')':'';?> to
            hotel and back from the hotel to the airport. (Please hold on to this creditcard for your return transfer to the airport). For this return transfer we have reserved <?php echo number_format($info_of_card_taxi->value, 2, '.', ',');?> Euro, on this creditcard and it will have limited validity but at least until your onward travel if applicable.
            <br />
            Please note that this Creditcard can only be used for Taxi transportation and not for other services.
            <br /><br />           
            Please <strong>give the numbers</strong> on this voucher<strong> to the taxi driver</strong> so he can enter the creditcard number in his credit card machine but keep the voucher for your return taxi transfer.
            
            <?php
                $info_of_card = json_encode($info_of_card_taxi);
                require_once JPATH_COMPONENT.'/libraries/emails/card.php';
            ?>
           <br />
            Kindly note that for your return transfer you will have to make your own taxi reservation.<br />
            You can do this by arranging this in the taxi or through the front desk of your hotel.            
            <p align="center" style="color:#666; display:none;">Share your experience on www.strandedexperience.com</p>
        </div>
    </div>
</div>
<?php endif;?>
