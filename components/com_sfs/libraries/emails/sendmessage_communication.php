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

<?php 
    $conv_count_issue = array();   

    if(count($data->count_author) > count($data->count_issue)){
        foreach ($data->count_author as $key_ => $value) {
            if( (int)$data->count_issue[$key_] > 0){
                array_push($conv_count_issue, (int)$data->count_issue[$key_]);
            }else{
                (int)$data->count_issue[$key_] = 0;
                array_push($conv_count_issue, (int)$data->count_issue[$key_]);
            }
        }
    }else{
        foreach ($data->count_issue as $key_ => $value) {
            if( (int)$data->count_author[$key_] > 0){
                array_push($conv_count_issue, (int)$data->count_author[$key_]);
            }else{
                (int)$data->count_author[$key_] = 0;
                array_push($conv_count_issue, (int)$data->count_author[$key_]);
            }
        }
    }

    
?>
<div style="height:30px;">&#160;</div>
<div style="float: left; width: 100%; padding: 20px 5px;font-weight:600">
    Summary: <br />
    Attention all concerned at <?php echo $data->dep . ' and '. $data->arr; ?>  <br />
    Please <a href="http://www.sfs-web.org/dev3-map2-v3/index.php?option=com_sfs&view=passengersimport&itemit-171">checkout SFS 360</a> for vouchers to be issued, arrange services and check bags to be rerouted.
</div>
<div style="height:30px;">&#160;</div>
<div>
    <table cellspacing="0" cellpadding="0" border="0" class="mainInfo_count" style="border:0; width:60%; ">
        <tr>
            <th width="30%">&#160;</th>
            <th width="30%">Authorised</th>
            <th width="30%">Issued</th>
        </tr>
        <?php if(count($data->count_author) > count($data->count_issue)) : ?>
            <?php $count = 0; foreach ($data->count_author as $k => $val) : ?>
                <tr>
                    <td width="30%"><?php echo $k; ?></td>
                    <td width="30%"><?php echo $val; ?></td>
                    <td width="30%"><?php echo $conv_count_issue[$count]; ?></td>
                </tr>
            <?php $count++; endforeach; ?>
        <?php else: ?>
            <?php $count = 0; foreach ($data->count_issue as $k => $val) : ?>
                <tr>
                    <td width="30%"><?php echo $k; ?></td>
                    <td width="30%"><?php echo $conv_count_issue[$count]; ?></td>
                    <td width="30%"><?php echo $val; ?></td>
                </tr>
            <?php $count++; endforeach; ?>
        <?php endif; ?>
    </table>
    
</div>
<div style="height:30px;">&#160;</div>
<div style="float: left; width: 100%; padding: 20px 5px;font-weight:600">
   <?php echo $text_1;  ?> 
</div>
<table cellspacing="0" cellpadding="0" border="0" class="mainInfo" style="border:0; width:98%; ">
    <tr>
        <th width="22%">Passenger</th>
        <th width="10%">PNR</th>
        <th width="12%">Add Info</th>
        <th width="14%">Flightnumber</th>
        <th width="12%">Onward Flight</th>
        <th width="30%">Services</th>
    </tr>
    <?php foreach ($data->passengerInfo as $key => $value): ?>
        
        <tr style="<?php echo $key%2 > 0 ? "background:#f8f8f8" : "background:#ffffff"; ?> ">
            <td width="22%" valign="middle" style="border-bottom: 1px solid #78B8F0;padding: 10px;"><?php echo $value->first_name .' '. $value->last_name .' '. $value->title; ?></td>
            <td width="10%" valign="middle" style="border-bottom: 1px solid #78B8F0;padding: 10px;"><?php echo $value->pnr; ?></td>
            <td width="12%" valign="middle" style="border-bottom: 1px solid #78B8F0;padding: 10px;">
                <?php echo $data->irreg_reason . ',' . $value->irreg_reason; ?></td>
            <td width="14%" valign="middle" style="border-bottom: 1px solid #78B8F0;padding: 10px;">
                <?php echo $data->dep .' '. $data->carrier .''. $data->flight_no .' '.$data->arr; ?></td>
            <td width="12%" valign="middle" style="border-bottom: 1px solid #78B8F0;padding: 10px;"></td>
            <td width="30%" valign="middle" style="border-bottom: 1px solid #78B8F0;padding: 5px;">
                <?php if($value->name_service != ""): ?>
                    <?php foreach ($value->name_service as $k => $val) :?>
                        <?php if( (int)$val->id == 1): ?>
                            <div style="float:left; width: 100px;">
                                <?php 
                                    if(!empty($val->hotel_name) ){
                                        echo $val->name_service. ': ' . $val->hotel_name->name;
                                    }else{
                                        echo $val->name_service. ': ';
                                    }
                                ?> 
                            </div>
                        <?php else: ?>
                            <div style="float:left; width: 100px;">
                                <?php 
                                    if(!empty($val->hotel_name) ){
                                        echo $val->name_service.': '. $val->price_per_person;
                                    }else{
                                        echo $val->name_service.': ';
                                    }
                                ?>                            
                            </div>
                        <?php endif; ?>
                        
                    <?php endforeach; ?>
                    <!-- <ul class="servicename">                                        
                        <?php //foreach ($value->name_service as $k => $val) :?>
                            <li><?php //echo $val->name_service;?></li>
                        <?php //endforeach; ?>
                    </ul> -->
                <?php endif; ?>
            </td>
            
        <!--     <td width="35%" valign="middle" style="border-bottom: 1px solid #78B8F0;padding: 5px;">
                <?php //foreach ($value->name_service as $k => $val) :?>
                    <?php //if((int)$val->id == 1) :?>
                        HOTAC WILL BE ARRANGED BY PMI REFRESHMENTVOUCHERS VALUE EUR 5
                    <?php //endif; ?>
                <?php //endforeach; ?>
            </td>   -->
        </tr>        
    <?php endforeach ?> 
</table>
<div style="float: left; width: 100%; padding: 20px 5px;font-weight:600;">
    <?php echo $text_2;  ?> 
</div>
</body>
</html>
