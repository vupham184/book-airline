<?php

    $app = JFactory::getApplication();
    $airline = SFactory::getAirline();
    $airline_current = SAirline::getInstance()->getCurrentAirport();
    //print_r($airline_current);
//print_r($airline);die;

foreach ($this->result as $key => $value) {
    if(intval($value->ws_id)>0){

            if($value->s_room_rate>0){
                $value->price_hotel = $value->s_room_rate;
            }elseif($value->sd_room_rate>0){
                $value->price_hotel = $value->sd_room_rate;
            }elseif($value->t_room_rate>0){
                $value->price_hotel = $value->t_room_rate;
            }if($value->q_room_rate>0){
                $value->price_hotel = $value->q_room_rate;
            }
    }
    else{

        /*
        // code dev3-map2-v3
        if(floatval($value->s_room_rate) == 0){
            $value->price_hotel = $value->convert_sd_room_rate;
        }elseif(floatval($value->sd_room_rate) == 0){
            $value->price_hotel = $value->convert_t_room_rate;
        }elseif(floatval($value->t_room_rate) == 0){
            $value->price_hotel = $value->convert_q_room_rate;
        }else{
            $value->price_hotel = $value->convert_s_room_rate;
        } */
        // code txl3test
        if(floatval($value->s_room_rate)>0){
            $value->price_hotel = $value->s_room_rate;
        }elseif(floatval($value->sd_room_rate) >0){
            $value->price_hotel = $value->sd_room_rate;
        }elseif(floatval($value->t_room_rate)>0){
            $value->price_hotel = $value->t_room_rate;
        }else{
            $value->price_hotel = $value->q_room_rate;
        }
    }    
}
if(JRequest::getInt('debug')=='test'){
    //print_r($this->result);
   // die;
}
if( count($this->result) ) :

$dataNew_ = array();
$ordering = JRequest::getInt('ordering');

switch ($ordering) {
    case 1: 
        usort($this->result, function($a, $b) {
            return $a->star - $b->star;
        });
        break;
    case 2:   
        usort($this->result, function($a, $b) {
            return $a->price_hotel - $b->price_hotel;
        });
        break;
    case 3:
        usort($this->result, function($a, $b) {
            return $a->distance - $b->distance;
        });
        break;
    case 4:
        foreach($this->result as $key => $item){
            if(empty($item->wsData)){
                if((int)$item->distance > 0 && (int) $item->transport_included == 0){
                    $item->taxi_cost=(floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2;   
                }
            }
            else{
                if( (int)$item->distance > 0 && (int)$item->transport_available == 0 && (int) $item->transport_included == 0){
                        $item->taxi_cost=(floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2;  
                }
                else{
                    $item->taxi_cost = 0;
                }
            }
        }   
        
        
        usort($this->result, function($a, $b) {
        return $a->taxi_cost - $b->taxi_cost;
        });
        
        break;
    case 5:

        foreach($this->result as $key => $item){
            if(empty($item->wsData)){
                if( (int)$item->distance > 0 && (int) $item->transport_included == 0){
                    $item->total_price_hotel=(floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2 + (int)$item->price_hotel;
                }else{
                    $item->total_price_hotel = $item->price_hotel;
                }
            }else{
                if( (int)$item->distance > 0 && (int)$item->transport_available == 0 && (int) $item->transport_included == 0){
                    $item->total_price_hotel=(floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2 + (int)$item->price_hotel;
                }else{
                    $item->total_price_hotel = $item->price_hotel;
                }
            }            
        } 
        
        usort($this->result, function($a, $b) {
            return $a->total_price_hotel - $b->total_price_hotel;
        }); 
        break;
    default:
        $this->result;
        break;
}
/*
$arrList = array();

if(count($this->result) > $airline->partner_limit_for_extra_search){
    for ($i=0; $i < $airline->partner_limit_for_extra_search; $i++) { 
        array_push($arrList, $this->result[$i]);
    }    
}else{
    $arrList = $this->result;
}
*/
$dataNew_ = $this->result;

// $value_new = array();
// $count = 0;

// foreach($this->result as $key => $value){
//     $value_new[$value->hotel_id] = $value;
//     $count++;
// }
// //print_r($this->result);die;
// //sort($value_new);
// //print_r( $value_new );die;

// //lchung
// $ordering = JRequest::getInt('ordering');
// if(JRequest::getInt('ordering')){
//     $ordering = JRequest::getInt('ordering');    
// }
// else{
//     if($airline->params['default_sort_order']){
//         $ordering = $airline->params['default_sort_order'];   
//     }else{
//         $ordering =0;
//     }
// }
// $dataNew = array();
// $dataNew1 = array();
// $dataNew_ = array();
// $kk = 0;
// if ( $ordering == 5 || $ordering == 2 ) {
// 	foreach ($value_new as $keyN => $itemN) :
// 		$kk++;
// 		$taxi_cost = (floatval($itemN->km_rate_ws)*$itemN->distance+$itemN->starting_tariff_ws)*2;
// 		if( (int)$itemN->ws_id > 0 ){
// 			$wsValueSortS = array();
// 			$wsValueSortS1 = array();
// 			$k = 0;
// 			foreach($itemN->wsData->RoomTypes as $rt) : //truong hop de sort du lieu khi co nhieu data tu dich vu
// 				$k++;
// 				if ( $ordering == 5 ){ //sort by Total calculated price
// 					$wsValueSortS['v' . $k ] = round( $rt->Total + $taxi_cost );
// 					$wsValueSortS1['o' . $k ] = $rt;
// 				}
// 				elseif ( $ordering == 2 ){ //sort by Price of hotel
// 					$wsValueSortS['v' . $k ] = round( $rt->OriginalTotal );
// 					$wsValueSortS1['o' . $k ] = $rt;
// 				}
// 			endforeach;
// 			asort($wsValueSortS);
// 			$itemN->wsData->RoomTypes = array();
// 			foreach ( $wsValueSortS as $kv =>$v ) {
// 				$KeyIn = str_replace("v","o", $kv);
// 				$itemN->wsData->RoomTypes[] = $wsValueSortS1[$KeyIn];
// 			}
			
// 			if ( $ordering == 5 ){ //sort by Total calculated price
// 				$dataNew['v' . $kk ] = round( $itemN->wsData->RoomTypes[0]->Total + $taxi_cost);
// 				$dataNew1['o' . $kk ] = $itemN;
// 			}
// 			elseif ( $ordering == 2 ){ //sort by Price of hotel
// 				$dataNew['v' . $kk ] = round( $itemN->wsData->RoomTypes[0]->OriginalTotal );
// 				$dataNew1['o' . $kk ] = $itemN;
// 			}
// 			//elseif ( $ordering == 1 ) //sort by Star
// 			//	$wsValueSort = $kk+$itemN->star; 
// 			//elseif ( $ordering == 3 ) //sort by Distance to airport
// 			//	$wsValueSort = $kk+$itemN->distance;
// 			///elseif ( isset ( $_GET['ordering'] ) && $_GET['ordering'] == 4 ) //sort by Hotel shuttle available
// 				//$wsValueSort = 'Total' . $itemN->wsData->RoomTypes[0]->Total;
				
// 		}
// 		else { //hung hotel khong thuoc dich vu
// 			if((int)$itemN->s_room_total) {
// 				$kk+=1;
// 				if ( $ordering == 5 ){ //sort by Total calculated price
// 					if( 
// 						(int)$itemN->distance > 0 
// 						&& (int)$itemN->transport_available == 0 
// 						&& (int) $$itemN->transport_included == 0) 
// 					{
// 						$dataNew['v' . $kk ] = round( $itemN->s_room_rate + $taxi_cost);
// 					}
// 					else {
// 						$dataNew['v' . $kk ] = round( $itemN->s_room_rate);
// 					}
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 				elseif ( $ordering == 2 ){ //sort by Price of hotel
// 					$dataNew['v' . $kk ] = round( $itemN->s_room_rate );
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 			}//End s_room_total
			
// 			if((int)$itemN->sd_room_total) {
// 				$kk+=1;
// 				if ( $ordering == 5 ){ //sort by Total calculated price
// 					if( 
// 						(int)$itemN->distance > 0 
// 						&& (int)$itemN->transport_available == 0 
// 						&& (int) $$itemN->transport_included == 0) 
// 					{
// 						$dataNew['v' . $kk ] = round( $itemN->sd_room_rate + $taxi_cost);
// 					}
// 					else {
// 						$dataNew['v' . $kk ] = round( $itemN->sd_room_rate);
// 					}
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 				elseif ( $ordering == 2 ){ //sort by Price of hotel
// 					$dataNew['v' . $kk ] = round( $itemN->sd_room_rate );
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 				//asort($dataNew);
// 				//asort($dataNew1);
// 				//print_r( $dataNew );
// 				//print_r( $dataNew1 );die;
// 			}//End sd_room_total
			
// 			if((int)$itemN->t_room_total) {
// 				$kk+=1;
// 				if ( $ordering == 5 ){ //sort by Total calculated price
// 					if( 
// 						(int)$itemN->distance > 0 
// 						&& (int)$itemN->transport_available == 0 
// 						&& (int) $$itemN->transport_included == 0) 
// 					{
// 						$dataNew['v' . $kk ] = round( $itemN->t_room_rate + $taxi_cost);
// 					}
// 					else {
// 						$dataNew['v' . $kk ] = round( $itemN->t_room_rate);
// 					}
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 				elseif ( $ordering == 2 ){ //sort by Price of hotel
// 					$dataNew['v' . $kk ] = round( $itemN->t_room_rate );
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 			}//End t_room_total
			
// 			if((int)$itemN->q_room_total) {
// 				$kk+=1;
// 				if ( $ordering == 5 ){ //sort by Total calculated price
// 					if( 
// 						(int)$itemN->distance > 0 
// 						&& (int)$itemN->transport_available == 0 
// 						&& (int) $$itemN->transport_included == 0) 
// 					{
// 						$dataNew['v' . $kk ] = round( $itemN->q_room_rate + $taxi_cost);
// 					}
// 					else {
// 						$dataNew['v' . $kk ] = round( $itemN->q_room_rate);
// 					}
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 				elseif ( $ordering == 2 ){ //sort by Price of hotel
// 					$dataNew['v' . $kk ] = round( $itemN->q_room_rate );
// 					$dataNew1['o' . $kk ] = $itemN;
// 				}
// 			}//End q_room_total
			
// 		}
// 	endforeach;
	
// 	asort($dataNew);
// 	//print_r( $dataNew );die;
// 	foreach ( $dataNew as $vks => $vs ) {
// 		$key_In = str_replace("v","o", $vks);
// 		$dataNew_[] = $dataNew1[$key_In];
// 	}
// 	//print_r( $dataNew_ );
// 	//die;
// }elseif( $ordering == 3 ){
//     $dataNew=$this->result;
//     usort($dataNew, function($a, $b) {
//     return $a->distance - $b->distance;
//     });
//     $dataNew_=$dataNew;
// }elseif($ordering==4){
//     foreach($this->result as $key => $item){
//         if(empty($item->wsData)){
//             if((int)$item->distance > 0 && (int) $item->transport_included == 0){
//                 $item->taxi_cost=(floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2;   
//             }
//         }
//         else{
//             if( (int)$item->distance > 0 && (int)$item->transport_available == 0 && (int) $item->transport_included == 0){
//                     $item->taxi_cost=(floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2;  
//             }
//             else{
//                 $item->taxi_cost = 0;
//             }
//         }
//     }   
    
//     $dataNew=$this->result;
//     usort($dataNew, function($a, $b) {
//     return $a->taxi_cost - $b->taxi_cost;
//     });
//     $dataNew_=$dataNew;
// }
// else {
// 	$dataNew_ = $value_new;
// }
// if( empty( $dataNew ) ) {
// 	$dataNew_ = $value_new;
// }
//print_r( $dataNew );
//print_r( $dataNew1 );die;
//print_r( $dataNew_ );
//die;
$noAvailabilityCount = 0;
$rooms = JRequest::getInt('rooms');
//End lchung

$stt = 0;
$tmpl=JRequest::getVar('tmpl');
foreach ($dataNew_ as $key => $item) :
	
    $ok = false;

    if(!empty($item->wsData)) {
        $ok = true;
    }

    if( ((int)$item->sd_room_total > 0 ) && (floatval($item->sd_room_rate)) > 0 ) {
        $ok = true;
    }
    if( ((int)$item->t_room_total > 0 ) && (floatval($item->t_room_rate)) > 0 ) {
        $ok = true;
    }
    if( !empty($item->single_room_available) && (int)$item->single_room_available == 1 ){
        if( ((int)$item->s_room_total > 0 ) && (floatval($item->s_room_rate)) > 0 ) {
            $ok = true;
        }
    }
    if( !empty($item->quad_room_available) && (int)$item->quad_room_available == 1 ){
        if( ((int)$item->q_room_total > 0 ) && (floatval($item->q_room_rate)) > 0 ) {
            $ok = true;
        }
    }

    if( $ok == false ) {
        $noAvailabilityCount ++;
        continue;
    }
    
    //shortcut
    $this->item = & $item;

    $formName = 'filter-hotel-'.$item->hotel_id;

    if($item->association_id ) {
        $formName = 'filter-remote-hotel-'.$item->hotel_id;
    } else {
        $item->association_id = 0;
    }
    ?>
    <tbody action="<?php echo JRoute::_('index.php?option=com_sfs')?>" id="<?php echo $formName;?>" name="<?php echo $formName;?>" method="post" class="id-filter sfs-form form-vertical <?php echo $formName?> hotel_id_<?php echo $item->hotel_id;?>">
    <tr class="hotel-row-space"><td></td></tr>
    <tr class="hotel-row">
        <td <?php if($key == 0): ?> data-step="4" data-intro="<?php echo SfsHelper::getTooltipTextEsc('hotel_details', $text,'airline'); ?>" <?php endif;?> width="25%">
            <?php echo $this->loadTemplate('item_hotel');?>
        </td>

        <td <?php if($key == 0): ?> data-step="5" data-intro="<?php echo SfsHelper::getTooltipTextEsc('mealplan', $text,'airline'); ?>" <?php endif;?> width="15%">
            <?php if(empty($item->wsData)){?>
                <?php echo $this->loadTemplate('item_mealplan');?>
            <?php }else{?>
                <span class="r-heading">Mealplan</span>
                <span id="show_breakfast_<?php echo $this->item->id;?>"></span>
                <span id="show_lunch_<?php echo $this->item->id;?>"></span>
                <span id="show_dinner_<?php echo $this->item->id;?>"></span>
            <?php }?>
        </td>
        <!-- Show item room -->


        <td <?php if($key == 0): ?> data-step="6" data-intro="<?php echo SfsHelper::getTooltipTextEsc('item_rooms', $text,'airline'); ?>" <?php endif;?> >
            <table width="100%" cellpadding="0" cellspacing="0" class="room-table">
                <?php if(empty($item->wsData)) : ?>
                    <?php echo $this->loadTemplate('item_rooms');?>
                <?php else : ?>
                    <?php echo $this->loadTemplate('ws_item_rooms');?>
                <?php endif;?>
            </table>

            <?php if($item->isContractedRate) :?>
                <div class="contracted-checkbox">
                    <?php echo $airline->getAirlineName();?>
                    contracted rate hotel
                </div>
            <?php endif;?>

            <?php if($item->wsData->RoomTypes[0]->SpecialOfferApplied) : ?>
                <div class="special-offer">
                    <img src="<?php echo JURI::base();?>templates/<?php echo $app->getTemplate();?>/images/special_icon.png" style="float:left; margin-left: 5px; display: table-cell" />
                    <div style="display: table-cell;vertical-align: middle; font-size: 13px">
                        <?php echo $item->wsData->RoomTypes[0]->SpecialOfferApplied;?>
                    </div>
                </div>
            <?php endif;?>

            <?php if($item->wsData->RoomTypes[0]->Errata) : ?>
                <div class="erratum">
                    <img src="<?php echo JURI::base();?>templates/<?php echo $app->getTemplate();?>/images/alert_icon.png" style="float:left; margin-left: 5px;" />
                    <div style="margin-left: 35px; font-size: 13px; padding: 10px 5px 10px 5px">
                        <?php foreach($item->wsData->RoomTypes[0]->Errata as $erratum):?>
                            Erratum: <?php echo $erratum->Subject?>
                            <br/>
                            <?php echo $erratum->Description?>
                            <br/>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endif;?>
        </td>

        <td <?php if($key == 0): ?> data-step="7" data-intro="<?php echo SfsHelper::getTooltipTextEsc('estimated_charges', $text,'airline'); ?>" <?php endif;?> width="20%">
            <div style="width: 200px; height: 280px; padding: 5px 20px 5px 10px; background-color: #DFEFFF; border-radius: 5px; position: relative; font-weight: bold">
                <table width="100%" cellpadding="0" cellspacing="0" class="room-table">
                    <tr><td colspan="2">Estimated charges</td></tr>
                    <tr>
                        <td><b>Room</b></td>
                        <td><?php echo $item->currency_symbol; ?> 
                            <label id="estimated_rooms">
                                <?php
                                $estimated_rooms = $item->estimated_rooms;
                                echo number_format($estimated_rooms, 2);
                                ?>
                            </label></td>
                    </tr>
                    <?php if(empty($item->wsData)) : ?>
                        <tr>
                            <td>Mealplan</td>
                            <td style="vertical-align: middle"><?php echo $item->currency_symbol?> <label id="estimated_mealplan">0.00</label></td>
                        </tr>
                        <?php if( (int)$item->distance > 0 && (int) $item->transport_included == 0) :?>
                            <tr>
                                <td>Estimated<br/>&nbsp;taxi cost<br/>(return trip)</td>
                                <td style="vertical-align: middle">
                                    <?php echo $item->currency_symbol?>
                                    <input type="hidden" value="<?php echo (floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2?>" id="taxi_cost"/>
                                    <label id="estimated_transport">0.00</label>
                                </td>
                            </tr>
                        <?php endif;?>
                    <?php else:?>
                        <?php if( (int)$item->distance > 0 && (int)$item->transport_available == 0 && (int) $item->transport_included == 0) :?>
                            <tr>
                                <td>Estimated<br/>&nbsp;taxi cost<br/>(return trip)</td>
                                <td style="vertical-align: middle">
                                    <?php echo $item->currency_symbol?>
                                    <input type="hidden" value="<?php echo (floatval($item->km_rate_ws)*$item->distance+$item->starting_tariff_ws)*2?>" id="taxi_cost"/>
                                    <label id="estimated_transport">0.00</label>
                                </td>
                            </tr>
                        <?php endif;?>
                    <?php endif;?>
                    <tr>
                        <td colspan="2" style="padding-right: 0px;">
                            <hr style="border-top:3px solid; width: 82%; margin: 10px 0px 0px 0px; display: inline-block;"/>
                            <i class="fa fa-plus-circle" style="float: right; font-size: 25px"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Total charge</td>
                        <td><?php echo $item->currency_symbol?> <label id="total_charge" data-hotel="<?php echo $item->hotel_id;?>" >
                                <?php echo number_format(floatval($estimated_taxi_cost+$estimated_rooms),2, ".", "");?>
                            </label></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php if( (int)$item->percent_release_policy ) : ?>
                                <p style="color: #AFC6E7;position: absolute; bottom: 5px; margin-bottom: 0px">free release percentage <?php echo (int)$item->percent_release_policy;?>%</p>
                                <input type="hidden" name="percent_release_policy" value="<?php echo (int)$item->percent_release_policy;?>" />
                            <?php else : ?>
                                <input type="hidden" name="percent_release_policy" value="0" />
                            <?php endif;?>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="tr button-row">
        <td class="hotel-row" colspan="3" >
            <?php if($item->wsData) : ?>
                <label style="margin-left: 20px">* SFS has calculated the estimated Taxi cost based on distance to airport and on a 2 way trip, actual fare may differ</label>
            <?php endif;?>
        </td>
        <td class="hotel-row" >


            <?php if( (int)$item->t_room_total ||(int)$item->sd_room_total || (int)$item->s_room_total ||(int)$item->q_room_total || !empty($item->wsData)) : ?>
                <button type="button" class="btn orange sm bookingbutton button-now pull-right <?php echo !empty($item->wsData) ? 'ws-book-now ws-' : ''?>book-now-<?php echo $item->id?>" rel="<?php echo $item->id.','.$item->association_id;?>" data-id="<?php echo $item->id?>" <?php if($key == 0):?>data-step="8" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_booking', $text,'airline'); ?>" <?php endif;?>>Book now</button>
            <?php endif;?>
            <span id="spinner<?php echo $item->id;?>" class="ws-booking-spinner ajax-Spinner48 float-right" style="display: none;"></span>

            <input type="hidden" name="hotel_id" value="<?php echo $item->hotel_id;?>" />
            <input type="hidden" name="room_id" value="<?php echo $item->id;?>" />
            <input type="hidden" name="association_id" value="<?php echo $item->association_id;?>" />
            <input type="hidden" name="date_start" value="<?php echo $this->state->get('filter.date_start');?>" />
            <input type="hidden" name="date_end" value="<?php echo $this->state->get('filter.date_end');?>" />
            <input type="hidden" name="rooms" value="<?php echo $this->state->get('filter.rooms');?>" />
            <?php 
				//lchung
				$bf_layover_price = $item->bf_layover_price;
				if( $item->isContractedRate && $item->contracted_breakfast > 0 ) {
					$bf_layover_price = $item->contracted_breakfast;
				}

				$lunch_standard_price = $item->lunch_standard_price;
				if( $item->isContractedRate && $item->contracted_lunch > 0 ) {
					$lunch_standard_price = $item->contracted_lunch;
				}
			
				$course_1 = $item->course_1;
				if( $item->isContractedRate && $item->contracted_dinner > 0 ) {
					$course_1 = $item->contracted_dinner;
				}
				$course_2 = $item->course_2;
				if( $item->isContractedRate && $item->contracted_dinner > 0 ) {
					$course_2 = $item->contracted_dinner * 2;
				}
				$course_3 = $item->course_3;
				if( $item->isContractedRate && $item->contracted_dinner > 0 ) {
					$course_3 = $item->contracted_dinner * 3;
				}
				//End lchung
			?>
            <input type="hidden" name="breakfast_price" value="<?php echo $bf_layover_price;?>" />
            <input type="hidden" name="lunch_price" value="<?php echo $lunch_standard_price;?>" />
            <input type="hidden" name="course1" value="<?php echo $course_1;?>" />
            <input type="hidden" name="course2" value="<?php echo $course_2;?>" />
            <input type="hidden" name="course3" value="<?php echo $course_3;?>" />
            
            <input type="hidden" name="task" value="booking.process" />
            <?php echo JHtml::_('form.token'); ?>
        </td>
    </tr>
    </tbody>
<?php   
endforeach;
?>


<tbody id="noAvailability">
<tr>
    <td colspan="4">
        <?php if( $noAvailabilityCount > 0) : ?>
            <h3>Hotels without availability</h3>
            <div style="overflow: hidden; padding: 15px; background: #c1c1c4;">
                <?php
                foreach ($value_new as $item) :
                    $ok = true;
                    if( empty($item->hotel_id) ){
                        $ok = true;
                    } else {
                        if( ((int)$item->sd_room_total > 0 ) && (floatval($item->sd_room_rate)) > 0 ) {
                            $ok = false;
                        }
                        if( ((int)$item->t_room_total > 0 ) && (floatval($item->t_room_rate)) > 0 ) {
                            $ok = false;
                        }
                        if( !empty($item->single_room_available) && (int)$item->single_room_available == 1 ){
                            if( ((int)$item->s_room_total > 0 ) && (floatval($item->s_room_rate)) > 0 ) {
                                $ok = false;
                            }
                        }
                        if( !empty($item->quad_room_available) && (int)$item->quad_room_available == 1 ){
                            if( ((int)$item->q_room_total > 0 ) && (floatval($item->q_room_rate)) > 0 ) {
                                $ok = false;
                            }
                        }

                        if(!empty($item->wsData)) {
                            $ok = false;
                        }
                    }
                    if( $ok == true ) :
                        ?>
                        <div class="floatbox clear midmarginbottom">
											<span class="noavail-hotel float-left">
												<span class="noavail-name float-left"><?php echo $item->name?></span>
												<span class="star noavail-star star<?php echo $item->star;?>"></span>
											</span>
                            <span class="float-left noavail-text">No availability at this moment</span>
                        </div>
                    <?php
                    endif;
                endforeach;
                ?>
            </div>
        <?php endif;?>
        <?php endif;?>
    </td>
</tr>
</tbody>
