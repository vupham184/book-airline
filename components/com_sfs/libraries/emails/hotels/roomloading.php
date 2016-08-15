<?php
// No direct access
defined('_JEXEC') or die;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFS roomloading</title>
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
                Dear Hotel Partner,
                <br /><br />
               
                SFS contacts you to invite you to upload rooms for the {}.<br/> 
				The reason for this can be expected mass disruption or high occupancy levels on the Brussels hotel market.
                <br /><br />
                You can access on SFS online by clicking on the below link:<br/>
                <?php echo JURI::base();?>index.php?option=com_users&view=login&Itemid=104
				<br /><br /> 
                When you lost your password or login you can request these with entering your email that was used during registration.
                <br /><br />
                For further assistance please use the below contact details. 
				<br />
				<br />
				<p>Kind regards,</p>
				<p>
				Stranded Flight Solutions<br />Hotel team {}<br />
				<img src="<?php echo JURI::base();?>components/com_sfs/assets/images/logo/logo.jpg" /><br />
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
