<?php

//file sing new
defined('_JEXEC') or die();
$count_trace_passengers = 0;
if( count($this->trace_passengers) )
{
    $trace_passengersA = $this->trace_passengers;
    $nameArray = array();
    foreach($trace_passengersA as $trace_passengers) {
      foreach($trace_passengers as $trace_passenger) {
         $name = $trace_passenger->first_name." ".$trace_passenger->last_name ;
         $nameArray[] = trim($name);
         $nameText = implode(', ', $nameArray);
         $count_trace_passengers++;
     }
 }
}
$list_name_pass = '';
$number_passenger = 0;
if($this->voucher_current){
    foreach($this->voucher_current as $v){
        if($list_name_pass!=''){
            $list_name_pass.=', ';
        }
        $list_name_pass .=$v->first_name.' '. $v->last_name;
        $number_passenger++;
    }
}
$airline = SFactory::getAirline();

$totalOfRooms = 0;
$sroom = 0;
$sdroom = 0;
$troom = 0;
$qroom = 0;
$lunch = 0;
$v_lunch = 0;
$mealplan = 0;
$v_mealplan = 0;
$breakfast = 0;
$v_breakfast = 0;
// foreach ($this->vouchers as $vo) {
// 	$sroom += (int)$vo->sroom;
// 	$sdroom += (int)$vo->sdroom;
// 	$troom += (int)$vo->troom;
// 	$qroom += (int)$vo->qroom;
// 	$lunch += (int)$vo->lunch;
// 	$v_lunch += (int)$vo->v_lunch;
// 	$mealplan += (int)$vo->mealplan;
// 	$v_mealplan += (int)$vo->v_mealplan;
// 	$breakfast += (int)$vo->breakfast;
// 	$v_breakfast += (int)$vo->v_breakfast;
	
// }
 $sroom += (int)$this->detail_voucher->sroom;
 $sdroom += (int)$this->detail_voucher->sdroom;
 $troom += (int)$this->detail_voucher->troom;
 $qroom += (int)$this->detail_voucher->qroom;
 $lunch += (int)$this->detail_voucher->lunch;
 $v_lunch += (int)$this->detail_voucher->v_lunch;
 $mealplan += (int)$this->detail_voucher->mealplan;
 $v_mealplan += (int)$this->detail_voucher->v_mealplan;
 $breakfast += (int)$this->detail_voucher->breakfast;
 $v_breakfast += (int)$this->detail_voucher->v_breakfast;

$totalOfRooms = $sroom+$sdroom+$troom+$qroom;

?>


<br clear="all" />
<div class="main_specific">
    <div class="hotel">
        <?php if ($this->airline->logo):?>
           <div class="logo">    
            <img style="margin-bottom: 20px;float:right;" src="<?php echo $this->airline->logo?>"/>
        </div>

    <?php endif;?>



    <h2 style="margin-top:0">YOUR ACCOMMODATION DETAILS:</h2>

    <?php if($this->hotel):?>
        <p>
            <?php
            echo $this->hotel->name.'<br />'.$this->hotel->address.'<br />'.$this->hotel->zipcode.', '.$this->hotel->city;?>
            <br />
            <?php
            if($this->hotel->telephone)
                echo 'Tel: '.$this->hotel->telephone;
            else
                echo 'Tel: '.JText::_('COM_SFS_NO_TELEPHONE')
            ?>
        </p>
    <?php endif;?>

    <h2>YOUR VOUCHER DETAILS:</h2>

    <p>

        <?php if( $this->detail_voucher->get('payment_type','airline') == 'passenger' ):   ?>
            NON PREPAID VOUCHER,<br />
            <?php
            echo 'Your total estimated charges will be '.$this->detail_voucher->getTotalCharged().' '.$this->system_currency;?>
            <br />
        <?php endif;?>

        
        <?php if( $list_name_pass) : ?>
            Voucher issued for: <?php echo $list_name_pass; ?><br />
        <?php endif;?>
        

        Hotel Voucher Code: <?php echo $this->detail_voucher->code; ?><br />
        Voucher <?php if(count($this->voucher_current) > 0 ) echo "for ".count($this->voucher_current)." person(s)";?> entitled for:<br />

        <?php
        if( (int)$this->detail_voucher->vgroup == 1 ) {        
            echo "- One night accommodation in ".$totalOfRooms." different rooms";
        } else {
            switch ( (int)$this->detail_voucher->room_type )
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

        $date        = JFactory::getDate($this->detail_voucher->get('date'));
        $dayFrom     = (int)$date->format('d');
        $dayFromText = SfsHelper::addOrdinalNumberSuffix( $dayFrom );
        $dateTo      = SfsHelperDate::getNextDate('d', $date);
        $dateToText  = SfsHelper::addOrdinalNumberSuffix( (int) $dateTo).' of '.SfsHelperDate::getNextDate('F Y', $date);

        echo ' starting '.$dayFromText.'   ending '.$dateToText;

        if( (int)$this->detail_voucher->vgroup == 1 ) {

            if($sroom)
            {
            echo '<br/>&nbsp;&nbsp;' . $sroom . ' single room';// $this->voucher->sroom
        }
        if($sdroom)
        {
            echo '<br/>&nbsp;&nbsp;'.$sdroom.' single/double room';
        }
        if($troom)
        {
            echo '<br/>&nbsp;&nbsp;'.$troom.' triple room';
        }
        if($qroom)
        {
            echo '<br/>&nbsp;&nbsp;'.$qroom.' quad room';
        }
    }
    ?>
    <br />

    <?php
    if($this->hotel):
        $mealplan = $this->hotel->getMealPlan();
    ?>
    <?php if( (int)$lunch && (int)$v_lunch ) : ?>
        <?php
        $serviceHour = $mealplan->lunch_service_hour;
        if( (int)$serviceHour == 1 ) {
            echo '- Pre arranged lunch available 24 hours';
        } elseif( (int)$serviceHour == 2 ) {
            $lunchText=' available between '.str_replace(':','h',$mealplan->lunch_opentime).' and '.str_replace(':','h',$mealplan->lunch_closetime);
            echo '- Pre arranged lunch'.$lunchText;
        }else{
            echo '- Pre arranged lunch';
        }
        ?>
        <br />
    <?php endif;?>

    <?php if( (int)$mealplan && (int)$v_mealplan ) : ?>
        <?php
        $stopSellingTime = $mealplan->stop_selling_time;
        if( (int)$stopSellingTime == 24 ) {
            echo '- Pre arranged dinner available 24 hours';
        } elseif(!empty($stopSellingTime)) {
            echo '- Pre arranged dinner available until '.str_replace(':','h',$mealplan->stop_selling_time);
        }else{
            echo '- Pre arranged dinner';
        }
        ?>
        <br />
    <?php endif;?>

    <?php if( (int)$breakfast && (int)$v_breakfast ) : ?>
        <?php
        $bfServiceHour = $mealplan->bf_service_hour;
        if( (int)$bfServiceHour == 1 ) {
            echo '- Pre arranged breakfast available 24 hours';
        } elseif( (int)$bfServiceHour == 2 ) {
            $breakfastText=' available between '.str_replace(':','h',$mealplan->bf_opentime).' and '.str_replace(':','h',$mealplan->bf_closetime) ;
            echo '- Pre arranged breakfast'.$breakfastText;
        }else{
            echo '- Pre arranged breakfast';
        }
        ?>
        <br />
    <?php endif;?>

<?php endif;?>
</p>

<h2>Transportation to hotel</h2>
<p>
    <?php
    if( (int)$this->detail_voucher->transport ) :
        $transport = $this->hotel->getTransportDetail();
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
if( $this->detail_voucher->comment || $this->detail_voucher->return_flight_number !='' || ( isset($this->airline->params['show_general_comment']) && (int)$this->airline->params['show_general_comment'] == 1 ) ) :?>
<h2>General comments</h2>
<?php
if(isset($this->airline->params['show_general_comment']) && (int)$this->airline->params['show_general_comment'] == 1) {
    $generalComment = $this->airline->getVoucherComment();
    if($generalComment)
    {
        ?>
        <?php echo $generalComment?><br/>
        <?php
    }
}
?>

<?php if($this->detail_voucher->return_flight_number) : ?>
    Your new flight details: Flight number <?php echo $this->detail_voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/><br/>
<?php endif;?>

<?php
if( $this->detail_voucher->comment ) {
    echo $this->voucher->comment;
}
?>

<?php endif;
if( $this->detail_voucher->flight_code == '' ){
	$this->detail_voucher->flight_code = (isset($_GET['flight_number'])) ? $_GET['flight_number'] : '';
}
?>

<div style="background-color:#FFDE73; border:solid 10px #FFB700; padding:10px; margin-top:15px;font-family: 'lucida grande', tahoma, verdana, arial, sans-serif; ">
    <p style="font-weight: bold;margin-top:5px;">
        Hotel billing information:<br />
        <?php echo 'Flight number: '.$this->detail_voucher->flight_code?><br />
        <?php echo 'Block code: '.$this->detail_voucher->blockcode ?><br />
        <?php echo 'Voucher code: '.$this->detail_voucher->code?><br />

        <?php //echo 'Voucher '.$this->voucher_groups->seats.' person'
        echo 'Voucher '.$number_passenger.' person'?><br />
        <?php if($this->detail_voucher->return_flight_number) : ?>
            Old flight info: Flight number <?php echo JString::strtoupper($this->detail_voucher->flight_code);?> departure date <?php echo JHTML::_('date', $this->detail_voucher->date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
            New flight info: Flight number <?php echo $this->detail_voucher->return_flight_number?> departure date <?php echo JHTML::_('date', $this->detail_voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?><br/>
        <?php endif;?>
    </p>

    <p style="font-size:90%;">
        <?php if( $this->detail_voucher->get('payment_type','airline') != 'passenger') : ?>
            <?php if( isset($this->airline->params['voucher_vat_comment_line']) && strlen(trim($this->airline->params['voucher_vat_comment_line'])) > 0 ) : ?>
                <?php echo $this->airline->params['voucher_vat_comment_line'];?>
            <?php else : ?>
                *Bestelbon - vrijstelling van de BTW art 42 para 2 alinea 5 W.BTW
            <?php endif;?>
            <br/>
        <?php endif?>

        <?php if( $this->detail_voucher->get('payment_type','airline') != 'passenger') : ?>
            <span><?php echo 'Booking guaranteed by '.$this->airline->getAirlineName().' as agreed with your hotel on SFS-web.com'?></span>
        <?php endif?>
    </p>
</div>


<?php foreach( $this->card_airplusws as $card_airplusws) :?>
    <div style="page-break-after:always;">
        <?php if(!empty($card_airplusws['info_of_card_ap_taxi'])) : ?>
            <?php foreach($card_airplusws['info_of_card_ap_taxi'] as $info_of_card) : ?>
                <?php $_GET['info_of_card_ap_taxi'] = json_encode($info_of_card);?>
                <?php include 'default_single_ws_cardtransfer.php';?>
            <?php endforeach;?>
        <?php endif; ?>
    </div>
    <div style="page-break-after:always;">
        <?php if(!empty($card_airplusws['info_of_card_ap_meal'])) : ?>
            <?php foreach($card_airplusws['info_of_card_ap_meal'] as $info_of_card) : ?>
                <?php $_GET['info_of_card_ap_meal'] = json_encode($info_of_card);?>
                <?php include 'default_single_ws_cardmealplan.php';?>
            <?php endforeach;?>
        <?php endif; ?>
    </div> 
<?php endforeach;?>
<p align="center" style="color:#666;display: none;">Share your experience on www.strandedexperience.com</p> 
    </div>
</div>

