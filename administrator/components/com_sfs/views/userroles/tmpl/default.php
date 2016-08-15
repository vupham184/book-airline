<?php
// no direct access
defined('_JEXEC') or die;
$items = $this->items;

//jimport('joomla.form.helper');
//JFormHelper::loadFieldClass('list');
//JHTML::_('behavior.modal');   
JHTML::_('behavior.modal', 'a.modal');              
?>
<style>
.list-code {
	float: left;
	width: 30px;
}
.max-airport-code {
	max-width: 810px;
	overflow-x: scroll;
}
.max-list-h {
	max-height: 500px;
	overflow-y: scroll;
}
.adminlist p {
	padding: 0px;
	margin: 0px;
}
.adminlist td.padding-b1 {
	padding-bottom: 1px;
}
.adminlist td.padding-b2 {
	padding-bottom: 2px;
}
.adminlist td.padding-b3 {
	padding-bottom: 3px;
}
/*
td {
    border-collapse:collapse;
    border: 1px black solid;
}
tr:nth-of-type(5) td:nth-of-type(1) {
    visibility: hidden;
}*/

.padding-b1.rotate {
	position:relative;
}
.rotate .w{
	
	/* FF3.5+ */
	-moz-transform: rotate(-90.0deg);
	/* Opera 10.5 */
	-o-transform: rotate(-90.0deg);
	/* Saf3.1+, Chrome */
	-webkit-transform: rotate(-90.0deg);
  /* IE6,IE7 */
  filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
	/* IE8 */
	-ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
	/* Standard */
	transform: rotate(-90.0deg);
	width:262px;
	/*text-align:center;*/
	font-weight:bold;
	position:absolute;
	writing-mode:horizontal-tb;
	left:-112px;
	top:130px;
}
.d-table {
	overflow: hidden;
}
.d-col {
	float: left;
}
.d-row {
	clear: both;
}
.d-col-1 {
	width: 250px;
}
.d-col-2 {
	max-width: 970px;
	overflow-x: scroll;
	position: relative;
}
.title, .title-text, .contents-input {
	padding: 5px 0px 5px 7px;
}
.row-contents {
	border-bottom: 1px solid #CCC;
}
.max-h {
	height: 88px;
	margin-top: 65px;/*text-align:center;*/
}
.title strong {
	vertical-align: bottom;
}
.d-col-w {
	width: 160px;
}
.d-col-l {
	width: auto;
}
.contents-input {
	text-align: center;/*padding-left:5px;*/
}
.row-contents-r {
 width:<?php echo count($this->menus) * 174;
?>px;
}
.contents-input {
	padding-bottom: 4px;
}
#top-title-a {
	/*overflow-x: scroll;*/
	background: ddd;
	z-index: 2;
}
.show-add-new {
	display: none;
	background-color: #CCC;
	border: 10px solid #000;
	position: absolute;
	width: 40%;
	z-index: 1000;
	margin: auto;
	padding: 7px 15px;
	top: 0px;
}
.show-add-new-close {
	padding: 3px 10px;
	border-radius: 10px;
	background-color: #999;
	color: #fff;
	font-weight: bold;
}
#ap-content, .d-close {
	position: relative;
}
.show-add-new-close {
	position: absolute;
	right: -29px;
	top: -17px;
	cursor: pointer;
}
#top-title-a-left, #top-title-a-left-title {
	position: absolute;
	z-index: 1;
}
#top-title-a-left-title {
	z-index: 3;
}
.theme5 #top-title-a-left .admintable, .theme5 #top-title-a-left .adminlist {
	background-color: transparent;
}
.theme5 #top-title-a-left .adminlist tr td, .theme5 #top-title-a-left-title .adminlist tr td {
	background-color: #f6f6f6;
}
.theme5 #top-title-a-left .adminlist tr.txt-user td {
	padding-bottom: 5px;
}
.margin-bottom{
	margin-bottom:250px;
}
.w-par{
	width:20px;
}
</style>
<script language="javascript">
	jQuery(function( $ ){
		///$('#toolbar-new a').addClass('modal').attr('rel',"");
		$('#toolbar-new a').click(function(e) {
			$('.show-add-new').css({'left':( $('.show-add-new').width()/2) +'px','display':'block'});
        });
		
		<?php if( isset( $_GET['is_close'] ) && $_GET['is_close'] == "" ):?>
			var tOu = setTimeout(function(){
				$('#toolbar-new a').click();
				clearTimeout(tOu);
			}, 1000);
		<?php endif; ?>
		
		
	});
	

jQuery(function($){
	///$('#top-title-a').css({'max-width': $('#adminForm').width() + 'px'});
	$('#adminForm').scroll(function(){
		//show select airline_the_add_name
		/*if( $(this).scrollTop() >= 15 ){
			$('.show-add-new').css({'top': $(this).scrollTop() + 'px'});
		}
		else {
			$('.show-add-new').css({'top':'0px'});
		}*/
		if( $(this).scrollTop() >= 100 ){
			///$('#top-title').css({'position':'fixed', 'background-color':'#e3e3e3','border-bottom':'0px'});
			$('#top-title-a').css({'position':'absolute','display':'block','top': $(this).scrollTop() + 'px'});
			$('#top-title-a-left-title').css({'display':'block','top': $(this).scrollTop() + 'px'});
		}
		else {
			$('#top-title-a').css({'position':'', 'display':'none'});
			$('#top-title-a-left-title').css({'position':'', 'display':'none'});
		}
		
	});
	
	$('.show-add-new-close, #toolbar-cancel a').click(function(e) {
		 $('#is_close').val("");
        $('.show-add-new').css({'display':'none'});
		document.getElementById("adminForm").reset();
    });
	
	
	$('#adminFormN').submit(function(e) {
		var t = true;
		/*$('input[type="checkbox"]').each(function(index, element) {
            if( $(this).is(':checked')){
				t = true;
			}
        });		*/
		if( $('#airline_the_add_name option:selected').val() == '' ) {
			t = false;
			alert('Please choose an airline');
		}
		/*else if ( $('#users_the_add_name option:selected').val() == '' ) {
			t = false;
			alert('Please select users');
		}*/
		else if ( $('#userrole_the_add_name option:selected').val() == '' ) {
			t = false;
			alert('Please select user role');
		}
		
        return t;
    });
	$('#toolbar-apply a').click(function(e) {
		 $('#is_close').val("");
		 if( $('.show-add-new').css('display') != 'none' ) {
       		$('#adminFormN').submit();
		 }
		 else {//lưu khi checked on table list
			$('#adminForm').submit();
		 }
    });
	
	$('#toolbar-save a, #toolbar-save a ').click(function(e) {
		if( $('.show-add-new').css('display') != 'none' ) {
			$('#is_close').val(1);
			$('#adminFormN').submit();
		}
		else {//lưu khi checked on table list
			$('#adminForm').submit();
		 }
    });
	
	
	var lastScrollLeft = 0;
	$('#adminForm').scroll(function() {
		var documentScrollLeft = $('#adminForm').scrollLeft();
		if (lastScrollLeft != documentScrollLeft) {
			console.log('scroll x');
			lastScrollLeft = documentScrollLeft;
			if( lastScrollLeft > 160 ) {
				$('#top-title-a-left').css({'left' : lastScrollLeft + 'px', 'display':'block' });
				$('#top-title-a-left-title').css({'left' : lastScrollLeft + 'px'});
			}
			else {
				$('#top-title-a-left').css({'left' : lastScrollLeft + 'px', 'display':'none' });
				$('#top-title-a-left-title').css({'left' : lastScrollLeft + 'px', 'display':'none'});
			}
		}
	});
	
	/*
	$('#top-title-a').mousemove(function(event) {
    	captureMousePosition(event);
	}).scroll(function(event) {
		//console.log( xMousePos );
		$('#adminForm').scrollLeft( xMousePos );
		xMousePos = event.pageX + $(document).scrollLeft();
		///yMousePos = event.pageY + $(document).scrollTop();
		///window.status = "x = " + xMousePos + " y = " + yMousePos;
	});
	*/
	
	/*
	var xMousePos;
	function captureMousePosition(event){
		xMousePos = event.pageX;
		///yMousePos = event.pageY;
		//window.status = "x = " + xMousePos + " y = " + yMousePos;
		///console.log( xMousePos );
	}
	
	$('#adminForm').mousemove(function(event) {
    	captureMousePosition(event);
	}).scroll(function(event) {
		//console.log( $('#adminForm').scrollLeft() );
		if ( $('#adminForm').scrollLeft() == 0 && $('#adminForm').scrollTop() == 0 )
			$('#top-title-a').css("left", (($('#adminForm').scrollLeft()+100)) + 'px');
		else if( $('#adminForm').scrollTop() > 120 && $('#adminForm').scrollLeft() == 0 ) {
			$('#top-title-a').css("left", (($('#adminForm').scrollLeft()+90)) + 'px');
		}
		else 
			$('#top-title-a').css("left", "-" + ($('#adminForm').scrollLeft() - 90) + 'px');//.scrollLeft( xMousePos );
		///xMousePos = event.pageX + $(document).scrollLeft();
		///yMousePos = event.pageY + $(document).scrollTop();
		///window.status = "x = " + xMousePos + " y = " + yMousePos;
	});
	*/
	/*
	$('#airline_the_add_name').change(function(e) {
		$('#userrole_the_add_name option').attr("selected", false);
        var airline_id = $(this).val();
		$.get('<?php echo JRoute::_('index.php?option=com_sfs&view=userroles');?>',
			{
				'airline_id':airline_id,
				'task':'userroles.getUserOfAirline'
			},function( data ){
				$('#users_the_add_name').html('').html( data );
			}
		);
    });
	*/
	$('.select-all').click(function(e) {
		var id = $(this).attr("data-id");
        if( $(this).is(':checked') ) {
			$('#row-' + id).find(".row-" + id).attr("checked", true);
		}
		else {
			$('#row-' + id).find(".row-" + id).attr("checked", false);
		}
    });

});

function findSelected( data_id ){
	jQuery(function($){
		if( data_id != "" ) {
			$('#userrole_the_add_name option').attr("selected", false);
			var strArr = data_id.split(",");
			for( i = 0; i < strArr.length; i++) {
				$('#userrole_the_add_name option').each(function(index, element) {
					console.log( element );
					if( $(this).val() != '' ){
						if( $(this).val() == strArr[i] ){
							$(this).attr("selected", true);
						}
					}
				});
			}
		}
	});
     
}
</script>
<div class="show-add-new">
  <form action="" method="post" name="adminFormN" id="adminFormN">
    <div class="d-close"> <span class="show-add-new-close">X</span> </div>
    <table>
      <tr>
        <th> Airline: </th>
        <td><select name="airline_the_add_name" id="airline_the_add_name">
            <option value="">Choose</option>
            <?php foreach ($this->airlines as $airline ): ?>
            <option value="<?php echo $airline->id;?>"><?php echo $airline->name;?></option>
            <?php endforeach;?>
          </select></td>
      </tr>
      <tr style="display:none;">
        <th> Users: </th>
        <td><select name="users_the_add_name[]" id="users_the_add_name" multiple="multiple" 
                style="min-height:40px; max-height:150px; min-width:160px;">
            <option value="">Choose</option>
          </select></td>
      </tr>
      <tr>
        <th> Userrole: </th>
        <td><select name="userrole_the_add_name[]" id="userrole_the_add_name" multiple="multiple" 
                style="height:150px; min-width:160px;">
            <option value="">Choose</option>
            <?php foreach ( $this->items as $item_g ): ?>
            <option value="<?php echo $item_g->group_id;?>"><?php echo $item_g->g_name;?></option>
            <?php endforeach;?>
          </select></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="hidden" name="is_close" id="is_close" value="" />
          <input type="submit" value="Save" style="display:none;" /></td>
      </tr>
    </table>
    <input type="hidden" name="task" value="userroles.save" />
    <?php echo JHtml::_('form.token'); ?>
  </form>
</div>
<form action="" method="post" class="max-list-h" style="position:relative;"name="adminForm" id="adminForm">
  <div id="top-title-a" style="display:none;">
    <table class="adminlist" style="margin-top:0px; margin-left:3px;">
      <tr>
        <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>ID</strong></td>
        <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>Airline</strong></td>
        <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>Userrole</strong></td>
        <td><div style="width:60px;">&nbsp;</div></td>
        <?php foreach  ( $this->menus as $menu ): ?>
        <td class="padding-b1 rotate"><div class="w-par"><div class="w"><?php echo $menu->title; ?></div></div></td>
        <?php endforeach;?>
      </tr>
    </table>
  </div>
  <div id="top-title-a-left-title" style="display:none;">
    <table class="adminlist" style="margin-top:0px; margin-left:0px;">
      <tr class="title-1" >
        <td style="" valign="bottom"><div class="margin-bottom"></div>
          <strong>ID</strong></td>
        <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>Airline</strong></td>
        <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>Userrole</strong></td>
        <td align="center" style="background-color:transparent;" colspan="<?php echo count($this->menus);?>"><div style="width:53px;">&nbsp;</div></td>
      </tr>
    </table>
  </div>
  <div id="top-title-a-left" style="display:none; width:213px;">
    <table class="adminlist" style="margin-top:0px; " >
      <tr class="title-1" >
        <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>ID</strong></td>
        <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>Airline</strong></td>
        <td style="width:150px;" valign="bottom"><div class="margin-bottom"></div>
          <strong>Userrole</strong></td>
        <td align="center" style="background-color:transparent;" colspan="<?php echo count($this->menus);?>"></td>
      </tr>
      <?php $i = 0; foreach ( $this->items as $item) : $i++;?>
      <tr id="row-<?php echo $i;?>" class="txt-user">
        <td style="white-space:nowrap;"><?php echo $item->group_id; ?></td>
        <td style="white-space:nowrap;"><?php echo ( $item->code != '' ) ? $item->code : 'Default'; ?></td>
        <td style="white-space:nowrap;"><?php echo $item->g_name; ?></td>
        <td align="center" style="background-color:transparent;" colspan="<?php echo count($this->menus);?>"></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>
  <table class="adminlist" style="margin-top:0px; " >
    <tr class="title-1" >
      <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
        <strong>ID</strong></td>
      <td style="width:50px;" valign="bottom"><div class="margin-bottom"></div>
        <strong>Airline</strong></td>
      <td style="width:150px;" valign="bottom"><div class="margin-bottom"></div>
        <strong>Userrole</strong></td>
      <!--<td><div style="width:50px;">&nbsp;</div></td>-->
      <?php foreach  ( $this->menus as $menu ): ?>
      <td class="padding-b1 rotate" ><div class="w-par"><div class="w"><?php echo $menu->title; ?></div></div></td>
      <?php endforeach;?>
    </tr>
    <?php $i = 0; foreach ( $this->items as $item) : $i++;?>
    <tr id="row-<?php echo $i;?>">
      <td style="white-space:nowrap;"><?php echo $item->group_id; ?></td>
      <td style="white-space:nowrap;"><?php echo ( $item->code != '' ) ? $item->code : 'Default'; ?></td>
      <td style="white-space:nowrap;"><?php echo $item->g_name; ?></td>
      <!--<td>
                <div style="width:50px;">All:<input data-id="<?php echo $i;?>" style="display:inline;" type="checkbox" class="select-all"  /></div>
                </td>-->
      <?php 
				foreach  ( $this->menus as $menu ): 
				$values = str_replace('"',"'", json_encode( array('group_id' => $item->group_id, 'menu_id' => $menu->id) ) ); 
				?>
      <td align="center"><?php //echo $item->airline_id .'=='. $menu->access;?>
        <input name="newUserrole[<?php echo $item->group_id . '_' . $menu->id . '_' . $i;?>]" type="checkbox" class="padding-b2 row-<?php echo $i;?>" 
				   <?php echo($item->group_id == $menu->access )? "checked=\"checked\"" :"";?> 
                   value="<?php echo $values;?>"
                   /></td>
      <?php endforeach;?>
    </tr>
    <?php endforeach;?>
    <tfoot>
      <tr>
        <td colspan="<?php echo count($this->menus)+1;?>"><?php echo $this->pagination->getListFooter(); ?></td>
      </tr>
    </tfoot>
  </table>
  <div>
    <input type="hidden" name="task" value="userroles.saveList" />
    <?php echo JHtml::_('form.token'); ?> </div>
</form>
