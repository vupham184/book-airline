<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFS-web SHORT TERM ROOMBLOCK Reservation</title>
</head>
<body>
<table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;width:98%">
  <tbody>
    <tr>
      <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
      
        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
            <tbody>
            <tr>
            	<td>
	                Dear {contactname},                
	                <br /><br />
	                You have requested a minimum guarantee voucher as <?php echo $airline->airline_name;?> made a block booking of <?php echo ( $reservation->s_room + $reservation->sd_room + $reservation->t_room + $reservation->q_room ) ?> rooms, but you only recieved <?php echo $numberIssuedVoucher;?> vouchers.
					Therefor you are entitled to get compensated for the remaining <?php echo $remainingRooms;?> rooms as you have a free release percentage of <?php echo (int)$reservation->percent_release_policy ;?> %.
					
 					<br /><br />
	                Your minimum guarantee voucher number is: <?php echo $voucher->code;?>
	                <br /><br />
					Please go to the rooming list loading page add the above Vouchercode during the name loading.
					You can add this vouchernumber a maximum of <?php echo $remainingRooms;?> times.
					Please note that the names that correspond with this vouchernumber will have to be First name "No show" Last name:  "No show" for the system to accept this vouchernumber. 
	                <br /><br />
					The below persons have access to this system in your organization:<br />
					<?php echo $hotelContacts;?>
					<br /><br />
					When none of the above persons are available you can contact the SFS-web hotel support.	                	                
					<br />
					<br />
					<p>Best regards,</p>
					<p>Stranded Flight Solutions<br />
					<img src="<?php echo JURI::root().'images/logo.jpg'?>" /><br />
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
