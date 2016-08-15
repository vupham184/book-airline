<?php
$info_of_card = json_decode( $info_of_card_meal ); 
$CardNumber = SfsHelper::getCardNumber($info_of_card->CardNumber);
?>
<table cellpadding="1" style="background-color:#DADADA;">
	<tr style="background-color:#FFFFFF;">
    	<th colspan="5" align="left">CREDITCARD:</th>
    </tr>
    <tr style="background-color:#FFFFFF;">
    	<th align="right">Valid for:</th>
        <td>
        	<?php echo $info_of_card->TypeOfService;?>
        </td>
        <td>
        </td>
        <td>
        </td>
    </tr>
    <tr style="background-color:#FFFFFF;">
    	<th align="right">Card number:</th>
        <td>
        	<?php echo $CardNumber;?>
        </td>
        <td>
        </td>
        <td>
        </td>
    </tr>
    <tr style="background-color:#FFFFFF;">
    	<th align="right">Valid from:</th>
        <td>
        	<?php echo $info_of_card->ValidFrom;?>
        </td>
        <th align="right">Valid thru:</th>
        <td>
        	<?php echo $info_of_card->ValidThru;?>
        </td>
    </tr>
    <tr style="background-color:#FFFFFF;">
    	<th align="right">CVC Code:</th>
        <td>
        	<?php echo $info_of_card->CVC;?>
        </td>
        <th>
        </th>
        <td>
        </td>
    </tr>
    <tr style="background-color:#FFFFFF;">
    	<th align="right">Card name:</th>
        <td>
        	<?php echo $info_of_card->PassengerName;?>
        </td>
        <th>
        </th>
        <td>
        </td>
    </tr>
</table>
<br /><br />