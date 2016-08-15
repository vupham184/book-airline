<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
/*JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');*/
?>

<div style="padding: 20px;">
	<h2>Add Airline Admin</h2>	
	<form action="<?php echo JRoute::_('index.php?option=com_sfs'); ?>" method="post" name="airlineNewAdmin" id="airlineNewAdmin" class="form-validate">
		<div>
			<table >
				<tr>
					<td>Username</td>
					<td><input type="text" name="contact[username]" class="inputbox required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="text" name="contact[email]" class="inputbox required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="contact[password]" class="inputbox required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>Re-Type Password</td>
					<td><input type="password" name="contact[password2]" class="inputbox required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>		
				<tr>
					<td>Job Title</td>
					<td><input type="text" name="contact[job_title]" class="inputbox required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>First Name</td>
					<td><input type="text" name="contact[name]" class="inputbox required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>Last Name</td>
					<td><input type="text" name="contact[surname]" class="inputbox required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>Title</td>
					<td>
						<select name="contact[gender]">
				            <option value="Mr">Mr</option>
				            <option value="Mrs">Mrs</option>
				            <option value="Ms">Ms</option>
			       		 </select>
					</td>
				</tr>
				<tr>
					<td>Direct office telephone</td>
					<td><input type="text" name="contact[tel_code]" class="required" style="width:45px;"> <input type="text" name="contact[tel_number]" class="required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>Direct fax</td>
					<td>
						<input type="text" name="contact[fax_code]" class="required" style="width:45px;"> <input type="text" name="contact[fax_number]" class="required" style="width: 150px;"></td>
				</tr>
				<tr>
					<td>Mobile</td>
					<td><input type="text" name="contact[mobile_code]" style="width:45px;"> <input type="text" name="contact[mobile_number]" style="width: 150px;"></td>
				</tr>
								
			</table>
							   
		</div>
		
		<button type="submit" class="validate">Save</button>
		<input type="hidden" name="id" value="<?php echo JRequest::getInt('id')?>" />
		<input type="hidden" name="task" value="airline.newAdmin" />	
		<?php echo JHtml::_('form.token'); ?>
	</form>
	
	
</div>


