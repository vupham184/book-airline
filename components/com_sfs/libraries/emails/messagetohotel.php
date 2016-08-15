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
                Kindly note the message you was sent to you by <?php echo $airline_name;?> for the below room block.
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

                <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
          			<tbody>
            		<tr>
              			<td style="padding:10px 20px;background-color:#fff;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;border-bottom:1px solid #ccc;line-height:16px">
	              			<table cellspacing="0" cellpadding="0" width="100%" style="border-collapse:collapse">
	                  			<tbody>
	                    		<tr>
	                    			<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
	                    			<?php echo $message;?>
	                    			</td>
	                    		</tr>
	                    	</tbody>
	                    	</table>
                    	</td>
                    </tr>
                    </tbody>
                </table>

                <br /><br />
				The follow action is required from your side:<br /> <br />
				Login to SFS-web.com and reply to the message.
				After login choose "block overview" on the top menu bar. Type in the block code in the detailed search section and open the room block to reply to message.
				<br /> <br />
				When you use the reply in SFS-web.com the communication will remain with specific room block. This will allow your organization to better track correspondence relating to the different charges.
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
