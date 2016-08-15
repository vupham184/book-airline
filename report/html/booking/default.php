<?php
defined('_JEXEC') or die;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<title>Booking report for: <?php echo $airline->name;//airline_alliance;?> - <?php echo $airline->code;?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
	body{ font-family: "Lucida Grande", Arial, Helvetica, sans-serif; }
	h2{ font-size:22px; font-weight:normal;}
	table {
		border-collapse: separate;display: table;border-spacing: 0;border-style: none;border-width: 1px 1px 1px 0;border-color: #CCC #CCC #CCC -moz-use-text-color;
	}
	table tr.table-header td{font-size:12px; background:#F0F0F0;}
	table td{font-size:12px;}
	table th, table td {
	    border-left: 1px solid #CCC;border-top: 1px solid #CCC;line-height: 20px;padding: 5px 8px;text-align: center;vertical-align: middle;
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

<h2>Booking report for: <?php echo $airline->name;//airline_alliance;?> - <?php echo $airline->code;?></h2>

<?php if(count($reservations)):?>

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

<table border=1>

    <tr style="background-color:gray">
        <th>Date</th>
        <th>WS or Partner</th>
        <th>Airportcode</th>
        <th>Airline name</th>
        <th>Airline code</th>
        <th>Date and time of booking</th>
        <th>Hotel name</th>
        <th>Type of room</th>
        <th>F&B</th>
        <th>Price per room</th>
        <th>Number of rooms</th>
        <th>How many persons</th>
        <th>Total sum price of the booked rooms</th>
        <th>Total sum price of the booked F&B</th>
    </tr>

    <?php
    $temp_date = '';
    foreach ($reservations as $value) : ?>
        <?php
            $str = unserialize($value->ws_room_type);
            if ($temp_date != $value->blockdate)
            {
                $temp_date = $value->blockdate;
                ?>
<!--                <tr style="background-color: black;color: white;">-->
<!--                    <td colspan="11">--><?php //echo $temp_date;?><!--</td>-->
<!--                </tr>-->
                <?php
            }

            $newDate = date("m-d-Y", strtotime($value->blockdate));

            $is_ws = 'WS';
            if ($value->ws_room_type == '')
            {
                $is_ws = 'Partner';
            }

        $value->booked_date = date("m-d-Y h:s", strtotime($value->booked_date));
        ?>
    <tr>
        <td style="text-align: center"> <?php echo $newDate;?></td>
        <td style="text-align: center"> <?php echo $is_ws;?></td>
        <td style="text-align: center"> <?php echo $value->airport_code;?></td>
        <td style="text-align: center"> <?php echo $value->airline_name;?></td>
        <td style="text-align: center"> <?php echo $value->airline_code;?></td>
        <td style="text-align: center"> <?php echo $value->booked_date;?></td>
        <td style="text-align: center"> <?php echo $value->hotel_name;?></td>
        <td style="text-align: center">
        <?php

        $person = 0;
        $total_sum = 0;
        $number_room = 0;
        $mealplan = 0;
        $per_room = array();
        $FB = array();

        if (is_array($str))
        {
        ?>
            <?php foreach ($str as $roomType)
            {
                $var = $roomType['roomType'];
                $var = unserialize(base64_decode($var));
                $array = (array) $var;//print '<pre>';print_r($array);exit;
                $name = $array[Name];
                $FB[] =  $array[MealBasisName];
                $price = $array[Total];
                $per_room[] = $price;
                $number = $array[NumberOfRooms];
                $total_sum += (int) $number * (int) $price ;
                $number_room += $number;
                $person += (int) $array[NumAdultsPerRoom] + (int) $array[NumChildrenPerRoom] + (int) $array[NumInfantsPerRoom];
                ?>
                    <?php echo $name;?><br/>
                <?php
            }
            ?>
        <?php
        } // Not ws
        else
        {
            ?>
                <?php if ($value->sd_room){
                    $person += 2 * $value->sd_room;
                    $number_room += $value->sd_room;
                    $total_sum += $value->sd_room * $value->sd_rate ;
                    $per_room[] = $value->sd_rate;
                 ?>
                    S/D Room <br/>
                <?php } ?>
                <?php if ($value->t_room){
                    $person += 3 * $value->t_room;
                    $number_room += $value->t_room;
                    $total_sum += $value->t_room * $value->t_rate ;
                    $per_room[] = $value->t_rate;
                ?>
                    T Room <br/>
                <?php } ?>
                <?php if ($value->q_room){
                    $person += 4 * $value->q_room;
                    $number_room += $value->q_room;
                    $total_sum += $value->q_room * $value->q_rate ;
                    $per_room[] = $value->q_rate;
                ?>
                    Q Room <br/>
                <?php } ?>
                <?php if ($value->s_room){
                    $person += 1 * $value->s_room;
                    $number_room += $value->s_room;
                    $total_sum += $value->s_room * $value->s_rate ;
                    $per_room[] = $value->s_rate;
                ?>
                    S Room <br/>
                <?php } ?>
        <?php
        }
        ?>
        </td>
        <td style="text-align: center">
            <?php
            if (is_array($FB)) {
                foreach ($FB as $FB_room) {
                    print $FB_room . '<br/>';
                }
            }
            ?>
        </td>
        <td style="text-align: center">
            <?php
                foreach($per_room as $price_room)
                {
                    print '€'. numberformat ( $price_room ).'<br/>';
                }
            ?>
        </td>
        <td style="text-align: center"> <?php echo $number_room;?></td>
        <td style="text-align: center"> <?php echo $person;?></td>
        <?php
//                if ($value->booked)
//                {
//                    $booked = $value->booked;
//                    $booked = unserialize(base64_decode($booked));
//                    $booked = (array) $booked;//print '<pre>';print_r($booked);exit;
//                    $booked = $booked[TotalPrice];
//                    $total_sum = '€'.$booked;
//
//                }
        ?>
        <td style="text-align: center"> <?php echo '€'. numberformat ( $total_sum );?></td>
        <td style="text-align: center">
            <?php
                if ($value->breakfast >0){
                    $mealplan += $value->breakfast;
                    echo 'Breakfast <br/>';
                }
                if ($value->lunch >0) {
                    $mealplan += $value->lunch;
                    echo 'Lunch <br/>';
                }
                if ($value->mealplan >0) {
                    $mealplan += $value->mealplan;
                    echo 'Dinner ('.$value->course_type.')<br/>';
                }

                $mealplan = $mealplan * $person;
            ?>
            <?php echo '€'. numberformat ( $mealplan );?>
        </td>
    </tr>
    <?php endforeach;?>
    
</table>

<?php endif;?>

</body>
</html>