<?php
defined('_JEXEC') or die;
?>
<div class="heading-block clearfix">
	<div class="sfs-above-main">
		<h2>Bus Confirmation</h2>
	</div>
</div>
<div class="sfs-main-wrapper-none">
	<div class="sfs-orange-wrapper">
	    <div class="sfs-white-wrapper floatbox fs-14">	       
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
			<a href="<?php echo JRoute::_('index.php?option=com_sfsuser&view=login&Itemid=104')?>" class="button"><?php echo JText::_('COM_SFS_CLOSE')?></a>
		</li>
	</ul>
</div>
