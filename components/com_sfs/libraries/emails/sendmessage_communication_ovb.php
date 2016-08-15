<?php
// No direct access
defined('_JEXEC') or die;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SFS-web SHORT TERM ROOMBLOCK Reservation</title>
<style type="text/css">
th{text-align: left; background-color: #dfdfdf; height: 30px; border:2px solid #78B8F0; border-left: none;border-right: none; padding: 5px 5px;}
table.mainInfo td{border-bottom: 1px solid #78B8F0;padding: 5px;}  
table.mainInfo_count td{border: 1px solid #78B8F0;padding: 5px;}
ul li{list-style: none; margin-left:0;}
ul.servicename li{margin-bottom: 0;}  
tr.row_le{background: #f8f8f8;}
table.mainInfo_count{margin: 10px;}
</style>
</head>
<body>

<?php $dataFortmat = explode('T',$data->flight_date); ?>
<div style="height:30px;">&#160;</div>
<div style="float: left; width: 100%; padding: 20px 5px;font-weight:600">    
    Attention all concerned at <?php echo $data->dep ?> <br />Due to <?php echo $data->irreg_reason ?> (and <?php echo $data->passengerInfo[0]->irreg_reason ?> ) <?php echo $data->carrier.$data->flight_no ?> <?php echo $dataFortmat[0]  ?> Please note following
</div>
<div style="height:30px;">&#160;</div>

<div style="float: left; width: 100%; padding: 20px 5px;font-weight:600">    
    Please search for {text_1} volunteers. Voluntary denied boarding passengers will recieve {text_2} and be protected via {text_3} in case of involuntary denied boarding passengers will recieve {text_4} and will be reprotected via {text_5}
</div>
<div style="float: left; width: 100%; padding: 20px 5px;font-weight:600;">
    <?php echo $text_1;  ?> 
</div>
</body>
</html>
