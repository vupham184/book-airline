<?php defined('_JEXEC') or die;?>	
<div>
	<?php
	echo JHtml::_('sliders.start','panel-sliders',array('useCookie'=>'0'));
	echo JHtml::_('sliders.panel', 'SFS Statistics', 'cpanel-panel-statistics');
	?>	
	<div>
	<table cellspacing="10">
		<tbody>
			<tr>
				<td>Total Airlines:</td>
				<td><?php echo $this->total_airline;?> ( <?php echo $this->total_airline_contact;?> contacts )</td>
			</tr>
			<tr>
				<td>Total Ground Handlers:</td>
				<td><?php echo (int)$this->total_gh;?> ( <?php echo $this->total_gh_contact;?> contacts )</td>
			</tr>
			<tr>
				<td>Total Hotels:</td>
				<td><?php echo $this->total_hotel;?> ( <?php echo $this->total_hotel_contact;?> contacts )</td>
			</tr>		
		</tbody>
	</table>
	</div>	
	
	<?php echo JHtml::_('sliders.panel', 'Latest Airlines', 'cpanel-panel-latest-airline');?>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th>Latest Items</th>
				<th>Approved</th>				
				<th><strong>Created By</strong></th>
				<th><strong>Created</strong></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->latest_airlines as $item):?>
			<tr>
				<td><a href="index.php?option=com_sfs&task=airline.edit&id=<?php echo $item->id?>"><?php echo $item->name?></a></td>
				<td class="center jgrid">
					<?php if(!$item->approved):?>
					<span class="state unpublish"><span class="text">Unapproved</span></span>
					<?php else:?>
					<span class="state publish"><span class="text">Approved</span></span>
					<?php endif;?>
				</td>				
				<td class="center">
					<a href="index.php?option=com_users&view=user&layout=edit&id=<?php echo $item->user_id?>">
						<?php echo $item->created_by?>
					</a>
				</td>
				<td class="center"><?php echo JHTML::_('date',$item->created, JText::_('DATE_FORMAT_LC2')); ?></td>
			</tr>		
			<?php endforeach;?>
		</tbody>
	</table>
	
	<?php echo JHtml::_('sliders.panel', 'Latest Ground Handlers', 'cpanel-panel-latest-gh');?>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th>Latest Items</th>
				<th>Approved</th>				
				<th><strong>Created By</strong></th>
				<th><strong>Created</strong></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->latest_ghs as $item):?>
			<tr>
				<td><a href="index.php?option=com_sfs&task=airline.edit&id=<?php echo $item->id?>"><?php echo $item->company_name?></a></td>
				<td class="center jgrid">
					<?php if(!$item->approved):?>
					<span class="state unpublish"><span class="text">Unapproved</span></span>
					<?php else:?>
					<span class="state publish"><span class="text">Approved</span></span>
					<?php endif;?>
				</td>				
				<td class="center">
					<a href="index.php?option=com_users&view=user&layout=edit&id=<?php echo $item->user_id?>">
						<?php echo $item->created_by?>
					</a>
				</td>
				<td class="center"><?php echo JHTML::_('date',$item->created, JText::_('DATE_FORMAT_LC2')); ?></td>
			</tr>		
			<?php endforeach;?>
		</tbody>
	</table>	
	

	<?php echo JHtml::_('sliders.panel', 'Latest Hotels', 'cpanel-panel-latest-hotel');?>
	<table class="adminlist">
		<thead>
			<tr>
				<th>Latest Items</th>				
				<th><strong>Created By</strong></th>
				<th><strong>Created</strong></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->latest_hotels as $item):?>
			<tr>
				<td><a href="index.php?option=com_sfs&task=hotel.edit&id=<?php echo $item->id?>"><?php echo $item->name?></a></td>				
				<td class="center">
					<a href="index.php?option=com_users&view=user&layout=edit&id=<?php echo $item->user_id?>">
						<?php echo $item->created_by?>
					</a>
				</td>
				<td class="center"><?php echo JHTML::_('date',$item->created, JText::_('DATE_FORMAT_LC2')); ?></td>
			</tr>		
			<?php endforeach;?>
		</tbody>
	</table>	
	
	<?php echo JHtml::_('sliders.end');?>
</div>	