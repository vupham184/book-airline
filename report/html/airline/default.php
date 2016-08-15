<?php
defined('_JEXEC') or die;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Airline report for: <?php echo $airline->name;?> - <?php echo $airline->code;?></title>
<style type="text/css">
	body{ font-family: "Lucida Grande", Arial, Helvetica, sans-serif; }
	h2{ font-size:22px; font-weight:normal;}
	table {
		border-collapse: separate;display: table;border-spacing: 0;border-style: none;border-width: 1px 1px 1px 0;border-color: #CCC #CCC #CCC -moz-use-text-color;
	}
	table tr.table-header td{font-size:12px; background:#F0F0F0;}
	table td{font-size:12px;}
	table th, table td {
	    border-left: 1px solid #CCC;border-top: 1px solid #CCC;line-height: 20px;padding: 5px 8px;text-align: left;vertical-align: top;
	}
	table th:last-child, table td:last-child{border-right: 1px solid #CCC}
	table tr:last-child td{border-bottom: 1px solid #CCC}
	.col1{width:200px}
	.col2{width:30px}
	.col3{width:150px}	
	table td{ width:75px;}	
	.noleftborder{ border-left: none !important}	
	
	
	#shadow {
		-webkit-box-shadow: 10px 18px 44px -24px rgba(0,0,0,0.75);
		-moz-box-shadow: 10px 18px 44px -24px rgba(0,0,0,0.75);
		box-shadow: 10px 18px 44px -24px rgba(0,0,0,0.75);
		border-radius:6px;
		padding:5px 20px;
		background-color:#5A6AF5;
		text-decoration:none;
		color:#fff;
		border:0px;
		cursor:pointer;
	}
</style>
</head>

<body>

<h2>Airline report for: <?php echo $airline->name;?> - <?php echo $airline->code;?></h2>

<?php if(count($data)):?>

<div style="margin-bottom:10px;">
	<form action="">
    <input type="hidden" name="exportexcel" value="1">
    <?php if( isset( $_GET['start_period'] ) ) : ?>
    <input type="hidden" name="start_period" value="<?php echo ($_GET['start_period']); ?>">
    <?php endif;?>
    
    <?php if( isset( $_GET['end_period'] ) ) : ?>
    <input type="hidden" name="end_period" value="<?php echo ($_GET['end_period']); ?>">
    <?php endif;?>
    
    <?php if( isset( $_GET['period'] ) ) : ?>
    <input type="hidden" name="period" value="<?php echo ($_GET['period']); ?>">
    <?php endif;?>
    
    <?php if( isset( $_GET['uk'] ) ) : ?>
    <input type="hidden" name="uk" value="<?php echo ($_GET['uk']); ?>">
    <?php endif;?>
    
    <input id="shadow" type="submit" value="Export to Excel" />
    </form>
</div>

<table>
	
    <tbody>
    	
		<!-- SD RATE -->
        <tr class="table-header">
        	<th class="col1" nowrap="nowrap">Blockcode</th>
            <th class="col2" nowrap="nowrap">Date</th>
            <th class="col3" nowrap="nowrap">Airport</th>
            <th class="col3" nowrap="nowrap">Hotelname</th>
            <th class="col2" nowrap="nowrap">Status</th>
            <th class="col2" nowrap="nowrap">Flight number</th>
            <th class="col2" nowrap="nowrap"># Rooms</th>
            <th class="col2" nowrap="nowrap">Gross Price</th>
            <th class="col2" nowrap="nowrap"># pax BFST</th>            
            <th class="col2" nowrap="nowrap">Gross Price BFST</th>
            <th class="col2" nowrap="nowrap"># pax Lunch</th>
            <th class="col2" nowrap="nowrap">Gross Price Lunch</th>
            <th class="col2" nowrap="nowrap"># pax Dinner</th>
            <th class="col2" nowrap="nowrap">Gross Price Dinner</th>
            <th class="col3" nowrap="nowrap">Grand Total Amount</th>
            
        </tr>
        <?php 
		$airport_code = $airline->code;
		foreach ($data as $v) : 
			/*$total = floatval( $v->gross_price ) +
			$v->people_num * floatval( $v->gross_price_bfst ) + 
			$v->people_num * floatval( $v->gross_price_lunch ) + 
			$v->people_num * floatval( $v->gross_price_dinner );*/
			
			$rooms = 0;
				///$rooms = $v->s_room+$v->sd_room+$v->t_room+$v->q_room;
				
			$gross_price = 0;
			if ( $v->s_room > 0 ) {
				$gross_price = $v->s_rate;
				$rooms = $v->s_room;
			}
			if ( $v->sd_room > 0 ) {
				$gross_price = $v->sd_rate;
				$rooms = $v->sd_room;
			}
			if ( $v->t_room > 0 ) {
				$gross_price = $v->t_rate;
				$rooms = $v->t_room;
			}
			if ( $v->q_room > 0 ) {
				$gross_price = $v->q_rate;
				$rooms = $v->q_room;
			}
			if ( $v->ws_room == 1 && $rooms == 0){
				$rooms = 1;
				$gross_price = $v->s_rate;
			}
			
			
			$total = floatval( $gross_price );
			if ($v->breakfast > 0 ) {
				$total += $v->people_num * floatval( $v->breakfast );
			}
			if ($v->lunch > 0 ) {
				$total += $v->people_num * floatval( $v->lunch );
			}
			if ($v->mealplan > 0 ) {
				$total += $v->people_num * floatval( $v->mealplan );
			}
		?>
        <tr>
        	<td class="col1" nowrap="nowrap"><?php echo $v->blockcode;?></td>
            <td class="col2" nowrap="nowrap"><?php echo $v->date;?></td>
            <td class="col3" nowrap="nowrap"><?php echo $v->airport_code;?></td>
            <td class="col3" nowrap="nowrap"><?php echo $v->hotel_name;?></td>
            <td class="col2" nowrap="nowrap"><?php echo $v->status;?></td>
            <td class="col2" nowrap="nowrap"><?php echo $v->flight_number;?></td>
            <td class="col2" nowrap="nowrap"><?php echo $rooms;?></td>
            <td class="col2" nowrap="nowrap"><?php echo numberformat ( $gross_price );?></td>
            <td class="col2" nowrap="nowrap"><?php echo ($v->breakfast > 0 ) ? $v->people_num : "0";?></td>
            <td class="col2" nowrap="nowrap"><?php echo numberformat( ($v->breakfast > 0 ) ? $v->people_num * $v->breakfast : "0.0");?></td>
            <td class="col2" nowrap="nowrap"><?php echo ($v->lunch > 0 ) ? $v->people_num : "0";?></td>
            <td class="col2" nowrap="nowrap"><?php echo numberformat( ($v->lunch > 0 ) ? $v->people_num * $v->lunch : "0.0");?></td>
            <td class="col2" nowrap="nowrap"><?php echo ( $v->mealplan > 0 ) ? $v->people_num : "0";?></td>
            <td class="col2" nowrap="nowrap"><?php echo numberformat(( $v->mealplan > 0 ) ? $v->people_num * $v->mealplan : "0.0");?></td>
            <td class="col3" nowrap="nowrap"><?php echo numberformat($total);?></td>
        </tr>        	      
        <?php endforeach;?>
       

    </tbody>
    
</table>

<?php endif;?>

</body>
</html>