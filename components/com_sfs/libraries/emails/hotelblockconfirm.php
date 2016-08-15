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

<title>SFS-web SHORT TERM ROOMBLOCK Reservation</title>
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
                    SFS-web<br />SHORT TERM ROOMBLOCK Reservation
              </td>
            </tr>
            <tr>
            	<td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif">
                For the direct attention of : {hotelcontact}<br />
                Hotel: <?php echo $hotel->name;?><br />
                City: <?php echo $hotel->city;?>
                <br />
                We here with inform about <strong><?php echo $room_number?> rooms with block code: <?php echo $blockcode;?></strong><br /><br />
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
                            <td><?php echo $airline_contact_telephone.' - '.$airline_contact_email;?><br />PLEASE CONTACT AIRLINE PER TELEPHONE TO<br/>INTRODUCE HOTEL DUTY MANAGER FOR TODAY</td>
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
                            <td><strong>DEPARTURE DATE:</strong></td><td><strong><?php echo $date_tomorrow;?></strong></td>
                        </tr>
                        <?php if((int)$sroom_number > 0):?>
                        <tr>
                            <td style="padding-right: 15px;">Nr of single rooms:</td><td><?php echo $sroom_number?></td>
                        </tr>      
                        <tr>
                            <td>Gross price</td><td><?php echo $sroom_rate?></td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <?php endif;?>
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
                        <?php if((int)$qroom_number > 0):?>
                        <tr>
                            <td style="padding-right: 15px;">Nr of quad rooms:</td><td><?php echo $qroom_number?></td>
                        </tr>      
                        <tr>
                            <td>Gross price</td><td><?php echo $qroom_rate?></td>
                        </tr>
                        <tr>
                            <td height="15" colspan="2"><br /></td>
                        </tr>
                        <?php endif;?>    
                        <tr>
                            <td>F&amp;B Details:</td><td></td>
                        </tr>    
                        <tr>
                            <td>Gross price Breakfast</td><td><?php echo (int)$breakfast > 0 ? $breakfast_price.' per person': 'N/A'?> </td>
                        </tr>
                        <tr>
                            <td>Gross price Lunch</td><td><?php echo (int)$lunch > 0 ? $lunch_price.' per person': 'N/A'?> </td>
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
            <td style="font-size:12px;font-family:'lucida grande',tahoma,verdana,arial,sans-serif; padding-top:5px;">
            
				Please make sure that you book prepare your staff for this SHORT TERM arrival, and use the block code: <strong><?php echo $blockcode;?></strong> in all correspondence on this booking.
				<br />
				<br />
				
				The guest names and vouchers need be loaded in www.SFS-web.com .The below persons have access to www.SFS-web.com  in your organization:
				<br />
				<?php 
				foreach ($hotel_contacts as $contact):
					echo '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
				endforeach;?>				
				<br />
				SFS-web will send an email for every passenger that receives a voucher for your hotel.
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
