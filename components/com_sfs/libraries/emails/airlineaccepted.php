<?php defined('_JEXEC') or die;?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
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
            	<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
                Dear <?php echo $hotel_contact_name;?>,
                <br /><br />
                Kindly note the change of status for the below room block.
                <br /><br />
                <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
                	<tbody>
                		<tr>
                			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong>Airline:</strong></td>
                			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong><?php echo $airline_name;?></strong></td>
                		</tr>
                		<tr>
                			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong>Block code:</strong></td>
                			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong><?php echo $blockcode;?></strong></td>
                		</tr>
                		<tr>
                			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong>Arrival date:</strong></td>
                			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong><?php echo $arrival_date?></strong></td>
                		</tr>
                		<tr>
                			<td style="padding-right:25px;font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong>Current Status:</strong></td>
                			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif"><strong><?php echo $current_status?></strong></td>
                		</tr>
                	</tbody>
                </table>
                <br /><br />
				The follow action is required from your side based on the indicated status above.<br /> <br />
				<strong>Challenged:</strong><br />
				Login to SFS-web.com to reply and modify room block when needed. <br />
				You can access this block with selecting the "Block Overview" on the top taskbar.<br /> <br />
				The above block will be listed under "Challenged" in the "Quick Selection Roomblocks"
				Scroll down to open the block to see the message that you received. <br /> <br />
				You can reply and modify the room block and press "Send" to share this with the airline booker.
				<br />
				<br />
				<strong>Accepted:</strong><br />
				The charges are expected by the airline booker and you can send the official invoice. Login to SFS-web.com as the airline booker might have added a note with more details that need to be mentioned on the invoice for an efficient payment.
				<br />
				<br />
				You can access this block with selecting the "Block Overview" on the top taskbar. The above block will be listed under "Approved" in the "Quick Selection Roomblocks" Scroll down to open the block to see if you received a message for this invoice, often airlines require Purchase Numbers to enhance the payments.
				<br />
				<br />
				You can now archive this block to make sure that your overview remains clear. Archived room blocks remain available on SFS-web.com for at least one year. The room blocks in SFS-web.com can never be considered as official invoices and the airline booker expects an official invoice to make the final payment.
				<br />
				<br />

				The below persons have accesses to www.SFS-web.com  in your organization:<br />
				<?php echo $hotel_contacts_str;?>
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
