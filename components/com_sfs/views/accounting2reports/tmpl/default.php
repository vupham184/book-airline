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
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=tracepassenger'); ?>" method="post">

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
            <button type="submit" class="small-button" style="margin-top: 20px; margin-right:10px;">Search</button>
            </td>
            
            <td style="border-left:5px solid #F381C9; padding-left:10px;"> 
            	<p>
                	Currently <?php echo count($this->passengers);?> in selection
                </p>
                <p>
                	<button type="button" class="small-button tracepassenger-export-to-excel" style="margin-top: 20px; margin-right:10px;">
                    Export to Excel
                    </button>
                </p>
            </td>
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


<input type="hidden" name="task" value="" />

<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />

<?php echo JHtml::_('form.token'); ?>
<?php endif;?>
</form>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=tracepassenger'); ?>" method="post" id="ftracepassenger-export-to-excel" name="ftracepassenger-export-to-excel">

<input type="hidden" name="filter_date" value="<?php echo $this->state->get('filter_date');?>" />
<input type="hidden" name="filter_until_date" value="<?php echo $this->state->get('filter_until_date');?>" />
<input type="hidden" name="task" value="report.TracepassExportExcel" />

<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
<input type="submit"  style="display:none;" />
<?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
	jQuery(function( $ ){
		$('.tracepassenger-export-to-excel').click(function(e) {
            $('#ftracepassenger-export-to-excel').submit();
        });
	});
</script>

</div>