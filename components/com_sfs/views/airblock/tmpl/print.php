<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');

$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );

?>

<div id="sfs-print-wrapper">

	<div class="heading-buttons">
		<a onclick="window.print();return false;" class="sfs-button float-right">Print</a>
		<a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
	</div>

    <div id="print-logo">
    	<img src="<?php echo JURI::base(); ?>components/com_sfs/assets/images/logo.jpg" width="223px" height="86px" />
    </div>
    
    <div class="clear"></div>
    
    <h1><?php echo $this->airline->name.': '.JText::_('COM_SFS_AIRBLOCK_DETAILS_NAME_LOADING');?></h1>
    <?php if(empty($this->hotel->ws_id)) : ?>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr valign="top">
                <td width="50%">
                    <?php echo $this->loadTemplate('hotel');?>
                    <?php echo $this->loadTemplate('rooms');?>
                </td>
                <td width="50%">
                    <?php echo $this->loadTemplate('estimate');?>
                </td>
            </tr>
        </table>
        <?php echo $this->loadTemplate('vouchers');?>
    <?php else:?>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr valign="top">
                <td width="50%">
                    <?php echo $this->loadTemplate('hotel_ws');?>
                    <?php echo $this->loadTemplate('rooms_ws');?>
                </td>
                <td width="50%">
                    <?php echo $this->loadTemplate('estimate_ws');?>
                </td>
            </tr>
        </table>
        <?php echo $this->loadTemplate('vouchers_ws');?>
    <?php endif;?>

    <?php
    if ( count($this->messages) ) :
        echo $this->loadTemplate('correspondence');
    endif;
    ?>

</div>