<?php
defined('_JEXEC') or die;
$status_array = array('A' => 'Approved');
?>
<fieldset class="batch">

	<p>Status update process for the selected blocks</p>

	<label>Select Status for update </label> 
	
	<select name="blockbatch[status]">
		<option value="" selected="selected">Select</option>
		<?php foreach ($status_array as $key => $value) : ?>
			<option value="<?php echo $key?>"><?php echo $value;?></option>
		<?php endforeach;?>	
	</select>

	<button type="button" onclick="Joomla.submitbutton('reservations.batch');">Process</button>

</fieldset>
