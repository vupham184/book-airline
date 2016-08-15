<?php
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
$this->cancel_count = 0;
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_sfs/assets/datatables/jquery.dataTables.css');
$link_Img = JURI::root().'media/media/images/select-pass-icons';
// echo "<pre>";
// print_r($this->trace_passengers);
// echo "</pre>";
?>
<style>
    table.trace-passenger-table tr.even td {
        background: white;
    }
    #DataTable_filter{
        display: none;
    }
    .DataTable_input{
        width: 100% !important;
        border: 1px solid #82adf1;
    }
    table.dataTable thead th{
        position: relative;
    }
    table.dataTable thead th img{
        position: absolute;
        right:9px;
        top:14px;
        cursor:pointer
    }
    table.airblocktable th {
        background: white;
    }
    table.dataTable thead th, table.dataTable thead td {
        background: #dddddd;
        font-size: 12px !important;
        font-weight: bold;
        padding: 5px;
        border-bottom: 1px solid #82adf1;
        text-align: left;
    }
    table.dataTable td.dataTables_empty {
        text-align: left;
    }
    .service{
        float: right;
        width: 18px !important;
        height: 25px !important;
        background-repeat: no-repeat !important;
        background-image:url(<?php echo $link_Img.'/selected-services.png' ?>) !important;
        background-position:0 -28px;

    }
    .service{
        float: right;
        width: 18px !important;
        height: 25px !important;
        background-repeat: no-repeat !important;
        background-image:url(<?php echo $link_Img.'/selected-services.png' ?>) !important;
        background-position:0 -28px;

    }
    .service.service-hotel{
        background-position:-4px -20px;
    }
    #service-taxi{
        background-position:-22px -25px;
    }
    .service-taxi{
        background-position:0px -129px !important;
        width: 19px!important;
    }
    .service.service-refreshment{
        background-position:-2px -47px !important; 
    }
    .service.service-bus-transfer{
        background-position:-2px -156px !important; 
    }
    .service.service-train{
        background-position:-2px -102px !important; 
    }
    .service.service-rental-car{
        background-position:-2px -75px !important; 
    }
    .service.service-other{
        background-position:-2px -215px !important; 
    }
    .add-services-list{
        width: 29px !important;
        background-image:url(<?php echo $link_Img.'/icon-add-service.png' ?>) !important;
        background-position:0px 3px !important;
        background-color: #FFFFFF !important;
        margin-left:1px;
    }
</style>
<script src="<?php echo JURI::base()?>components/com_sfs/assets/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script>
   // jQuery.noConflict();
    jQuery(function($){
            var table;
            table = load_data();
            function load_data(){
                var data = $('#DataTable').DataTable( {
                    "processing": true,
                    //"serverSide": true,
                    "bPaginate": false,
                    "bSort": false,
                    "bFilter": true,
                    "language": {
                        "lengthMenu": "",
                        "zeroRecords": "There are no result left! please remove one or more of your filter setting above or below",
                        "info": "",
                        "infoEmpty": "",
                        "infoFiltered": ""
                    },
                    bAutoWidth: false,
                    "columns": [
                        { "width": "12%" },
                        { "width": "5%" },
                        { "width": "15%" },
                        { "width": "15%" },
                        { "width": "5%" },
                        { "width": "2%" },
                        { "width": "13%" },
                        { "width": "20%" },
                        { "width": "15%" }
                    ],
                    "preDrawCallback": function( settings ) {

                    }
                } );

                return data;
            }

            $('#DataTable thead#DataTable_header th').each( function () {
                var title = $('#DataTable thead#DataTable_header th').eq( $(this).index() ).text();
                var id= title.replace(" ", "");
                $(this).html( '<input class="DataTable_input" id="'+id+'" type="text" placeholder="'+title+'" />');
            } );

            // Apply the search
            table.columns().every( function () {
                var that = this;
                $("input", this.header()).keyup( function (e) {
                        that.search( this.value ).draw();
                } );
            } );

            table.on( 'draw', function () {
                if ($('.dataTables_empty').text().trim().length > 0)
                {
                    $('.dataTables_empty').append('<br/>Remove all filters <a style=\"cursor:pointer\" href=\"javascript:void(0);\" class=\"remove_filters_all\"> (X) </a>');
                }
            });

            $("body").on("click", "img.remove_filter" , function() {
                var id = $(this).attr('id')
                var columns = $(this).attr('columns');
                if (id && columns){
                    $.trim( $('#'+id).val("") );
                    table.search('').columns(columns).search('').draw();
                }
            });

            $("body").on("click", "a.remove_filters_all" , function() {
                $('.DataTable_input').val("");
                table.search( '' ).columns().search( '' ).draw();
            });

            $('#arr').html('Filter on:');
            $('#mealplan, #phone_number, #voucher, #taxi').html('');

            var c = 1;
            $('.DataTable_input').each( function () {
                $(this).attr('columns',c);
                $(this).after('<img href=\"javascript:void(0);\" class=\"remove_filter\" id='+$(this).attr('id')+' columns='+c+' src="<?php echo JURI::base()?>components/com_sfs/assets/images/image_close.png"/>');
                c++;
            } );

            $('.fancybox').fancybox(
                 {
                    'width': 380,
                    'height': 'auto'
                 }
            );

    })
</script>
<table id="DataTable" class="airblocktable trace-passenger-table" cellspacing="0" width="100%">
    <thead id="DataTable_header">
        <tr style="width: 100%">
            <th id="arr" style="text-align: right;background-color: white;">Arr/dep date hotel</th>
            <th style="background-color: white;">Airp</th>
            <th style="background-color: white;">Passenger name</th>
            <th style="background-color: white;">Hotel name</th>
            <th style="background-color: white;">Flight number</th>
            <th id="mealplan" style="background-color: white;">Mealplan</th>
            <th id="phone_number" style="background-color: white;">Phone numbers</th>
            <th id="voucher" colspan="3" style="background-color: white;">Additional services</th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th>Arr/dep date hotel</th>
            <th>Airp</th>
            <th>Passenger name</th>
            <th>Hotel name</th>
            <th>Flight number</th>
            <th>Mealplan</th>
            <th>Phone numbers</th>
            <th colspan="2">Additional services</th>
            <th></th>
        </tr>
    </thead>
	<?php
	if(count($this->trace_passengers)):
	$filter_lastname = $this->state->get('filter_lastname');
	foreach ($this->trace_passengers as $item) :
		 if( (int)$item->status >= 3 ) {
		 	$this->cancel_count++;
		 	continue;		 	
		 }
		 
		 $toolTip = '';
		 $hasTip  = false;
		 $toolTip = '<table class="tooltiptable"><tr><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>';

		 $toolTip .= '<tr><td>';
		 if((int)$item->breakfast){
		 	$hasTip = true;	
		 	$toolTip .='Yes';					 	
		 } else {
		 	$toolTip .='No';
		 }						 
		 $toolTip .= '</td><td>';
		 if((int)$item->lunch){
		 	$hasTip = true;		
		 	$toolTip .='Yes';				 	
		 } else {
		 	$toolTip .='No';
		 }					
		 $toolTip .= '</td><td>';		
		 if((int)$item->mealplan){
		 	$hasTip = true;							 	
		 	$toolTip .= $item->course_type.'-course';					 	
		 } else {
		 	$toolTip .='No';
		 }	 
		 $toolTip .= '</td></tr>';
		 $toolTip .= '</table>';			
			
		if ($filter_lastname)
		{			
			$class  = '';																
			$regex		= '/'.$filter_lastname.'/i';													
			preg_match_all($regex, $item->last_name, $matches, PREG_SET_ORDER);							
			if ($matches) {
				$class = 'even';
			} else {
				preg_match_all($regex, $item->first_name, $matches, PREG_SET_ORDER);
				if ($matches) {
					$class = 'even';
				} 		
			}						
		}

		foreach( $item->pax as $vk => $v ){
			
			if( (int)$item->voucher_id > 0 ) { // truong hop booking hotel
				$meal_value = $v->meal_value;
				$taxi_value = $v->taxi_value;
			}
			
			$airplus_mealplan = $v->airplus_mealplan;
			$airplus_taxi = $v->airplus_mealplan;
			$card_number_meal = SfsHelper::getCardNumber( $v->card_number_meal );
			$card_number_taxi = SfsHelper::getCardNumber( $v->card_number_taxi );
			$airplus_cash = $v->airplus_cash;
			$card_number = $v->card_number;
			$airplus_phone = $v->airplus_phone;
			$airplus_phone = $v->airplus_phone;
			$phone_card_number = $v->phone_card_number;
		}
		?>
			<tr class="<?php echo $class?>">
				<td>
					<?php
                        echo JFactory::getDate($item->created_date)->format('d-M');
                        $nextDate = SfsHelperDate::getNextDate('d-M', $item->created_date);
						echo ' / '.$nextDate;
					?>
				</td>
                <td><?php echo $item->airport_code?></td>
				<td><?php echo $item->first_name .' ' . $item->last_name?></td>
				<td>
					<?php
					if( $item->hotel_phone ) :
						$toolTip_hotel ='<div class="fs-14">Hotel Phone Number<br/>'.$item->hotel_phone.'<br/><br/>Blockcode:'.$item->blockcode.'<br/>Voucher Number:'.$item->voucher_code.'</div>';
						?>
						<span class="hasTip2 underline-text" title="<?php echo SfsHelper::escape($toolTip_hotel);?>">
						<?php echo $item->hotel_name; ?>
					</span>
					<?php endif;?>
				</td>
                <td><?php echo $item->rebook[0]->carrier.$item->rebook[0]->flight_no;?></td>
				<td>
					<span <?php if($hasTip):?>class="underline-text hasTip2" title="<?php echo SfsHelper::escape($toolTip);?>"<?php endif;?>>
					<?php
					if($hasTip) {
						if($item->breakfast){ echo 'B ';} 
						?>
						<?php
						if($item->lunch) {echo 'L ';} 
						?>
						<?php
						if($item->mealplan) {echo 'D ';}						
					}else {
						echo 'No';
					}
					?>
					</span>
				</td>							
				<td>
						<?php echo $item->phone_number; ?>
				</td>

				<td colspan="2">
                <?php $list_services=''; ?>
                <?php foreach ($item->services as $key => $value): ?>
                    <img src="<?php echo $value->icon_service; ?>" style = "width:26px">
                <?php endforeach ?>
				</td>

                <td style="width:105px;">
                    <?php
                    $printLinkView = 'index.php?option=com_sfs&view=tracepassenger&layout=default_individualpassengerpage&passenger_id='.$item->passenger_id."";
                    ?>
                    <div style="padding-bottom: 10px; display: inline-block; float: right; margin-left:5px;">
                        <a class="small-button" href="<?php echo $printLinkView;?>" style="width: 50px">View</a>
                    </div>
                    <?php
                    /*
                    if( (int)$item->status != 3 ) :
                        $printLink  = 'index.php?option=com_sfs&view=tracepassenger&layout=additional_services&airplus_id='.$item->airplus_id.'&voucher_id='.$item->voucher_id.'&passenger_id='.$item->passenger_id.'&tmpl=component';
                        ?>
                        <div style="padding-bottom: 10px; display: inline-block; float: right">
                            <a class="fancybox fancybox.iframe small-button" href="<?php echo $printLink;?>" style="width: 50px">Add</a>
                        </div>
                    <?php endif;*/?>
                </td>
			</tr>
	<?php
        endforeach;
	    endif;
	?>
</table>