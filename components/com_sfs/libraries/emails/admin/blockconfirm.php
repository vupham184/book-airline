<?php
// No direct access
//defined('_JEXEC') or die;

$total_sum = 0;

if ($data_ws)
{
    $total_sum += (int) $data_ws->NumberOfRooms * (int) $data_ws->Total;
}else{ //not ws
    if ($data->sd_room){
        $total_sum += (int) $data->sd_room * floatval($data->sd_rate) ;
    }
    if ($data->t_room){
        $total_sum += (int) $data->t_room * floatval($data->t_rate) ;
    }
    if ($data->q_room){
        $total_sum += (int) $data->q_room * floatval($data->q_rate) ;
    }
    if ($data->s_room){
        $total_sum += (int) $data->s_room * floatval($data->s_rate) ;
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
	                Hi SFS Administrator,
	                <br /><br />
	                A new roomblock with blockcode <?php echo $blockcode?> created on SFS by <?php echo $airline->name;?> user &lt;<?php echo $booked_name;?>&gt; you can contact them through &lt;<?php echo $booked_contact->telephone;?>&gt;
	                <br /><br />
	                Hotel details
	                <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
	                	<tbody>
	                		<tr>
	                			<td style="padding-right:15px;">Name</td><td><?php echo $hotel->name;?></td>
	                		</tr>
	                		<tr>
	                			<td style="padding-right:15px;">Address</td><td><?php echo $hotel->address;?>, <?php echo $hotel->city;?></td>
	                		</tr>
	                		<tr>
	                			<td style="padding-right:15px;">Country</td><td><?php echo $hotel->country_name?></td>
	                		</tr>    
	                		<tr>
	                			<td style="padding-right:15px;">Telephone</td><td><?php echo $hotel->telephone?></td>
	                		</tr>
                            <tr>
                                <td style="padding-right:15px;">Rates of the hotel</td><td><?php echo '€'.$total_sum?></td>
                            </tr>
                            <tr>
                                <td style="padding-right:15px;">WS or Partner</td><td><?php echo $is_ws?></td>
                            </tr>
	                	</tbody>
	                </table>
	                
	                <br /><br />
	                Rooms Details
	                 <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
	                	<tbody>
	                		<?php if((int)$data->s_room>0):?>
	                		<tr>
	                			<td><?php echo $data->s_room?> Single price: <?php echo $data->s_rate?></td>
	                		</tr>
	                		<?php endif;?>
	                		<?php if($data->sd_room):?>
	                		<tr>
	                			<td><?php echo $data->sd_room?> Single/Double price: <?php echo $data->sd_rate?></td>
	                		</tr>
	                		<?php endif;?>
	                		<?php if($data->t_room):?>
	                		<tr>
	                			<td><?php echo $data->t_room?> Triple price: <?php echo $data->t_rate?></td>
	                		</tr>
	                		<?php endif;?>
	                		<?php if((int)$data->q_room>0):?>
	                		<tr>
	                			<td><?php echo $data->q_room?> Quad price: <?php echo $data->q_rate?></td>
	                		</tr>
	                		<?php endif;?>
	                		<tr>
	                			<td>Total initial blocked rooms: <?php echo $room_number?></td>
	                		</tr>
	                		               		
	                	</tbody>
	                </table>
	                
	                <br /><br />
	                <?php
	                $eRoomCharge  = (int)$data->sd_room * floatval($data->sd_rate) + (int)$data->t_room * floatval($data->t_rate);
	                $eRoomCharge += (int)$data->s_room * floatval($data->s_rate) + (int)$data->q_room * floatval($data->q_rate);
	                 
	                $eMealplan = 0;
	                if($data->breakfast){
	                	$eMealplan += $data->breakfast * $room_number;
	                }
	                if($data->lunch){
	                	$eMealplan += $data->lunch * $room_number;
	                }
	                if($data->mealplan){
	                	$eMealplan += $data->mealplan * $room_number;
	                }
	                $totalCharge = $eMealplan + $eRoomCharge;
	                ?>
	                Estimated Charges<br />
					Estimated room charge <?php echo $eRoomCharge;?><br />
					<?php if($eMealplan):?>
					Estimated mealplan charge <?php echo $eMealplan?><br />
					<?php endif;?>
					Estimated invoice charge <?php echo $totalCharge;?><br />
					Currency <?php echo $currency;?>
	                <br /><br />
	                
					For more details click on the following url: <?php echo $adminBlockcodeUrl;?>
					
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
