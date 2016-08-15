<?php
defined('_JEXEC') or die();
?>
<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=contacts'); ?>" method="post" name="adminForm">

	
	<table class="adminlist">
		<!-- Header -->
		<thead>
			<tr>														
				<th>
					Name
				</th>
				<th>
					Email
				</th>												              
				<th width="8%">
					Job Title
				</th>				
				<th width="8%">
					Telephone
				</th>
				<th width="8%">
					Fax
				</th>				
				<th width="8%">
					Mobile
				</th>											
			</tr>
		</thead>
		
		<!-- Body -->
		<tbody>
		<?php foreach($this->contacts as $i => $item): 						
		?>
			<tr class="row<?php echo $i % 2; ?>">		
				<td>					
					<a href="<?php echo JRoute::_('index.php?option=com_sfs&task=contact.edit&id='.(int)$item->id); ?>" target="_blank">
						<?php echo $item->name.' '.$item->surname; ?>
					</a>					
				</td>  
				<td>					
					<a href="mailto:<?php echo $item->email ?>">
						<?php echo $item->email ?>
					</a>					
				</td>          
				<td align="center"><?php echo $item->job_title; ?></td>
				<td align="center"><?php echo $item->telephone ?></td>
				<td align="center"><?php echo $item->fax ?></td>
				<td align="center"><?php echo $item->mobile ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		

	</table>
		
	<div>
		<input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="parent_id" value="<?php echo JRequest::getInt('parent_id'); ?>" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
