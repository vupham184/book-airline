<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

if($this->tooltip)
{
	$tooltip = $this->tooltip;
} else {
	$tooltip = array();
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=tooltip&layout=airline'); ?>" method="post" name="adminForm" id="item-form" class="form-validate">

<div class="width-100">
	
<?php echo JHtml::_('tabs.start','airline-tooltip-tabs', array('useCookie'=>1)); ?>
	<?php echo JHtml::_('tabs.panel','Hotel Search', 'hotel-search-result'); ?>
	
		<table>
			<?php echo $this->getField('Hotel shuttle available','search_result_transport',$tooltip);?>			
			<?php echo $this->getField('Website','search_result_website',$tooltip);?>
		</table>           
	

<?php echo JHtml::_('tabs.end'); ?>

	<div class="current">
		<button type="submit">Save</button>
	</div>
	
	<input type="hidden" name="task" value="tooltip.saveTooltip" />
	<input type="hidden" name="tooltip_type" value="taxi" /> 
	<input type="hidden" name="option" value="com_sfs" />					
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
	
</div>



</form>