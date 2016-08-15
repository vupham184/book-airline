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
{logo}
<br /><br />
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
                        On behalf of {} I kindly invite you to upload your available rooms asap in SFS. This way you make your available rooms known to the Airlines & Ground handlers. Obviously you will receive a notification when bookings are made in your hotel (s).
                        <br /><br />
                        To upload rooms just click on the following link:
                        <a href="<?php echo JURI::base();?>index.php?option=com_users&view=login&Itemid=104"><?php echo JURI::base();?>index.php?option=com_users&view=login&Itemid=104</a> 
                        <br /><br />
                        In case you lost your login/password you can request this on the log-in page.
                        <br /><br />
                        <p>Kind regards,</p>
                        <p>
                            {sender_name},<br />
                            {}
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
