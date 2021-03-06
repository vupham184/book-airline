<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$isWS = $this->reservation->ws_room;
$initial_rooms = 0;
foreach ($this->initial_rooms as $value) :
    $initial_rooms += $value;
endforeach;
$picked_rooms = 0;
foreach ($this->picked_rooms as $value) :
    $picked_rooms += $value;
endforeach;
$freeRooms = ( $initial_rooms * (int)$this->reservation->percent_release_policy) / 100;
$freeRooms = (int)$freeRooms;
$remainingRooms = $initial_rooms - $picked_rooms - $freeRooms;

//lchung add
//$document = &JFactory::getDocument();
//$document->addScript(JPATH_SITE.'/media/system/js/jquery-1.2.6.min');
//$document->addScriptDeclaration ('jQuery.noConflict();');
$ws_change_delete = "";
if($this->reservation->ws_room > 0){//neu la WS is not have funciton add new or edit
	$ws_change_delete = $this->reservation->ws_room;
}
$total_initial_rooms = 0;
$total_picked_up_rooms = 0;


function getNextDate ( $format,$inputDate ) {
				 
	$result = strtotime($inputDate);
//	echo $inputDate;die;
	if($result !== false){
		return date( $format , strtotime('+1 day', $result) );
	}			
	return null;			
}

function getGroupcodeVoucher( $voucher_groups_id ){
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select('a.*');
	$query->from('#__sfs_voucher_groups AS a');
	$query->where('a.id='. $voucher_groups_id);
	$db->setQuery($query);
	return $db->loadObject();
}
//End lchung
?>
<style>

<!--
h3{padding:4px 0;margin:0}
.customer-information{font-size:13px;line-height:170%;}
.approved{color:green;}
.add-block{
	position:relative;
}
.add-right{
	/*float:right;
	position:absolute;
	right:15px;
	top:-2px;*/
}
.add-right.top{
	float:right;
	position:absolute;
	right:15px;
	top:23px;
	z-index:1;
}
.icon-16-add{
	background:url(templates/adminpraise3/images/icons/16/add.png) no-repeat;
	padding:1px 8px;
}
.icon-16-edit{
	background:url(templates/adminpraise3/images/icons/16/pencil.png) no-repeat;
	padding:2px 8px;
}
.icon-16-delete{
	background:url(templates/adminpraise3/images/icons/16/minus.png) no-repeat;
	padding:2px 8px;
}
.add-block .add-right, .add-block .add-right.top{
	display:none;
}
.edit-del{
	top:2px;
	position:relative;
}
.edit-del a{
	margin-left:7px;
	padding:5px 1px;
}
.edit-content{
	border-radius:5px;
	background:#B6B6B6;
	/*opacity:0.5;*/
	position:absolute;
	z-index:10;
	top:0px;
	right:0;
	border:1px solid #9B9B9B;
	box-shadow:3px 3px 3px #000;
	display:none;
}
-->

</style>
<!--<script src="https://code.jquery.com/jquery-1.10.2.js" type="text/javascript"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">/*jquery*/</script>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	///$(".toolbar-edit<?php echo $ws_change_delete;?>").hide();
	var edit_click = 0;
	$("#toolbar-edit a<?php echo $ws_change_delete;?>").click(function(e) {
		var position = $(".block-vouchers<?php echo $ws_change_delete;?>").position();
		if ( edit_click == 0 ) {
			edit_click = 1;
        	$('.add-block .add-right<?php echo $ws_change_delete;?>').css("display", "block");
			$('html, body, document<?php echo $ws_change_delete;?>').animate({scrollTop: position.top}, '1000');
		}
		else if( edit_click == 1 ) {
			edit_click = 0;
			$('.add-block .add-right<?php echo $ws_change_delete;?>').css("display", "none");
			$('.add-new-voucher<?php echo $ws_change_delete;?>').css({'display':'none'});
		}
    });

    // reminder send mail
    
    var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};

    $("#toolbar-reminder").click(function(e) {
		var arr = [];
		var blockCode = [];
		var hotelArr = [];
		var id = getUrlParameter('id');

		blockCode.push(getUrlParameter('blockcode'));
		hotelArr.push(getUrlParameter('hotelArr'));
		arr.push(id);
		
		$.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=reservations.sendMailReminder'; ?>",
            type:"POST",
            data:{
                arrData: arr,
                blockCode: blockCode,
                hotelArr : hotelArr               
            },
            dataType: 'json',
            success:function(response){
            	var msg = (response.error != 0) ? response.error : response.message;
            	alert(msg);           	
                //console.log(response);
            }
        });
    });
	
	//Add vouchers
	
	$('.add-vouchers<?php echo $ws_change_delete;?>').click(function(e) {
		var position = $(".block-vouchers<?php echo $ws_change_delete;?>").position();
		var w = $('.add-new-voucher<?php echo $ws_change_delete;?>').width()/1.5;
        $('.add-new-voucher<?php echo $ws_change_delete;?>').css({'top':position.top, 'display':'block', 'right': w});
    });
	
	<?php if ( isset( $_GET['erroraddvoucher'] ) && $_GET['erroraddvoucher'] != '' ) :?>
	var position = $(".block-vouchers<?php echo $ws_change_delete;?>").position();
	var w = $('.add-new-voucher<?php echo $ws_change_delete;?>').width()/1.5;
	$('.add-new-voucher<?php echo $ws_change_delete;?>').css({'top':position.top, 'display':'block', 'right': w});
	$("#toolbar-edit a<?php echo $ws_change_delete;?>").click();
	<?php endif;?>
	$('.voucher-content-cancel<?php echo $ws_change_delete;?>').click(function(e) {
		$('.add-new-voucher<?php echo $ws_change_delete;?>').css({'display':'none'});
	});
	
	$('.cls-disabled').submit(function(e) {
		alert('Curent Initial rooms less Picked up rooms!');
        return false;
    });
	
	$('#stranded_seats').keyup(function ( event ) { // cap nha gia tri cua so luong
		var keycode = (event.keyCode ? event.keyCode : event.which);
		var res = '';
		var value = $(this).val();
		if(keycode != 37 && keycode != 39 && keycode != 190){
			for(i = 0; i<= value.length; i++){
				if ($.isNumeric(value[i])) {
					res = res+value[i];
				}
				else if(value[i] == '.'){ //e.which == 190
					res = res+value[i];
				}
			}
			
			$(this).val(res);
			$(this).val($(this).val());
		}
	});
	//End add-vouchers
	
	//Passengers
	$('.add-passengers<?php echo $ws_change_delete;?>').click(function(e) {
		$('.edit-passengers-content<?php echo $ws_change_delete;?>').css('display', "none");
        $('.add-passengers-content<?php echo $ws_change_delete;?>').css('display', "block");
		$('.add-passengers-content-cancel<?php echo $ws_change_delete;?>').click(function(e) {
			$('.add-passengers-content<?php echo $ws_change_delete;?>').css('display', "none");
		});
    });
	
	$('.edit-passengers<?php echo $ws_change_delete;?>').click(function(e) {
		$('.edit-passengers-content<?php echo $ws_change_delete;?>').css('display', "none");
        var id = $(this).attr('data-id');
		$('.passengers-content-' + id).css('display', "block");
    });
	
	$('.passengers-content-cancel<?php echo $ws_change_delete;?>').click(function(e) {
        var id = $(this).attr('data-id');
		$('.passengers-content-' + id).css('display', "none");
    });
	
	$('.delete-passengers<?php echo $ws_change_delete;?>').click(function(e) {
		if(confirm("Do you want delete passengers?")){
			var id = $(this).attr('data-id');
			$('.del-passengers-fsubmi-del-' + id ).click();
		}
    });	
	//End Passengers
	
	
	//Trace passengers
	$('.add-trace-passengers<?php echo $ws_change_delete;?>').click(function(e) {
		if(e.preventDefault) e.preventDefault(); else e.returnValue = false;
		
		$('.edit-trace-passengers-content<?php echo $ws_change_delete;?>').css('display', "none");
        $('.add-trace-passengers-content<?php echo $ws_change_delete;?>').css('display', "block");
		$('.add-trace-passengers-content-cancel<?php echo $ws_change_delete;?>').click(function(e) {
			$('.add-trace-passengers-content<?php echo $ws_change_delete;?>').css('display', "none");
		});
    });
	
	$('.edit-trace-passengers<?php echo $ws_change_delete;?>').click(function(e) {
		$('.edit-trace-passengers-content<?php echo $ws_change_delete;?>').css('display', "none");
        var id = $(this).attr('data-id');
		$('.trace-passengers-content-' + id).css('display', "block");
    });
	
	$('.trace-passengers-content-cancel<?php echo $ws_change_delete;?>').click(function(e) {
        var id = $(this).attr('data-id');
		$('.trace-passengers-content-' + id).css('display', "none");
    });
	$('.delete-trace-passengers<?php echo $ws_change_delete;?>').click(function(e) {
		if(confirm("Do you want delete trace passengers?")){
			var id = $(this).attr('data-id');
			$('.del-trace-passengers-fsubmi-del-' + id ).click();
		}
    });	
	//End Trace passengers
	
	
	//Voucher comments
	$('.edit-voucher-comments<?php echo $ws_change_delete;?>').click(function(e) {
		$('.edit-voucher-comments-content<?php echo $ws_change_delete;?>').css('display', "none");
        var id = $(this).attr('data-id');
		$('.voucher-comment-content-' + id).css('display', "block");
    });
	
	$('.voucher-comment-content-cancel<?php echo $ws_change_delete;?>').click(function(e) {
        var id = $(this).attr('data-id');
		$('.voucher-comment-content-' + id).css('display', "none");
    });
	$('.delete-voucher-comments<?php echo $ws_change_delete;?>').click(function(e) {
		if(confirm("Do you want delete voucher comment?")){
			var id = $(this).attr('data-id');
			$('.del-voucher-comments-fsubmi-del-' + id ).click();
		}
    });	
	//End Voucher comments
	
	//check add number passengers when add new passenger
	$('.passengers-voucher-id').change(function(e) {
		var text = $(this).children('option:selected').text();
		if ( text != 'Choose' ) {
			var t = $('input[name="vouchers-'+text+'"]').val();
			var num_Passenger = $('input[name="'+text+'"]').val();
			if ( num_Passenger == t ) {
				$(".btn-save-add-passenger").css("display", 'none');
				alert( 'You can\'t add Passengers in a voucher code (' + text + ') because have ' + num_Passenger + ' Passengers in ' + t + ' Seats');
			}
			else {
				$(".btn-save-add-passenger").css("display", 'block');
			}
			
		}
    });
	
	//check add number trace passengers when add new passenger
	$('.trace-passengers-voucher-id').change(function(e) {
		var text = $(this).children('option:selected').text();
		if ( text != 'Choose' ) {
			var t = $('input[name="vouchers-'+text+'"]').val();
			var num_Passenger = $('input[name="trace-passengers-'+text+'"]').val();
			if ( num_Passenger == t ) {
				$(".btn-save-add-trace-passenger").css("display", 'none');
				alert( 'You can\'t add Trace passengers in a voucher code (' + text + ') because have ' + num_Passenger + ' Trace passengers in ' + t + ' Seats');
			}
			else {
				$(".btn-save-add-trace-passenger").css("display", 'block');
			}
			
		}
    });
	
	
});

function textCounter(field,cnt, maxlimit)
{
	var cntfield = document.getElementById(cnt)
	if (field.value.length > maxlimit)
		field.value = field.value.substring(0, maxlimit);
	else
		cntfield.value = maxlimit - field.value.length;
}
</script>

<div id="reservation-detail">
<?php if ( !isset($this->fakeVoucher) && (int)$remainingRooms > 0) :?>
    <fieldset>
        <div class="fltrt">
            <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 550}, onClose: function() {}}" href="index.php?option=com_sfs&view=reservation&layout=issuevoucher&id=<?php echo $this->reservation->id?>&tmpl=component">
                <button type="button">Issue voucher</button>
            </a>
        </div>
        <div class="configuration">
            Issue soft block code
        </div>
    </fieldset>
<?php endif;?>
<div class="width-50 fltlft">

    <?php echo $this->loadTemplate('blockdetail'); ?>


    <fieldset class="adminform">
        <legend>Mealplans Details</legend>
        <table class="adminlist">
            <thead>
            <tr>
                <th><strong>Mealplans</strong></th>
                <th><strong>Nett rates</strong></th>
                <th><strong>Picked up mealplans</strong></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Breakfast price:</td>
                <td><?php echo floatval($this->reservation->breakfast) > 0 ? $this->reservation->breakfast : 'N/A' ;?></td>
                <td><?php echo floatval($this->reservation->breakfast) > 0  ? $this->picked_breakfasts : 'N/A' ;?></td>
            </tr>
            <tr>
                <td>Lunch price:</td>
                <td><?php echo floatval($this->reservation->lunch) > 0  ? $this->reservation->lunch : 'N/A';?></td>
                <td><?php echo floatval($this->reservation->lunch) > 0  ? $this->picked_lunchs : 'N/A' ;?></td>
            </tr>
            <tr>
                <td>Dinner price:</td>
                <td><?php echo floatval($this->reservation->mealplan) > 0 ? $this->reservation->mealplan : 'N/A';?></td>
                <td><?php echo floatval($this->reservation->mealplan) > 0 ? $this->picked_mealplans : 'N/A' ;?></td>
            </tr>
            </tbody>
        </table>
    </fieldset>
</div>

<div class="width-50 fltlft">

    <fieldset class="adminform">
        <legend>Estimated Charges</legend>
        <table class="adminlist">
            <tbody>
            <tr>
                <td width="300"><h3>Estimated room charge</h3></td>
                <td><?php echo $this->total_room_charge;?></td>
            </tr>
            <tr>
                <td><h3>Estimated mealplan charge</h3></td>
                <td><?php echo $this->total_mealplan_charge ;?></td>
            </tr>
            <tr>
                <td><h3>Estimated invoice charge</h3></td>
                <td><?php echo $this->total_invoice_charge;?></td>
            </tr>
            <tr>
                <td><h3>Currency</h3></td>
                <td><?php echo $this->hotel->currency;?></td>
            </tr>
            </tbody>
        </table>
    </fieldset>


        <?php if(!$isWS):?>
        <fieldset class="adminform">
            <legend>Rooms Details</legend>
            <table class="adminlist">
                <thead>
                    <tr>
                        <th><strong>Rooms</strong></th>
                        <th><strong>Nett rates</strong></th>
                        <th><strong>Picked up rooms</strong></th>
                        <th><strong>Initial rooms</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Single price:</td>
                        <td><?php echo $this->reservation->s_rate;?></td>
                        <td><?php echo $this->picked_rooms[1]; $total_picked_up_rooms += $this->picked_rooms[1];?></td>
                        <td><?php echo $this->initial_rooms[1]; $total_initial_rooms += $this->initial_rooms[1];?></td>
                    </tr>
                    <tr>
                        <td>Double price:</td>
                        <td><?php echo $this->reservation->sd_rate;?></td>
                        <td><?php echo $this->picked_rooms[2]; $total_picked_up_rooms += $this->picked_rooms[2];?></td>
                        <td><?php echo $this->initial_rooms[2]; $total_initial_rooms += $this->initial_rooms[2];?></td>
                    </tr>
                    <tr>
                        <td>Triple price:</td>
                        <td><?php echo $this->reservation->t_rate;?></td>
                        <td><?php echo $this->picked_rooms[3]; $total_picked_up_rooms += $this->picked_rooms[3];?></td>
                        <td><?php echo $this->initial_rooms[3]; $total_initial_rooms += $this->initial_rooms[3];?></td>
                    </tr>
                    <tr>
                        <td>Quad price:</td>
                        <td><?php echo $this->reservation->q_rate;?></td>
                        <td><?php echo $this->picked_rooms[4]; $total_picked_up_rooms += $this->picked_rooms[4];?></td>
                        <td><?php echo $this->initial_rooms[4]; $total_initial_rooms += $this->initial_rooms[4];?></td>
                    </tr>
                </tbody>
            </table>
            <?php if( (int) $this->reservation->percent_release_policy > 0):?>
                <div class="clr"></div>
                <div style="float:right;padding: 15px 0 0 0;font-size:15px;">
                free release percentage: <?php echo $this->reservation->percent_release_policy;?>%
                </div>
            <?php endif;?>
            <?php
                echo '<div style="padding: 15px 0 0 0;font-size:15px;">Total initial blocked rooms: '.$initial_rooms.'</div>';
                ?>
                <?php
                echo '<div style="padding: 0;font-size:15px;">Total picked up (used) rooms: '.$picked_rooms.'</div>';
                ?>
            </fieldset>
        <?php else:?>
        <fieldset class="adminform">
            <legend>Rooms Details</legend>
            <table class="adminlist">
                <thead>
                <tr>
                    <th><strong>Rooms</strong></th>
                    <th><strong>Ws rates</strong></th>
                    <th><strong>Sales price</strong></th>
                    <th><strong>Picked up rooms</strong></th>
                    <th><strong>Initial rooms</strong></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Single price:</td>
                    <td><?php echo $this->reservation->ws_s_rate;?></td>
                    <td><?php echo number_format(ceil($this->reservation->s_rate),2);?></td>
                    <td><?php echo $this->initial_rooms[1];?></td>
                    <td><?php echo $this->initial_rooms[1];?></td>
                </tr>
                <tr>
                    <td>Double price:</td>
                    <td><?php echo $this->reservation->ws_sd_rate;?></td>
                    <td><?php echo number_format(ceil($this->reservation->sd_rate),2);?></td>
                    <td><?php echo $this->initial_rooms[2];?></td>
                    <td><?php echo $this->initial_rooms[2];?></td>
                </tr>
                <tr>
                    <td>Triple price:</td>
                    <td><?php echo $this->reservation->ws_t_rate;?></td>
                    <td><?php echo number_format(ceil($this->reservation->t_rate),2);?></td>
                    <td><?php echo $this->initial_rooms[3];?></td>
                    <td><?php echo $this->initial_rooms[3];?></td>
                </tr>
                <tr>
                    <td>Quad price:</td>
                    <td><?php echo $this->reservation->ws_q_rate;?></td>
                    <td><?php echo number_format(ceil($this->reservation->q_rate),2);?></td>
                    <td><?php echo $this->initial_rooms[4];?></td>
                    <td><?php echo $this->initial_rooms[4];?></td>
                </tr>
                </tbody>
            </table>
            <?php if( (int) $this->reservation->percent_release_policy > 0):?>
                <div class="clr"></div>
                <div style="float:right;padding: 15px 0 0 0;font-size:15px;">
                    free release percentage: <?php echo $this->reservation->percent_release_policy;?>%
                </div>
            <?php endif;?>
            <?php
            echo '<div style="padding: 15px 0 0 0;font-size:15px;">Total initial blocked rooms: '.$initial_rooms.'</div>';
            echo '<div style="padding: 0;font-size:15px;">Total picked up (used) rooms: '.$initial_rooms.'</div>';
            ?>
        </fieldset>

    <?php endif;?>


</div>

<div class="clr"></div>


<div class="width-100 fltlft add-block block-vouchers">
	<?php echo $this->loadTemplate('vouchers'); ?>
</div>

<div class="clr"></div>
<div class="width-33 fltlft add-block">
    <span class="add-right top">
        <a href="javascript:void(0);" class="add-passengers">
            <span class="icon-16-add"></span>
        </a>
    </span>
	<fieldset class="adminform">
		<legend>Passengers </legend>
		<table class="adminlist" width="100%">
			<tr>
				<th>#</th>
				<th>First Name</th>
				<th>Lastname</th>
				<th>Voucher</th>
                <th></th>			
			</tr>
			<?php 
			    $i = 0;
				$arrayCode = array();
			    foreach ( $this->passengers as $item ) : ?>	
			    <tr class="add-block">
			        <td><?php echo ++$i; ;?></td>
			        <td><?php echo $item->first_name;?></td>
			        <td><?php echo $item->last_name ;?></td>
			        <td><?php echo $item->v_code; $arrayCode[$item->code] ++;?></td>
                    <td>
                    	<span class="add-right edit-del">
                        	<div class="edit-content edit-passengers-content passengers-content-<?php echo $item->pid?>" style="right:-78px;">
                            	<form action="" name="edit-passengers-<?php echo $item->pid?>" id="edit-passengers-<?php echo $item->pid?>" method="post" >
                                <table>
                                    <tr>
                                    	<td>
                                            Voucher number
                                            <select name="voucher_id" style="width:100px;" disabled="disabled">
                                            	<option value="">Choose</option>
                                            	<?php foreach ($this->list_voucher_code as $itemV) :?>
                                            	<option <?php if ( $itemV->code ==  $item->voucher_code) :?> selected="selected"<?php endif;?> value="<?php echo $itemV->id;?>"><?php echo $itemV->code;?></option>
                                                <?php endforeach;?>
                                            </select>
                                            <!--<select name="title">
                                            	<option <?php if ( $item->title == 'Mr') :?> selected="selected"<?php endif;?> value="Mr">Mr</option>
                                                <option  <?php if ( $item->title == 'Mrs') :?> selected="selected"<?php endif;?> value="Mrs">Mrs</option>
                                                <option  <?php if ( $item->title == 'Ms') :?> selected="selected"<?php endif;?> value="Ms">Ms</option>
                                            </select>-->
                                        </td>
                                        <td>
                                            First Name<input name="first_name" value="<?php echo $item->first_name?>" />
                                        </td>
                                        <td>
                                            Lastname<input name="last_name" value="<?php echo $item->last_name?>" />
                                        </td>
                                        <td style="text-align:center">
                                            <button data-id="<?php echo $item->pid?>" type="submit">Save</button>
                                            <button data-id="<?php echo $item->pid?>" type="button" class="passengers-content-cancel">Cancel</button>
                                        </td>
                                    </tr>
                                </table>
                                	<input type="hidden" name="task" value="reservation.editpassengers" />
                                    <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />   
                                    <input type="hidden" name="id" value="<?php echo $item->pid?>" />      
                                    <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                                    <?php echo JHtml::_('form.token'); ?>
                                </form>
                            </div>
                            <form action="" name="del-passengers-<?php echo $item->pid?>" id="del-passengers-<?php echo $item->pid?>" method="post" >
                            	<input type="hidden" name="task" value="reservation.delpassengers" />
                                <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />   
                                <input type="hidden" name="id" value="<?php echo $item->pid?>" />      
                                <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                                <button type="submit" class="del-passengers-fsubmi-del-<?php echo $item->pid?>" style="display:none;">del</button>
                                <?php echo JHtml::_('form.token'); ?>
                            </form>
                            
                        	<a href="javascript:void(0);" data-id="<?php echo $item->pid?>" class="edit-passengers">
                        	<span class="icon-16-edit"></span><!--<i class="fa fa-plus-circle"></i>--></a>
                            <a href="javascript:void(0);" data-id="<?php echo $item->pid?>" class="delete-passengers">
                        	<span class="icon-16-delete"></span></a>
                        </span>
					</td>
                    
			    </tr>			
		    <?php endforeach ; ?>
            	<tr class="add-block">
                	<td colspan="5" >
					<?php foreach ( $arrayCode as $vk => $v ) : ?>
                    <input type="hidden" name="<?php echo $vk;?>" value="<?php echo $v;?>"  />
                    <?php endforeach;?>
                    	<form action="" name="add-passengers" id="add-passengers" method="post" >
                    	<div style="position:relative;">
                            <div class="edit-content edit-passengers-content add-passengers-content" style="right:-78px;">
                                <table>
                                    <tr>
                                    	<td>
                                        	Voucher number
                                            <select name="voucher_id" style="width:100px;" class="passengers-voucher-id" >
                                            	<option value="">Choose</option>
                                            	<?php foreach ($this->list_voucher_code as $itemV) :?>
                                            	<option value="<?php echo $itemV->id;?>"><?php echo $itemV->code;?></option>
                                                <?php endforeach;?>
                                            </select>
                                           <!-- Title
                                            <select name="title">
                                            	<option value="Mr">Mr</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Ms">Ms</option>
                                            </select>-->
                                        </td>
                                        <td>
                                            First Name<input name="first_name" value="" />
                                        </td>
                                        <td>
                                            Lastname<input name="last_name" value="" />
                                        </td>
                                        <td style="text-align:center">
                                            <button type="submit" class="btn-save-add-passenger" >Save</button>
                                            <button type="button" class="add-passengers-content-cancel">Cancel</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        	<input type="hidden" name="task" value="reservation.addnewpassengers" />
                            <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />        
                            <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                            <?php echo JHtml::_('form.token'); ?>
                        </form>
                    </td>
                </tr>
		</table>		
		
	</fieldset>
    	
</div>	

<div class="width-33 fltlft add-block">
	<span class="add-right top">
    	<a href="javascript:void(0);" class="add-trace-passengers">
    		<span class="icon-16-add"></span>
    	</a>
    </span> 
	<fieldset class="adminform ">
		<legend>Trace Passengers</legend>
		<table class="adminlist" width="100%">
			<tr>
				<th>Voucher number</th>
				<th>First name</th>
				<th>Last name</th>				
				<th>Phone passenger</th>
			</tr>
			<?php
			$tracePassengersarrayCode = array();

			if(count($this->tracePassengers)):		
			foreach ($this->tracePassengers as $item) : ?>
						
				<tr class="add-block">
					<td><?php echo $item->voucher_code; $tracePassengersarrayCode[$item->voucher_code] ++?></td>
					<td><?php echo $item->first_name?></td>
					<td><?php echo $item->last_name?></td>															
					<td><span style="display:inline;"><?php echo trim($item->phone_number);?></span>                   	
                    	<span class="add-right edit-del" style="width:50%; float:right;">
                        	<div class="edit-content edit-trace-passengers-content trace-passengers-content-<?php echo $item->id?>">
                            	<form action="" name="edit-trace-passengers-<?php echo $item->id?>" id="edit-trace-passengers-<?php echo $item->id?>" method="post" >
                                <table>
                                    <tr>
                                    	<td>
                                        	Voucher number
                                            <select name="voucher_id" style="width:100px;" disabled="disabled">
                                            	<?php foreach ($this->list_voucher_code as $itemV) :?>
                                            	<option <?php if ( $itemV->code ==  $item->voucher_code) :?> selected="selected"<?php endif;?> value="<?php echo $itemV->id;?>"><?php echo $itemV->code;?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </td>
                                        <td>
                                            First name<input name="first_name" value="<?php echo $item->first_name?>" />
                                        </td>
                                        <td>
                                            Last name<input name="last_name" value="<?php echo $item->last_name?>" />
                                        </td>
                                        <td>
                                            Phone passenger<input name="phone_number" value="<?php echo $item->phone_number?>" />
                                        </td>
                                        <td style="text-align:center">
                                            <button data-id="<?php echo $item->id?>" type="submit" class="trace-passengers-content-save">Save</button>
                                            <button data-id="<?php echo $item->id?>" type="button" class="trace-passengers-content-cancel">Cancel</button>
                                        </td>
                                    </tr>
                                </table>
                                	<input type="hidden" name="task" value="reservation.edittracepassengers" />
                                    <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" /> 
                                    <input type="hidden" name="id" value="<?php echo $item->id?>" />        
                                    <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                                    <?php echo JHtml::_('form.token'); ?>
                                </form>
                            </div>
                            <form action="" name="del-trace-passengers-<?php echo $item->id?>" id="del-trace-passengers-<?php echo $item->id?>" method="post" >
                            	<input type="hidden" name="task" value="reservation.deltracepassengers" />
                                <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />   
                                <input type="hidden" name="id" value="<?php echo $item->id?>" />      
                                <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                                <button type="submit" class="del-trace-passengers-fsubmi-del-<?php echo $item->id?>" style="display:none;">del</button>
                                <?php echo JHtml::_('form.token'); ?>
                            </form>
                        	<a style="display:inline;" href="javascript:void(0);" data-id="<?php echo $item->id?>" class="edit-trace-passengers">
                        	<span class="icon-16-edit"></span></a>
                            <a style="display:inline;" href="javascript:void(0);" data-id="<?php echo $item->id?>" class="delete-trace-passengers">
                        	<span class="icon-16-delete"></span></a>
                        </span>
					</td>									
				</tr>
			<?php
			endforeach;
			endif;
			?>			
                <tr class="add-block">
                    <td colspan="4">
                    	<?php foreach ( $tracePassengersarrayCode as $vk => $v ) : ?>
                        <input type="hidden" name="trace-passengers-<?php echo $vk;?>" value="<?php echo $v;?>"  />
                        <?php endforeach;?>
                    	<form action="" name="add-trace-passengers" id="add-trace-passengers" method="post" >
                    	<div style="position:relative;">
                            <div class="edit-content edit-trace-passengers-content add-trace-passengers-content">
                                <table>
                                    <tr>
                                    	<td>
                                            <!--Title
                                            <select name="title">
                                            	<option value="Mr">Mr</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Ms">Ms</option>
                                            </select>-->
                                            Voucher number
                                            <select name="voucher_id" style="width:100px;" class="trace-passengers-voucher-id">
                                            	<option value="">Choose</option>
                                            	<?php foreach ($this->list_voucher_code as $itemV) :?>
                                            	<option value="<?php echo $itemV->id;?>"><?php echo $itemV->code;?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </td>
                                        <td>
                                            First Name<input name="first_name" value="" />
                                        </td>
                                        <td>
                                            Lastname<input name="last_name" value="" />
                                        </td>
                                        <td>
                                            Phone passenger<input name="phone_number" value="" />
                                        </td>
                                        <td style="text-align:center">
                                            <button type="submit" class="btn-save-add-trace-passenger">Save</button>
                                            <button type="button" class="add-trace-passengers-content-cancel">Cancel</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        	<input type="hidden" name="task" value="reservation.addnewtracepassengers" />
                            <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />
                            <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                            <?php echo JHtml::_('form.token'); ?>
                        </form>
                    </td>
                </tr>
		</table>		
	</fieldset>
    	
</div>

<div class="width-34 fltlft">
	<fieldset class="adminform">
		<legend>Voucher comments</legend>
			<table class="adminlist">
			<tr>
				<th width="120">Voucher number</th>
				<th>Comment</th>
                <th></th>			
			</tr>
			<?php 
			if(count($this->vouchers)) :
			    $i = 0;
			    foreach ($this->vouchers as $item) : ?>
			    	<?php if($item->comment):?>
			    	<tr class="add-block">
			    		<td><?php /*echo $item->code;*/ 
						echo getGroupcodeVoucher($item->voucher_groups_id)->code;?></td>
			    		<td><?php echo $item->comment?></td>
                        <td>
                    	<span class="add-right edit-del">
                        	<div class="edit-content edit-voucher-comments-content voucher-comment-content-<?php echo $item->id?>">
                            	<form action="" name="edit-voucher-comments-<?php echo $item->pid?>" id="edit-voucher-comments-<?php echo $item->pid?>" method="post" >
                                <table>
                                    <tr>
                                        <td>
                                            Comment
                                            <textarea name="comment"><?php echo $item->comment?></textarea>
                                        </td>
                                        <td style="text-align:center">
                                            <button data-id="<?php echo $item->id?>" type="submit">Save</button>
                                            <button data-id="<?php echo $item->id?>" type="button" class="voucher-comment-content-cancel">Cancel</button>
                                        </td>
                                    </tr>
                                </table>
                                	<input type="hidden" name="task" value="reservation.editvouchercomments" />
                                    <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />  
                                    <input type="hidden" name="id" value="<?php echo $item->id?>" />      
                                    <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                                    <?php echo JHtml::_('form.token'); ?>
                                </form>
                            </div>
                            <form action="" name="del-voucher-comments-<?php echo $item->id?>" id="del-voucher-comments-<?php echo $item->id?>" method="post" >
                            	<input type="hidden" name="task" value="reservation.delvouchercomments" />
                                <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />   
                                <input type="hidden" name="id" value="<?php echo $item->id?>" />      
                                <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
                                <button type="submit" class="del-voucher-comments-fsubmi-del-<?php echo $item->id?>" style="display:none;">del</button>
                                <?php echo JHtml::_('form.token'); ?>
                            </form>
                        	<a href="javascript:void(0);" data-id="<?php echo $item->id?>" class="edit-voucher-comments">
                        	<span class="icon-16-edit"></span></a>
                            <a href="javascript:void(0);" data-id="<?php echo $item->id?>" class="delete-voucher-comments">
                        	<span class="icon-16-delete"></span></a>
                        </span>
					</td>		    		  		
			  		</tr>
			  		<?php endif;?>
		    	<?php 
		    	endforeach ; 
		    endif;
		    ?>					
			</table>
	</fieldset>
</div>

<div class="clr"></div>

<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>Messages</legend>
        <?php
        if( count($this->messages) ) :
            foreach ($this->messages as $m) :
                ?>
                <div style="padding:10px;font-size:12px;<?php if($m->type==1) echo 'background:#F4F4F4;';?>">
                    <div>
                        <?php if($m->type==1) : ?>
                            <i>From Airline by <?php echo $m->from_name?>, Posted at <?php echo JHtml::_('date',$m->posted_date,JText::_('DATE_FORMAT_LC2'))?></i>
                        <?php else:?>
                            <i>From Hotel by <?php echo $m->from_name?>, Posted at <?php echo JHtml::_('date',$m->posted_date,JText::_('DATE_FORMAT_LC2'))?></i>
                        <?php endif;?>
                    </div>
                    <?php echo $m->body;?>
                </div>
            <?php
            endforeach;
        endif;
        ?>
    </fieldset>
</div>

<div class="width-50 fltlft">
    <fieldset class="adminform">
        <legend>Notes</legend>

        <?php
        if(count($this->notes)) :
            $i = 0;
            foreach ( $this->notes as $item ) : ?>
                <div>
                    <?php echo $item->notes;?>
                </div>
            <?php
            endforeach ;
        endif;
        ?>

        <a rel="{handler: 'iframe', size: {x: 750, y: 650}, onClose: function() {}}" href="index.php?option=com_sfs&amp;view=reservation&amp;layout=notes&amp;tmpl=component&amp;id=<?php echo $this->reservation->id?>" class="modal">
            Add Note
        </a>

    </fieldset>
</div>


</div>
<?php 
$str_readonly = '';
$str_disabled = ''; 
if ( $total_initial_rooms <= $total_picked_up_rooms){
	//$str_readonly = 'readonly="readonly"';
	$str_disabled = 'cls-disabled';
} 
?>
<!--Form add new a Vouchers-->
<div class="edit-content add-new-voucher" style="display:none; position:absolute; padding:15px;">
	<form action="" method="post" class="<?php echo $str_disabled;?>" >
    	<table>
        	<?php $session = &JFactory::getSession();
			$datapost = array();
			$app = &JFactory::getApplication();
			if ( isset( $_GET['erroraddvoucher'] ) && $_GET['erroraddvoucher'] != '' ) :?>
            	<?php
					$datapost = (array)$session->get('datapost');
					if ( $_GET['erroraddvoucher'] == 'error-flight-number' )
						$app->enqueueMessage("Please enter flight number!","error");
					elseif ( $_GET['erroraddvoucher'] == 'error-iata-stranded-code' )
						$app->enqueueMessage("Please IATA stranded code!","error");
				?>
            <?php 
			elseif( isset( $_GET['erroraddvoucher'] ) ):
				$app->enqueueMessage("Add  Vouchers successfully!");
			endif;?>
            
            <tr>
            	<td>
				Room type
                </td>
                <td>
                    <select name="room_type" style="width:100px;">
						<?php if ($this->reservation_sub->sd_room > 0 ):?>
                        <option value="2">Single/double room</option>
                        <?php elseif ($this->reservation_sub->t_room > 0 ):?>
                        <option value="3">Triple room</option>
                        <?php elseif ($this->reservation_sub->s_room > 0 ):?>
                        <option value="1">Single room</option>
                        <?php elseif ($this->reservation_sub->q_room > 0 ):?>
                        <option value="4">Quad room</option>
                        <?php endif;?>
                    </select>
                </td>
            </tr>
            
            <!--<tr>
            	<td>
Number of stranded passengers
                </td>
                <td>
                    <input <?php echo $str_readonly;?> name="stranded_seats" id="stranded_seats" value="<?php echo isset( $datapost["stranded_seats"] ) ? $datapost["stranded_seats"] : ""?>" />
                </td>
            </tr>-->
            <tr>
            	<td>
                    Flight number
                </td>
                <td>
                    <input <?php echo $str_readonly;?> name="flight_code" value="<?php echo isset( $datapost["flight_code"] ) ? $datapost["flight_code"] : ""?>" />
                </td>
            </tr>
            <tr>
            	<td>
                    IATA stranded code
                </td>
                <td>
                    <input <?php echo $str_readonly;?> name="iata_stranged_code" value="<?php echo isset( $datapost["iata_stranged_code"] ) ? $datapost["iata_stranged_code"] : ""?>" />
                </td>
            </tr>
            <tr>
            	<td>
                    Add comment on the voucher
                </td>
                <td>
                    <textarea <?php echo $str_readonly;?> onkeyup="textCounter(this,'comment_length' ,500)" onkeydown="textCounter(this,500);" style="width:98%;height:90px;border: 1px solid #909bb1" id="vouchercomment" name="comment"><?php echo isset( $datapost["comment"] ) ? $datapost["comment"] : ""?></textarea>
                    <p>
                        Maximum characters: 500 - You have
                        <input type="text" value="500" maxlength="3" size="3" name="comment_length" id="comment_length" readonly="" style="color:red;font-size:12pt;font-style:italic;width:40px;border: 0px">
                        characters left
                    </p>
                </td>
            </tr>
            <!--<tr>
            	<td>
                    First name
                </td>
                <td>
                    <input name="first_name" value="<?php echo isset( $datapost["first_name"] ) ? $datapost["first_name"] : ""?>" />
                </td>
            </tr>
            <tr>
            	<td>
                    Last name
                </td>
                <td>
                    <input name="last_name" value="<?php echo isset( $datapost["last_name"] ) ? $datapost["last_name"] : ""?>" />
                </td>
            </tr>-->
            <!--<tr>
            	<td>
                    Phone number
                </td>
                <td>
                	<table>
                    	<tr>
                        	<td>
                    			country ext
                            </td>
                            <td>
                                <input <?php echo $str_readonly;?> name="passenger_mobile_ext" value="<?php echo isset( $datapost["passenger_mobile_ext"] ) ? $datapost["passenger_mobile_ext"] : ""?>" />
                            </td>
                            <td>
                                <input <?php echo $str_readonly;?> name="passenger_mobile" value="<?php echo isset( $datapost["passenger_mobile"] ) ? $datapost["passenger_mobile"] : ""?>" />
                            </td>
                        </tr>
                    </table>
                    
                </td>
            </tr>-->
            <tr>
            	<td>
                    Return flight number
                </td>
                <td>
                    <input <?php echo $str_readonly;?> name="returnflight" value="<?php echo isset( $datapost["returnflight"] ) ? $datapost["returnflight"] : ""?>" />
                </td>
            </tr>
            
            <tr>
            	<td>
                    New flight date
                </td>
                <td>
                   <?php
						$return = '<select id="returnflightdate" name="returnflightdate" class="inputbox">';	
						$j = 1;
						for ($i = 0; $i <= 30; $i++) {
							$selected = '';
							$dat = date( 'Y-m-d' , strtotime('+' . $j . ' day', time() ) );
							if ( isset( $datapost["returnflightdate"] ) && $datapost["returnflightdate"] == $dat ) {
								$selected = ' selected="selected"';
							}
							$return .= '<option value="'.$dat.'"'.$selected.'>';							
								$return .= JHTML::_('date', $dat , JText::_('DATE_FORMAT_LC3'), false );
							$return .= '</option>';	
							$j++;					
						}
						$return .= '</select>';	
						echo $return;
					?>
                </td>
            </tr>
           
            <tr>
                <td style="text-align:center" colspan="2">
                    <button data-id="<?php echo $item->id?>" type="submit">Save</button>
                    <button data-id="<?php echo $item->id?>" type="reset" class="voucher-content-cancel">Cancel</button>
                </td>
            </tr>
        </table>
    	<input type="hidden" name="task" value="reservation.addvoucher" />
        <input type="hidden" name="reservation_id" value="<?php echo $_GET['id'];?>" />
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
<!--End Form add new a Vouchers-->
