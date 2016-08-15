<?php
defined('_JEXEC') or die;
?>
<div class="passengers">
    <div class="p-title">
        <span class="order">&nbsp;</span>
        <span>First name</span>
        <span>Last name</span>
        <span>Voucher number</span>
        <span>Flight number</span>
        <span class="vfb">Dinner</span>
        <span class="vfb">Lunch</span>
        <span class="vfb">Breakfast</span>        
    </div>
    <?php 
    $i = 0;
    if( count( $this->passengers) ) :
    foreach ( $this->passengers as $item ) : ?>	
    <div class="passenger">
        <span class="order"><?php echo ++$i; ;?></span>
        <span><?php echo $item->first_name;?></span>
        <span><?php echo $item->last_name ;?></span>
        <span><?php echo ($item->individual_code)?$item->individual_code:$item->code ;?></span>
        <span><?php echo $this->reservation->_vouchers[0]->flight_code ;?></span>
        <span class="vfb"><?php echo (int)$item->mealplan ? 'Yes':'No';?></span>
        <span class="vfb"><?php echo (int)$item->lunch ? 'Yes':'No';?></span>
        <span class="vfb"><?php echo (int)$item->breakfast ? 'Yes':'No';?></span>
    </div>			
    <?php 
    endforeach ; 
    endif;
    ?>
    
    <?php
    if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 )
    {
    	$j=0;	
    	while ($j < $this->guaranteeVoucher->issued)
    	{
    		?>
    		<div class="passenger">
		        <span class="order"><?php echo ++$i; ;?></span>
		        <span>No show</span>
		        <span>No show</span>
		        <span><?php echo $this->guaranteeVoucher->code ;?></span>
		    </div>
    		<?php 
    		$j++;
    	}
    }
    ?>
    
    <form name="exportrooming" action="index.php" method="post">
		<button type="submit" class="small-button float-right midmargintop">
			<?php echo JText::_('COM_SFS_EXPORT_TO_CSV') ?>
		</button>		
		<input type="hidden" name="option" value="com_sfs" />
		<input type="hidden" name="exportid" value="<?php echo $this->state->get('filter.blockid');?>" />
		<input type="hidden" name="task" value="airblock.exportr" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
    
</div>