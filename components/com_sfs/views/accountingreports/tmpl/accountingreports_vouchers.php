<?php
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
$this->cancel_count = 0;
$airline = SFactory::getAirline();
$airport_code = $airline->airport_code;

$airplusparams= $airline->airplusparams;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_sfs/assets/datatables/jquery.dataTables.css');
$link_Img = JURI::root().'media/media/images/select-pass-icons';
$link_SubImg = JURI::root().'media/media/images';
$listImgService = array();
$data['service_id'] 	= '';
$data['icon_service'] 	= '';
foreach ($this->passengers as $value) {
	if ($value->airport_code == $airport_code || $value->airport_code_hotel == $airport_code || $airport_code =='All Airports' ) {

		$data['service_id'] 	= $value->service_id;
		$data['icon_service'] 	= $value->icon_service;
		array_push($listImgService, $data);	
		// $aa .= $value->airport_code.'</br>';
	}
}
$listImgService = array_unique($listImgService, SORT_REGULAR);
// echo "<pre>";
// print_r($aa);
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
	.remove_filter, .hidden{
		display:none;
	}
	.fontsize11{
		font-size:11px !important;
	}
	.service{
		float: right;
		width: 35px !important;
		height: 50px !important;
		background-repeat: no-repeat !important;
		background-image:url(<?php echo $link_Img.'/selected-services.png' ?>) !important;
		background-position:0 -28px;

	}
	.service.service-hotel{
		background-position: 0px 0px;
	    height: 30px!important;
	    margin-top: 10px;
	    margin-right: 2px;
	}
	#service-taxi{
		background-position:-22px -25px;
	}
	.service-taxi{
		background-position: 0px -117px !important;
	    height: 34px!important;
	    margin-top: 8px;
	    margin-bottom: 8px;
	}
	.service.service-refreshment{
		background-position: -2px -35px !important;
	    height: 38px!important;
	    margin-top: 6px;
	    margin-bottom: 6px; 
	}
	.service.service-bus-transfer{
		background-position: -2px -78px !important;
	    height: 35px!important;
	    margin-top: 7px;
	    margin-bottom: 8px; 
	}
	.service.service-train{
		background-position: -2px -155px !important;
	    height: 36px!important;
	    margin-top: 7px;
	    margin-bottom: 7px;
	}
	.service.service-rental-car{
		background-position:-2px -188px !important; 
	}
	.service.service-other{
		background-position:-2px -235px !important 
	}
	.service_maas{
		background-image: url(<?php echo $link_SubImg.'/maas.png' ?>) !important;
	}
	.service_waas{
		background-image: url(<?php echo $link_SubImg.'/maas.png' ?>) !important;
	}
	.service_Snackbags{
		background-image: url(<?php echo $link_SubImg.'/snackbag.png' ?>) !important;
	}
	.service_Phonecards{
		background-image: url(<?php echo $link_SubImg.'/phonecard.png' ?>) !important;

	}
	.service_Cash{
		background-image: url(<?php echo $link_SubImg.'/cash-payment.png' ?>) !important;

	}
	.w150{
		width:150px !important;
	}

	.SearchService img{
		width: 24px;
		padding-left: 2px;
		padding-right: 2px;
	}
	tbody .total_service input{
		width:50px !important;
		margin-left:3px !important; 
		padding: 0px !important;
		height: 25px !important;

	}
</style>

<script src="<?php echo JURI::base()?>components/com_sfs/assets/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script>
   // jQuery.noConflict();
    var table;	
    jQuery(function($){
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
						{ "width": "1%" },
                        { "width": "7%" },
                        { "width": "20%" },
                        { "width": "4%" },
                        { "width": "15%" },
                        { "width": "7%" },
                        { "width": "7%" },
                        { "width": "10%" },
                        { "width": "11%" },
                        { "width": "15%" },
						{ "width": "7%" },
						{ "width": "1%" },
                    ],
                    "preDrawCallback": function( settings ) {

                    }
                } );
			

                return data;
            }

            $('#DataTable thead#DataTable_header th').each( function () {
                var title = $('#DataTable thead#DataTable_header th').eq( $(this).index() ).text();
                var id = title.replace(" ", "");
				if( id != 'Services' && id != 'SearchService' && id != '' )
                	$(this).html( '<input class="DataTable_input fontsize11" id="'+id+'" type="text" placeholder="'+title+'" />');
				else if( id == 'Services' ) {
					
					var str = '', left = 0;
					
					 <?php if($airplusparams['taxi_enabled']): ?>
                  str += '<img class="img-taxi" src="<?php echo JURI::base(true)."/media/system/images/airplus/taxi-icon.png"?>" style="right:0px; left:' + left + 'px;">';
				  left += 27;
				  <?php else:?>
					<?php endif;?>
					<?php if($airplusparams['meal_enabled'] ): ?>
						str += '<img class="img-mealplan" src="<?php echo JURI::base(true)."/media/system/images/airplus/mealplan-icon.png"?>" style="right:0px; left:' + left + 'px;">';
					left += 27;
                     <?php else:?>
					 left += 0;
					 <?php endif;?>
					
					<?php if($airplusparams['cashreim_enabled'] ): ?>
						str += '<img class="img-cash" src="<?php echo JURI::base(true)."/media/system/images/airplus/cash-icon.png"?>" style="right:0px; left:' + left + 'px;">';
						left += 27;
                     <?php else:?>
					 left += 0;
					 <?php endif;?>
                    <?php if($airplusparams['telcard_enabled']): ?>
                  str += '<img class="img-telephone" src="<?php echo JURI::base(true)."/media/system/images/airplus/telephone-icon.png"?>" style="right:0px; left:' + left + 'px;">';
                   <?php endif;?>
					$(this).html( str );
				}
				// else if(id == 'Pax'){
				// 	$(this).html( '<input class="DataTable_input fontsize11" id="'+id+'" type="text" placeholder="'+title+'" />');
				// }
				else if( id == 'SearchService'){
					var str = '<div class = "SearchService" style="width:100%;">';

					<?php 
						$i = 0;
					?>

					<?php foreach ($listImgService as $key => $value): ?>
						<?php $src = $value['icon_service']; $val = $value['service_id']; ?>

						<?php if ($i < count($listImgService)/2): ?>

							<?php 
								$i++ ; $top = -15; $right = ($i * 25) +2;
							?>
							str += '<img class="active" src="<?php echo $src; ?>" value = "<?php echo $val; ?>" style = "top:<?php echo $top.'px'; ?>; right:<?php echo $right.'px'; ?>">';

						<?php else: ?>

							<?php 
								$i++;  $right = (($i-4) * 25) +2; 
							?>	
							str += '<img class="active" src="<?php echo $src; ?>" value = "<?php echo $val; ?>" style = " right:<?php echo $right.'px'; ?>">'

						<?php endif ?>
						
	            	<?php endforeach; ?>
					str += '</div>';
					$(this).html( str );
				}
            } );

              //begin Filter Service
       //      var checkInputIsNull = function () {
       //      	var $result = false;
       //      	$('.DataTable_input').each(function() {
       //      		$result  =	$result || ($(this).val() != '');
       //      	});
       //      	return !$result;
       //      }
            	
       //      $("input").keyup( function (e) {
       //      	if(checkInputIsNull()){
       //      		$('#DataTable_wrapper tr').removeClass('activeFilter').show();
       //      	}

       //       	var val 	= $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
       //       	var idName	= $(this).attr('id');
       //       	var $rows 	= $('#DataTable_wrapper .'+idName);
             	
       //       	if($rows.parent('tr').hasClass('activeFilter'))
       //       	{
       //       		$rows = $('#DataTable_wrapper .activeFilter .'+idName);
       //       		// alert(1);
       //       	}


			    // $rows.parent('tr').show();    
			    

			    // $rows.show().filter(function() {
			        
			    //     var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
			    //     return !~text.indexOf(val);

			    // }).parent('tr').addClass('activeFilter').hide();  


       //      });
            
            //end filter service

            // Apply the search
            table.columns().every( function () {
                var that = this;
                $("input", this.header()).keyup( function (e) {
                	// console.log(this.value );
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
                    $(this).hide();
					$(this).prev('.DataTable_input').keyup();
					$('.totalItems').text($('#DataTable tbody tr').length);
                }
            });

            $("body").on("click", "a.remove_filters_all" , function() {
                $('.DataTable_input').val("");
                table.search( '' ).columns().search( '' ).draw();
            });

           /// $('#arr').html('Filter on:');
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
	
		$('.DataTable_input').keyup(function(e) {
            var v = $(this).val();
			if ( v != '' ) {
				$(this).next('img').css('display', 'block');
				
				var ts = setTimeout(function(){
					var l = $('#DataTable tbody tr').length;
					if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
						l = 0;
					$('.totalItems').text(l);
					clearTimeout( ts );
				}, 5);
			
			}
			else {
				$(this).next('img').css('display', 'none');
				var ts = setTimeout(function(){
					$('.totalItems').text($('#DataTable tbody tr').length);
					clearTimeout( ts );
				}, 5);
			}
        });
		
		// $('.remove_filter').on('click',function(e) {
  //           $(this).hide();
		// 	$(this).prev('.DataTable_input').keyup();

		// 	var ts = setTimeout(function(){
		// 		$('.totalItems').text($('#DataTable tbody tr').length);
		// 		clearTimeout( ts );
		// 	}, 5);
  //       });
		
		$('.img-taxi').click(function(e) {
			table
			.columns( 11 )
			.search( 'taxi' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
        });
		
		$('.img-mealplan').click(function(e) {
			table
			.columns( 11 )
			.search( 'mealplan' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
        });
		
		$('.img-cash').click(function(e) {
			table
			.columns( 11 )
			.search( 'cash' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
        });
		
		
		$('.img-telephone').click(function(e) {
			table
			.columns( 11 )
			.search( 'telephone' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
        });
		
		//filter service

		$('.SearchService img').on('click', function() {
			var val 	= $(this).attr('value');
			var active 	= $(this).hasClass('active');
			if(active){
				
				$(this).css('opacity', '0.5').removeClass('active');
			}else{
				$(this).css('opacity', '1').addClass('active');
			}
			$('.service_index_'+val).toggle();
			// alert(val);
		});
		

		//begin CPhuc
		function isNumber(evt, element) {
			var charCode = (evt.which) ? evt.which : event.keyCode;

			if ((charCode != 46 || $(element).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
				return false;

			return true;
		} 


		$('tbody .total_service input').keypress(function (event) {
			return isNumber(event, this)
		});
		//end CPHuc
    });
</script>
<table id="DataTable" class="airblocktable trace-passenger-table" cellspacing="0" width="100%">
    <thead id="DataTable_header">
    	<tr><td colspan="11" style="background-color:#fff; border-bottom:0px;">Filter on:</td></tr>
        <tr style="width: 100%">
        	<th style="display:none">ID</th>
        	<th style="background-color: white;">Airport</th>
            <th  id="arr" style="text-align: right;background-color: white;width:42%">Date</th>
            <th style="background-color: white;">FlightN</th>
            <th style="background-color: white;">Block Code</th>
            <th style="background-color: white;">PNR</th>
            <th style="background-color: white; width:50px;">Pax Name</th>
            <th style="background-color: white;">Hotel name</th>
            <th  style="background-color: white; width:20%; text-align:left;">Services</th>
            <th  style="background-color: white; width:20%; text-align:left;">Search Service</th>
           	<th style="display:none"></th>
            <th style="display:none">Search</th>
        </tr>
    </thead>
    <thead>
        <tr>
        	<th style="display:none">ID</th>
            <th>Airport</th>
            <th >Date</th>
            <th>FlightN</th>
            <th>Block Code</th>
            <th>PNR</th>
            <th style="width:50px;">Pax</th>
            <th>Hotel name</th>
            <th style="width:20%;">Services</th>
            <th></th>
            <th></th>
            
        </tr>
    </thead>
	<?php
	$pax ='';
	if(count($this->passengers)):
	$filter_lastname = JRequest::getVar('filter_lastname');
	foreach ($this->passengers as $item) :
	//print_r( $item );
		 if( (int)$item->status >= 3 ) {
		 	$this->cancel_count++;
		 	continue;		 	
		 }
		 
		 $toolTip = '';
		 $hasTip  = false;
		 $toolTip = '<table class="tooltiptable"><tr><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>';

		 $toolTip .= '<tr><td>';
		 if($item->breakfast){
		 	$hasTip = true;	
		 	$toolTip .='Yes';					 	
		 } else {
		 	$toolTip .='No';
		 }						 
		 $toolTip .= '</td><td>';
		 if($item->lunch){
		 	$hasTip = true;		
		 	$toolTip .='Yes';				 	
		 } else {
		 	$toolTip .='No';
		 }					
		 $toolTip .= '</td><td>';		
		 if($item->mealplan){
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
		
		/*$value_hotel = ($item->sd_room * floatval( $item->sd_rate) ) + 
							($item->t_room * floatval( $item->t_rate ) ) +
							($item->s_room * floatval( $item->s_rate ) ) +
							($item->q_room * floatval( $item->q_rate ) ) ;*//*+ 
							floatval($item->amount_meal) + 
							floatval($item->amount_taxi);*/
		
		
		$pax ='Group('.count( $item->pax ) . ' pax)';
		$toolTip_pax = '';
		$toolTip_pax = '<div>Passenger names</div>';
		$taxi_value = 0;
		$meal_value = 0;
		$airplus_mealplan = 0;
		$airplus_taxi = 0;
		$airplus_cash = 0;
		$airplus_phone = 0;
		$airplus_phone = 0;
		$card_number_meal = '';
		$card_number_taxi = '';
		$cash_card_number = '';
		$phone_card_number = '';
		
		foreach( $item->pax as $vk => $v ){
			
			if( (int)$item->voucher_id > 0 ) { // truong hop booking hotel
				$meal_value = $v->meal_value;
				$taxi_value = $v->taxi_value;
			}
			
			/*if( (int)$item->voucher_id > 0 ) { // truong hop booking hotel
				$meal_value += $v->meal_value;
				$taxi_value += $v->taxi_value;
			}
			else {
				$meal_value = $v->meal_value;
				$taxi_value = $v->taxi_value;
			}*/
			$airplus_mealplan = $v->airplus_mealplan;
			$airplus_taxi = $v->airplus_mealplan;
			$card_number_meal = SfsHelper::getCardNumber( $v->card_number_meal );
			$card_number_taxi = SfsHelper::getCardNumber( $v->card_number_taxi );
			$airplus_cash = $v->airplus_cash;
			$card_number = $v->card_number;
			$airplus_phone = $v->airplus_phone;
			$airplus_phone = $v->airplus_phone;
			$phone_card_number = $v->phone_card_number;
			$toolTip_pax .= '<div style="padding-left:30px;">' . $v->first_name . ' ' . $v->last_name . '</div>';
		}
		///$value_hotel += $taxi_value + $meal_value;
		//print_r($item).
		$input_search_class = '';
		if($airplusparams['taxi_enabled'] && $item->airplus_taxi == 1){
			$input_search_class = 'taxi ';
		}
		if($airplusparams['meal_enabled'] && $item->airplus_mealplan == 1){
			$input_search_class .= 'mealplan ';
		}
		if($airplusparams['cashreim_enabled'] && $item->airplus_cash == 1 ){
			$input_search_class .= 'cash ';
		}
		if($airplusparams['telcard_enabled'] && $item->airplus_phone){
			$input_search_class .= 'telephone ';
		}
		
		$card_number = SfsHelper::getCardNumber( $item->card_number );
		?>
		<?php if ($item->airport_code == $airport_code || $item->airport_code_hotel == $airport_code || 
		$airport_code =='All Airports' ): ?>
			
			<tr class="<?php echo $class . $input_search_class?> <?php echo 'service_index_'.(int)$item->service_id; ?> " value = "<?php echo (int)$item->service_id; ?>">
            	<td style="display:none"><?php echo $item->passenger_id;?></td>
            	<td class="Airport">
	            	<?php if ((int)$item->service_id == 1): ?>
						<?php echo $item->airport_code_hotel; ?>
					<?php elseif((int)$item->service_id != 1): ?>
						<?php echo $item->airport_code; ?>
					<?php endif ?>
                </td>
				<td class="Date">
				
					<?php
                        echo JFactory::getDate($item->startdate)->format('d-m-Y');
                       // $nextDate = SfsHelperDate::getNextDate('d-M', $item->blockdate);
						//echo ' / '.$nextDate;
					?>
					
				</td>
                <td class="FlightN"><?php echo $item->flight_no?></td><!--flight_code-->
				<td class="BlockCode">
					<?php if ((int)$item->service_id == 1): ?>
						<?php echo ($item->blockcode == '' ) ? $card_number : $item->blockcode;?>
					<?php elseif((int)$item->service_id != 1): ?>
						<?php echo $item->block_code; ?>
					<?php endif ?>
					

				</td>
                <td class="PNR"><?php echo $item->url_code?></td>
                <td colspan="" class="PaxName">
                <!--Begin block( don't remove this block comment) -->
					<!-- <small>
	                	<?php //$i = 1 ?>
						<?php //foreach ($item->services as $key => $value): ?>

							<p value = '<?php //echo $value->service_id; ?>' style = 'margin: 0px'>
								<?php //echo $i.' '.$value->name_service; $i++?>
							</p>

						<?php //endforeach ?>
						
					</small> -->
		               
	            <!-- End block -->
	            	
	        		<?php  echo $item->first_name.' '.$item->last_name; ?>
	        		<!-- <span class="hasTip2 underline-text" title="<?php //echo SfsHelper::escape($toolTip_pax);?>">
	                <?php //echo $pax?> 
	            	</span> -->
	            	
                </td>
				<td class="Hotelname">
					<?php
					if( $item->hotel_phone && $item->service_id == 1) :
						$toolTip_hotel ='<div class="fs-14">Hotel Phone Number<br/>'.$item->hotel_phone.'<br/><br/>Blockcode:'.$item->blockcode.'<br/>Voucher Number:'.$item->voucher_code.'</div>';
						?>
						<span class="hasTip2 underline-text" title="<?php echo SfsHelper::escape($toolTip_hotel);?>">
						<?php echo $item->hotel_name; ?>
					</span>
					<?php endif;?>
				</td>
                
				<td > 
				<div>
					
	                <?php $list_services='';
					$service_bus_transfer='';
					$service_train='';
					$service_rental_car='';
					$service_other='';
					$input_search_class_taxi= '';
					$service_maas ='';
					if(count($item->services)>0){
							$list_services.=$item->service_id.',';
							echo '<img src="'.$item->icon_service.'" alt="">';
							
					}
					?>
					<input type="hidden" id="pass-service-<?php echo $item->passenger_id; ?>" value="<?php echo $list_services; ?>" >
	            	<div class="" style="float: right;">
	                </div>
				</div>
                    
				</td>
                <td class="total_service">
                	<?php if ($item->service_id == 1): ?>
	                	<div style="white-space: nowrap">Total expected: <?php echo $item->total_expected;?></div>
	                	<div style="white-space: nowrap">
	                	Total spent:<!-- <input type="text" maxlength="10"> --></div>
                	<?php else: ?>
                		<?php if (!empty($item->block_code)): ?>
	                		<div style="white-space: nowrap">Total expected: <?php echo $item->price_per_person;?></div>
		                	<div style="white-space: nowrap">
		                	Total spent:<!-- <input type="text" maxlength="10"> --> </div>
                		<?php endif ?>
                	<?php endif ?>
                </td>
                <td style="width:105px;">
                     <?php
                    $printLinkView = 'index.php?option=com_sfs&view=tracepassenger&layout=default_individualpassengerpage&passenger_id='.$item->passenger_id;
                    ?>
                    <div style="padding-bottom: 10px; display: inline-block; float: right; margin-left:5px;">
                        <a class="small-button" href="<?php echo $printLinkView;?>" style="width: 90px">View Detail</a>
                    </div>
                </td>
               <td style="display:none"><?php echo $input_search_class;?></td>
			</tr>
		<?php endif ?>
	<?php
        endforeach;
	    endif;
	?>
</table>