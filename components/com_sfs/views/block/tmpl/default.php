<?php
defined('_JEXEC') or die;
?>
<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo JText::_('COM_SFS_BLOCK_DETAILED_SEARCH_CRITERIA');?></h3>
        <div class="descript-txt"><?php echo JText::_('COM_SFS_BLOCK_NOTE');?></div>
    </div>
</div>

<div class="main">
	<form name="blockOverviewForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=block');?>" method="post" class="sfs-form form-horizone">
	                
        <div class="block-selection">
        	<?php echo $this->loadTemplate('quickselection');?>
        </div>
                
		<div class="sfs-main-wrapper bottom-border-radius">
            <div class="sfs-white-wrapper floatbox orange-top-border detailsearch">
                <?php echo $this->loadTemplate('search');?>
            </div>
		</div>
		
        <?php if( isset($this->blocks) ) :?>
            <div class="sfs-yellow-wrapper">
                <div class="sfs-white-wrapper">
                    <?php
                    switch ($this->state->get('block.status')){
                        case 'O':
                            echo $this->loadTemplate('open');
                            break;
                        case 'P':
                            echo $this->loadTemplate('pending');
                            break;
                        case 'T':
                            echo $this->loadTemplate('tentative');
                            break;
                        case 'A':
                            echo $this->loadTemplate('approved');
                            break;
                        case 'C':
                            echo $this->loadTemplate('challenged');
                            break;
                        case 'R':
                            echo $this->loadTemplate('archived');
                            break;
                        default:
                            echo $this->loadTemplate('list');
                            break;
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
              
        <input type="hidden" name="task" value="block.filter" />
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
    
</div>

