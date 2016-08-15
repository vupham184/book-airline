<?php
defined('_JEXEC') or die();
$referer = $_SERVER["HTTP_REFERER"]  ;
if(isset($referer)) {
	$bookmore = $referer;
} else {
	$bookmore = JRoute::_( SfsHelperRoute::getSFSRoute('handler','search') );
}
$bookmore = JRoute::_( SfsHelperRoute::getSFSRoute('handler','search') );
?>
<div id="sfs-wrapper">
	<div class="heading-block descript clearfix">
		<div class="heading-block-wrap">
			<h3><?php echo JText::_('COM_SFS_BLOCK_CONFIRM_TITLE');?></h3>
		</div>
	</div>

    <div class="sfs-main-wrapper-none">
    <div class="sfs-orange-wrapper">
        <div class="sfs-white-wrapper">        
        	<?php echo JText::sprintf('COM_SFS_LABEL_DEAR', $this->contact->gender.' '.$this->contact->name)?>
        	<?php echo JText::sprintf('COM_SFS_BLOCK_CONFIRM_TEXT', $this->hotel->name, $this->contact->telephone, $this->hotel->telephone, $this->hotel->fax )?>            	
        </div>
    </div>
    </div>
    
    <div class="floatbox sfs-below-main">
        
        <div class="s-button float-left">
        	<a href="<?php echo $bookmore?>" class="s-button"><?php echo JText::_('COM_SFS_BOOK_MORE');?></a>
        </div>
        
        <div class="s-button float-right">
            <a href="index.php?option=com_sfs&view=handler&layout=flightform&Itemid=118" class="s-button"><?php echo JText::_('COM_SFS_CLOSE');?></a></li></ul>
        </div>	
    </div>

</div>
