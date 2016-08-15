<?php
defined('_JEXEC') or die();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <base href="http://www.sfs-web.com/BRU/">

    <title>SFS-web Your Booking Detail</title>
</head>
<body style="width:620px;">

<div style="background-color:#fff; border:solid 10px #82ADF1;padding: 10px; font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;">
<div style="text-align: center;font-size:16px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; font-weight: bold">SFS-web<br />Your Booking Detail</div>
    <h2 style="margin-top:0">YOUR ACCOMMODATION DETAILS:</h2>
    <?php if($hotel):?>
        <p>
            <?php
            echo $hotel->name.'<br />'.$hotel->address.'<br />'.$hotel->zipcode.', '.$hotel->city;?>
            <br />
            <?php
            if($hotel->telephone)
                echo 'Tel: '.$hotel->telephone;
            else
                echo 'Tel: '.JText::_('COM_SFS_NO_TELEPHONE')
            ?>
        </p>
    <?php endif;?>
    <p>
        <?php if( $voucher->get('payment_type','airline') == 'passenger' ):	?>
            NON PREPAID VOUCHER,<br />
            <?php
            echo 'Your total estimated charges will be '.$voucher->getTotalCharged().' '.$system_currency;?>
            <br />
        <?php endif;?>

        <?php if( $nameText) : ?>
            Voucher issued for: <?php echo $nameText; ?><br />
        <?php endif;?>

        <?php if(isset($passengers[0]->phone_number)):?>
            Telephone number:  <?php echo $passengers[0]->phone_number;?><br />
        <?php endif;?>

        Hotel Voucher Code: <?php echo $voucher->voucher_code; ?><br />
        Voucher for <?php echo $voucher->seats;?> person(s) entitled for:<br />

        <?php
        if( (int)$voucher->vgroup == 1 ) {
            $totalOfRooms = (int)$voucher->sroom + (int)$voucher->sdroom + (int)$voucher->troom + (int)$voucher->qroom;
            echo "- One night accommodation in ".$totalOfRooms." different rooms";
        } else {
            switch ( (int)$voucher->room_type )
            {
                case 1:
                    echo "- One night accommodation in single room";
                    break;
                case 2:
                    echo "- One night accommodation in double room";
                    break;
                case 3:
                    echo "- One night accommodation in triple room";
                    break;
                case 4:
                    echo "- One night accommodation in quad room";
                    break;
                default:
                    break;
            }
        }

        $date 		 = JFactory::getDate($voucher->get('date'));
        $dayFrom 	 = (int)$date->format('d');
        $dayFromText = SfsHelper::addOrdinalNumberSuffix( $dayFrom );
        $dateTo 	 = SfsHelperDate::getNextDate('d', $date);
        $dateToText  = SfsHelper::addOrdinalNumberSuffix( (int) $dateTo).' of '.SfsHelperDate::getNextDate('F Y', $date);

        echo ' starting '.$dayFromText.'   ending '.$dateToText;

        if( (int)$voucher->vgroup == 1 ) {
            $totalOfRooms = (int)$voucher->sroom + (int)$voucher->sdroom + (int)$voucher->troom + (int)$voucher->qroom;
            if($voucher->sroom)
            {
                echo '<br/>&nbsp;&nbsp;'.$voucher->sroom.' single room';
            }
            if($voucher->sdroom)
            {
                echo '<br/>&nbsp;&nbsp;'.$voucher->sdroom.' single/double room';
            }
            if($voucher->troom)
            {
                echo '<br/>&nbsp;&nbsp;'.$voucher->troom.' triple room';
            }
            if($voucher->qroom)
            {
                echo '<br/>&nbsp;&nbsp;'.$voucher->qroom.' quad room';
            }
        }
        ?>
        <br />

        <?php
        if($hotel):
            $mealplan = $hotel->getMealPlan();
            ?>
            <?php if( (int)$voucher->lunch && (int)$voucher->v_lunch ) : ?>
            <?php
            $serviceHour = $mealplan->lunch_service_hour;
            if( (int)$serviceHour == 1 ) {
                echo '- Pre arranged lunch available 24 hours';
            } else {
                $lunchText=' available between '.str_replace(':','h',$mealplan->lunch_opentime).' and '.str_replace(':','h',$mealplan->lunch_closetime);
                echo '- Pre arranged lunch'.$lunchText;
            }
            ?>
            <br />
            <?php endif;?>

            <?php if( (int)$voucher->mealplan && (int)$voucher->v_mealplan ) : ?>
            <?php
            $stopSellingTime = $mealplan->stop_selling_time;
            if( (int)$stopSellingTime == 24 ) {
                echo '- Pre arranged dinner available 24 hours';
            } else {
                echo '- Pre arranged dinner available until '.str_replace(':','h',$mealplan->stop_selling_time);
            }
            ?>
            <br />
            <?php endif;?>

            <?php if( (int)$voucher->breakfast && (int)$voucher->v_breakfast ) : ?>
            <?php
            if( (int)$mealplan->bf_service_hour == 1 ) {
                $breakfastText=' available 24 hours';
            } else {
                $breakfastText=' available between '.str_replace(':','h',$mealplan->bf_opentime).' and '.str_replace(':','h',$mealplan->bf_closetime) ;
            }
            echo '- Pre arranged breakfast'.$breakfastText;
            ?>
            <br />
             <?php endif;?>

        <?php endif;?>
    </p>

<p>
    <?php
    if( (int)$voucher->transport == 1 ) :
        $transport = $hotel->getTransportDetail();
        if($transport) {
            $transportText = 'Transport to accommodation included: ';
            switch ( (int)$transport->transport_available ) {
                case 1:
                    $transportText .= 'Yes';
                    break;
                case 2:
                    $transportText.='Not necessary (walking distance)';
                    break;
                default :
                    $transportText .= 'No';
                    break;
            }
            $transportText .= (int)$transport->transport_complementary == 1 ? '<br/>Complimentary: Yes':'<br/>Complimentary: No';
            echo $transportText;

            $transportText = '';
            $transport->operating_hour = (int)$transport->operating_hour;
            if($transport->operating_hour == 0 ){
                $transportText .='Operation hours: Not available';
            } else if($transport->operating_hour == 1) {
                $transportText .='Operation hours: 24 hours';
            } else if($transport->operating_hour == 2) {
                $transportText .='Operation hours: From '.str_replace(':','h',$transport->operating_opentime).' till '.str_replace(':','h',$transport->operating_closetime);
            }
            $transportText .='   Every: '.$transport->frequency_service.' minutes';
            echo '<br/>'.$transportText;
            if($transport->pickup_details){
                echo '<br/>';
                echo 'Transport details: '.$transport->pickup_details;
            }

        } else {
            echo 'Transport to accommodation included: No';
        }
        ?>
    <?php else : ?>
        <?php echo 'Transport to accommodation included: No';?>
    <?php endif;?>
</p>

    <?php
    if( $voucher->comment || $voucher->return_flight_number || ( isset($airline->params['show_general_comment']) && (int)$airline->params['show_general_comment'] == 1 ) ) :?>
        <h2>General comments</h2>
        <?php
        if(isset($airline->params['show_general_comment']) && (int)$airline->params['show_general_comment'] == 1) {
            $generalComment = $airline->getVoucherComment();
            if($generalComment)
            {
                ?>
                <?php echo $generalComment?><br/>
            <?php
            }
        }
        ?>

        <?php if($voucher->return_flight_number) : ?>
            Your new flight details: Flight number <?php echo $voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/><br/>
        <?php endif;?>

        <?php
        if( $voucher->comment) {
            echo $voucher->comment;
        }
        ?>

    <?php endif;?>

</div>


<div style="background-color:#FFDE73; border:solid 10px #FFB700; padding:10px; margin-top:15px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif; font-size: 12px">
    <p style="font-weight: bold;margin-top:5px;">
        Hotel billing information:<br />
        <?php echo 'Block code: '.$voucher->blockcode?><br />
        <?php echo 'Voucher code: '.$voucher->voucher_code?><br />
        <?php echo 'Voucher '.$voucher->seats.' person'?><br />
        <?php if($voucher->return_flight_number) : ?>
            Old flight info: Flight number <?php echo JString::strtoupper($voucher->flight_code);?> departure date <?php echo JHTML::_('date', $voucher->date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
            New flight info: Flight number <?php echo $voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
        <?php endif;?>
    </p>

    <p style="font-size:90%;">
        <?php if( $voucher->get('payment_type','airline') != 'passenger') : ?>
            <?php if( isset($airline->params['voucher_vat_comment_line']) && strlen(trim($airline->params['voucher_vat_comment_line'])) > 0 ) : ?>
                <?php echo $airline->params['voucher_vat_comment_line'];?>
            <?php else : ?>
                *Bestelbon - vrijstelling van de BTW art 42 para 2 alinea 5 W.BTW
            <?php endif;?>
            <br/>
        <?php endif?>

        <?php if( $voucher->get('payment_type','airline') != 'passenger') : ?>
            <span><?php echo 'Booking guaranteed by '.$airline->getAirlineName().' as agreed with your hotel on SFS-web.com'?></span>
        <?php endif?>
    </p>
</div>

<p align="center" style="color:#666">Share your experience on www.strandedexperience.com</p>


</body>
</html>
