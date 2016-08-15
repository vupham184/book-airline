<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="http://www.sfs-web.com/<?php echo $systemAirport;?>/"> [^]

<title>SFS TAXI VOUCHER CANCELLED</title>
</head> 
<body>
<div style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">{date}</div>

<table cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 620px">
	<tbody>
		<tr>
			<td style="font-size: 16px; font-family: 'lucida grande', tahoma, verdana, arial, sans-serif; text-align: center; font-weight: bold; vertical-align: middle; letter-spacing: -0.03em; padding: 10px 38px 4px">
				SFS TAXI VOUCHER CANCELLED<br /> <?php echo $taxiVoucher->block_date;?>
			</td>
		</tr>
		<tr>
			<td style="font-size: 12px; font-family: 'lucida grande', tahoma, verdana, arial, sans-serif">
				Dear <?php echo $taxiVoucher->taxi_name;?>, <br /> <br /> Kindly
				note that <?php echo $airline->getAirlineName();?> cancelled <?php echo $taxiVoucher->taxi_name;?>
				with voucher number: <strong><?php echo $taxiVoucher->taxi_voucher_code;?>
			</strong> <br /> <br /> <?php echo count($taxiPassengers);?>
				passenger(s) <br /> <br /> Names:<br /> <?php echo implode('<br />', $taxiPassengers);?>
				<br /> <br /> Pickup: <?php echo $airline->getAirportName();?> <?php if($hotel):?>
				<br /> Drop Off: <?php echo $hotel->name.', '.$hotel->address.', '.$hotel->zipcode.', '.$hotel->country_name;?>
				<?php endif;?> <br /> <br /> Please recall your driver as the
				service for these clients is no longer needed. <br /> <br /> And
				thus this voucher will not be accepted by the Airline during
				invoicing. We apologize for the inconvenience caused. <br /> <br />

				<p>Best regards,</p>
				<p>
					Stranded Flight Solutions<br /> <img
						src="<?php echo JURI::base().'images/logo.jpg'?>" /><br />
					Telephone: +31 35 678 1255<br /> Email: info@sfs-web.com
				</p>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>
