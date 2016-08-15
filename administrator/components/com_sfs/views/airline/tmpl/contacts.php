<?php
defined('_JEXEC') or die;
$this->contacts = $this->item->contacts;
?>
<h2>Other Members of <?php echo $this->item->airline_name?> (<?php echo $this->item->airline_code?>)</h2>
<?php foreach ($this->contacts as $contact) : ?>
<fieldset class="adminform">
<legend><?php echo $contact->gender;?> <?php echo $contact->contact_name;?></legend>
<ul class="adminformlist">
	<li>
		<label for="">Job title:</label>
		<input type="text" size="30" value="<?php echo $contact->job_title;?>" readonly="readonly" class="readonly">
	</li>		
	<li>
		<label for="">Gender:</label>
		<input type="text" size="30" value="<?php echo $contact->gender;?>" readonly="readonly" class="readonly">
	</li>										
	<li>
		<label for="">Name:</label>
		<input type="text" size="60" value="<?php echo $contact->name;?>" readonly="readonly" class="readonly">
	</li>
	<li>
		<label for="">Surname:</label>
		<input type="text" size="30" value="<?php echo $contact->surname;?>" readonly="readonly" class="readonly">
	</li>		
	<li>
		<label for="">Email:</label>
		<input type="text" size="30" value="<?php echo $contact->email;?>" readonly="readonly" class="readonly">
	</li>	
	<li>
		<label for="">Direct office telephone:</label>					
		<input type="text" size="30" value="<?php echo $contact->telephone;?>" readonly="readonly" class="readonly">
	</li>
	<li>
		<label for="">Direct fax:</label>
		<input type="text" size="30" value="<?php echo $contact->fax;?>" readonly="readonly" class="readonly">
	</li>
	<li>
		<label for="">Mobile:</label>
		<input type="text" size="30" value="<?php echo $contact->mobile;?>" readonly="readonly" class="readonly">
	</li>																																														
</ul>
<div class="clr"></div>
<a href="index.php?option=com_sfs&view=contact&layout=edit&id=<?php echo $contact->id;?>" target="_blank">Click to edit</a>
</fieldset>
<div class="clr"></div>		
<?php endforeach;?>