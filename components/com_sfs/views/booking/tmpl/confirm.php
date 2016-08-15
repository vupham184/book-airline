<?php
defined('_JEXEC') or die();
$bookmore = JRoute::_( 'index.php?option=com_sfs&view=handler&layout=search&Itemid=119' );
$app	  = JFactory::getApplication();		
$confirmStatus = $app->getUserState('com_sfs.booking.status');
if( $confirmStatus == 'success' ) :
	$app->setUserState('com_sfs.booking.status', null);
?>
    <div class="heading-block descript clearfix">
        <div class="heading-block-wrap">
            <h3><?php echo JText::_('COM_SFS_BLOCK_CONFIRM_TITLE');?></h3>
        </div>
    </div>
<div class="main">
            
	<?php echo JText::sprintf('COM_SFS_LABEL_DEAR', $this->contact->gender.' '.$this->contact->name.' '.$this->contact->surname)?>
	<?php echo JText::sprintf('COM_SFS_BLOCK_CONFIRM_TEXT', $this->hotel->name, $this->contact->fax, $this->hotel->name, $this->hotel->telephone )?>
        
    <div class="main-bottom-block clearfix">
        <a href="<?php echo $bookmore?>" class="btn orange sm pull-left"><?php echo JText::_('COM_SFS_BOOK_MORE');?></a>        
        <a href="index.php?option=com_sfs&view=handler&layout=flightform&Itemid=118" class="btn orange sm pull-right"><?php echo JText::_('COM_SFS_CLOSE');?></a>
    </div>

</div>
<?php else : ?>
<?php
	$app->redirect('index.php?option=com_sfs&view=handler&layout=overview&Itemid=116'); 
	return;
?>
<?php endif;?>