<?php
defined('_JEXEC') or die;
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo JText::_('COM_SFS_PERSONAL_CONTACT_TITLE')?></h3>
	</div>
</div>
<div id="sfs-wrapper" class="fs-14 main">
<div class="">
<div class="sfs-orange-wrapper">

	<div class="sfs-white-wrapper floatbox">
		Hi <?php echo $this->user->name;?>,<br />
		<?php echo JText::_('COM_SFS_PERSONAL_CONTACT_NOTE')?>
	</div>

	<div class="sfs-white-wrapper floatbox" style="margin-top: 25px;">


		<div class="fieldset-fields">

			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_JOB_TITLE')?>:</label> <?php echo $this->contact->job_title;?>
			</div>
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_FIRST_NAME')?>:</label>  <?php echo $this->contact->name;?>
			</div>
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_SURNAME')?>:</label>  <?php echo $this->contact->surname;?>
			</div>
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_GENDER')?></label>  <?php echo $this->contact->gender;?>
				
			</div>
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_EMAIL')?></label>  <?php echo $this->contact->email;?>
			</div>
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_DIRECT_OFFICE_TELEPHONE')?>:</label> <?php echo $this->contact->telephone;?>
				
			</div>
			<div class="register-field clear floatbox">
				<label><?php echo JText::_('COM_SFS_DIRECT_FAX')?>:</label> <?php echo $this->contact->fax;?>
			</div>
			<div class="register-field clear floatbox"> <?php echo $this->contact->mobile;?>
				<label><?php echo JText::_('COM_SFS_MOBILE')?>:</label>
			</div>

		</div>

	</div>


	</div>
</div>

<div class="sfs-below-main">
	<div class="s-button float-right">
		<a href="index.php?option=com_sfs&view=contact&layout=edit&Itemid=554" class="s-button"><?php echo JText::_('COM_SFS_EDIT')?></a>
	</div>
</div>

</div>