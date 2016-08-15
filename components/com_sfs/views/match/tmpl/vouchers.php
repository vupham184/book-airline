<?php
defined('_JEXEC') or die;

//JHTML::_('behavior.tooltip');

$toolTipArray = array('className'=>'tooltip-custom');
JHTML::_('behavior.tooltip', '.hasTip2', $toolTipArray);

JHTML::_('behavior.modal');
$this->cancel_count = 0;
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Vouchers for hotel: <?php echo $this->hotel->name; ?></h3>
    </div>
</div>

<div class="main">

	<?php echo $this->loadTemplate('created')?>

	<div id="reprint-form"></div>
	
	<?php if($this->cancel_count) : ?>
		<?php echo $this->loadTemplate('cancelled')?>
	<?php endif;?>

	<div class="main-bottom-block">
		<a class="btn orange sm" href="<?php echo JRoute::_('index.php?option=com_sfs&view=match&nightdate='.JRequest::getVar('nightdate').'&Itemid='.JRequest::getInt('Itemid'))?>" data-step="17" data-intro="<?php echo SfsHelper::getTooltipTextEsc('button_back', $text, 'airline'); ?>">
			<?php echo JText::_('COM_SFS_BACK')?>
		</a>
	</div>
</div>
