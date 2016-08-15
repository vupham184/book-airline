<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');
$print = JRequest::getInt('print');
if($this->airline->grouptype == 3) {
	$selectedAirline = $this->airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
} else {
	$airlineName = 	$this->airline->name;
}
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $airlineName.': '.JText::_('COM_SFS_AIRBLOCK_DETAILS_NAME_LOADING');?></h3>
        <?php if( $print) : ?>
            <a onclick="document.id('sfslogo').show();document.id('sfstime').show();window.print();return false;" class="sfs-button float-right">Print</a>
            <a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right">Close</a>
        <?php else : ?>
            <a href="index.php?option=com_sfs&view=airblock&layout=print&id=<?php echo $this->reservation->id; ?>&tmpl=component&print=1" class="small-button float-right modal" rel="{handler: 'iframe', size: {x: 860, y: 600}}" style="margin-top:10px;">Print</a>
        <?php endif;?>
    </div>
</div>
<div id="sfs-wrapper" class="main">
    <div class="width100 float-right" style="display:none" id="sfslogo"><img src="<?php echo JURI::base(); ?>components/com_sfs/assets/images/logo.jpg" width="223px" height="86px" />
    </div>
    <?php if(empty($this->hotel->ws_id)) : ?>
        <div class="sfs-main-wrapper bottom-border-radius clear" style="padding:0px 1px 20px 1px; margin-bottom:15px;">
            <div class="sfs-white-wrapper orange-top-border floatbox">
                <div class="ec-left float-left">
                    <div class="floatbox pd">
                        <?php echo $this->loadTemplate('hotel');?>
                        <?php echo $this->loadTemplate('rooms');?>
                    </div>
                </div>
                <div class="ec-right float-right">
                    <div class="floatbox pd">
                        <?php echo $this->loadTemplate('estimate');?>
                    </div>
                </div>
            </div>
            <?php echo $this->loadTemplate('vouchers');?>
        </div>
    <?php else:?>
        <div class="sfs-main-wrapper bottom-border-radius clear" style="padding:0px 1px 20px 1px; margin-bottom:15px;">
            <div class="sfs-white-wrapper orange-top-border floatbox">
                <div class="ec-left float-left">
                    <div class="floatbox pd">
                        <?php echo $this->loadTemplate('hotel_ws');?>
                        <?php echo $this->loadTemplate('rooms_ws');?>
                    </div>
                </div>
                <div class="ec-right float-right">
                    <div class="floatbox pd">
                        <?php echo $this->loadTemplate('estimate_ws');?>
                    </div>
                </div>
            </div>
            <?php echo $this->loadTemplate('vouchers_ws');?>
        </div>
    <?php endif;?>

    
	<span class="width100 float-right"  style="display:none;text-align:right" id="sfstime"><?php echo date("d F Y, H:i:s",time()); ?></span>

    <div class="main-bottom-block">
        <div class="pull-left">
            <a class="btn orange sm" href="<?php echo JRoute::_('index.php?option=com_sfs&view=airblock&Itemid='.JRequest::getInt('Itemid'))?>"><?php echo JText::_('COM_SFS_BACK')?></a>
        </div>
        <div class="pull-right">
            <form action="<?php echo JRoute::_('index.php?option=com_sfs');?>" method="post" name="blockform">
                <?php if( $this->reservation->status =='C' || $this->reservation->status =='T' ) : ?>
                <div class="float-right" style="padding-bottom:15px;">
                    <?php if( $this->reservation->status =='T' ) : ?>
                    <a href="index.php?option=com_sfs&view=airblock&tmpl=component&layout=challenge&id=<?php echo $this->reservation->id;?>&Itemid=554" rel="{handler: 'iframe', size: {x: 675, y: 500}}" class="modal small-button float-left">Challenge</a>
                     <?php endif;?>
                    <input type="submit" name="accept" value="Accept" class="small-button float-left" style="margin-left:15px;" />
                </div>
                <input type="hidden" name="option" value="com_sfs" />
                <input type="hidden" name="id" value="<?php echo $this->state->get('filter.blockid');?>" />
                <input type="hidden" name="task" value="rooming.airlineAccept" />
                <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
                <?php endif;?>
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
    
    <div class="clear"></div>
    
    <?php 
	if ( count($this->messages) ) :
    	 echo $this->loadTemplate('correspondence');
    endif;
	?>    
    

</div>