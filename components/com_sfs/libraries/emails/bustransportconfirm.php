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
<title>SFS-web SHORT TERM BUS TRANSPORTATION Reservation</title>
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
                    SFS-web<br />SHORT TERM BUS TRANSPORTATION Reservation
              </td>
            </tr>
            <tr>
            	<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
                For the direct attention of : <?php echo $reservation->bus_company?><br />               
                We here with inform that a group of <?php echo $reservation->total_passengers;?> passengers need transportation<br />
                <strong>Reference number: <?php echo $reservation->reference_number;?></strong>                                                                
                </td>
            </tr>
            </tbody>
        </table>
        
        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
        <tbody>
            <tr valign="middle">
                <td style="padding:10px 10px 10px 10px;">
                	<a href="<?php echo $acceptLink?>" style="background:#98f881;border: solid 1px #a5f792;display:block;color:#393a39;text-decoration:none; font-size:14px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; padding:4px 8px; width:120px; text-align:center; border-radius:5px;">
                    	Accept Booking
                	</a>                 	
                </td>
                <td style="padding:10px 10px 10px 10px;">
                	<a href="<?php echo $declineLink?>" style=" background:#f5f6f7; border: solid 1px #ccc; display:block; color:#393a39; text-decoration:none; font-size:14px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; padding:4px 8px; width:120px; text-align:center; border-radius:5px;">
                    	Decline Booking
                	</a>                 	
                </td>
            </tr>
        </tbody>
        </table>
        
        
        <p>Client Details:</p>
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
                            <td><?php echo $airline_contact_telephone.' - '.$airline_contact_email;?><br />PLEASE CONTACT AIRLINE PER TELEPHONE IF YOU HAVE ANY QUESTIONS OR CONCERNS
							</td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <tr>
                            <td colspan="2">Transportation Details:</td>
                        </tr>
                        <tr>
                            <td>ARRIVAL DATE:</td><td><?php echo $reservation->arrival_date;?></td>
                        </tr>
                        <tr>
                            <td>Time on Terminal:</td>
                            <td>
                            <?php
                            $requestedTime = 'as soon as possible';
							if( $reservation->requested_time != '0' ) {
								$requestedTime = $reservation->requested_time;
							} 
                            echo '<strong>'.$requestedTime.'</strong>';?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nr of passengers:</td><td><?php echo $reservation->total_passengers;?></td>
                        </tr>
                        
                        <tr>
                            <td>Pick up location:</td><td><?php echo $reservation->pick_up_location;?></td>
                        </tr>
                        <tr>
                            <td>Drop off location:</td><td><?php echo $reservation->drop_off_location;?></td>
                        </tr>
                        <tr>
                            <td>Comments :</td><td><?php echo $reservation->comment;?></td>
                        </tr>
                                                                                  
                    </table>                			
              </td>
            </tr>         
        </table>     
        
        <table cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:620px">
        	<tr>
            <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; padding-top:5px;">
            	<p>
            	Please make sure that you book prepare your staff for this pick up, and use reference code: <br /><strong><?php echo $reservation->reference_number;?></strong> in all correspondence on this booking.				
				</p>
				<p>
				Billing details:
				</p>
				<p>
				Please send bill containing booking reference number to:<br />
				<?php echo $airline_name;?><br />
				<?php echo $airline->address;?><br />
				<?php echo $airline->zipcode.', '.$airline->city;?><br />
				<?php echo $airline->country_name;?><br />
				</p>
				<br />
				<br />	
				<p>Best regards,</p>
				<p>Stranded Flight Solutions<br />
				<img src="<?php echo JURI::base();?>components/com_sfs/assets/images/logo/logo.jpg" /><br />
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
