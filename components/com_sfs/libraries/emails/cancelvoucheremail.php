<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFS-web voucher cancelled</title>
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
                    SFS ROOMBLOCK<br /><?php echo $blockcode;?><br />VOUCHER CANCELLED<br /><?php echo $booked_date;?>
              </td>
            </tr>
            <tr>
            	<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
                Dear <?php echo $contact_name;?>,
                <br /><br />
                Kindly note that the SFS-web cancelled one voucher for block code <?php echo $blockcode;?> coming from <?php echo $airline_name;?>
                <br /><br />
				Voucher number:
				<br />
				<?php echo $cancel_date;?> <strong><?php echo $voucher_code;?></strong>
				<br /><br />

				<?php echo $seats;?> passenger(s)
				<br />
				<?php if((int)$breakfast):?>
					- <?php echo $seats;?> Breakfast <br />
				<?php endif;?>
				<?php if((int)$course_type):?>
					- <?php echo $seats;?> course <?php echo $course_type?><br /><br />
				<?php endif;?>

				This voucher will not be accepted by the SFS-web system. The total number of rooms and conditions for block code <?php echo $blockcode;?> remains unchanged.
				<br /><br />
				<strong>
				The below persons have accesses to this system in your organization:<br />
				<?php echo $hotel_contacts_str;?>
				</strong>
				<br />
				<br />
				When none of the above persons are available you can contact the SFS-web hotel support.
				<br />
				<br />
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
