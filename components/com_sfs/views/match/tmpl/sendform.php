<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
switch($this->voucher->room_type){
    case 1: $room_type = "Single room";break;
    case 2: $room_type = "Double room";break;
    case 3: $room_type = "Triple room";break;
    case 4: $room_type = "Quadra room";break;
}
$textSMS = "View your booking details on ".JUri::root()."v/".$this->reservation->url_code." :".$this->voucher->name." ".$this->voucher->city ." :". $this->voucher->blockdate." code:".$this->voucher->code." :1 night"." :1 ".$room_type;
if( empty($this->voucher) )
{
	echo 'Invalid Voucher';
	return;
}
$session = JFactory::getSession();
$sent = (int)$session->get('vemailsuccess');
$airline = SFactory::getAirline();
$enableSMS = (int)$airline->params['send_sms_message'];
$sender_title = $airline->params['sender_title'];
?>
<?php if($sent==1):?>
<div class="success-msg">
	Email is successfully sent!
</div>
<?php
$session->clear('vemailsuccess');
endif?>
<script type="text/javascript">
<!--
function sfsPopupCenter2(pageURL,title,w,h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	win = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}
-->
jQuery(function ($) {
    $("#sendSMS").on("click", function(){
        var ok = true,
            phone_sms = $("#phone_sms").val(),
            regex = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/,
            button = jQuery(this);
        if(!regex.test(phone_sms)) {
            $('#phone_sms').css('border-color','#ff0000');
            $('#phone_sms').css('background-color','#ffdddd');
            ok = false;
        }
        if (ok) {
            button.attr("disabled", "disabled");
            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=ajax.sendMessage&format=raw'; ?>",
                type:"POST",
                data:{
                    phone: phone_sms,
                    text: "<?php echo $textSMS;?>",
                    sender: "<?php echo $sender_title;?>"
                },
                dataType: 'text',
                success:function(response){
                    if(response.charAt(0) == 1){
                        $('#phone_sms').css('border-color','');
                        $('#phone_sms').css('background-color','');
                        alert("Send SMS successfully!");
                        setTimeout(function(){
                            button.removeAttr("disabled");
                        },60000);
                    }
                    else
                    {
                        button.removeAttr("disabled");
                        alert("Failed!");
                    }
                }
            })
        }
    });
})
</script>
<h2>Reprint Hotel Voucher<a style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: 5px" class="sfs-button float-right" onclick="window.parent.SqueezeBox.close();">Close</a></h2>

<form action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post" name="voucherPrintForm" id="voucherPrintForm" class="form-validate">
	<table cellpadding="5" cellspacing="5">
        <?php if($enableSMS):?>
        <tr>
            <td><input type="text" name="phone_sms" id="phone_sms" value="<?php echo $this->voucher->phone_number;?>" /></td>
            <td><button type="button" class="small-button" id="sendSMS" >Send SMS</button></td>
        </tr>
        <?php endif;?>
		<tr>
	    	<td><input type="text" name="email" value="@" class="validate-email required" /></td>
	        <td><button type="submit" class="small-button validate" >Email</button></td>
	    </tr>
		<tr>
	    	<td>
	    		<?php
	    		if( $this->voucher->vgroup && isset($this->voucher->individualVoucher) ):
	    		?>
	    			<input type="text" name="vouchercode" value="<?php echo $this->voucher->individualVoucher->code;?>" />
	    			<input type="hidden" name="individual_voucher" value="<?php echo $this->voucher->individualVoucher->voucher_id?>" />
	    		<?php else :?>
	    			<input type="text" name="vouchercode" value="<?php echo $this->voucher->code;?>" />
	    			<input type="hidden" name="individual_voucher" value="0" />
	    		<?php endif?>
	    	</td>
	        <td>
	        	<?php
        		$printUrl  = JURI::base().'index.php?option=com_sfs&view=voucher&tmpl=component&reprint=1';
        		$printUrl .= '&voucher_id='.$this->voucher->id;
        		if( $this->voucher->vgroup && isset($this->voucher->individualVoucher) )
        		{
        			$printUrl .= '&individual_voucher_id='.$this->voucher->individualVoucher->voucher_id;
        		}
	        	?>
	        	<a class="small-button" href="javascript:sfsPopupCenter2('<?php echo $printUrl;?>', 'PrintVoucher',945,690);">
	        		Print
	        	</a>
	        </td>
	    </tr>
	</table>
	<input type="hidden" name="voucherid" value="<?php echo $this->voucher->id?>" />
	<input type="hidden" name="blockcode" value="<?php echo $this->voucher->blockcode?>" />
	<input type="hidden" name="task" value="match.sendVoucher" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>

