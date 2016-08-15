<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFS Assign Passenger Import</title>
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
                Dear {name},
                <br /><br />
               
                Airberlin requests you to assign the services to the passengers.
                <br /><br />
                You can access on SFS online by clicking on the below link:<br/>
                <?php echo JURI::root()?>index.php?option=com_users&view=login&Itemid=104
				<br /><br />                 
                Please log in to SFS 360 and go to this page so you can start servicing the passengers.
                <br /><br />                
				<p>With best regards,</p>
				<p>{user}</p>
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
