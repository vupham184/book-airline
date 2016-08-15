<?php
defined('_JEXEC') or die;
$session = JFactory::getSession();
?>
<div class="heading-block descript clearfix">
	<div class="heading-block-wrap">
		<h3><?php echo JText::_('COM_SFS_AIRLINE_REGISTER_STEP4_TITLE');?></h3>
	</div>
</div>

<div class="sfs-main-wrapper-none">
	<div class="sfs-orange-wrapper">
	    <div class="sfs-white-wrapper floatbox" style="font-size:14px;">
	        <p>
	       		<?php echo JText::sprintf('COM_SFS_LABEL_DEAR',$session->get('airlineMainContactName')); ?>
	        </p>
			<p>
				<?php 
					$text = SfsHelper::getIntroTextOfArticle((int)$this->params->get('article_page_2_06'));
					echo empty($text) ? JText::_('COM_SFS_AIRLINE_THANK_CONTENT') : $text;	
				?>
			</p>   
	    </div>
	</div>    
</div>

<div class="sfs-below-main">
	<ul class="menu-command float-right">
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_sfs&view=login')?>" class="button"><?php echo JText::_('COM_SFS_CLOSE')?></a>
		</li>
	</ul>
</div>
<?php
	$session->clear('airlineMainContactName'); 
?>
