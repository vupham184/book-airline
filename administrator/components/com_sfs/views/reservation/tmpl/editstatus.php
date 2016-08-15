<?php
defined('_JEXEC') or die;
// Load the tooltip behavior.
JHtml::_('behavior.keepalive');

$status_array = array(
            	'O' => 'Open',
                'P' => 'Pending',
                'T' => 'Tentative',
                'C' => 'Challenged',
                'A' => 'Approved',
                'R' => 'Archived',
				'D' => 'Deleted'
);

?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" name="adminForm" id="status-form">

	<fieldset>
		<div class="fltrt">
			<button type="submit">
				Save
			</button>			
			<button onclick="  window.parent.SqueezeBox.close();" type="button">
				Close
			</button>
		</div>
		<div class="configuration">
			Update block status 
		</div>
	</fieldset>
	
	<div style="overflow:hidden;background-color: #FFFFFF;box-shadow: none;clear: both;font-size:15px;">

		<div style="padding: 20px 10px 10px 20px;">
			Blockcode: <?php echo $this->reservation->blockcode;?>
		</div>
		<div style="padding: 10px 10px 10px 20px;">
			Current Status: <?php echo $status_array[$this->reservation->status];?>
		</div>
		
		<div style="padding: 10px 10px 10px 20px;">
		Change to: 
		<select name="blockstatus">
			<?php foreach ($status_array as $key => $value) : ?>
				<?php if($key != $this->reservation->status):?>
					<option value="<?php echo $key?>"><?php echo $value?></option>
				<?php endif;?>
			<?php endforeach;?>
		</select>	
		</div>	
	</div> 
	
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="task" value="reservation.changestatus" />
	<input type="hidden" name="arrList[airline]" value="<?php echo $this->reservation->airline_id;?>">
	<input type="hidden" name="arrList[blockcode]" value="<?php echo $this->reservation->blockcode;?>">
	<input type="hidden" name="arrList[hotel_id]" value="<?php echo $this->reservation->hotel_id;?>">
	<input type="hidden" name="arrList[date]" value="<?php echo $this->reservation->blockdate;?>">
	
	<input type="hidden" name="id" value="<?php echo $this->reservation->id?>" />	
	
	<?php echo JHtml::_('form.token'); ?>
</form>