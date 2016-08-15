<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$printUrl  = JURI::base().'index.php?option=com_sfs&view=voucher&tmpl=component&reprint=1';
$printUrl .= '&voucher_id='.$this->voucher->id;

?>
<?php /* @var $wsBooking Ws_Do_Book_Response */?>
<?php $wsBooking = @$this->wsBooking?>

<script type="text/javascript">
    jQuery(function ($) {

    	iframeModalAutoSize();

    	$('#printRequest').on('click', function(){
    		sfsPopupCenter2('<?php echo $printUrl;?>', 'PrintVoucher',745,690);
        });

		var $mess = $('#messages-box');
    	var $form = $("#voucherPrintForm");
        $form.on('submit', function(){
            $.ajax({
					url: $form.attr('action'),
					data: $form.serialize(),
					type: 'post',
					dataType: 'json',
					beforeSend: function(){
						$('#wsRequestSpinner').show();
            		},
            		complete: function(){
            			$('#wsRequestSpinner').hide();
            		},
					success: function(json) {
    					$mess.toggleClass('uk-alert', true);
    					$mess.toggleClass('uk-alert-danger', json.code != 0);
    					$mess.toggleClass('uk-alert-success', json.code == 0);
						$mess.html(json.message);
						iframeModalAutoSize();
        			}
                });
			return false;
        });
    });
</script>

<style>
<!--
body.contentpane,iframe{
	border:none !important;
	padding: 0 !important;
	margin: 0 !important;
}
body.contentpane{
	padding: 10px !important;
	background:#82adf1;
}
.sfs-white-wrapper{
	background:#FFFFFF;
	padding:20px;
	overflow:hidden;
}
#wsRequestSpinner{
	width: 16px;
	height: 16px;
	display: block;
	margin-right: 5px;
	display: none;
}
.singlevoucher-passenger-title{
	width: 50px !important;
}

.preview-number{
	color: green;
	border: 1px solid green;
	padding: 5px;
	border-radius: 5px;
	width: 160px;
}
-->
</style>

<script type="text/javascript">	
<!--
function sfsPopupCenter2(pageURL,title,w,h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	win = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);	
}
-->	
</script>

<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post" name="voucherPrintForm" id="voucherPrintForm" class="form-validate">

<div id="sfs-wrapper" class="match">
	<div id="sfs-voucher-print-form">
		<div id="messages-box"></div>
		<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="min-height:50px;">		
			<div class="sfs-row">
				<div class="sfs-column-left" style="width:140px;">
					Flight number *
				</div>
				<?php echo JString::strtoupper($this->voucher->flight_code);?>
			</div>			
			<div class="sfs-row">
				<div class="sfs-column-left" style="width:140px;">
					IATA stranded code *
				</div>
				<?php echo JString::strtoupper($this->voucher->flight_delay_code);?>
			</div>
		</div>
		
		<?php if($this->voucher->comment) : ?>
		<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom">			
			<?php echo JText::_('COM_SFS_AIRLINE_FLIGHT_NUMBER_ADD_COMMENT');?>:<br />				
			<?php echo $this->voucher->comment?>
		</div>
		<?php endif; ?>
		
		<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom">
		
			<div>Insert names (optional)</div>
			<div class="fs-11" style="padding-top: 5px; line-height:13px;">When you insert names it will be easier for you to trace the passengers at a later stage.</div>
			
			<div class="midmargintop">															
			<?php #print_r($this->voucher);?>
				<table border="0" width="100%" class="match-passengers">
					<thead>
						<tr>
							<th></th>
							<th>Title</th>
							<th>First name</th>
							<th>Last name</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 0;?>
						<?php foreach($this->trace_passengers as $p) : ?>
						<tr>
							<td>Passenger <?php echo ++$i?></td>
							<td>
								<?php echo $p->title;?>
							</td>
							<td>
								<?php echo $p->first_name;?>
							</td>
							<td>
								<?php echo $p->last_name;?>
							</td>
						</tr>
						<?php endforeach;?>
						<tr>
							<td>Phone number</td>
							<td colspan="2">						
								<?php echo @$this->trace_passengers[0]->phone_number?>
							</td>						
						</tr>
					</tbody>
				</table>
			</div>
			
		</div>	
		
		<?php if($this->voucher->return_flight_number) : ?>
		<div class="sfs-white-wrapper sfs-voucher-print-box floatbox midmarginbottom" style="min-height:50px;">
			<div class="sfs-row">
				<div class="sfs-column-left" style="width:120px;">
					Return flight number
				</div>
				<?php echo $this->voucher->return_flight_number?>
			</div>			
			<div class="sfs-row">
				<div class="sfs-column-left" style="width:120px;">
					Return flight date
				</div>
				<?php echo JHTML::_('date', $this->voucher->return_flight_date , JText::_('DATE_FORMAT_LC3'), false )?>
			</div>
		</div>
		<?php endif; ?>
		
		<div class="sfs-white-wrapper sfs-voucher-print-box floatbox">				
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr valign="top">
				    	<td valign="top" style="padding-bottom:7px;">
				    		<input type="text" name="email" value="@" class="validate-email required" style="width:160px;" />
				    		<?php if($wsBooking) : ?>
				    			<p class="preview-number">
				    				Your booking number is: 
				    				<?php echo $wsBooking->BookingReference?>
			    				</p>
				    		<?php endif;?>
				    		<a style="margin-top:0;" class="small-button" onclick="window.parent.SqueezeBox.close();">Close</a>
			    		</td>
				        <td valign="top" style="padding-bottom:7px; padding-left:5px; vertical-align: top">	
				        	<span id="wsRequestSpinner" class="ajax-Spinner"></span> 
				       		<div class="mid-button" >
						    	<button type="submit" id="emailRequest" class="validate" style="text-indent:22px;width:152px;">
						        	Email hotelvoucher
						        </button>
					        </div>
					        <br/>
					        <div class="mid-button" >
				        		<button type="button" id="printRequest" style="text-indent:22px;width:152px;">
							    	Print hotelvoucher
								</button>					    						        
					        </div>	
				        </td>
				    </tr>
				</table>
				
		</div>		
			
	</div>
</div>    	   

	<input type="hidden" name="voucherid" value="<?php echo $this->voucher->id?>" />														
	<input type="hidden" name="blockcode" value="<?php echo $this->voucher->blockcode?>" />	
	<input type="hidden" name="task" value="match.sendVoucherJSON" />
	<?php echo JHtml::_( 'form.token' ); ?>
													
</form>	