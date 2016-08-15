<?php
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
$this->cancel_count = 0;
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_sfs/assets/datatables/jquery.dataTables.css');
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
	.ico-3{
		margin-right:2px;
	}
	.ico-3.ico-3-last{
		margin-right:0px;
	}
	.filter-buttons{
		float:left;
		width:16px;
		padding:10px 12px;
		cursor:pointer;
	}
	.filter-button-txt{
		left: -70px;
		padding: 0;
		position: absolute;
		top: 16px;
		width: auto;
	}
	
	.filter-button-ok{
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) 0px 0px;
		
	}
	.filter-button-nok{
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) -23px 0px;
	}
	.filter-button-ok-ligth{
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) -46px 0px;
	}
	.filter-button-nok-ligth{
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) -72px 0px;
	}
	
	.filter-button-ok-def{
		margin-left:10px;
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) 0px 21px;
	}
	.filter-button-nok-def{
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) -23px 64px;
	}
	.filter-button-ok-ligth-def{
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) -46px 64px;
	}
	.filter-button-nok-ligth-def{
		background:url(<?php echo JURI::base()?>/media/system/images/accounting-2-0-updated-reports/filter_buttons.png) -72px 64px;
	}
	
	
	
	.w150{
		width:150px !important;
	}
</style>

<script src="<?php echo JURI::base()?>components/com_sfs/assets/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script>
   // jQuery.noConflict();
    var table;	
	var cl = 10;
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
                        { "width": "12%" },
                        { "width": "10%" },
                        { "width": "15%" },
                        { "width": "15%" },
                        { "width": "7%" },
                        { "width": "15%" },
                        { "width": "30%" },
                        { "width": "15%" },
						{ "width": "0%" },
                    ],
                    "preDrawCallback": function( settings ) {

                    }
                } );
			

                return data;
            }
	
            $('#DataTable thead#DataTable_header th').each( function () {
                var title = $('#DataTable thead#DataTable_header th').eq( $(this).index() ).text();
                var id = title.replace(" ", "");
				if(id != 'Showstatus:' && id != 'Services' && id != '')
                	$(this).html( '<input class="DataTable_input fontsize11" id="'+id+'" type="text" placeholder="'+title+'" />');
				else if( id == 'Services' ) {
					var str = '', left = 0;
				}
				else if( id == 'Showstatus:' ) {
					var str = '<div style="width:110px;">';
					str += '<div class="filter-buttons filter-button-txt">Show status:</div>';
					str += '<div class="filter-buttons filter-button-ok"></div>';
					str += '<div class="filter-buttons filter-button-nok"></div>';
					str += '<div class="filter-buttons filter-button-ok-ligth"></div>';
					str += '<div class="filter-buttons filter-button-nok-ligth"></div>';
					str += '</div>';
					
					$(this).html( str );
				}
				
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
				
				var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
						l = 0;
				$('.totalItems').text(l);
				
				$('.filter-button-ok').removeClass("filter-button-ok-def");
				$('.filter-button-nok').removeClass("filter-button-nok-def");
				$('.filter-button-ok-ligth').removeClass("filter-button-ok-ligth-def");
				$('.filter-button-nok-ligth').removeClass("filter-button-nok-ligth-def");
			
				
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
		
		$('.remove_filter').click(function(e) {
            $(this).css('display', 'none');
			$(this).prev('.DataTable_input').keyup();
			var ts = setTimeout(function(){
				$('.totalItems').text($('#DataTable tbody tr').length);
				clearTimeout( ts );
			}, 5);
        });
		
		$('.filter-button-ok, .accept-all').click(function(e) {
			$(this).addClass("filter-button-ok-def");
			table
			.columns( cl )
			.search( '1' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
        });
		
		$('.filter-button-nok').click(function(e) {
			$(this).addClass("filter-button-nok-def");
			
			table
			.columns( cl )
			.search( '2' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
			
        });
		
		$('.filter-button-ok-ligth').click(function(e) {
			$(this).addClass("filter-button-ok-ligth-def");
			table
			.columns( cl )
			.search( '3' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
        });
		
		$('.filter-button-nok-ligth').click(function(e) {
			$(this).addClass("filter-button-nok-ligth-def");
			table
			.columns( cl )
			.search( '4' )
			.draw();
			var l = $('#DataTable tbody tr').length;
				if ( $('#DataTable tbody tr td').hasClass('dataTables_empty') )
					l = 0;
			$('.totalItems').text(l);
        });
		
		
    });
</script>
<?php 
$reservation_id = JRequest::getInt('id', 0);
$url_comment = JURI::base()."index.php?option=com_sfs&view=airblock&tmpl=component&layout=comment&reservation_id=$reservation_id"; 
?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(function($){
	function upload( v, id){
		$.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=airblock.saveInvoiceStatus'; ?>",
			type:"POST",
			data:{'invoice_status':v, 'passenger_id':id},
			dataType: 'text',
			success:function(response){
				$('button[type="submit"]').click();
				///document.location.reload(true);
			}
		});
	}
	
	$('.ok, .ok-ligth, .nok, .nok-ligth').click(function(e) {
		var passenger_id = $(this).attr('data-id')
		var cs = $(this).attr('data-class');
		var v = 0, t = false;
		switch ( cs ){
			case"ok":
				v = '0';
			break;
			case"ok-ligth":
				v = '1';
			break;
			case"nok-ligth":
				v = '2';
			break;
		}
		
		if ( cs == 'nok' ) {
			t = true;
			alert('The following names are not corresponding to our records');
			return false;
		}
		if ( t == false)
			upload(v, passenger_id);
	});	
});
</script>
<table id="DataTable" class="airblocktable trace-passenger-table" cellspacing="0" width="100%">
    <thead id="DataTable_header">
    	<tr><td colspan="11" style="background-color:#fff; border-bottom:0px;">Filter on:</td></tr>
        <tr style="width: 100%">
        	<th style="display:none">ID</th>
        	<th style="background-color: white;">Airport</th>
            <th id="arr" style="text-align: right;background-color: white;">Date</th>
            <th style="background-color: white;">FlightN</th>
            <th style="background-color: white;">Block Code</th>
            <th style="background-color: white;">Passenger name</th>
            <th style="background-color: white;">Hotel name</th>
            <th colspan="2" style="background-color: white; width:20%; text-align:left;"></th>
            <th style="background-color: white;"></th>
            <th class="w150" style="background-color: white; width:150px;">Show status:</th>
            <th style="display:none">Search</th>
            <th style="display:none">Search</th>
        </tr>
    </thead>
    <thead>
        <tr>
        	<th style="display:none">ID</th>
            <th>Airport</th>
            <th>Date</th>
            <th>FlightN</th>
            <th>Block Code</th>
            <th>Passenger name</th>
            <th>Hotel name</th>
            <th style="width:20%;">Services</th>
            <th></th>
            <th>Total amount</th>
            <th style="width:150px;"></th>
            <th></th>
        </tr>
    </thead>
	<?php
	$pax ='';
	$t = 0;
	if(count($this->passengers)):
	$filter_lastname = JRequest::getVar('filter_lastname');
	foreach ($this->passengers as $item) :
	$t++;
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
		
		$pax = count( $item->pax ) . 'pax';
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
		
		$card_number = SfsHelper::getCardNumber( $item->card_number );
		$img_status1 = 'OK';
		$img_status2 = 'NOK_ligth';
		$input_search_status1 = '1';//==OK
		$input_search_status2 = '4';//==NOK_ligth
		$data_class1 = 'ok';
		$data_class2 = 'nok-ligth';
		if($item->invoice_status == 1) {
			$input_search_status1 = '1';//==OK
			$input_search_status2 = '4';//==NOK_ligth
		}
		if( $item->invoice_status == 2 ) {
			$input_search_status1 = '3';//==OK_ligth
			$input_search_status2 = '2';//NOK
			$img_status1 = 'OK_ligth';
			$img_status2 = 'NOK';
			$data_class1 = 'ok-ligth';
			$data_class2 = 'nok';
		}
		if($item->invoice_status == 0 ) {
			$input_search_status1 = '3';//==OK_ligth
			$input_search_status2 = '4';//==NOK_ligth
			$img_status1 = 'OK_ligth';
			$img_status2 = 'NOK_ligth';
			$data_class1 = 'ok-ligth';
			$data_class2 = 'nok-ligth';
		}
		?>
			<tr class="<?php echo $class . $input_search_class?>">
            	<td style="display:none"><?php echo $item->passenger_id;?></td>
            	<td>
				<?php echo $item->airport_code?>
                </td>
				<td>
					<?php
                        echo JFactory::getDate($item->startdate)->format('d/m/Y');
					?>
				</td>
                <td><?php echo $item->flight_number?></td><!--flight_code-->
				<td><?php echo ($item->blockcode == '' ) ? $card_number : $item->blockcode;?></td>
                <td><?php echo $item->first_name . '/' . $item->last_name;?></td>
                
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
                
				<td colspan="2">
                    <div style="display: inline-block; float: left">
                        
                        <?php
						
                        if( $item->breakfast == 1):
                            echo '<div>B</div>';
                        ?>
                        <?php endif;?>
                        <?php							
                            if($item->lunch == 1):
                                echo '<div>L</div>';
                        ?>
                        <?php endif;?>
                        <?php
                        if($item->mealplan == 1 ):
                            echo '<div>D</div>';
                        ?>
                        <?php endif;?>
                    </div>
                    
				</td>
                <td>
                	<div style="white-space: nowrap">Total expected: <?php echo $item->total_expected;?></div>
                	<div style="white-space: nowrap; display:none;">
                	Total spent: <?php echo $item->total_spent?></div>
                </td>
                <td>
                	<div style="padding-bottom: 10px; display: inline-block; float: right; margin-left:5px; width:100px;">
                        <a href="javascript:void(0);" class="ico-3 <?php echo $data_class1;?>" data-id="<?php echo $item->passenger_id;?>" data-class="<?php echo $data_class1;?>">
                        	<img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/' . $img_status1 . '.png'); ?>" alt="OK" />
                        </a>
                        <a href="javascript:void(0);" class="ico-3 <?php echo $data_class2;?>" data-id="<?php echo $item->passenger_id;?>" data-class="<?php echo $data_class2;?>">
                        	<img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/' . $img_status2 . '.png'); ?>" alt="NOK ligth" />
                        </a>
                        
                        <?php if( $item->p_comment != "" || $item->insurance != 3 || $item->touroperator_client != 3 ):?>
                        <a data-size-x="210" data-size-y="250" class="ico-3 ico-last open-popup" 
                        href="<?php echo $url_comment . '&passenger_id=' . $item->passenger_id ;?>&box=1" style="text-decoration:none;">
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/comment-26.png'); ?>" alt="comment" />
                        </a>
                        <?php else:?>
                        <a data-size-x="210" data-size-y="250" class="ico-3 ico-last open-popup"                         
                        href="<?php echo $url_comment . '&passenger_id=' . $item->passenger_id ;?>&box=1" style="text-decoration:none;">
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/comment-26-grey.png'); ?>" alt="comment" />
                        </a>
                        <?php endif;?>
                        
                    </div>
                </td>
               <td style="display:none"><?php echo $input_search_status1 . ' ' . $input_search_status2;?></td>
			</tr>
	<?php
        endforeach;
	    endif;
	?>
</table>