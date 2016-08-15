<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFS-web SHORT TERM ROOMBLOCK Reservation</title>

<style type="text/css">
span{float:left; width:170px;}
</style>

</head>
<body>
<table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;width:98%">
  <tbody>
    <tr>
      <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">

        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
            <tbody>
            <tr>
              <td style="font-size:16px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; text-align:center;font-weight:bold;vertical-align:middle;letter-spacing:-0.03em;padding:10px 38px 4px">
                    VOUCHERS ISSUED FOR ROOMBLOCK<br /><?php echo $blockcode;?><br /><?php echo $created_date;?>
              </td>
            </tr>
            <tr><?php $arrF = array("passenger(s)", "passenger"); $arrR = array("", "");?>
            	<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
                    Dear {hotelcontact},
                    <br /><br />
                    Please find below the information.
                    <br /><br />
                    <span>Hotelname </span> : <?php echo $hotel_name;?><br />
                    <span>Number of room(s)</span> : <?php echo $totalRooms;?><br />
                    <span>Number of passenger(s)</span> : <?php echo count($passengers); ?> <br /><br />

                    The passenger(s) are according to their voucher entitled to receive:

                    <?php if( (int)$course_type || (int)$breakfast ):?>
                        The <?php echo $totalSeats;// $seats;?> are according to their voucher entitled to receive:<br /><br />
                        <?php if((int)$breakfast):?>
                            -<?php echo str_replace($arrF, $arrR, $totalSeats );// $seats;?> Breakfast <br />
                        <?php endif;?>
                        <?php if((int)$course_type):?>
                            -<?php echo str_replace($arrF, $arrR, $totalSeats );// $seats;?> course <?php echo $course_type?><br />
                        <?php endif;?>
                        <?php if((int)$lunch):?>
                            -<?php echo str_replace($arrF, $arrR, $totalSeats );// $seats;?> Lunch<br />
                        <?php endif;?>
                    <?php endif;?>
                    <br />
                    
                    Passenger names: <?php echo $nameText?> <br /><br />
                    Note(s) from the airline: <?php echo $comment?> <br /><br />
                    The voucher number(s) and names need to be loaded on SFS-web.com for verification and approval process before issuing the official hotel invoice. The voucher number is printed on the voucher and will be handed over to the reception. The meal plan details are preloaded in the SFS-web system.<br /><br />
                    
                    <strong>
                    The below persons have accesses to this system in your organization:<br />
                    <?php echo $hotel_contacts_str;?>
                    </strong>
                    <br /><br />
                    When none of the above persons are available you can contact the SFS-web hotel support.
                    <br /><br />
                    <p>Best regards,</p>
                    <p>Stranded Flight Solutions<br />
                    <img src="<?php echo JURI::base().'images/logo.jpg'?>" /><br />
                    Telephone: +31 35 678 1255<br />
                    Email: hotel_support@sfs-web.com<br />
                    </p>
                </td>
            </tr>
            </tbody>
        </table>

      </td>
    </tr>
  </tbody>
</table>

</body>
</html>
