<?php
// No direct access
defined('_JEXEC') or die;

$passengers = $voucher->getPassengers();

if (count($passengers)) {
    $rooms = array();
    foreach ($passengers as $passenger) {
        $room = $passenger->voucher_room_id;
        $name = $passenger->first_name . " " . $passenger->last_name;
        $rooms[$room][] = trim($name);
    }
}




$mealplan = $hotel->getMealPlan();
$transport = $hotel->getTransportDetail();
$date = JFactory::getDate($voucher->date);

$dayFrom = (int)$date->format('d');
$dayFromText = SfsHelper::addOrdinalNumberSuffix($dayFrom);

$dateTo = SfsHelperDate::getNextDate('d', $date);

$dateToText = SfsHelper::addOrdinalNumberSuffix((int)$dateTo) . ' of ' . SfsHelperDate::getNextDate('F Y', $date);

$params = JComponentHelper::getParams('com_sfs');
$system_currency = $params->get('sfs_system_currency', 'EUR');

$reservation = SReservation::getInstance($voucher->booking_id);
$wsBooking = $reservation->ws_booking;
/* @var $wsBookingObj Ws_Do_Book_Response */
if (!empty($wsBooking)) {
    $wsBookingObj = Ws_Do_Book_Response::fromString($wsBooking);
}
?>
<?php
    foreach ($rooms as $room):
        $namesText = implode(', ', $room);
?>
    <table cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; width: 98%">
        <tbody>
        <tr>
            <td style="font-size: 12px; font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
                <div style="background-color:#dbe5f1; border:solid 1px #000000; padding:10px;">
                    <strong>YOUR ACCOMMODATION DETAILS:</strong>
                    <br/><br/><?php echo $hotel->name . '<br />' . $hotel->address . '<br />' . $hotel->zipcode . ', ' . $hotel->city;?>
                    <br/><br/>
                    <strong>YOUR VOUCHER DETAILS:</strong><br/><br/>
                    <?php
                    if ($voucher->payment_type == 'passenger') {
                        echo 'NON PREPAID VOUCHER' . '<br/>';
                        echo "Your total estimated charges will be " . $totalAmount . " " . $system_currency . "<br/>";
                    }
                    ?>
                    <?php if (count($passengers)): ?>
                        Voucher issued for: <?php echo $namesText; ?><br/>
                    <?php endif;?>

                    <?php if (@$wsBookingObj) : ?>
                        Booking reference: <?php echo $wsBookingObj->BookingReference ?> <br/>
                    <?php else: ?>
                        Hotel Voucher Code: <?php echo $vouchercode ?><br/>
                    <?php endif;?>

                    Voucher for <?php echo $voucher->seats;?> person(s) entitled for:<br/>
                    - One night accommodation <?php echo 'starting ' . $dayFromText . ' ending ' . $dateToText;?> <br/>
                    <?php
                    if ((int)$voucher->breakfast) {
                        $breakfastText = '';
                        if ((int)$mealplan->bf_service_hour == 1) {
                            $breakfastText = ' available 24 hours';
                        } else {
                            $breakfastText = ' available between ' . str_replace(':', 'h', $mealplan->bf_opentime) . ' and ' . str_replace(':', 'h', $mealplan->bf_closetime);
                        }

                        echo '- Pre arranged breakfast' . $breakfastText . '<br />';
                    }

                    if ((int)$voucher->lunch) {
                        $service_hour = $mealplan->lunch_service_hour;
                        if ((int)$service_hour == 1) {
                            echo '- Pre arranged lunch available 24 hours<br />';
                        } else {
                            $lunchText = ' available between ' . str_replace(':', 'h', $mealplan->lunch_opentime) . ' and ' . str_replace(':', 'h', $mealplan->lunch_closetime);
                            echo "- Pre arranged lunch" . $lunchText . '<br />';
                        }
                    }

                    if ((int)$voucher->mealplan) {
                        $stop_selling_time = $mealplan->stop_selling_time;
                        if ((int)$stop_selling_time == 24) {
                            echo '- Pre arranged dinner available 24 hours';
                        } else {
                            echo '- Pre arranged dinner available until ' . str_replace(':', 'h', $mealplan->stop_selling_time);
                        }
                        echo '<br />';
                    }

                    if (!empty($transport)) {
                        $transportText = 'Transport to accommodation included: ';
                        switch ((int)$transport->transport_available) {
                            case 1:
                                $transportText .= 'Yes';
                                break;
                            case 2:
                                $transportText .= 'Not necessary (walking distance)';
                                break;
                            default :
                                $transportText .= 'No';
                                break;
                        }
                        $transportText .= '<br />';
                        $transportText .= (int)$transport->transport_complementary == 1 ? 'Complimentary: Yes' : 'Complimentary: No';
                        echo '<br />' . $transportText . '<br />';

                        $transportText = '';
                        $transport->operating_hour = (int)$transport->operating_hour;
                        if ($transport->operating_hour == 0) {
                            $transportText .= 'Operation hours: Not available';
                        } else if ($transport->operating_hour == 1) {
                            $transportText .= 'Operation hours: 24 hours';
                        } else if ($transport->operating_hour == 2) {
                            $transportText .= 'Operation hours: From ' . str_replace(':', 'h', $transport->operating_opentime) . ' till ' . str_replace(':', 'h', $transport->operating_closetime);
                        }
                        $transportText .= '   Every: ' . $transport->frequency_service . ' minutes';

                        echo $transportText . '<br />';
                        echo 'Transport details: ' . $transport->pickup_details;
                    } else {
                        echo 'Transport to accommodation included: No';
                    }

                    if ($voucher->return_flight_number) {
                        echo '<br /><br />';
                        echo 'Your new flight details: Flight number <strong>' . $voucher->return_flight_number . '</strong> departure date <strong>' . JHTML::_('date', $voucher->return_flight_date, JText::_('DATE_FORMAT_LC3'), false) . '</strong>';
                    }

                    if ($voucher->comment) {
                        echo '<br /><br />General comments:<br />';
                        echo $voucher->comment;
                    }
                    ?>
                </div>
                <br/>
                <br/>
            </td>
        </tr>

        <tr>
            <td style="font-size: 12px; font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">

                <div style="background-color:#fde9d9; border:solid 1px #000000; padding:10px;">
                    <p align="center"><strong>IMPORTANT----IMPORTANT----IMPORTANT</strong></p>

                    <p align="center">E-VOUCHER HOTEL CHECKIN</p>

                    <p align="center"><strong>The below information needs to be shared with the hotel to validate your
                            e-voucher:</strong></p>
                    <?php if (@$wsBookingObj) : ?>
                        <p align="center">
                            Flight number: <?php echo $voucher->flight_code?> <br/>
                            Booking reference: <?php echo $wsBookingObj->BookingReference ?> <br/>
                            This reservation is booked and payable
                            by <?php echo $wsBookingObj->PropertyBookings[0]->Supplier ?>
                            with reference <?php echo $wsBookingObj->PropertyBookings[0]->SupplierReference ?> under no
                            circumstances should
                            the customer be charged for this booking.
                        </p>
                    <?php else: ?>
                        <p align="center"><strong>Flight number: <?php echo $voucher->flight_code;?></strong></p>
                        <p align="center"><strong>Block code reference: <?php echo $blockCode; ?></strong></p>
                        <p align="center"><strong>Voucher code: <?php echo $vouchercode ?></strong></p>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <br/>
    <br/>
<?php endforeach;?>
<!-- <p align="center">Share your experience on www.strandedexperience.com</p> -->