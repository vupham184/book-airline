<?php
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$cancel_count = 0;
?>
<script type="text/javascript">
<!--
	function processVoucher(pressbutton,id){
		if (id) {
			document.voucherListForm.id.value=id;
		} else {
			alert('No item is selected');
			return false;
		}
		if (pressbutton) {
			document.voucherListForm.task.value=pressbutton;
		}
		if (typeof document.voucherListForm.onsubmit == "function") {
			document.voucherListForm.onsubmit();
		}
		document.voucherListForm.submit();
	}
		
-->
</script>

<div id="sfs-wrapper">

<h2><?php echo JText::sprintf('COM_SFS_VOUCHER_PAGE_TITLE',$this->block->blockcode)?></h2>

<h4>Created Voucher(s)</h4>
<div class="sfs-main-wrapper" style="padding:10px">
			
	<div class="floatbox sfs-white-wrapper"  style="padding:15px">
		
		<form id="voucherListForm" name="voucherListForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=handler'); ?>" method="post">		
		<table class="airblocktable" width="100%">
			<tr>
				<th>Block code</th>
				<th>Flight number</th>
				<th>Voucher number</th>
				<th>Seats</th>
				<th>Creation</th>
				<th>Creation Rep</th>
				<th>Room Type</th>
				<th>Group</th>
				<th>Print/Email</th>
				<th></th>
			</tr>
			<?php foreach ($this->vouchers as $item) :
			if($item->status==1 || $item->status==2||$item->status==0) :
			?>
			<tr>			
				<td>
					<?php echo $this->block->blockcode;?>
				</td>
				<td>
					<?php echo $item->flight_code;?>
				</td>
				<td>
					<?php echo $item->code;?>
				</td>
				<td>
					<?php echo $item->seats;?>
				</td>				
				<td>
					<?php echo JHtml::_('date',$item->created,'H:i');?>
				</td>
				<td>
					<?php echo $item->created_name;?>
				</td>
				<td>
					<?php 
					if($item->room_type==1){
						echo 'Single';
					} else if($item->room_type==2){
						echo 'Double';
					} else if($item->room_type=3){
						echo 'Triple';	
					}
					?>
				</td>			
				<td>
					<?php 
						$item->vgroup = (int) $item->vgroup ;
						echo ($item->vgroup > 0 ) ? 'Yes ' : 'No';
					?>
				</td>	
				<td>
					<?php 
						switch((int)$item->status) {
							case 1:
								//echo '<span style="color:green">printed</span>';
								//echo JHtml::_('date',$item->created,'H:i');
							    echo JHtml::_('date',$item->handled_date,'H:i');
								break;
							case 2:
								echo '<span style="color:blue">'.$item->passenger_email.'</span>';
								break;	
						}
					?>			
				</td>	
				<td width="120">		
					<?php if( (int)$item->status != 3 ) : ?>		
                    <div class="s-button" style="margin-bottom:5px;">
						<button class="s-button" type="button" id="send-print-<?php echo $item->id;?>">Print/email</button>					
                    </div>
                    <div class="s-button">
					<a rel="{handler: 'iframe', size: {x: 300,y: 270}}" class="modal s-button" href="index.php?option=com_sfs&view=handler&layout=cancelvoucher&id=<?php echo $item->id;?>&blockcode=<?php echo $this->block->blockcode ?>&tmpl=component&Itemid=<?php echo JRequest::getInt('Itemid');?>">Cancel</a>
					<?php endif;?>
                    </div>
					
					<script type="text/javascript">
					<!--
						window.addEvent('domready', function() {																	
							$('send-print-<?php echo $item->id;?>').addEvent('click', function(event){
							     var result = $('reprint-form');	    
							     new Request.HTML({
							       url: '<?php echo JURI::root();?>index.php?option=com_sfs&format=raw&task=ajax.freprint&id='+<?php echo $item->id;?> ,
							       update: result,
							       onRequest: function(){
							    	   result.empty();
							       },
							       onSuccess: function(txt){
							    	   
							       }
							     }).send();	  
							});												
						});					
					-->
					</script>		
					
				</td>												
			</tr>
			<?php 
			else: 
				$cancel_count++;
			endif;
			endforeach;?>
		</table>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="id" value="" />
      		<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
      		<?php echo JHtml::_('form.token'); ?>		
		</form>
		
	</div>
	
</div>


<div id="reprint-form"></div>

<?php if($cancel_count) : ?>

<h4>Cancelled Voucher(s)</h4>
<div class="sfs-main-wrapper" style="padding:10px">
	<div class="floatbox sfs-white-wrapper"  style="padding:15px">
		<table class="airblocktable" width="100%">
			<tr>
				<th>Block code</th>
				<th>Flight number</th>
				<th>Voucher number</th>
				<th>Seats</th>
				<th>Creation</th>
				<th>Cxl_ed</th>
				<th>Creation Rep</th>
				<th>Room Type</th>
				<th>Group</th>		
			</tr>
			<?php foreach ($this->vouchers as $item) :
			if($item->status==3) :
			?>
			<tr>			
				<td>
					<?php echo $this->block->blockcode;?>
				</td>
				<td>
					<?php echo $item->flight_code;?>
				</td>
				<td>
					<?php echo $item->code;?>
				</td>
				<td>
					<?php echo $item->seats;?>
				</td>				
				<td>
					<?php echo JHtml::_('date',$item->created,'H:i');?>
				</td>
				<td>
					<?php echo JHtml::_('date',$item->handled_date,'H:i');?>
				</td>
				<td>
					<?php echo $item->created_name;?>
				</td>
				<td>
					<?php 
					if($item->room_type==1){
						echo 'Single';
					} else if($item->room_type==2){
						echo 'Double';
					} else if($item->room_type=3){
						echo 'Triple';	
					}
					?>
				</td>			
				<td>
					<?php 
						$item->vgroup = (int) $item->vgroup ;
						echo ($item->vgroup > 0 ) ? 'Yes ' : 'No';
					?>
				</td>													
			</tr>
			<?php 
			else: 
				$has_cancel++;
			endif;
			endforeach;?>
		</table>		
	</div>
</div>
<?php endif;?>	


</div>

<div class="floatbox" style="margin-top:10px;">
	<a class="small-button" href="<?php echo JRoute::_('index.php?option=com_sfs&view=handler&layout=match&Itemid='.JRequest::getInt('Itemid'))?>"><?php echo JText::_('COM_SFS_BACK')?></a>	
</div>