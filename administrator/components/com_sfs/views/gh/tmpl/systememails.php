<?php
defined('_JEXEC') or die();

?>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=gh&layout=systememails&id='.$this->item->id); ?>" method="post" name="adminForm">
	<div class="width-100 ">
	
	<fieldset>
		<table class="adminlist">
			<thead>
				<tr>														
					<th>Name</th>
					<th>Email</th>
					<th>Booking Confirm</th>
					<th>Voucher emails</th>
					<th>Block Status</th>									
					<th>Job Title</th>				
					<th>Telephone</th>												              									
				</tr>
			</thead>
			
			<!-- Body -->
			<tbody>
			<?php foreach($this->contacts as $i => $item): 		
				$register = new JRegistry();
				$register->loadString($item->systemEmails);
				$sEmails = $register->toArray();							
			?>
				<tr class="row<?php echo $i % 2; ?>">		
					<td style="padding-right:10px;padding-bottom:5px;">											
						<?php echo $item->name.' '.$item->surname; ?>
						<?php
						if($item->is_admin) echo ' [Admin]'; 
						?>					
						<input type="hidden" name="contacts[]" value="<?php echo $item->id;?>" />
					</td>  
					<td>					
						<a href="mailto:<?php echo $item->email ?>">
							<?php echo $item->email ?>
						</a>					
					</td>  
					<td align="center">	
						<?php 
						$checked = '';
						if( count($sEmails) && isset($sEmails['booking']) ){
							$checked = ' checked="checked"';
						}
						?>
						<input style="float:none;" type="checkbox" name="semail[<?php echo $item->id;?>][booking]" value="1" <?php echo $checked;?>>
					</td>   
					<td align="center">
						<?php 
						$checked = '';
						if( count($sEmails) && isset($sEmails['voucher']) ){
							$checked = ' checked="checked"';
						}
						?>	
						<input style="float:none;" type="checkbox" name="semail[<?php echo $item->id;?>][voucher]" value="1" <?php echo $checked;?>>
					</td>   
					<td align="center">	
						<?php 
						$checked = '';
						if( count($sEmails) && isset($sEmails['blockstatus']) ){
							$checked = ' checked="checked"';
						}
						?>
						<input style="float:none;" type="checkbox" name="semail[<?php echo $item->id;?>][blockstatus]" value="1" <?php echo $checked;?>>
					</td>    
				 
					  
					<td style="padding-right:10px;padding-bottom:5px;"><?php echo $item->job_title; ?></td>
					<td style="padding-right:10px;padding-bottom:5px;"><?php echo $item->telephone ?></td> 
				</tr>
			<?php 
			endforeach; 
			?>
			</tbody>
			
		</table>
			
		<div>
			<button type="submit" class="button" style="margin-top:5px;background:green;color:white;padding:5px 20px;border:none;">Save</button>
			<input type="hidden" name="task" value="gh.saveSystemEmails" />
			<input type="hidden" name="option" value="com_sfs" />	        
			<input type="hidden" name="gh_id" value="<?php echo $this->item->id; ?>" />			
			<?php echo JHtml::_('form.token'); ?>
		</div>
		
	</fieldset>
	</div>
</form>
