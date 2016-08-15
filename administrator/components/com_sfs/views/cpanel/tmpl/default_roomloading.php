<?php
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$modelHotel = JModel::getInstance('Hotels','SfsModel');
$hotels = $modelHotel->getHotels();//getItems();
///$pagination = $modelHotel->getPagination();

$totalTotalLoadedRooms = $modelHotel->getTotalInvitedLoadedRooms();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (document.formvalidator.isValid(document.id('cpanel-form'))) {			
			Joomla.submitform(task, document.getElementById('cpanel-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div style="padding-top: 0px;margin:15px 0 15px"><!--cpanel-form-->
<form id="cpanel-form" action="<?php echo JRoute::_('index.php?option=com_sfs&view=cpanel'); ?>" method="post" name="adminForm">
	<fieldset class="adminform" style="background: none;">
	
		<h2 style="padding: 0; margin:0 0 15px">Roomloading Overview</h2>
		
		<div style="padding-top: 0px;margin:15px 0 15px">
			Select Ring
			<select name="filter_ring" class="inputbox" onchange="this.form.submit()">
				<option value="">All</option>
				<?php echo JHtml::_('select.options', SfsHelper::getRingOptions(), 'value', 'text', $this->state->get('filter.ring'));?>
			</select>
			
			<button type="button" onClick="Joomla.submitbutton('cpanel.sends')" style="margin-left:15px;">
				Send roomloading invitation all below hotels
			</button>
			
		</div>
		<div style="overflow-y:scroll; max-height:500px;">
		<table width="100%" class="adminlist" >
	
			<tr>
				<th>ID</th>			
				<th><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($hotels); ?>);" /></th>
				<th style="width:120px;">Hotel</th>
				<th>Star</th>				
				<th>Ring</th>
				<th>Loaded rooms today <?php echo $totalTotalLoadedRooms;?></th>
			</tr>		
			
			<?php foreach ($hotels as $i => $hotel) : ?>
			
			<tr>
			
				<td>				
					<?php echo $hotel->id;?>				
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $hotel->id); ?>
				</td>
				<td style="width:120px;"><?php echo $hotel->name;?></td>
				<td><?php echo $hotel->star;?></td>
				<td><?php echo $hotel->ring;?></td>
				
				<td>
					<?php if( isset($hotel->room_id) && $hotel->total_loaded_room != 0) :
						$total = (int)$hotel->total_loaded_room;
					?>
						<strong>Yes <?php echo $total > 0 ? ' - total '.$total : ' - low availablity';?></strong>
						<?php
						if( $hotel->sender ) : 							
						?>
						<br />
						<span style=""><i><?php echo $hotel->sender;?></i></span>						
						<?php endif;?>							
					<?php else : ?>
					No <a rel="{handler: 'iframe', size: {x: 750, y: 650}, onClose: function() {}}" href="index.php?option=com_sfs&layout=edit&view=hotel&tmpl=component&id=<?php echo $hotel->id?>" class="modal">
						Send
					</a>
					<?php
						if( $hotel->sender ) : 							
						?>
						<br />
						<span style=""><i><?php echo $hotel->sender;?></i></span>
						
						<?php endif;?>
					<?php endif;?>
				</td>			
				
			</tr>
			
			<?php endforeach;?>
			<!-- Footer -->
            <!--<tfoot>
                <tr>
                    <td colspan="6"><?php ///echo $pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>-->
		</table>
        </div>
	</fieldset>
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />				
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
</form>
</div>
<style>
.pagination .numbers{
	margin-left:0px;
}
</style>