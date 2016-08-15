<?php
// No direct access
defined('_JEXEC') or die;
$params = JComponentHelper::getParams('com_sfs');
$systemAirport = $params->get('sfs_system_suffix');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="http://www.sfs-web.com/<?php echo $systemAirport;?>/"> [^]

<title>SFS-web TAXI TRANSPORTATION Reservation</title>
</head> 
<body>
<div style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">{date}</div>
<table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;width:98%">
  <tbody>
    <tr>
      <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
      
        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
            <tbody>
            <tr>
              <td style="font-size:16px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; text-align:center;font-weight:bold;vertical-align:middle;letter-spacing:-0.03em;padding:10px 38px 4px">
                    SFS-web<br />TAXI TRANSPORTATION Reservation
              </td>
            </tr>
            <tr>
            	<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
                For the direct attention of : <?php echo $taxiReservation->taxi_name?><br />
                We here with inform that <?php echo count($taxiPassengers)?> passengers need transportation<br />
                <strong>reference number: <?php echo $taxiReservation->reference_number?></strong>
                <br /><br />                
				Client Details:<br /><br />                                                
                </td>
            </tr>
            </tbody>
        </table>
        
        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
            <tr>
              <td style="padding:10px;background-color:#daeef3;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;border-bottom:1px solid #ccc;line-height:16px">
			      <table border="0" cellpadding="0" cellspacing="0" style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
                        <tr>
                            <td>COMPANY</td><td><?php echo $airline_name;?></td>
                        </tr>
                        <tr>
                            <td>NAME</td><td><?php echo $airline_contact_name;?></td>
                        </tr>
                        <tr>
                            <td>TITLE</td><td><?php echo $airline_contact_title;?></td>
                        </tr>
                        <tr style="vertical-align: top;">
                            <td>CONTACT DETAILS</td>
                            <td><?php echo $airline_contact_telephone.' - '.$airline_contact_email;?><br />PLEASE CONTACT AIRLINE PER TELEPHONE IF YOU HAVE ANY  QUESTIONS OR CONCERNS</td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td nowrap="nowrap">Transportation Details:</td><td></td>
                        </tr>
                        <tr>
                            <td>ARRIVAL DATE:</td><td><?php echo $taxiReservation->block_date;?></td>
                        </tr>
                         <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td>Time on Terminal:</td><td><?php echo $taxiReservation->arrival_time;?></td>
                        </tr>
                        <tr>
                            <td>Nr of passengers:</td><td><?php echo count($taxiPassengers);?></td>
                        </tr>
                        <tr>
                            <td>Names of passengers:</td><td><?php echo count($taxiPassengers)? implode(', ', $taxiPassengers) : '';?></td>
                        </tr>  
                        <?php if($passengerMobile):
                        	if(is_array($passengerMobile))
                        	{
                        		$passengerMobile = implode(', ', $passengerMobile);
                        	}
                        ?>
                        <tr>
                            <td nowrap="nowrap" style="padding-right: 10px;">Phone number of passengers:</td><td><?php echo $passengerMobile;?></td>
                        </tr>    
                        <?php endif;?>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td nowrap="nowrap">Pick up location comments:</td><td><?php echo $taxiReservation->comment;?></td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td nowrap="nowrap">Drop of location comments:</td><td><?php echo $taxiReservation->return_comment;?></td>
                        </tr>                                                    
                    </table>                			
              </td>
            </tr>         
        </table>     
        
        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
        	<tr>
            <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; padding-top:5px;">
            
				Please make sure that you book prepare your staff for this pick up, and use reference code: <strong><?php echo $taxiReservation->reference_number?></strong> in all correspondence on this booking.
				<br />
				<br />
				
				Billing details:
				<br />
				<br />
				Please send bill containing booking reference number to:<br />
				<?php				
				echo $airline->name.'<br />';
				echo $billingDetail->address.'<br />';
				echo $billingDetail->zipcode.', '.$billingDetail->city.'<br />';
				echo $billingDetail->country_name;				
				?>
				<br />
				<br />
				<p>Best regards,</p>
				<p>Stranded Flight Solutions<br />
				<img src="<?php echo JURI::base();?>components/com_sfs/assets/images/logo/logo.jpg" /><br />
				Telephone: +31 35 678 1255<br />
				Email: hotel_support@sfs-web.com<br />
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
