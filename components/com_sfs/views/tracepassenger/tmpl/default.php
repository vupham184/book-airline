<?php
defined('_JEXEC') or die;

$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);

JHtml::_('behavior.keepalive');
$filter_lastname = $this->state->get('filter_lastname');
if( $filter_lastname == 'min 3 letters' )
{
	$filter_lastname = null;
}
$passenger_id = JRequest::getInt('passenger_id');
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3>Trace Passengers</h3>
	</div>
</div>
<?php if ( $passenger_id > 0 ):?>
	<?php echo $this->loadTemplate('individualpassengerpage');?>
<?php else:?>
<div id="sfs-wrapper" class="main">
<form action="<?php echo JRoute::_('index.php'); ?>" method="get">
<input type="hidden" name="option" value="com_sfs" />
<input type="hidden" name="view" value="tracepassenger" />
<div class="sfs-main-wrapper" style="padding:0 1px 0 1px ; margin-bottom:15px;">
<div class="sfs-orange-wrapper">
<div class="sfs-white-wrapper">
	<table cellpadding="0" cellspacing="0" border="0" class="fs-14">
		<tr>
			<td class="midpaddingbottom">Select date(s)</td>
			<td class="midpaddingleft midpaddingbottom">
				from <?php SfsHelperField::getCalendar('filter_date', $this->state->get('filter_date'));?>
			</td>
			<td class="midpaddingleft midpaddingbottom">
				until <?php SfsHelperField::getCalendar('filter_until_date', $this->state->get('filter_until_date'));?>
			</td>
			<td class="midpaddingleft midpaddingbottom">
            
            </td>
            
            <!--<td style="border-left:5px solid #F381C9; padding-left:10px;"> 
            	<p>
                	Currently <?php echo count($this->passengers);?> in selection
                </p>
                <p>
                	<button type="button" class="small-button tracepassenger-export-to-excel" style="margin-top: 20px; margin-right:10px;">
                    Export to Excel
                    </button>
                </p>
            </td>-->
		</tr>
		<tr><td colspan="3">
			Guest relations ID: <input name="filter_guest_relations" id="filter_guest_relations" type="text" style=" width: 175px;margin-left:6px;" value="<?php echo $this->state->get('filter_guest_relations'); ?>">
		</td>
		<td>
			<button type="submit" class="small-button" style="margin-top: 20px; margin-left:10px;">Search</button></td>
		</tr>
	</table>
	
	<div class="midmarginbottom fs-14">
		With below list you can trace all passengers of which you have listed the names during the voucher issueing process.
	</div>
</div>
</div>
</div>

<div class="sfs-main-wrapper" style="padding:10px">
	<div class="floatbox sfs-white-wrapper">
		<?php echo $this->loadTemplate('vouchers');?>
	</div>
</div>

<?php echo $this->loadTemplate('cancelledvouchers');?>


<!--<input type="hidden" name="task" value="" />-->
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />

<?php //echo JHtml::_('form.token'); ?>
<?php endif;?>
</form>

</div>
<script type="text/javascript">
	jQuery(function($){
			jQuery("#filter_guest_relations").keydown(function (e) {
		        // Allow: backspace, delete, tab, escape, enter and .
		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Allow: Ctrl+A, Command+A
		            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
		             // Allow: home, end, left, right, down, up
		            (e.keyCode >= 35 && e.keyCode <= 40)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
	    	});
	});
</script>