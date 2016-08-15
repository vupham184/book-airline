<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

?>
	
<table class="adminlist">
	<thead>
		<tr>			
			<th width="150"><a>Airport Name</a></th>
			<th width="150"><a>Airport Code</a></th>			
			<th><a>Enable</a></th>					
		</tr>
	</thead>
	
	<tbody>
	<?php foreach ($this->items as $i => $item) :
		$editLink = 'index.php?option=com_sfs&view=association&layout=edit&id='.$item->id.'&tmpl=component';				
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<a href="<?php echo $editLink?>" class="modal text-underline" rel="{handler: 'iframe', size: {x: 750, y: 550}}">
				<?php echo $item->name;?>
			</a>			
		</td>
		<td>
			<?php echo $item->code;?>
		</td>
		<td>
			<?php
			if((int)$item->state==1) {
				echo '<span style="color:green">Enable</span>';
			}  else {
				echo '<span style="color:gray">Disable</span>';
			}
			?>
		</td>									
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>



