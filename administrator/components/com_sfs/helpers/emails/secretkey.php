<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Key</title>
</head>
<body>
<table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;width:98%">
  <tbody>
    <tr>
      <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">

        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
        
 		<tr>
   		<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;padding:10px;background-color:#fff;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;border-bottom:1px solid #ccc;line-height:16px;">
       		<?php echo $user->name;?> 	                  
	    </td>
        
        <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;padding:10px;background-color:#fff;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;border-bottom:1px solid #ccc;line-height:16px;">
            <?php echo $user->username;?>
        </td>
        
        <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif;padding:10px;background-color:#fff;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;border-bottom:1px solid #ccc;line-height:16px;">
            <?php echo $user->email;?>
        </td>
        
   		</tr>
	    </table>

      </td>
    </tr>
  </tbody>
</table>

</body>
</html>
