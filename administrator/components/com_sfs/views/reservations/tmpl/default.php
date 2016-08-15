<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$status_array = array(
            	'O' => 'Open',
                'P' => 'Pending',
                'T' => 'Tentative',
                'C' => 'Challenged',
                'A' => 'Approved',
                'R' => 'Archived',
                'D' => 'Deleted'
);

$date_start  = JRequest::getVar('date_start');
$date_end  = JRequest::getVar('date_end');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js">/*jquery*/</script>
<script type="text/javascript">

jQuery(document).ready(function( $ ){
	$("#send_mail_reminder").click(function(){
		var dataObj = $("#adminForm").serializeArray();console.log(dataObj);
		var arrId = [];
		var hotelArrId = [];
		var blockCodeList = [];
		var t = 0;
		for (var i = 8; i < dataObj.length; i++) {
			if(dataObj[i].name == "cid[]"){
				var v = dataObj[i].value;
				arrId.push(v);
				hotelArrId.push( $("#" + v).val() );
				blockCodeList.push( $("#" + v).attr('data-block-code') );
				t = 1;
			}
		};
		
		if( t == 0 ){
			alert('Please check a Item Block Code the send mail');
			return false;
		}else{			
			$.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=reservations.sendMailReminder'; ?>",
                type:"POST",
                data:{
                    arrData: arrId,
					hotelArr: hotelArrId,
					blockCode: blockCodeList
                },
                dataType: 'json',
                success:function(response){
					if( response.error == 0) {
						alert(response.message);   
						document.location.reload(true);
					}
                    //console.log(response);
                }
            });
		}		
	});

});	
</script>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=reservations');?>" method="post" name="adminForm" id="adminForm">
	
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />

			<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

			<button type="button" id="send_mail_reminder"><?php echo JText::_('Send reminder e-mail to upload vouchercode'); ?></button>
		</div>
		<div class="filter-select fltrt">
        	<?php echo JHtml::_('calendar',$date_start,'date_start','date_start');?>
            <?php echo JHtml::_('calendar',$date_end,'date_end','date_end');?>
        	<?php
			$filter_ws_room = $this->state->get('filter.ws_room',""); 
			?>
			<select name="filter_ws_room" class="inputbox" onchange="this.form.submit()">
				<option value="">WS or Partner</option>
				<option value="Partner" <?php if ( $filter_ws_room == 'Partner' ) :?>selected="selected"<?php endif;?>>Partner hotel</option>	
                <option value="WS" <?php if ( $filter_ws_room == 'WS' ) :?>selected="selected"<?php endif;?>>WS hotel</option>
			</select>
            
			<?php
			$filter_airline_id = $this->state->get('filter.airline_id',0); 
			?>
			<select name="filter_airline_id" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Airline</option>
				<?php foreach ($this->airlines as $airline) : ?>
					<option value="<?php echo $airline->id?>"<?php echo (int)$filter_airline_id == (int)$airline->id ? ' selected="selected"':''?>>
						<?php echo (!empty($airline->name)) ? $airline->name : $airline->company_name;?>
					</option>
				<?php endforeach;?>				
			</select>
			
			<?php
			$filter_hotel_id = $this->state->get('filter.hotel_id',0); 
			?>
            <!--<input type="text" name="product" list="filter_hotel_id"/>
			<select id="filter_hotel_id" name="filter_hotel_id" class="inputbox" onchange="this.form.submit()">
				<option value="">Select Hotel</option>
				<?php //foreach ($this->hotels as $hotel) : ?>
					<option value="<?php //echo $hotel->name; //$hotel->id?>"<?php //echo $filter_hotel_id == $hotel->name ? ' selected="selected"':''?>><?php //echo $hotel->name;?></option>
				<?php //endforeach;?>				
			</select>-->
			
            <input class="filter_hotel_id" value="<?php echo $filter_hotel_id;?>" type="text" name="filter_hotel_id" list="filter_hotel_id" placeholder="Select Hotel" style="width:180px;" />
            <datalist id="filter_hotel_id" >
                <?php foreach ($this->hotels as $hotel) : ?>
					<option value="<?php echo $hotel->name; //$hotel->id?>"<?php echo $filter_hotel_id == $hotel->name ? ' selected="selected"':''?>><?php echo $hotel->name;?></option>
				<?php endforeach;?>	
            </datalist>

			<?php
			$filter_block_status = $this->state->get('filter.blockstatus',''); 
			?>
			<select name="filter_block_status" class="inputbox" onchange="this.form.submit()">
				<option value="">Block Status</option>
				<?php foreach ($status_array as $key => $value) : ?>
					<option value="<?php echo $key?>"<?php echo $filter_block_status == $key ? ' selected="selected"':''?>><?php echo $value;?></option>
				<?php endforeach;?>				
			</select>
			

		</div>
	</fieldset>
	
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th width="10%" style="text-align:left; text-indent:5px;">
					<a href="#">Block code</a>
				</th>
				<th width="20">Note</th>
				<th width="10%" style="text-align:left;text-indent:5px;">
					<a href="#">Booked Date</a>
				</th>					
				<th style="text-align:left;text-indent:5px;">
					<a href="#">Booked By</a>
				</th>
				<th><a href="#">Issue soft<br/>block code</a></th>
				<th style="text-align:left;text-indent:5px;">
					<a href="#">Airline</a>
				</th>
				<th style="text-align:left;text-indent:5px;">
					<a href="#">Hotel</a>
				</th>									
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#" style="text-align:left;text-indent:5px;">Rooms</a>
				</th>					
				<th width="10%" style="text-align:left;text-indent:5px;">
					<a href="#" style="text-align:left;text-indent:5px;">Rate</a>
				</th>					
				<th width="7%" style="text-align:left;text-indent:5px;">
					<a href="#">Trasport</a>
				</th>					
				<th width="5%" style="text-align:left;text-indent:5px;">
					<a href="#">Status</a>
				</th>
				<th width="1%" style="text-align:left;">
					<a href="#">ID</a>
				</th>										
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
                	<input type="hidden" data-block-code="<?php echo $item->blockcode;?>" id="<?php echo $item->id;?>" value="<?php echo $item->hotel_id;?>" />
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>			
				<td>								
					<a href="index.php?option=com_sfs&view=reservation&id=<?php echo $item->id?>&blockcode=<?php echo $item->blockcode?>&hotelArr=<?php echo $item->hotel_id?>">
						<?php echo $item->blockcode;?>
					</a>					
				</td>	
				<td>
					<a rel="{handler: 'iframe', size: {x: 750, y: 650}, onClose: function() {}}" href="index.php?option=com_sfs&view=reservation&layout=notes&tmpl=component&id=<?php echo $item->id?>" class="modal">
						<img title="Add a note" alt="" src="templates/bluestork/images/menu/icon-16-user-note.png">
					</a>
				</td>		
				<td>
					<?php 					
					echo sfsHelper::getATDate($item->booked_date,JText::_('DATE_FORMAT_LC2'))
					?>
				</td>					
				<td>
					<a href="index.php?option=com_users&task=user.edit&id=<?php echo $item->booked_by;?>">
						<?php echo $item->booked_name;?>
					</a>
				</td>
				<td>
					<a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 550}, onClose: function() {}}" href="index.php?option=com_sfs&view=reservation&layout=issuevoucher&id=<?php echo $item->id?>&tmpl=component">
						Issue voucher
					</a>
				</td>
				<td>
					<?php if(!empty($item->company_name)) : ?>
						<a href="index.php?option=com_sfs&task=gh.edit&id=<?php echo $item->airline_id;?>">
                            <?php echo $item->company_name.', '.$item->airline_name_gh;?>
						</a>
					<?php else : ?>
						<a href="index.php?option=com_sfs&task=airline.edit&id=<?php echo $item->airline_id;?>">
							<?php echo $item->airline_code.', '.$item->airline_name;?>
						</a>
					<?php endif; ?>					
				</td>
				<td>
					<?php if($item->association_id==0):?>
					<a href="index.php?option=com_sfs&task=hotel.edit&id=<?php echo $item->hotel_id;?>">
						<?php echo $item->hotel_name;?> 
					</a>				    
					<?php endif;?>

                    <br/>
                    <?php if($item->ws_room > 0){?>
                        WS hotel
                    <?php }else{?>
                        Partner hotel
                    <?php }?>
				</td>					
				<td>
					Initial: <?php echo (int)$item->sd_room+(int)$item->t_room+(int)$item->s_room+(int)$item->q_room;?><br />
					Claimed: <?php echo $item->claimed_rooms;?>					
				</td>					
				<td>
					S Rate: <strong><?php echo floatval($item->s_rate);?></strong><br />
					D Rate: <strong><?php echo floatval($item->sd_rate);?></strong><br/>
					T Rate: <strong><?php echo floatval($item->t_rate);?></strong><br/>
					Q Rate: <strong><?php echo floatval($item->q_rate);?></strong>
				</td>	
				
				<td>
					<?php echo (int)$item->transport==1 ? 'Included':'';?>
				</td>																						
				<td>
					<span class="<?php echo JString::strtolower($status_array[$item->status])?>-status">
						<?php echo $status_array[$item->status];?>
					</span>
				</td>	
				<td>
					<?php echo $item->id; ?>
				</td>	
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php echo $this->loadTemplate('batch');?>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$('li[id="toolbar-make_report vouchers-download-pdf"] a').click(function () {
		var hr = $(this).attr('href');
		var date_start = $('#date_start').val();
		var date_end = $('#date_end').val();
		var filter_search = $('#filter_search').val();
		$('#adminFormDownload #date_start').val(date_start);
		$('#adminFormDownload #date_end').val(date_end);
		$('#adminFormDownload #filter_search').val(filter_search);
		$('#adminFormDownload input[name="task"]').val('vouchersdownload.vouchers_download');
		var oj = setTimeout(function(){
			$('#adminFormDownload').submit();
			clearTimeout( oj );
		},70);
	});
	
	$('li[id="toolbar-make_report vouchers-download-excel"] a').click(function () {
		var hr = $(this).attr('href');
		var date_start = $('#date_start').val();
		var date_end = $('#date_end').val();
		var filter_search = $('#filter_search').val();
		$('#adminFormDownload #date_start').val(date_start);
		$('#adminFormDownload #date_end').val(date_end);
		$('#adminFormDownload #filter_search').val(filter_search);
		$('#adminFormDownload input[name="task"]').val('makereport.export');
		
		var oj = setTimeout(function(){
			$('#adminFormDownload').submit();
			clearTimeout( oj );
		},70);
	});
	
});//makereport.export
</script>
<?php
$filter_search = JRequest::getVar('filter_search');
$date_start  = JRequest::getVar('date_start');
$date_end  = JRequest::getVar('date_end');
$filter_ws_room = JRequest::getVar('filter_ws_room');
$filter_airline_id = JRequest::getVar('filter_airline_id');
$filter_hotel_id = JRequest::getVar('filter_hotel_id');
$filter_block_status = JRequest::getVar('filter_block_status');
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs');?>" method="post" name="adminFormDownload" id="adminFormDownload">
		<input type="hidden" name="task" value="vouchersdownload.vouchers_download" />
        <input type="hidden" name="filter_search" id="filter_search" value="<?php echo $filter_search; ?>" />
        <input type="hidden" name="date_start" id="date_start" value="<?php echo $date_start; ?>" />
        <input type="hidden" name="date_end" id="date_end" value="<?php echo $date_end; ?>" />        
		<input type="hidden" name="ws_room" id="ws_room" value="<?php echo $filter_ws_room; ?>" />
		<input type="hidden" name="airline_id" id="airline_id" value="<?php echo $filter_airline_id; ?>" />
		<input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo $filter_hotel_id; ?>" />
        <input type="hidden" name="status" id="status" value="<?php echo $filter_block_status; ?>" />
        
		<?php echo JHtml::_('form.token'); ?>
</form>

<script>
///document.getElementById('date_start').placeholder = 'From date';
///document.getElementById('date_end').placeholder = 'To date';
</script>
<style>
#date_start, #date_end{
	margin-right:0px;
}
#date_start_img, #date_end_img{
	margin-left:0px;
}
#alt-toolbar .toolbar-list a span.icon-32-make_report{
	display:none;
}
#alt-toolbar .toolbar-list li.button a{
	border:1px solid #999;
	padding:5px 7px;
	background-color:#fff;
	color:#000 !important;
	margin-bottom:5px;
}
</style>

