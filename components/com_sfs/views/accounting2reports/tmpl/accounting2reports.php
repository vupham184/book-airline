<?php
defined('_JEXEC') or die;

$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);

?>
<style>
table.bg{
	border-top:3px solid #12A6B5; background-color:#F0F8FF; padding:10px;
	width:100%;
}
table.bg tr.trs td{
	padding:10px 10px 0px 10px;
}
.popup-other{
	position:absolute;
	border:3px solid #ff8806;
	padding:10px;
	z-index:1;
	background-color:#fff;
}
.sfs-white-wrapper, .sfs-main-wrapper, .sfs-orange-wrapper{
	overflow:visible;
}
</style>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3></h3>
	</div>
</div>

<div id="sfs-wrapper" class="main" style="padding-left:10px; padding-right:10px;">
<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=accounting2reports&layout=accounting2reports'); ?>" method="post">

<div class="sfs-main-wrapper" style="padding:0 1px 0 1px ; margin-bottom:0px;">
<div class="sfs-orange-wrapper">
<div class="sfs-white-wrapper" style="position:relative;">
	<span style="position:absolute; top:30px; left:20px;">Search</span>
	<table cellpadding="0" cellspacing="0" border="0" class="fs-14 bg">
    	
        <tr class="trs">
        	<td class="midpaddingbottom">From:</td>
            <td class="midpaddingbottom">
            <?php SfsHelperField::getCalendar('filter_date',  (JRequest::getVar('filter_date') == '') ? date('Y-m-d'):JRequest::getVar('filter_date'))?>
            </td>
            <td class="midpaddingbottom" style="vertical-align:bottom;">&nbsp;</td>
            <td class="midpaddingbottom">
            <button type="submit" class="small-button" style="margin-top: 20px; margin-right:10px;">Search</button>
            </td>
            <td style="border-left:5px solid #12A6B5; padding-left:10px;" rowspan="3"> 
            	<p><?php $count_odl = count($this->passengers);?>
                	Currently <span class="totalItems" id="<?php echo $count_odl;?>"><?php echo $count_odl;?></span> in selection
                </p>
                <p><!--tracepassenger-export-to-excel-->
                	<button type="button" class="small-button accept-all" style="margin-top: 10px; margin-right:10px; width:150px;">
                    <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/OK.png'); ?>" style="float:left;" />
                    <span style="float:left; margin-top:4px; margin-left:5px;">
                    Accept All
                    </span>
                    </button>
                    <br />
                    <?php 
					$reservation_id = JRequest::getInt('id', 0);
					$url_comment = JURI::base()."index.php?option=com_sfs&view=airblock&tmpl=component&layout=comment&reservation_id=$reservation_id"; 
					$url_comment .= '&passenger_id=0&box=2';
					?>
                    <span style="position:relative;">
                    	
                        <a data-size-x="230" data-size-y="340" class="ico-3 ico-last open-popup other" 
                        href="<?php echo $url_comment;?>" 
                        style="position: absolute; right: -150px; top: -10px; text-decoration:underline;">Other</a>
                    	<!--<a rel="{handler: 'iframe', size: {x: 230,y: 340}}" class="ico-3 ico-last modal" 
                        href="<?php echo $url_comment;?>" style="position: absolute; right: -150px; top: -10px; text-decoration:underline;">Other</a>-->
                    </span>
                    <div style="position:relative;">
                        <div id="popup-other" class="popup-other" style="display:none;">
                        	<div class="loading"></div>
                            <div class="contents-popup"></div>
                        </div>
                    </div>
                </p>
            </td>
        </tr>
        <tr class="trs">
        	<td class="midpaddingbottom">To:</td>
            <td class="midpaddingbottom">
            
            <?php 
			$date = strtotime( date('Y-m-d') );
			$date = strtotime("+1 day", $date);			
			SfsHelperField::getCalendar('filter_until_date', (JRequest::getVar('filter_until_date') == '' ) ? date('Y-m-d', $date) : JRequest::getVar('filter_until_date'));?>
            </td>
            <td class="midpaddingbottom">&nbsp;</td>
            <td class="midpaddingbottom">
            <button type="reset" class="small-button bnt-reset" style="margin-top: 20px; margin-right:10px;">Reset</button>
            </td>
        </tr>
		
	</table>
</div>
</div>
</div>

<div class="sfs-main-wrapper" style="padding:0px 10px">
	<div class="floatbox sfs-white-wrapper" style="padding-top:0px;">
		<?php echo $this->loadTemplate('vouchers');?>
	</div>
</div>
<input type="hidden" name="task" value="" />

<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />

<?php echo JHtml::_('form.token'); ?>
</form>


</div>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=tracepassenger'); ?>" method="post" id="ftracepassenger-export-to-excel" name="ftracepassenger-export-to-excel">

<input type="hidden" name="filter_date" value="<?php echo JRequest::getVar('filter_date');?>" />
<input type="hidden" name="filter_until_date" value="<?php echo JRequest::getVar('filter_until_date');?>" />
<input type="hidden" name="task" value="accounting2reports.TracepassExportExcel" />

<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
<input type="hidden" name="export_withID" id="export_withID" value="" />
<input type="hidden" name="check_select_report" id="check_select_report" value="" />

<input type="hidden" name="txt_filter_airport" id="txt_filter_airport" value="" />
<input type="hidden" name="txt_filter_date" id="txt_filter_date" value="" />
<input type="hidden" name="txt_filter_FlightN" id="txt_filter_FlightN" value="" />
<input type="hidden" name="txt_filter_BlockCode" id="txt_filter_BlockCode" value="" />
<input type="hidden" name="txt_filter_passengers_name" id="txt_filter_passengers_name" value="" />
<input type="hidden" name="txt_filter_Hotelname" id="txt_filter_Hotelname" value="" />

<input type="submit"  style="display:none;" />
<?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
	jQuery(function( $ ){
		/*
		$('#export_withID').val('');
		$('.tracepassenger-export-to-excel').click(function(e) {
			var Arr = Array(), i = 0;
			 var rows  =  $('#DataTable').DataTable().columns(0,{ search:'applied' }).data();
			 for( i = 0; i < rows.length; i++){
				 Arr[i] = rows[i];
			 }

			var count_old = parseInt( $('.totalItems').attr('id') );
			var count_new = parseInt( $.trim( $('.totalItems').text() ) );
			if ( count_old == count_new ) {
				$('#check_select_report').val('All');
			}
			else {
				$('#check_select_report').val('');
			}
			
			if(Arr.toString() == '' )
				$('#export_withID').val('0');
			else
				$('#export_withID').val(Arr.toString());
			
			
			$('#txt_filter_airport').val($('#Airport').val());
			$('#txt_filter_date').val($('#Date').val());
			$('#txt_filter_FlightN').val($('#FlightN').val());
			$('#txt_filter_BlockCode').val($('#BlockCode').val());
			$('#txt_filter_passengers_name').val($('#passengers_name').val());
			$('#txt_filter_Hotelname').val($('#Hotelname').val());
			
			var tt = setTimeout(function(){
           		$('#ftracepassenger-export-to-excel').submit();
				clearTimeout(tt);
			},90);
			
        });
		
		$('.bnt-reset').click(function(e) {
            $('#DataTable tbody tr').removeClass('hidden');
			$('.totalItems').text( $('#DataTable tbody tr').length );
        });
		
		//bo khg xuat excel
	*/
		$('.open-popup').click(function(e) {
			$('.loading').css('display', 'block');
			var p = $( this ).offset();
			var t = $('.open-popup').offset();
			var x = $(this).attr("data-size-x");
			var y = $(this).attr("data-size-y");
			
			if ( ! $(this).hasClass('other') )
				$('#popup-other').css('bottom', "-" + (p.top - t.top) + 'px');
			else
				$('#popup-other').css('bottom', '0px');
				
			var vhref = $(this).attr("href");
			var boj = $('.popup-other');	
			$('.contents-popup').html("");		
            boj.css({"display":"block"});			
			$('.contents-popup').append('<iframe src="' + vhref + '" width = "' + x + '" height="' + y + '"></iframe>');
			//boj2.css({"width": + x + "px", "height": y + "px"});
			//boj2.attr("src", vhref);
			return false;
        });
	});
</script>