<?php
defined('_JEXEC') or die;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<title>Availability report for: <?php echo $airline->name;//airline_alliance;?> - <?php echo $airline->code;?></title>
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

<h2>Availability report for: <?php echo $airline->name;//airline_alliance;?> - <?php echo $airline->code;?></h2>

<?php if(count($inventories)):?>

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
    
    	<tr>
        	<td colspan="3" style="text-align:right;">Date</td>
         	<?php
			foreach ($dates as $date){			
				echo '<td>'.$date.'</td>';
			}
			?>
        </tr>
    	
		<!-- SD RATE -->
        <tr class="table-header">
        	<td class="col1" nowrap="nowrap">Total market</td>
            <td class="col2" nowrap="nowrap">Ring</th>
            <td class="col3" nowrap="nowrap">Avg Rate SD</td> 
            <?php
			foreach ($dates as $date){			
				if( isset($avgSDRooms[$date])){
					echo '<td>'. numberformat( $avgSDRooms[$date] ) .'&nbsp;</td>';
				}
			}
			?>            
        </tr>
        <?php foreach ($inventories as $inventory) : ?>
        <tr>
        	<td class="col1"><?php echo htmlspecialchars($inventory->name, ENT_COMPAT, 'UTF-8');?></td>
        	<td class="col2"><?php echo $inventory->ring;?>&nbsp;</td>
        	<td class="col3">Rate SD</td>                        
            
            <?php foreach ($dates as $date) : ?>
            <td>
            	<?php
				if( $inventory->dates[$date] && $inventory->dates[$date]->sd_room_rate > 0  )
				{					
					echo numberformat( $inventory->dates[$date]->sd_room_rate );					
				}	
				?>&nbsp;
            </td>
            <?php endforeach;?>
            
        </tr>        	      
        <?php endforeach;?>
        
        
        <!-- SD ROOM -->
        <tr class="table-header">
        	<td class="col1">Total market</td>
            <td class="col2">Ring</td>
            <td class="col3">Total number of rooms SD</td>
            <?php
			foreach ($dates as $date){			
				if( isset($sdRooms[$date])){
					echo '<td>'. numberformat( $sdRooms[$date] ) .'&nbsp;</td>';
				}
			}
			?>
        </tr>
        
        <?php foreach ($inventories as $inventory) : ?>
        
        <tr>
        	<td class="col1"><?php echo $inventory->name;?></td>
        	<td class="col2"><?php echo $inventory->ring;?>&nbsp;</td>
        	<td class="col3">Number of rooms SD</td>                        
            
            <?php foreach ($dates as $date) : ?>
            <td>
            	<?php
				if( $inventory->dates[$date] )
				{					
					echo numberformat( $inventory->dates[$date]->sd_room_total + $inventory->dates[$date]->booked_sdroom );					
				}	
				?>&nbsp;
            </td>
            <?php endforeach;?>
            
        </tr>
        	      
        <?php endforeach;?>
        
        <!-- T RATE -->
        <tr class="table-header">
        	<td class="col1">Total market</td>
            <td class="col2">Ring</td>
            <td class="col3">Avg Rate T</td>
            <?php
			foreach ($dates as $date){			
				if( isset($avgTRooms[$date])){
					echo '<td>'. numberformat( $avgTRooms[$date] ) .'&nbsp;</td>';
				}
			}
			?>    
        </tr>
        <?php foreach ($inventories as $inventory) : ?>
        
        <tr>
        	<td class="col1"><?php echo $inventory->name;?></td>
        	<td class="col2"><?php echo $inventory->ring;?>&nbsp;</td>
        	<td class="col3">Rate T</td>                        
            
            <?php foreach ($dates as $date) : ?>
            <td>
            	<?php
				if( $inventory->dates[$date] && $inventory->dates[$date]->t_room_rate > 0  )
				{					
					echo numberformat( $inventory->dates[$date]->t_room_rate );					
				}	
				?>&nbsp;
            </td>
            <?php endforeach;?>
            
        </tr>
        	      
        <?php endforeach;?>
        
        <!-- T ROOM -->
        <tr class="table-header">
        	<td class="col1">Total market</td>
            <td class="col2">Ring</td>
            <td class="col3">Total number of rooms T</td>           
            <?php
			foreach ($dates as $date){			
				if( isset($tRooms[$date])){
					echo '<td>'. numberformat( $tRooms[$date] ) .'&nbsp;</td>';
				}
			}
			?>
        </tr>
        
         <?php foreach ($inventories as $inventory) : ?>
        
        <tr>
        	<td class="col1"><?php echo $inventory->name;?></td>
        	<td class="col2"><?php echo $inventory->ring;?>&nbsp;</td>
        	<td class="col3">Number of rooms T</td>                        
            
            <?php foreach ($dates as $date) : ?>
            <td>
            	<?php
				if( $inventory->dates[$date] )
				{					
					echo numberformat( $inventory->dates[$date]->t_room_total + $inventory->dates[$date]->booked_troom );					
				}	
				?>&nbsp;
            </td>
            <?php endforeach;?>
            
        </tr>
        	      
        <?php endforeach;?>

    </tbody>
    
</table>

<?php endif;?>

</body>
</html>