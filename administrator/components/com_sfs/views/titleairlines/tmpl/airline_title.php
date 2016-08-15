<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
$title_id = JRequest::getInt('title_id',0);
/*echo "<pre>";
print_r($this->airline);
echo "</pre>";
die();*/
?>

<div>
	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">
	
		<fieldset>
			<div class="fltrt">
				<button type="submit">
					Save
				</button>			
				<button onclick="  window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				<?php
				$title = '';
				if($title_id){
					$title = 'Edit Title Airline';	
				} else {
					$title = 'New Title Airline';	
				}	
				echo $title;			
				?>
			</div>
		</fieldset>
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
		
			<div>				
				<ul class="adminformlist">
					<li>
						<label>Name Airline</label>
						<?php if ($title_id>0): ?>
							<?php echo $this->airline->nameairline; ?>
							<input type="hidden" name="id_title_airline" value="<?php echo $this->airline->id;?>" />
						<?php else: ?>
							<select name="id_ariline">
								<?php foreach ($this->airlines as $airline):?>
									<option value="<?php echo $airline->id?>"><?php echo $airline->code.' - '.$airline->name;?></option>
								<?php endforeach;?>
							</select>
						<?php endif;?>
					</li>
					<li>
						<label>Title</label>
						<input type="text" name="title" id="title" value="<?php echo isset($this->airline)?$this->airline->title:''; ?>" size="40" />
					</li>
					<li>
						<label>Option</label>
						<select name="id_option">
							<?php foreach ($this->options as $option):?>
								<?php if ($option->id == $this->airline->id_option): ?>
									<option value="<?php echo $option->id?>" selected><?php echo $option->value;?></option>
								<?php else: ?>
									<option value="<?php echo $option->id?>"><?php echo $option->value;?></option>
								<?php endif ?>
							<?php endforeach;?>
						</select>
					</li>
					<li>
						<label>Status</label>
						<select name="status">
							<option value="1" <?php echo $this->airline->status ? 'selected':''; ?>>Enable</option>
							<option value="0" <?php echo $this->airline->status ? '':'selected'; ?>>Disable</option>
						</select>
					</li>
				</ul>
			</div>		
		</div>
		<input type="hidden" name="task" value="titleairlines.saveTitleAirline" /> 
		<input type="hidden" name="option" value="com_sfs" />	
				
		
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>

