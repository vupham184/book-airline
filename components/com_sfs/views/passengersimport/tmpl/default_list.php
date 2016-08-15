<?php
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
$this->cancel_count = 0;
$airline = SFactory::getAirline();
$airplusparams= $airline->airplusparams;
$airline_current = SAirline::getInstance()->getCurrentAirport();
$code = $airline_current->code;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_sfs/assets/datatables/jquery.dataTables.css');
$document->addStyleSheet(JURI::base().'components/com_sfs/assets/css/createGroup.css');
/*begin code CPhuc*/
	$link_Img = JURI::root().'media/media/images/select-pass-icons';
	$link_SubImg = JURI::root().'media/media/images';
/*end code CPhuc*/
$list_pass_active='';
if(JRequest::getString('pass_issue_hotel')){
	$list_pass_active = explode("_",JRequest::getString('pass_issue_hotel'));	
}	
// echo "<pre>";
// print_r($this->passengers);
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
    .page_passenger_import table.dataTable thead th img{
        position: absolute;
        right:9px;
        top:28px;
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
	.subservice{
		float: right;
		width: 18px !important;
		height: 25px !important;
		background-repeat: no-repeat !important;
	}

	.service.service-hotel{
		background-position: 0px;
	    margin-right: 2px;
	    background-image:url(<?php if($this->services[0]) echo JURI::base().$this->services[0]->icon_service; ?>) !important;
	}
	#service-taxi{
		background-position:-22px -25px;
	}
	.service-taxi{
		background-position: 0 !important;
	    height: 33px!important;
	    margin-top: 8px;
    	margin-bottom: 9px;
	    background-image: url(<?php if($this->services[3]) echo JURI::base().$this->services[3]->icon_service; ?>) !important;
	}
	.service.service-refreshment{
		background-position: 0 !important;
	    height: 38px!important;
	    margin-top: 6px;
	    margin-bottom: 6px;
	    background-image: url(<?php if($this->services[1]) echo JURI::base().$this->services[1]->icon_service; ?>) !important;
	}
	.service.service-bus-transfer{
		background-position: 0 !important;
	    height: 35px!important;
	    margin-top: 7px;
	    margin-bottom: 8px;
	    background-image: url(<?php if($this->services[2]) echo JURI::base().$this->services[2]->icon_service; ?>) !important;
	}
	.service.service-train{
		background-position: 0 !important;
	    height: 36px!important;
	    margin-top: 7px;
	    margin-bottom: 7px;
	    background-image: url(<?php if($this->services[4]) echo JURI::base().$this->services[4]->icon_service; ?>) !important;
	}
	.service.service-rental-car{
		background-position:0 !important; 
		background-image: url(<?php if($this->services[5]) echo JURI::base().$this->services[5]->icon_service; ?>) !important;
	}
	

	.add-services-list{
		width: 44px !important;
		background-image:url(<?php echo $link_Img.'/icon-add-service.png' ?>) !important;
    	background-position:0px 3px !important;
    	background-color: #FFFFFF !important;
    	margin-left:1px;
	}
	.addService{
		background-position: 0 0; 
	}
	.clear-fix{
		clear: both;
	}
	.service-add{
		cursor:pointer;
	}
	.tooltip-s{
		border:2px solid #ff8806;
		display:none;
		position:absolute;
		background-color:#ffefbf;
		width:250px;
		height:160px;
		z-index:10;
	}
	.tooltip-content{
		padding:7px;
	}
	.has-Tip{
		position:relative;
		cursor:pointer;
	}
	.add-comment{
		cursor:pointer;
	}
	.tooltip-content p{
		margin:0px;
	}
	.tbutton{
		vertical-align:bottom;
	}
	.select-dep{
		padding: 0;
		margin-bottom: 5px;
		height: 20px;
		width: 90px;
		font-size: 12px;
	}
	.departed{
		width: 20px !important;
		height: 20px !important;
		background-repeat: no-repeat !important;
		background-image:url(<?php echo $link_Img.'/icon_depart.png' ?>) !important;
	}
	.time-deplay{
		-webkit-appearance: none;
    	-moz-appearance: none;
    	appearance: none;
	    border: medium none;
	    color: red;
	    font-weight: bold;
	    width: 30px;
	}
	#DataTable td{
		padding: 8px 2px;
	}
	#service-rebook{
		float: right;
	    width: 35px;
	    height: 35px;
	    margin-right: 2px;
	    margin-top: 9px;
	    margin-bottom: 9px;
	}
	#service-rebook.service-rebook
	{		
		background-repeat: no-repeat !important;
		background-image:url(<?php echo $link_Img.'/rebooked_icon.png' ?>);	
	}
	.content-line{
		position: relative;
		padding: 0;
		margin: 0;
	}
	.sfs-white-wrapper,.sfs-main-wrapper{
		overflow:initial;
	}
	.col-p-n{
		width: 100px;
	}
	.DataTable_input.fontsize11{
		padding: 0;
	}
	.lb-th{
		clear: both;;
		width: 100%;
		min-height: 20px;
	}
	.comment-passenger .tooltip-s{
		height: auto!important;
	}
	.DataTable_input.fontsize11{
		height: 45px;
	}
	.content-passenger-import .issue-voucher-passenger{
		font-size: 12px!important;
	}
	.content-passenger-import span{
		color: #000;
	}
	.has-Tip.name-passenger .tooltip-s{
		width: auto;
	    height: auto;
	    left: 80px!important;
	}
	.inbound-connections{
	    position: absolute;
	    width: 140px;
	    z-index: 1;
	    left: 29px;
	}
	.outbound-connections{
	    position: absolute;
	    width: 140px;
	    z-index: 1;
	    left: 29px;
	}
	.list-services-add .service  .tooltip-s{
		left: 125px!important;	
		height: 35px;	
		width: 150px;
	}
	.comment-passenger .tooltip-s{
		left: 45px!important;
		width: 600px;
	}
	.icons .name_service{
		border: 2px solid #FF8806;
		width: 130px;
		display: none;
		background-color: #ffefbf;
		position: absolute;
		min-height: 50px;

	}
	.icons .name_service span{
		padding: 5px;
	}
</style>

<script src="<?php echo JURI::base()?>components/com_sfs/assets/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script>
   // jQuery.noConflict();
    var table;	
    var group_all=[];
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
                    	{ "width": "15px" },
                        { "width": "100px" },
                        { "width": "48px" },
                        { "width": "15px" },
                        { "width": "20px" },
                        { "width": "58px" },
                        { "width": "50px" },
                        { "width": "70px" },
						{ "width": "45px" },
                        { "width": "55px" },
                        { "width": "30px" },
                        { "width": "20px" },
						{ "width": "110px" },
						{ "width": "30px" },
                    ],
                    "preDrawCallback": function( settings ) {

                    }
                } );
			

                return data;
            }

            $('#DataTable thead#DataTable_header th').each( function () {
                var title = $('#DataTable thead#DataTable_header th').eq( $(this).index() ).text();
                var id = title.replace(" ", "");
                var label='';  
                //alert(id);              
				if(
				id != 'Pax' && id != 'Services' 
				&& id != '' && id != 'All' 
				&& id != 'Date' 
				&& id != 'Std'
				&& id != 'Eta' 
				&& id != 'Group'
				&& id != "Delay_time_status" && id != "show_departed" && id!='Search' && id!='Comment'){		
					if(id=='Passengername'){
						label='<div class="lb-th">Passenger name</div>';
					} else if(id=='PNR'){
						label='<div class="lb-th">PNR</div>';
					}else if(id=='DWN'){						
						label='<img style="position:initial;margin-bottom:8px;" src="<?php echo $link_Img.'/alert-small.png'; ?>" alt="">';
					}else{label='<div class="lb-th"></div>';}			
                	$(this).html(label+'<div style="position:relative;"><input style="padding-left:3px;padding-right:3px" class="DataTable_input fontsize11" id="'+id+'" type="text" placeholder="'+title+'" /></div>');
				}
				else if(id=='Comment'){
					$(this).html('<div class="lb-th">Internal<br/>Comment</div>');
				}
				else if(id=='Delay_time_status'){
					$(this).html('<div class="lb-th">Delay time / status</div>');
				}
				else if(id=='show_departed'){
					$(this).html('<input type="checkbox" name="show_departed" id="show_departed" /><br/>Show Departed');	
				}
				else if(id=='Search'){
					$(this).html('<div style="width:80px"><input type="radio" name="search_ra" value="Open"> Open<br>  <input type="radio" name="search_ra" value="female"> Assigned<br><input type="radio" name="search_ra" value="other"> Show All</div>');
				}
				else if(id=='Date'){
					$(this).html('Date');
				}
				else if( id == 'Services' ) {
					
					$(this).html( "Services" );
				}
				else if(id == 'All'){
					$(this).html('<span style="color: #000;">All</span><br/><input style="float:right;" type="checkbox" id="all" />');
					
				}
				else if(id != 'Std' || id != 'Eta' || id != 'Group' ) {
					$(this).html( '&nbsp;' );
				}
				else if( id == "show_departed" ){
					$(this).html( '&nbsp;' );
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
            });

           /// $('#arr').html('Filter on:');
            $('#mealplan, #phone_number, #voucher, #taxi').html('');

            var c = 1;
            $('.DataTable_input').each( function () {
                $(this).attr('columns',c);
                $(this).after('<img href=\"javascript:void(0);\" class=\"remove_filter\" id='+$(this).attr('id')+' columns='+c+' src="<?php echo JURI::base()?>components/com_sfs/assets/images/icon_image_close.png"/>');
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
				//$('.add-linked').css('display','none');
				//$('.span-linked').css('display','none');
				jQuery('.add-linked .content-line').html('');
				jQuery('.span-linked').remove();
				createlistgroup();
			}
			else {
				$(this).next('img').css('display', 'none');
				var ts = setTimeout(function(){
					$('.totalItems').text($('#DataTable tbody tr').length);
					clearTimeout( ts );
				}, 5);
				jQuery('.add-linked .content-line').html('');
				jQuery('.span-linked').remove();
				createlistgroup();
				//$('.add-linked').css('display','block');
				//$('.span-linked').css('display','block');
			}
        });
		
		$('.remove_filter').click(function(e) {
            $(this).css('display', 'none');
			$(this).prev('.DataTable_input').keyup();
			var ts = setTimeout(function(){
				$('.totalItems').text($('#DataTable tbody tr').length);
				clearTimeout( ts );
				jQuery('.add-linked .content-line').html('');
				jQuery('.span-linked').remove();
				createlistgroup();
			}, 5);
        });
		
		
		$('.icons img').hover(function(e) {
			var position = $(this).position();
			var t = position.top + 30;
			var l = position.left + 40;
			$(this).next('.name_service').css({
				'display': 'block',
				'top' : t,
				'left' : l,
				'z-index' : 10 

			});
        }, function(){        	
			$('.icons .name_service').css('display', 'none');
		});

		$('.list-services-add .service-rebook').hover(function() {
			var position = $(this).position();
			var t = position.top + 30;
			var l = position.left + 40;
			$(this).prev('.tooltip-s').css({
				'display': 'block',
				'top' : t,
				'left' : l,
				'z-index' : 10 

			});
		}, function() {
			$('.tooltip-s').css('display', 'none');
		});

		$("#show_departed").change(function() {
		    if(this.checked) {
		    	$('.departed').css('display','block');    
		    }
		    else{
		    	$('.departed').css('display','none');    	
		    }
		});
		$('#sortby').change(function(){	
			this.form.submit();
		});		
		if ($) {
		var timeout = 200;
	}
	setTimeout(function(){
	var count_row = $('.trContent').length + 1;	
	$('.add-linked').css( "top",$('.content-passenger-import').offset().top-$('.add-linked').offset().top );
	$('.add-linked').prepend('<div class="content-line" style="height:'+$('.content-passenger-import').height()+'px"></div>');
	
	<?php 
					$group = $this->passengers['group'];
					$i=1;
					$group_search='';
					if(JRequest::getVar('sortby')==''){
						foreach ($group as $key => $value) {
							//$r = rand(128,255); 
					       	//$g = rand(128,255); 
	       					//$b = rand(128,255); 
	       					$group_search.='var group_search'.$i.'=['.$i.'];';
	       					//$fu_add.="addLinked('linked-passengers.png','rgb(".$r.",".$g.",".$b.")',group_search".$i.");";
	       					$fu_add.="addLinked('linked-icon.png','#D4D4D5',group_search".$i.");";
							echo 'var group'.$i.'=['.$i.','.implode(',',$value).'];';
							//echo "addLinked('linked-passengers.png','rgb(".$r.",".$g.",".$b.")',group".$i.");";
							echo "addLinked('linked-icon.png','#D4D4D5',group".$i.");";
							$i++;
						}
					}
					
				?>			
	
	$('.create-group').on('click',function(){
		addLinked('linked-passengers.png','#42B7BE');
	});
	$("#all").change(function () {
    	$('.content-passenger-import input[type="checkbox"]').prop('checked', $(this).prop("checked"));
	});
	

}, timeout);
    });
function createlistgroup(){
	//var group_search=[];	
	<?php 
		echo $group_search;			
	?>	
	jQuery(".content-passenger-import [type=checkbox]").each(function() {    	
    	<?php 
    		$i=1;
			foreach ($group as $key => $value) {
				?>
				if(group_search<?php echo $i;?>[0]==jQuery(this).attr('data-group')){					
					group_search<?php echo $i;?>.push(jQuery(this).attr('id'));
				}
				<?php
				$i++;
			}
    	?>
	});
	<?php echo $fu_add;?>
}

	function addLinked(img,colorlink,arr){		
		var id_input=[];
		var lengthArray;
		var i;		
		if (arr != null) {
			//console.log(arr);
			id_input = arr;
			lengthArray = id_input.length;
			jQuery('#'+id_input[0]).find('img').remove();
			jQuery('#'+id_input[0]).remove();
			if(arr.length>2){
				jQuery('.add-linked .content-line').append('<div id='+id_input[0]+' class="group-icon"  style=" width:2px "></div>');			
				/*if(id_input[0]!='temp' && id_input[0]!=1){
					var wd=(parseInt(id_input[0])-1)*10+10;
					var lft=(parseInt(id_input[0])-1)*10+2;
				}*/	
				for (i = 1; i < lengthArray  ; i++) {
					//jQuery('#'+id_input[i]).before('<span class="span-linked" style="background-color:'+colorlink+';width:'+wd+'px;left:-'+lft+'px;" ></span>');	
					var span_top = jQuery('#'+id_input[i]).offset().top - jQuery('#'+id_input[i]).parents('tr').offset().top + 8;	

					jQuery('#'+id_input[i]).before('<span class="span-linked" style="background-color:'+colorlink+';width:5px;top:'+span_top+'px;" ></span>');							
					jQuery('#'+id_input[i]).attr('data-group',id_input[0]); 
				}		
			}
			
		}
		else{
			id_input[0] = 'temp';

			jQuery('#'+id_input[0]).remove();
			jQuery('.add-linked .content-line').append('<div id='+id_input[0]+' class="group-icon"  style=" width:2px "></div>');
			
			jQuery('tbody input:checked').each(function() {
				id = jQuery(this).attr('id');
				id_input.push(id);
				jQuery('.'+id).remove();
				var id_span='';
				if(colorlink=='#D4D4D5'){
					id_span=' id="id_check_'+id+'"';
					if(jQuery('#id_check_'+id).length>0)
					{
						jQuery('#id_check_'+id).remove()
					}
					//jQuery(this).parent().find('span').remove();	
				}											
				jQuery(this).attr('data-group',id_input[0]); 
				jQuery(this).before('<span class="span-linked" '+id_span+' style="background-color:'+colorlink+';top:'+span_top+'" ></span>');
			});
		}		
		var length_arr = id_input.length;
		var first_positison = jQuery('#' + id_input[1]);
		var last_positison 	= jQuery('#' + id_input[length_arr -1]);
		var first_input = jQuery(first_positison).offset();
		var last_input  = jQuery(last_positison).offset();
		var position_add_linked = jQuery('.content-passenger-import').offset();
		if (first_input != null && last_input != null) {
			var height_div = parseInt(last_input.top) - parseInt(first_input.top);
			var top_div	   	= parseInt(parseInt(first_input.top) - parseInt(position_add_linked.top))+8;			
		}
		var right=0;
		/*if(id_input[0]!='temp' && id_input[0]!=1){
			var right=(parseInt(id_input[0])-1)*10;
		}	*/	
		jQuery('#'+id_input[0]).css({
			'position': 'absolute',
			'background-color': colorlink,
			'height': height_div+'px',
			'top'	: top_div+'px',
			'width'	: '2px',
			'right'	: right+'px'
		});
		var distance = 0;
		for (i=1 ; i < length_arr; i++) {
			var id_one = '#'+id_input[i];
			var id_two  = '#'+id_input[i+1];
			if (id_two != '#undefined') {
				var top_first = jQuery(id_one).offset().top;
				var top_last = jQuery(id_two).offset().top;
				var top_position = (( parseInt(top_last) -  parseInt(top_first)))/2 - 14 +distance;
				distance = distance + parseInt(top_last) - parseInt(top_first);
				jQuery('#'+id_input[0]).prepend('<img class="img-linked '+id_input[i]+'" src="templates/sfs_j16_hdwebsoft/images/'+img+'" style="top:'+top_position+'px" alt="">');
			}
		}
	}

</script>
<div class="page_passenger_import" style="position: relative;">
<div class="add-linked" style="position: absolute;"></div>
<table id="DataTable" class="airblocktable trace-passenger-table" cellspacing="0" width="100%">
    <thead id="DataTable_header">
    	<tr><td colspan="14" style="background-color:#fff; border-bottom:0px;">Filter on: <span>Today <?php echo date('Y-m-d') ?></span></span></td></tr>
		<tr style="background: #dddddd;"><td colspan="14" style="background-color:#dddddd; border-bottom:0px;text-align: right;">
					Sort page on <select name="sortby" id="sortby" style="width: 100px;width: 125px;height: 35px;border: 1px solid #ccc;background: #f0f8ff;">
				            	<option value="name" <?php if(JRequest::getVar('sortby')=='name') echo 'selected' ?>>Name</option>
				                <option value="date" <?php if(JRequest::getVar('sortby')=='date') echo 'selected' ?>>Date</option>
				                <option value="flightn" <?php if(JRequest::getVar('sortby')=='flightn') echo 'selected' ?>>Flightnumber</option>
				                <option value="std" <?php if(JRequest::getVar('sortby')=='std') echo 'selected' ?>>STD</option>
				                <option value="atd" <?php if(JRequest::getVar('sortby')=='atd') echo 'selected' ?>>ATD</option>				                
				            </select>
				
				<div class="filter-table"></div>
				</td></tr>
        <tr style="width: 100%">       	
        	<th >All</th>
        	<th class="col-p-n" >Passenger name</th>
            <th id="arr" style="text-align: center;">DWN</th>
            <th >PNR</th>
            <th id="arr" style="text-align: right;">Date</th>            
            <th id="arr" style="text-align: right;width:40px;">Std</th>
            <th style="padding-right:0; " >Dep Airp</th>
            <th style="padding-left:0;padding-right:0; " >FlightN</th>
            <th style="padding-left:0;">Arr Airp</th>
            <th id="arr" style="text-align: right;width:40px;">Eta</th>
            <th >Delay_time_status</th>
            <th >show_departed</th>            
            <th style="text-align:center;">Services</th><!--id="voucher"-->           
            <th >Search</th>
        </tr>
    </thead>
    <thead style="display: none;" >
        <tr>
        	<!-- <th style="display:none">ID</th> -->        	
        	<th class="tbutton">All</th>
            <th class="tbutton">Passenger name</th>
            <th class="tbutton" style="width:40px;">DWN</th>
            <th class="tbutton" style="white-space:nowrap;">PNR</th>            
            <th class="tbutton">Date</th>
            <th class="tbutton" style="width:40px;"></th>
            <th class="tbutton">Dep Airp</th>
            <th class="tbutton">FlightN</th>            
            <th class="tbutton">Arr Airp</th>
            <th class="tbutton" style="width:40px;"></th>
            <th class="tbutton" >Delay <br />time / <br />status</th>
            <th class="tbutton" style="width:40px;">
            <label>            	
            	Show Departed
            </label>
            </th>
            
            <th class="tbutton" style="text-align: center;" >Services</th>
            
            <th>&nbsp;
				           
            </th>
            
        </tr>
    </thead>
    <tbody class="content-passenger-import" >
    
	<?php
	$pax ='';
	if(count($this->passengers)):
		//print_r($this->passengers);die;
	$filter_lastname = JRequest::getVar('filter_lastname');
	//foreach ($this->passengers as $item) :
	unset($this->passengers[refreshment_amount]);	
	unset($this->passengers[group]);	
	foreach ($this->passengers as $vk => $item) :
		//if($vk!='refreshment_amount'):
	/*	if($vk!='group'){
			break;
		}*/
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
		
		//print_r($item).
		$input_search_class_rebook = '';
		$input_search_class_taxi = '';
		$input_search_class_refreshment = '';
		$input_search_class_cash = '';
		$input_search_class_telephone = '';
		$input_search_class_hotel = "";
		//if($item->rebooked_fltref!='')		
		if(count($item->rebook)>1)
		{
			$input_search_class_rebook='class="service-rebook"';
		}	     		
		$card_number = SfsHelper::getCardNumber( $item->card_number );
		 
        $printLinkView = 'index.php?option=com_sfs&view=tracepassenger&layout=default_individualpassengerpage&passenger_id='.$item->passenger_id;     
		
		$connections=json_decode($item->connections);
		//print_r($connections);die;

		$s_e_a='';
		$dep='';
		$flight_number='';
		$arr='';
		$sta_eta_ata='';
		$atd='';
		$flag_end=false;
		$time_deplay='';
		$eta='';
		$std='';
		$etd='';
		$sta='';
		$std_rebook='';
		$sta_rebook='';
	//if(count($item->flight_info)>0):                		          		
		$i=1;
		//foreach ($item->flight_info as $item) {
		//print_r($item->rebook);die();
			if($item->std!=''){
				$s_e_a.='STD: '.substr($item->rebook[0]->std,-8,5).'<br/>';	
			}
			if($item->etd!=''){
				$s_e_a.='ETD: '.substr($item->rebook[0]->etd,-8,5).'<br/>';	
			}
			if($item->atd!=''){
				$s_e_a.='ATD: '.substr($item->rebook[0]->atd,-8,5).'<br/>';		
			}
			if($item->dep!=''){
				$dep = $item->rebook[0]->dep;
			}
			$flight_number=$item->rebook[0]->carrier.$item->rebook[0]->flight_no;
			if($item->sta!=''){
				$sta_eta_ata.='STA: '.substr($item->rebook[0]->sta,-8,5).'<br/>';	
			}
			if($item->eta!=''){
				$sta_eta_ata.='ETA: '.substr($item->rebook[0]->eta,-8,5).'<br/>';	
			}
			if($item->ata!=''){
				$sta_eta_ata.='ATA: '.substr($item->rebook[0]->ata,-8,5).'<br/>';	
			}
			$selected="";
			if($i==1){
				$selected="selected";
				$std_rebook=substr($item->rebook[0]->std,-8,5);
				$sta_rebook=substr($item->rebook[0]->sta,-8,5);
			}
			
			
			if($item->std!='' && $item->etd!=''){
				$time_std=str_replace('T',' ',$item->rebook[0]->std);
				$time_etd=str_replace('T',' ',$item->rebook[0]->etd);

				$start_date = new DateTime($time_std);
				$since_start = $start_date->diff(new DateTime($time_etd));
				$time_deplay.=$since_start->format('%H').':'.$since_start->format('%I');								
			}									
			if($time_deplay=='')
			{
				$delay=json_decode($item->rebook[0]->delay);
				//$time_deplay=$delay->DelayTime;	
				$time_deplay=$item->rebook[0]->irreg_reason;
			}						
			
			if($item->rebook[0]->arr!=''){
				$arr=$item->rebook[0]->arr;
			}	
			
		$arrInfo['title']=$item->title;
		$arrInfo['first_name']=$item->first_name;
		$arrInfo['last_name']=$item->last_name;
		$arrInfo['phone_number']=$item->phone_number;
		$arrInfo['email_address']=$item->email_address;
		$arrInfo['flight_number']=$item->rebook[0]->carrier.$item->rebook[0]->flight_no;
		$arrInfo['pnr']=$item->pnr;
		$arrInfo['code']=$item->code;
		$arrInfo['dep']=$item->rebook[0]->dep;
		$arrInfo['arr']=$item->rebook[0]->arr;
		$arrInfo['std']=$item->rebook[0]->std;
		$arrInfo['etd']=$item->rebook[0]->etd;
		$arrInfo['rental_blockcode']=$item->rental_blockcode;
		$arrInfo['passenger_id']=$item->passenger_id;

		//process inbound
		
		$col_in_center = '';		
		$n = 0;
		$first_dep_in = '';
		if( $connections ){
			foreach( $connections as $in ){
				//print_r($connections);die;
				if($in->inboundconnection)
				{
					if(!is_object($in->inboundconnection->dep) && !is_object($in->inboundconnection->dep) ){
						if($n == 0){
							$col_in_center .= $in->inboundconnection->dep;
							$col_in_center .= '-'.$in->inboundconnection->arr;
							$first_dep_in = $in->inboundconnection->arr;
						}
						if( $n > 0 ){
							if( $in->inboundconnection->dep != $first_dep_in ){
								$col_in_center .= '|'.$in->inboundconnection->dep;
							}
							if($in->inboundconnection->arr){
								$col_in_center .= '-'.$in->inboundconnection->arr;
								$first_dep_in = $in->inboundconnection->arr;
							}
						}
						$n++;
						if( $n == 2 )
							break;
					}
					
				}
				
				
			}
			
		}
		$o=0;
		//process outbound		
		$col_out_center='';		
		$first_dep_out='';
		if($connections){
			foreach( $connections as $out ){
				if($out->outboundconnection){
					if(!is_object($out->outboundconnection->dep) && !is_object($out->outboundconnection->arr)){
						if($o == 0){
							$col_out_center .= $out->outboundconnection->dep;
							$col_out_center .= '-'.$out->outboundconnection->arr;
							$first_dep_out = $out->outboundconnection->arr;
						}

						if( $o >0 ){
							if( $out->outboundconnection->dep != $first_dep_out ){
								$col_out_center .= ' | '.$out->outboundconnection->dep;
							}
							if( $out->outboundconnection->arr ){
								$col_out_center .= '-'.$out->outboundconnection->arr;
								$first_dep_out = $in->inboundconnection->arr;
							}
						}
						$o++;
						if( $o == 2 )
							break;
					}
				}
			}
		}
		?>
			<tr class="<?php echo $class . $input_search_class?> trContent">

            	<td class="clss-check" >   
            		<?php 
            			$chk_checked='';
            			if($list_pass_active){
            				foreach($list_pass_active as $l){
            					if($l==$item->passenger_id){
            						$chk_checked='checked';
            						break;
            					}
            				}
            			} ?>         	
            		<input type="checkbox" <?php echo $chk_checked; ?> style="float: right;" id="<?php echo $item->passenger_id;?>" data-delay="<?php echo $time_deplay; ?>" data-name="<?php echo $item->title . ': ' . $item->first_name .' ' . $item->last_name?>" data-tel="<?php echo $item->phone_number;?>" data-pnr="<?php echo $item->pnr;?>" data-email="<?php echo $item->email_address ?>" data-hotel="<?php echo $item->hotel_id ?>" data-hotelname="<?php echo $item->hotel_name ?>" data-blockdate="<?php echo $item->blockdate ?>" data-reservationid="<?php echo $item->reservationid ?>" data-info="<?php echo str_replace('"',"'", json_encode($arrInfo)) ; ?>" 
                    data-flight_number="<?php echo $arrInfo['flight_number'];?>" 
                    data-std_="<?php echo $item->std;?>" 
                    data-etd_="<?php echo $item->etd;?>" 
                    data-gtcompany="<?php echo $item->group_transportation_types_id;?>" 
                    data-date_expire_time ="<?php echo $item->date_expire_time;?>" 
                    data-airline_airport_id ="<?php echo $item->airline_airport_id;?>" 
                    data-gtc_airport_id ="<?php echo $item->gtc_airport_id;?>" 
                    data-passenger_group_transport_company_id ="<?php echo $item->passenger_group_transport_company_id;?>" 
                    data-gtc_comment ="<?php echo $item->gtc_comment;?>" 
                    data-gtc_hotel_address ="<?php echo $item->hotel_address;?>" 
                    data-gtc_amount_address ="<?php echo $item->amount_address;?>" 
                    data-gtc_amount_price ="<?php echo $item->price;?>" 
                    data-option_taxi = "<?php echo $item->option_taxi;?>"
                    data-taxi_id = "<?php echo $item->taxi_id;?>"
                    data-taxiFromAddress = "<?php echo $item->tax_from_andress; ?>"
                    data-taxiToAddress = "<?php echo $item->tax_to_address; ?>"
                    data-taxDistance = <?php echo $item->taxDistance; ?>
                    data-taxTotalPrice = <?php echo $item->taxTotalPrice; ?>
                    data-taxCpnName = "<?php echo $item->taxCpnName; ?>"
                    data-taxWayOption = "<?php echo $item->taxWayOption; ?>"
                    data-voucher_id = "<?php echo $item->voucher_id;?>" 
                    data-voucher_groups_id = "<?php echo $item->voucher_groups_id;?>" 
                    data-group_id= "<?php echo $item->group_id;?>" data-rental_id="<?php echo $item->rental_id ?>" data-pick_up="<?php echo $item->pick_up; ?>" data-drop_off="<?php echo $item->drop_off; ?>"
                    data-isvIdTitleAirline = "<?php echo $item->isvIdTitleAirline; ?>"
                    data-isvDescription = "<?php echo $item->isvDescription; ?>"
                    data-isvNumberCosts = "<?php echo $item->isvNumberCosts; ?>"
                    data-isvCodeCurrency = "<?php echo $item->isvCodeCurrency; ?>"
                    data-isvInternalComment = "<?php echo $item->isvInternalComment; ?>"
                    data-isvTitleAirline = "<?php echo $item->isvTitleAirline; ?>"
					data-ws-id = "<?php echo $item->ws_id ;?>"
					data-refreshmentAmount = "<?php echo $item->refreshment_amount ;?>"
					data-refreshmentCurrency = "<?php echo $item->refreshment_currency ;?>"
                    <?php
						$list_group_partner='';
                    	if($this->user->groups[11]==11):                     	
	                    	if(count($item->group_partner)>0){
	                    		foreach($item->group_partner as $user){
	                    			if($list_group_partner==''){
	                    				$list_group_partner .= $user->user_id;
	                    			}else{
	                    				$list_group_partner .= ','.$user->user_id;
	                    			}
	                    		}
	                    	}
                    ?>
                    data-groupuser = "<?php echo $list_group_partner; ?>"
                    <?php endif; ?>
                    data-other-service-content = '<?php echo $item->info_other_service->other_service_content; ?>'
                    data-other-service-id = '<?php echo $item->info_other_service->other_sub_service_id; ?>'
                    data-username-partner='<?php echo $item->username_partner ?>'
                    data-supplier='<?php if($item->ws_booking!=''){ echo $item->info_ws->PropertyBookings[0]->Supplier;} ?>'
                    data-supplierreference='<?php if($item->ws_booking!=''){ echo $item->info_ws->PropertyBookings[0]->SupplierReference;} ?>'
                    >

            	</td> 
            	<td id="fullname-<?php echo $item->passenger_id;  ?>">
            	
				<?php 
				$first_name='';
				$last_name='';
				if(strlen($item->first_name)>12){
					$first_name = substr($item->first_name,0,9).'...';
				}
				else{
					$first_name = $item->first_name;
				}

				if(strlen($item->last_name)>12){
					$last_name = substr($item->last_name,0,9).'...';
				}
				else{
					$last_name = $item->last_name;
				}
				echo '<span>'.$item->title . '</span><span class="has-Tip name-passenger">: <a href="'. $printLinkView.'">' . $first_name .' ' . $last_name;?>
				</a>
				<div class="tooltip-s">
					<div class="tooltip-content">
						<span><?php echo $item->first_name.' '.$item->last_name; ?></span>
					</div>
				</div>
				</span>
				
                </td>
                <td style="position: relative; text-align: right;">                
                <?php 

	                if($item->irreg_reason_pass!=''){                	
	                	$irreg_reason_pass=explode(",",$item->irreg_reason_pass);
	                	if($irreg_reason_pass){
	                		if(count($irreg_reason_pass)<=3){
		                		foreach ($irreg_reason_pass as $value) {
		                			echo $value.'<br/>';
		                		}	
	                		}else{
	                			$j=1;
	                			echo '<span class="has-Tip wdn-passenger" style="width: 45px;float: left;"><div class="tooltip-s" style="width:100px;"><div class="tooltip-content" style="cursor:default;"><p style="margin-bottom:15px;">'.str_replace(',','<br/>',$item->irreg_reason_pass).'</p></div></div>';
	                			foreach ($irreg_reason_pass as $value) {
		                			echo '<div style="position:relative;text-align: right;
    width: 50px;">';
		                			if($j==2)
		                				echo '<img style="position: absolute;top: 10%;left: 0;" src="'.$link_Img.'/alert-small.png'.'" />';
		                			echo $value.'</div>';
		                			if($j==3){
		                				break;
		                			}
		                			$j++;
		                		}
		                		echo '</span>';
	                		}
	                		?>
	                		<!--<img style="position: absolute; top:40%;left: 0;" src="<?php echo $link_Img.'/alert-small.png'; ?>" alt="" />-->
	                		<?php
	                	}
	                }
                ?>
                
                </td>
                <td style="text-align: center;">
                	<span class="has-Tip comment-passenger" style="width: 50px; float: left;">
                <div class="tooltip-s">
                	<div class="tooltip-content" style="cursor:default;">
                        <p style="margin-bottom:15px;">
                        	<a class="pull-right add-comment" data-id="<?php echo $item->passenger_id;?>">
                            <img  src="<?php  echo $link_Img.'/add.png' ?>" alt=""> Add
                            </a>
                        </p>
                        <?php 
                        if($item->comment_passenger){                        	
							foreach($item->comment_passenger as $comment){
							?>
							<p><br />
	                        Internal comment:<br />
	                        <?php echo $comment->created_date . ' ' . $comment->name;?>:<br />
	                        <?php echo $comment->comment;?>
	                        </p>
							<?php		
							}
                        }
                        ?>
                        
                	</div>
                </div>
                <?php if( count($item->comment_passenger)>0):?>
                <img alt="comment" style="width: 18px;margin-left:0px " src="<?php echo JURI::base()?>media/system/images/accounting-2-0-updated-reports/comment-26.png">
                <!--<img alt="comment" src="<?php echo JURI::base()?>media/system/images/accounting-2-0-updated-reports/comment-26-grey.png">-->
                <?php endif;?>
                </span>
	                <span style="float: left;">
	                	<?php echo $item->pnr?>	
	                </span>
                </td>
				<td><?php
					echo JFactory::getDate($item->flight_date)->format('d/m');				
                       // echo JFactory::getDate($item->created_date)->format('d/m');
					?>
                </td>
                <td><?php 
                		
                	if($s_e_a!=''){
						echo $s_e_a;
                	}else{
                		echo 'N/A';
                	}
					?>					
				</td>				
				<td style="text-align: right;padding: 9px 0px 8px;">				
				<?php if($col_in_center!='') {//echo '<span>'.$col_in_left.'</span>';
					echo '<div class="inbound-connections"><span>'.$col_in_center.'</span>'.'<span>'.$col_in_right.'</span></div>';
					echo '<span></span>';
				}
				?>
				<div style="text-align: right;">
					<span <?php if($code==$dep) echo 'style="color:red;"'; ?>><?php echo $dep; ?></span>
				</div>
				<?php if($col_out_center!=''){ //echo '<span>'.$col_out_left.'</span>';
					echo '<div class="outbound-connections"><span>'.$col_out_center.'</span></div>';
					echo '<span></span>';
				}?>
				</td>
                <td style="padding: 8px 0px; text-align: center;">
				<?php if($col_in_center!='') //echo '<span>'.$col_in_center.'</span>';
				echo '<span></span>';
				?>
				
                 <?php if($flight_number){
                	?>
                		<div style="text-align: center;"><span style="padding: 0;">-</span> <span  style="padding: 0;"><?php echo $flight_number;?></span> <span  style="padding: 0;">-</span>
                		</div>
                	<?php
                	} ?>
                	<?php if($col_out_center!='') echo '<span></span>'; //echo '<span>'.$col_out_center.'</span>';?>
                </td>
                <td style="text-align: left;padding: 8px 0px;padding: 9px 0px 8px;">
                	<?php if($col_in_center!=''){
                		echo '<span>';
                		//if($col_in_right!='') echo $col_in_right;
                		echo '</span>';
                		} ?>
                	<div>
                	<span <?php if($code==$arr) echo 'style="color:red;"';?>><?php echo $arr?></span><span></span>
                	</div>
                	<?php if($col_out_center!=''){
                		echo '<span>';
                		//if($col_out_right!='') echo $col_out_right;
                		echo '</span>';
                		} ?>
                </td>				
                <td >
                	<?php 
					if($sta_eta_ata!=''){
						echo $sta_eta_ata;
					}else{
						echo 'N/A';
					}?>					
				</td>
                <td>
                	<?php					
						echo $time_deplay;								
					?>	
                </td>
				<td><?php
						if($flag_end==true)						
							echo '<div id="departed" class="departed "></div>';		
                	?>
                </td>                
				<td >
                    <div class="list-services-add">
						<?php $list_services=array();
						$service_bus_transfer='';
						$service_train='';
						$service_rental_car='';
						$service_other='';

						$check_service = false;
						$service_maas='';
						$service_waas='';
						$service_Snackbags ='';
						$service_Phonecards ='';
						$service_Cash ='';
						$service_cost_coverage ='';
						$service_Miscellanious ='';
						if(count($item->services)>0){
							foreach($item->services as $service){
								$list_services[]=$service->service_id;
								if($service->service_id==1){
									$input_search_class_hotel='service-hotel';
									$check_service=true;
								}
								if($service->service_id == 2){									
									$input_search_class_refreshment = 'service-refreshment ';
									$check_service=true;
								}
								if($service->service_id == 3){
									$service_bus_transfer = 'service-bus-transfer ';
									$check_service=true;
								}
								if($service->service_id == 4){
									$input_search_class_taxi = 'service-taxi ';
									$check_service=true;
								}								
								if($service->service_id == 5){
									$service_train = 'service-train ';
									$check_service=true;
								}
								if($service->service_id == 6){
									$service_rental_car = 'service-rental-car ';
									$check_service=true;
								}
								if($service->service_id == 7){
									$service_other = 'service-other ';
									$check_service=true;
								}
								if($service->service_id == 8){
									$service_maas = 'service_maas';
									$check_service=true;
								}
								if($service->service_id == 9){
									$service_waas = 'service_waas';
									$check_service=true;
								}
								if($service->service_id == 10){
									$service_Snackbags = 'service_Snackbags';
									$check_service=true;
								}
								if($service->service_id == 11){
									$service_Phonecards = 'service_Phonecards';
									$check_service=true;
								}
								if($service->service_id == 12){
									$service_Cash = 'service_Cash';
									$check_service=true;
								}
								if($service->service_id == 13){
									$service_cost_coverage = 'service_cost_coverage';
									$check_service=true;
								}
								if($service->service_id == 14){
									$service_Miscellanious = 'service_Miscellanious';
									$check_service=true;
								}
							}
						}
						?>
						<?php 
							$show_add_service = false;
							$show_issue_voucher = false;
							if($this->user->groups[11]==11){
								$show_add_service = true;
								$show_issue_voucher = true; 
							}  
							elseif($this->user->groups[18]==18){
								if(count($item->group_partner)>0){
		                    		foreach($item->group_partner as $user){
		                    			if($this->user->id==$user->user_id){
		                    				$show_add_service=true;
		                    				break;
		                    			}
		                    		}
	                    		}
	                    		if($check_service==true){
	                    			$show_issue_voucher = true; 
	                    		}
							}
							if($show_add_service == true):
							?>
	                    	<div class="" style="float: right;">
	                            <a class="service add-services-list" data-id="<?php echo $item->passenger_id; ?>" href="javascript:void(0);" ></a>
	                        </div>
                    		<?php endif;?>

                    	<input type="hidden" id="pass-service-<?php echo $item->passenger_id; ?>" value="<?php echo implode(',', $list_services); ?>">
                    	<!-- begin CPHUC -->

	                    	<?php if (count($item->services) > 0): $flag_clear = 0; ?>
	                    		<?php foreach ($item->services as $key => $value): ?>
	                    			<div class="icons" value = "">
	                    				<img src="<?php echo JURI::base().$value->icon_service; ?>" alt="<?php echo $value->name_service ?>" style ="width: 32px;float: right;margin-top: 12px;margin-bottom: 6px;margin-right:2px">
	                    				<div class="name_service"><span><?php echo $value->name_service ?></span></div>
	                    			</div>
	                    			<?php $flag_clear++; if ($flag_clear == 2 ): $flag_clear;?>
	                    					<div class="clear-fix"></div>
	                    			<?php endif ?>
	                    		<?php endforeach; ?>
	                    	<?php endif ?>

                    	<!-- End CPHUC -->
                    	<?php if($input_search_class_rebook){
						?>
							<div class="tooltip-s">
			                	<div class="tooltip-content" style="cursor:default;">
			                        <p style="margin-bottom:15px;">
			                        <span><img src="<?php echo $link_Img.'/rebooked_icon.png' ?>"></span><span>Rebooked to:</span>
			                        	
			                        </p>	
			                        <p>
			                        	<div style="width: 100%;padding-left: 10px;">
			                        		<div style="width: 40px;float: left;">DATE:</div>
			                        		<div style="width: 40px;float: left;">STD:</div>
			                        		<div style="width: 90px;float: left;">&nbsp;</div>
			                        		<div style="width: 40px;float: left;">STA:</div>
											<div style="clear: both;"></div>
			                        		<div style="width: 40px;float: left;"><?php echo JFactory::getDate($item->created_date)->format('d/m'); ?>&nbsp;</div>
			                        		<div style="width: 40px;float: left;"><?php echo $std_rebook; ?>&nbsp;</div>
			                        		<div style="width: 90px;float: left;"><?php echo $code.'-'.$item->flight_number.'-'.$arr ?>&nbsp;</div>
			                        		<div style="width: 40px;float: left;"><?php echo $sta_rebook; ?>&nbsp;</div>
			                        	</div>
			                        	
			                        </p>		                        
			                	</div>
			                </div>
						<?php
                    		} ?>	                    	
                    		<div <?php echo $input_search_class_rebook; ?> id="service-rebook"></div>  	
                    	</span>            	
                    		
                    		<div class="clear-fix"></div>
                    	</div>
				</td>
                <td style="width:75px;">
                    
                    <?php /*<a href="<?php echo $printLinkView;?>" style="width: 115px;color: #000;float: right;">to Guest details page </a> */?>
                    <div style="padding-bottom: 10px; display: inline-block; float: right; margin-left:0px;text-align: center;">
                    	<?php 
						
						$show_issue=false;
						if($this->user->groups[11]==11){
							$show_issue=true;
						}elseif($this->user->groups[18]==18){
							if($check_service==true){
								$show_issue=true;
							}
						}
						if($show_issue):
                    	?>
                        <a class="<?php if($item->status_issuevoucher==0) echo 'small-button' ?>  issue-voucher-passenger" data-id="<?php echo $item->passenger_id; ?>" href="javascript:void(0);<?php // echo $printLinkView;?>" style="width: 75px;<?php if($item->status_issuevoucher>0) echo 'color:#000;' ?>">
                        <?php if($item->status_issuevoucher>0) echo 'Show issued voucher';else echo 'issue'; ?></a>
                    <?php endif; ?>
                    </div>
                </td>              
			</tr>
	<?php	
		//endif;	
        endforeach;
	    endif;
	?>
	</tbody>
</table>

