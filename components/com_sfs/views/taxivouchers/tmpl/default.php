<?php
defined('_JEXEC') or die;

$airlineName = '';

if($this->airline->grouptype == 3) {
	$selectedAirline = $this->airline->getSelectedAirline();
	$airlineName = 	$selectedAirline->name;
} else {
	$airlineName = 	$this->airline->name;
}

$companies = $this->airline->getTaxiCompanies();
?>

<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $airlineName ? $airlineName . ': ' : '';?><?php echo JText::_('COM_SFS_ISSUED_TAXI_VOUCHERS');?></h3>
    </div>
</div>

<div class="main main-taxvouchers">
    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=taxivouchers');?>" method="post">
		<div class="sfs-yellow-wrapper orange-top-border">
            <div class="sfs-white-wrapper airblock-search">
            	<?php echo $this->loadTemplate('search');?>
            </div>
        </div>

    	<div class="sfs-main-wrapper bottom-border-radius" style=" padding:0 1px 15px 1px;">
            <table cellpadding="0" cellspacing="0" width="100%" border="0" class="airblocktable">
                <tr>
	            	<?php if($this->airline->grouptype == 3) : ?>
                    	<th>Airline</th>
                    <?php endif;?>
                    <th><?php echo JText::_('JDATE');?></th>
                    
                    <?php
                    if( $this->state->get('block.taxi_id') || count($companies) == 1 ):
                    ?>
                    <th><?php echo JText::_('COM_SFS_TAXI_COMPANY');?></th>
                    <?php endif;?>
                    
                    <th><?php echo JText::_('COM_SFS_HOTEL_NAME');?></th>
                    <th>Block code</th>
                    <th>Taxi vouchers</th>
                    <th>Rate per fare</th>
                    <th>Total charges</th>
                    <th></th>
                </tr>
                <?php foreach ( $this->reservations as $item ) : ?>
                <tr>
                    <?php
                    if($this->airline->grouptype == 3) :
                    ?>
                    <td><?php echo $item->airline_name;?></td>
                    <?php endif;?>
                    <td><?php echo JHTML::_('date', $item->date  , 'd/m/Y' );?></td>
                    
                    <?php
                    if( $this->state->get('block.taxi_id') || count($companies) == 1 ):
                    ?>
                    <td><?php echo $item->taxi_company ;?></td>
                    <?php endif;?>
                    
                    <td><?php echo $item->hotel_name ;?></td>
                    <td><?php echo $item->blockcode ;?></td>
                    <td>
                        <?php echo $item->total_voucher;?>
                    </td>
                    <td><?php echo number_format($item->rate_total/$item->total_voucher) ;?></td>
                    <td><?php echo $item->rate_total ;?></td>
                 	<td>
						<a href="index.php?option=com_sfs&task=taxivouchers.export&reservation_id=<?php echo $item->reservation_id?>" class="btn orange sm pull-right">Export</a>						
                 	</td>
                </tr>
                <?php endforeach ; ?>
            </table>
        </div>
            
        <input type="hidden" name="task" value="taxivouchers.filter" />
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
    </form>
</div>
   
