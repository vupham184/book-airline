<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFS-web Reservation Details</title>
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
                    SFS-web Reservation Details
              </td>
            </tr>
            <tr>
            	<td>                                
                A new reservation made by <?php echo $user->name;?><br /><br />
				Please find below the details of this booking:<br /><br />
                </td>
            </tr>
            </tbody>
        </table>

        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
            <tr>
              <td style="padding:10px;background-color:#daeef3;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;border-bottom:1px solid #ccc;line-height:16px">
			      <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>COMPANY</td><td><?php echo $hotel_name;?></td>
                        </tr>
                        <tr>
                            <td>NAME</td><td><?php echo $hotel_contact_name;?></td>
                        </tr>
                        <tr>
                            <td>TITLE</td><td><?php echo $hotel_contact_title;?></td>
                        </tr>
                        <tr style="vertical-align: top;">
                            <td>CONTACT DETAILS</td>
                            <td><?php echo $hotel_contact_telephone.' - '.$hotel_contact_email;?><br /></td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td>Room Details:</td><td></td>
                        </tr>
                        <tr>
                            <td><strong>ARRIVAL DATE:</strong></td><td><strong><?php echo $arrival_date;?></strong></td>
                        </tr>
                        <tr>
                            <td style="padding-right: 15px;">Nr of single/double rooms:</td><td><?php echo $sdroom_number?></td>
                        </tr>
                        <tr>
                            <td>Gross price</td><td><?php echo $sdroom_rate?></td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td>Nr of Triple rooms:</td><td><?php echo $troom_number?></td>
                        </tr>
                        <tr>
                            <td>Gross price</td><td><?php echo $troom_rate?></td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td>F&amp;B Details:</td><td></td>
                        </tr>
                        <tr>
                            <td>Gross price Breakfast</td><td><?php echo (int)$breakfast > 0 ? $breakfast_price.' per person' : 'N/A'?> </td>
                        </tr>
                        <tr>
                            <td>Gross price Lunch</td><td><?php echo (int)$lunch > 0 ? $lunch_price.' per person' : 'N/A'?> </td>
                        </tr>
                        <tr>
                        	<?php
                        	if ( (int)$mealplan > 0  ) :
                        	?>
                          		 <td>Gross price Dinner <?php echo $course_menu;?></td><td><?php echo $dinner_price?> per person</td>
                            <?php else : ?>
                            	<td>Gross price Dinner</td><td><?php echo 'N/A';?></td>
                            <?php endif;?>
                        </tr>                         
                    </table>
              </td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
        	<tr>
            <td style="padding-top:15px;">			
				<p>Best regards,</p>
				<p>Stranded Flight Solutions<br />
				<img src="<?php echo JURI::base().'images/logo.jpg'?>" /><br />
				Telephone: +31 35 678 1255<br />
				Email: airline_support@sfs-web.com<br />
				</p>

            </td>
            </tr>
        </table>


      </td>
    </tr>
  </tbody>
</table>

</body>
</html>