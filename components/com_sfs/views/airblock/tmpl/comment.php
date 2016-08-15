<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$reservation_id = JRequest::getInt('reservation_id', 0);
$passenger_id = JRequest::getInt('passenger_id', 0);
$box = JRequest::getInt('box', 0);
require_once(JPATH_COMPONENT.DS.'models'.DS.'airblock.php');
$model = new SfsModelAirblock();
$d = $model->getPassenger( $passenger_id );
?>
<style>
.row{
	margin-left:15px;
	margin-right:15px;
}
.popup-other-sub{
	/*border:3px solid #ff8806;*/
}
</style>
<div class="popup-other-sub">
<script type="text/javascript">
jQuery(function ($) {
    $("#airportForm").on("submit", function(){
		$.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=airblock.saveInvoiceNumberComment'; ?>",
			type:"POST",
			data:$( this ).serializeArray(),
			dataType: 'text',
			success:function(response){
				$('#searchForm',self.parent.document).submit();//.css('display', 'none');
				//self.parent.document.location.reload(true);
				///alert( response );
			}
		});
		return false;
    });
	
	$("#airportFormSaveAll").on("submit", function(){
		$.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=airblock.saveAllInvoiceNumberComment'; ?>",
			type:"POST",
			data:$( this ).serializeArray(),
			dataType: 'text',
			success:function(response){
				$('#searchForm',self.parent.document).submit();//.css('display', 'none');
				//self.parent.document.location.reload(true);
				///alert( response );
			}
		});
		return false;
    });
	
	$('.mark-selection-insurance').click(function(e) {
        $.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=airblock.MarkSelectionStatus'; ?>",
			type:"POST",
			data:{'colum':'insurance','value':'1'},
			dataType: 'text',
			success:function(response){
				alert( 'Save OK' );
			}
		});
		return false;
    });
	
	$('.mark-selection-nok').click(function(e) {
        $.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=airblock.MarkSelectionStatus'; ?>",
			type:"POST",
			data:{'colum':'invoice_status','value':'2'},
			dataType: 'text',
			success:function(response){
				alert( 'Save OK' );
			}
		});
		return false;
    });
	
	$('.mark-selection-touroperator').click(function(e) {
        $.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=airblock.MarkSelectionStatus'; ?>",
			type:"POST",
			data:{'colum':'touroperator_client','value':'1'},
			dataType: 'text',
			success:function(response){
				alert( 'Save OK' );
			}
		});
		return false;
    });
	
	$('.loading',self.parent.document).css('display', 'none');
	
});

function closePopup(){
	jQuery('#popup-other',self.parent.document).css('display', 'none');
}
</script>
<?php ///if( $box == 2 ): ?>
<h2><a style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px" class="sfs-button float-right" onclick="closePopup();">Close</a></h2>
<?php ///endif;?>

<?php if( $box == 1 ): ?>
<div class="row">
	<img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/comment-26.png'); ?>" alt="comment" />
</div>
<?php endif;?>

<?php if( $box == 1 ): ?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post" name="airportForm" id="airportForm" class="form-validate">
<?php elseif( $box == 2 ): ?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post" name="airportFormSaveAll" id="airportFormSaveAll" class="form-validate">
	<input type="hidden" name="insurance" value="0" />
    <input type="hidden" name="invoice_status" value="0" />
<?php endif;?>
	<table cellpadding="5" cellspacing="5">
        <?php if( $box == 2 ): ?>
        <tr>
	    	<td>
            	<button type="button" class="small-button mark-selection-insurance" style="margin-top: 10px; margin-right:10px; width:150px;">
                    <img width="20" src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/insurance_icon.png'); ?>" style="float:left; display:inline-block;" />
                    <span style="float:left; margin-top:0px; margin-left:5px; text-transform:none; line-height:13px; font-size:11px; ">
                    mark selection
                    </span>
                    <span style="float:left; margin-top:0px; margin-left:5px; text-transform:none; line-height:13px; font-size:11px; ">as Insurance
                    </span>
                    </button>
            </td>
	    </tr>
        
        <tr>
	    	<td>
            	<button type="button" class="small-button mark-selection-nok" style="margin-top: 10px; margin-right:10px; width:150px;">
                    <img width="20" src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/NOK.png'); ?>" style="float:left; display:inline-block; margin-top:5px;" />
                    <span style="float:left; margin-top:0px; margin-left:5px; text-transform:none; line-height:13px; font-size:11px; ">
                    mark selection 
                    </span>
                    <span style="float:left; margin-top:0px; margin-left:5px; text-transform:none; line-height:13px; font-size:11px; ">as NOT OK
                    </span>
                    </button>
            </td>
	    </tr>
        
        <tr>
	    	<td>
            	<button type="button" class="small-button mark-selection-touroperator" style="margin-top: 10px; margin-right:10px; width:150px;">
                    <img width="20" src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/touroperator-icon.png'); ?>" style="float:left; display:inline-block; margin-top:5px;" />
                    <span style="float:left; margin-top:0px; margin-left:5px; text-transform:none; line-height:13px; font-size:11px; ">
                    mark selection 
                    </span>
                    <span style="float:left; margin-top:0px; margin-left:5px; text-transform:none; line-height:13px; font-size:11px; ">as touroperator
                    </span>
                    </button>
            </td>
	    </tr>
        
        <?php endif;?>
		<tr>
	    	<td><?php //echo $reservation_id;?><!--class="required"-->
            	<input type="text" name="airblock[invoice_number]" value="<?php echo $d->invoice_number;?>" placeholder="Invoice number" />
            </td>
	    </tr>
        <tr>
	    	<td>
            	<textarea name="airblock[comment]" placeholder="Comment"><?php echo $d->comment;?></textarea>
            </td>
	    </tr>
        <?php if( $box == 1 ): ?>
        <tr>
	    	<td>
            	<label style="position:relative; overflow:hidden;">
            	<img width="20" src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/insurance_icon.png'); ?>" alt="comment" />
                <input type="checkbox" value="1" <?php echo ($d->insurance == 1 ) ? 'checked' : '';?> name="airblock[insurance]" />
                <span style="position:absolute; top:-6px; white-space: nowrap;">Insurance</span>
                </label>
            </td>
	    </tr>
        <tr>
	    	<td>
            	<label style="position:relative; overflow:hidden;">
            	<img width="20" src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/touroperator-icon.png'); ?>" alt="comment" />
                <input type="checkbox" value="1" <?php echo ($d->touroperator_client == 1 ) ? 'checked' : '';?> name="airblock[touroperator_client]" />
                <span style="position:absolute; top:-6px; white-space: nowrap;">Touroperator client</span>
                </label>
            </td>
	    </tr>
        <?php endif;?>
        <tr>
            <td align="right"><button type="submit" class="small-button validate" >Save</button></td>
        </tr>
		
	</table>
	<input type="hidden" name="airblock[reservation_id]" value="<?php echo $reservation_id?>" />
	<input type="hidden" name="airblock[passenger_id]" value="<?php echo $passenger_id?>" />
    <input type="hidden" name="airblock[box]" value="<?php echo $box?>" />
	<!--<input type="hidden" name="task" value="airblock.saveInvoiceNumberComment" />-->
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
</div>

