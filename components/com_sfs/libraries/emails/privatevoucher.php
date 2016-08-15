<?php
// No direct access
//defined('_JEXEC') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VOUCHER CREATED</title>
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
                    SFS ROOMBLOCK<br /><?php echo $blockcode;?><br />VOUCHER CREATED<br /><?php echo $created_date;?>
              </td>
            </tr>
            <tr>
            	<td>
                	A new Voucher <?php echo $code; ?> has been created by <?php echo $user->name?>
                	<br /><br />
					The <?php echo $seats;?> passenger(s) left the airport and are coming to <?php echo $hotel_name;?>.<br /><br />
					<?php if( (int)$course_type || (int)$breakfast ):?>
						The <?php echo $seats;?> passenger(s) are according to their voucher entitled to receive:<br /><br />
						<?php if((int)$breakfast):?>
							- <?php echo $seats;?> Breakfast <br />
						<?php endif;?>
						<?php if((int)$course_type):?>
							- <?php echo $seats;?> course <?php echo $course_type?><br />
						<?php endif;?>
						<?php if((int)$lunch):?>
						- <?php echo $seats;?> Lunch<br />
						<?php endif;?>
					<?php endif;?>
					<br />
					<br />
					<p>Best regards,</p>
					<p>Stranded Flight Solutions<br />
					<img src="<?php echo JURI::base().'images/logo.jpg'?>" /><br />
					Telephone: +31 35 678 1255
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
