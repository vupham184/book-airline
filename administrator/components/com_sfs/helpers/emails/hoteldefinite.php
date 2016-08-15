<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rooms Sold on Stranded Flight Solutions</title>
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
                Kindly note the change of status for the below room block.
                <br /><br />
                <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
                	<tbody>
                		<tr>
                			<td><strong>Hotel Name:</strong></td><td><strong><?php echo $hotel->name;?></strong></td>
                		</tr>
                		<tr>
                			<td><strong>Block code:</strong></td><td><strong><?php echo $reservation->blockcode;?></strong></td>
                		</tr>
                		<tr>
                			<td><strong>Arrival date:</strong></td><td><strong><?php echo JFactory::getDate($reservation->date)->format(JText::_('DATE_FORMAT_LC3'));?></strong></td>
                		</tr>
                		<tr>
                			<td style="padding-right:25px;"><strong>Current Status:</strong></td><td><strong><?php echo $status_array[$blockstatus]?></strong></td>
                		</tr>
                	</tbody>
                </table>
                <br /><br />
				The follow action is required from your side based on the indicated status above.<br /> <br />

				<strong>Definite:</strong><br />
				The total charges and name list are loaded in SFS-web.com. The hotel is waiting for your approval to send the official invoice.
				<br /><br />
				Login to SFS-web.com and accept or challenge this room block charge, you can add more information that you would like to have on the invoice in the message box, so the invoice will be created according to your accounting needs for the efficient payment.
				<br /><br />
				You can now archive this block to make sure that your overview remains clear. Archived room blocks remain available on SFS-web.com for at least one year. The room blocks in SFS-web.com can never be considered as official invoices and the hotel will send an official invoice so your organization can make the payment.
				<br />
				<br />
				The below persons have access to www.SFS-web.com  in your organization:<br />
				<?php echo $airline_contacts_str;?>
				<br />
				<br />
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
