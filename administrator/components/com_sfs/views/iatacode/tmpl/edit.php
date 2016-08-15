<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

//lchung
$id = 0;
if( isset( $_GET['id'] ) )
	$id = $_GET['id'];

$db = JFactory::getDbo();			
$db->setQuery("SELECT id, type FROM #__sfs_iatacodes WHERE id = $id");
$rows = $db->loadObject();
$type = NULL;
if ( $rows )
	$type = $rows->type;
//End lchung
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'setupairport.cancel' || document.formvalidator.isValid(document.id('item-form'))) {			
			Joomla.submitform(task, document.getElementById('item-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<style>
.width-50{
	float:left;
}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div <?php if($type == 2 ): echo 'class="width-50"'; else: echo 'class="width-100"'; endif;?>>
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('New Iatacode') : JText::sprintf('Edit Iatacode', $this->item->id); ?></legend>

			<ul class="adminformlist">
            	<li><?php echo $this->form->getLabel('status'); ?>
                    <?php echo $this->form->getInput('status'); ?></li>
                    
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>

				<li><?php echo $this->form->getLabel('code'); ?>
				<?php echo $this->form->getInput('code'); ?></li>

				<li><?php echo $this->form->getLabel('country_id'); ?>
				<?php echo $this->form->getInput('country_id'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>

				<li><?php echo $this->form->getLabel('type'); ?>
				<?php echo $this->form->getInput('type'); ?></li>

                <li><?php echo $this->form->getLabel('geo_lat'); ?>
                    <?php echo $this->form->getInput('geo_lat'); ?></li>

                <li><?php echo $this->form->getLabel('geo_lon'); ?>
                    <?php echo $this->form->getInput('geo_lon'); ?></li>

				<li><?php echo $this->form->getLabel('starting_tariff'); ?>
					<?php echo $this->form->getInput('starting_tariff'); ?></li>

				<li><?php echo $this->form->getLabel('km_rate'); ?>
					<?php echo $this->form->getInput('km_rate'); ?></li>

                <li><?php echo $this->form->getLabel('currency_code'); ?>
                    <?php echo $this->form->getInput('currency_code'); ?></li>

                <li><?php echo $this->form->getLabel('time_zone'); ?>
                    <?php //echo $this->item->time_zone;//echo $this->form->getInput('time_zone'); ?>
					<?php echo SfsHelperField::getTimeZone($this->item->time_zone, 'jform[time_zone]');?>				
                    </li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
				
				<?php if( isset($this->item) && is_object($this->item) && ($this->item->type == 1 ) ) : ?>
				<li><?php echo $this->form->getLabel('comment'); ?>
				<?php echo $this->form->getInput('comment'); ?></li>
				<?php endif;?>				
			</ul>			
		</fieldset>
	</div><?php if($type == 2 ): ?>
    <div class="width-50">
		<fieldset class="adminform">
			<legend><?php echo "Setup Airport"; ?></legend>
			<ul class="adminformlist">
            
            	<li><?php echo $this->form->getLabel('mail_communication'); ?>
				<?php echo $this->form->getInput('mail_communication'); ?></li>
                
				<li><?php echo $this->form->getLabel('fax_communication'); ?>
				<?php echo $this->form->getInput('fax_communication'); ?></li>

				<li><?php echo $this->form->getLabel('enabel_https'); ?>
				<?php echo $this->form->getInput('enabel_https'); ?></li>
                
                <li><?php echo $this->form->getLabel('enabel_send_sms'); ?>
				<?php echo $this->form->getInput('enabel_send_sms'); ?></li>
                
				<li><label>Time zone</label>
                    <?php echo SfsHelperField::getTimeZone($this->item->time_zone, 'jform[time_zone]');?>				
                </li>
                
                <li><?php echo $this->form->getLabel('site_suffix'); ?>
				<?php echo $this->form->getInput('site_suffix'); ?></li>
               
                <li><?php echo $this->form->getLabel('sfs_system_currency'); ?>
				<?php echo $this->form->getInput('sfs_system_currency'); ?></li>
                
				<li><?php echo $this->form->getLabel('system_smails'); ?>
				<?php echo $this->form->getInput('system_smails'); ?></li>

				<li><?php echo $this->form->getLabel('enabel_25rule'); ?>
				<?php echo $this->form->getInput('enabel_25rule'); ?></li>

                <li><?php echo $this->form->getLabel('rule25'); ?>
                    <?php echo $this->form->getInput('rule25'); ?></li>

                <li><?php echo $this->form->getLabel('hours_on_match_page'); ?>
                    <?php echo $this->form->getInput('hours_on_match_page'); ?></li>
							
			</ul>		
		</fieldset>
	</div><?php endif;?>
	<div class="clr"></div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

