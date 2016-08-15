<?php
defined('_JEXEC') or die;
?>
<style>
div.passengers span{
	width:100px;
	padding-left:10px;
	padding-right:10px;
}
.w5{
	width:5%;
	padding-top:7px;
	padding-bottom:7px;
}
.w12{
	width:12%;
}
.ico-3{
	margin-right:2px;
}
.ico-3.ico-3-last{
	margin-right:0px;
}
.tr-boby{
	background-color:#fff;
}
.tr-boby td{
	padding:3px 5px;
}

.popup-other{
	position:absolute;
	border:3px solid #ff8806;
	padding:10px;
	z-index:1;
	background-color:#fff;
}

</style>
<?php 
$reservation_id = JRequest::getInt('id', 0);
$url_comment = JURI::base()."index.php?option=com_sfs&view=airblock&tmpl=component&layout=comment&reservation_id=$reservation_id"; 
?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(function($){
	//SqueezeBox.open('<?php echo $url_comment;?>', {handler: 'iframe', size: {x: 200, y: 270} });
	
	function upload( v, id){
		$.ajax({
			url:"<?php echo JURI::base().'index.php?option=com_sfs&task=airblock.saveInvoiceStatus'; ?>",
			type:"POST",
			data:{'invoice_status':v, 'passenger_id':id},
			dataType: 'text',
			success:function(response){
				document.location.reload(true);
			}
		});
	}
	
	$('.ok, .ok-ligth, .nok, .nok-ligth').click(function(e) {
		var passenger_id = $(this).attr('data-id')
		var cs = $(this).attr('data-class');
		var v = 0, t = false;
		switch ( cs ){
			case"ok":
				v = '0';
				/*var obj = $(this).children('img');
				obj.attr('src','<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/OK_ligth.png');?>');
				obj.removeClass('ok');
				obj.addClass('ok-ligth');*/
			break;
			case"ok-ligth":
				v = '1';
				/*var obj = $(this).children('img');
				obj.attr('src','<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/OK.png');?>');
				obj.removeClass('ok-ligth');
				obj.addClass('ok');*/
			break;
			case"nok-ligth":
				v = '2';
				/*var obj = $(this).children('img');
				obj.attr('src','<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/NOK.png');?>');
				*/
			break;
		}
		
		if ( cs == 'nok' ) {
			t = true;
			alert('The following names are not corresponding to our records');
			return false;
		}
		if ( t == false)
			upload(v, passenger_id);
	});
	
	/*$('.ok, .ok-ligth').click(function(e) {
		alert( $(this).attr('data-class') );
	});*/
	
	$('.open-popup').click(function(e) {
		$('.loading').css('display', 'block');
		var p = $( this ).offset();
		var t = $('.open-popup').offset();
		var x = $(this).attr("data-size-x");
		var y = $(this).attr("data-size-y");
		
		if ( ! $(this).hasClass('other') )
			$('#popup-other').css('bottom', "-" + (p.top - t.top +35) + 'px');
		
		var vhref = $(this).attr("href");
		var boj = $('.popup-other');	
		$('.contents-popup').html("");		
		boj.css({"display":"block"});			
		$('.contents-popup').append('<iframe src="' + vhref + '" width = "' + x + '" height="' + y + '"></iframe>');
		return false;
	});
	
});
</script>

<div class="passengers">  

	<table style="background-color:#ddd;" cellpadding="0" cellspacing="1">
    	<tr class="p-title">
        	<th class="w5">&nbsp;
            	
            </th>
            <th class="w12">
            	First name
            </th>
            <th class="w12">
            	Last name
            </th>
            <th class="w12">
            	Voucher number
            </th>
            <th class="w12">
            	Flight number
            </th>
            <th class="w12">
            	Dinner
            </th>
            <th class="w12">
            	Lunch
                <div style="position:relative;">
                    <div id="popup-other" class="popup-other" style="display:none;">
                        <div class="loading"></div>
                        <div class="contents-popup"></div>
                    </div>
                </div>
            </th>
            <th class="w12">
            	Breakfast
            </th>
            <th class="w12">
            	Invoice
            </th>
        </tr>
        <?php 
		$i = 0;
		if( count( $this->passengers) ) :
		foreach ( $this->passengers as $item ) : //print_r( $item );die;?>	
        <tr class="tr-boby">
        	<td><?php echo ++$i; ;?></td>
            <td><?php echo $item->first_name;?></td>
            <td><?php echo $item->last_name ;?></td>
            <td><?php echo ($item->individual_code)?$item->individual_code:$item->code ;?></td>
            <td><?php echo $this->reservation->_vouchers[0]->flight_code ;?></td>
            <td><?php echo (int)$item->mealplan ? 'Yes':'No';?></td>
            <td><?php echo (int)$item->lunch ? 'Yes':'No';?></td>
            <td><?php echo (int)$item->breakfast ? 'Yes':'No';?></td>
            <td>
                <div style="white-space: nowrap;">
                	<?php if( $item->invoice_status == 1 ):?>
                    <a href="javascript:void(0);" class="ico-3 ok" data-id="<?php echo $item->id;?>" data-class="ok" >
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/OK.png'); ?>" alt="OK" />
                    </a>
                    <a href="javascript:void(0);" class="ico-3 nok-ligth" data-id="<?php echo $item->id;?>" data-class="nok-ligth" >
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/NOK_ligth.png'); ?>" alt="NOK ligth" />
                    </a>
                    <?php elseif( $item->invoice_status == 2 ):?>
                    
                    <a href="javascript:void(0);" class="ico-3 ok-ligth" data-id="<?php echo $item->id;?>" data-class="ok-ligth" >
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/OK_ligth.png'); ?>" alt="NOK ligth" />
                    </a>
                    <a href="javascript:void(0);" class="ico-3 nok" data-id="<?php echo $item->id;?>" data-class="nok" >
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/NOK.png'); ?>" alt="NOK ligth" />
                    </a>
                    <?php elseif( $item->invoice_status == 0 ):?>
                    <a href="javascript:void(0);" class="ico-3 ok-ligth" data-id="<?php echo $item->id;?>" data-class="ok-ligth" >
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/OK_ligth.png'); ?>" alt="NOK ligth" />
                    </a>
                    <a href="javascript:void(0);" class="ico-3 nok-ligth" data-id="<?php echo $item->id;?>" data-class="nok-ligth" >
                        <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/NOK_ligth.png'); ?>" alt="NOK ligth" />
                    </a>
                    <?php endif;?>
                    
					<?php if( $item->comment != "" || $item->insurance != 3 || $item->touroperator_client != 3 ):?>
                    <a data-size-x="210" data-size-y="250" class="ico-3 ico-last open-popup"
                    href="<?php echo $url_comment . '&passenger_id=' . $item->id ;?>&box=1" style="text-decoration:none;">
                    <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/comment-26.png'); ?>" alt="comment" />
                    </a>
                    <?php else:?>
                    <a data-size-x="210" data-size-y="250" class="ico-3 ico-last open-popup"
                    href="<?php echo $url_comment . '&passenger_id=' . $item->id ;?>&box=1" style="text-decoration:none;">
                    <img src="<?php echo JRoute::_('media/system/images/accounting-2-0-updated-reports/comment-26-grey.png'); ?>" alt="comment" />
                    </a>
                    <?php endif;?>
                </div>
            </td>
        </tr>
        <?php 
			endforeach;
			endif;
		?>
    </table>
  
    <?php
    if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 )
    {
    	$j=0;	
    	while ($j < $this->guaranteeVoucher->issued)
    	{
    		?>
    		<div class="passenger">
		        <span class="order"><?php echo ++$i; ;?></span>
		        <span>No show</span>
		        <span>No show</span>
		        <span><?php echo $this->guaranteeVoucher->code ;?></span>
		    </div>
    		<?php 
    		$j++;
    	}
    }
    ?>
    <form name="exportrooming" action="index.php" method="post">
		<button type="submit" class="small-button float-right midmargintop">
			<?php echo JText::_('COM_SFS_EXPORT_TO_CSV') ?>
		</button>		
		<input type="hidden" name="option" value="com_sfs" />
		<input type="hidden" name="exportid" value="<?php echo $this->state->get('filter.blockid');?>" />
		<input type="hidden" name="task" value="airblock.exportr" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
    
</div>