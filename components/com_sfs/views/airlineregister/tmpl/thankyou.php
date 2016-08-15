<?php
defined('_JEXEC') or die;
$session = JFactory::getSession();
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_STEP4_TITLE');?></h3>        
    </div>
</div>

<div class="main">	
	<p><?php echo JText::sprintf('COM_SFS_LABEL_DEAR',$session->get('airlineMainContactName')); ?></p>
	<p>
		<?php 
			$text = SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_06'));
			echo empty($text) ? JText::_('COM_SFS_AIRLINE_THANK_CONTENT') : $text;	
		?>
	</p>   
	    
	<div class="main-bottom-block">
		<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=login')?>" class="btn orange lg pull-right"><?php echo JText::_('COM_SFS_CLOSE')?></a>
	</div>
</div>

<?php
	$session->clear('airlineMainContactName'); 
?>
