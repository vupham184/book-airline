<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>

<div>	
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate" id="adminForm">
	
		<fieldset>
			<div class="fltrt">
				<button type="submit">
					Save
				</button>			
				<button onclick="window.parent.location.href=window.parent.location.href;window.parent.SqueezeBox.close();" type="button">
					Close
				</button>
			</div>
			<div class="configuration">
				<?php
				$title = '';; 
				
				if($this->association){
					$title .= $this->association->name.': Edit';	
				} else {
					$title .= 'New Airport Association';	
				}	
				echo $title;			
				?>
			</div>
		</fieldset>
		
		<div style="overflow:hidden;padding:20px;background-color: #FFFFFF;box-shadow: none;clear: both;">
	
			<div style="width: 50%;float:left;">				
				<ul class="adminformlist">
					<li>
						<label>Airport Name</label>
						<input type="text" name="name" id="name" class="required" value="<?php echo isset($this->association)? $this->association->name : ''; ?>" size="40" />
					</li>
					<li>
						<label>Airport Code</label>
						<input type="text" name="code" id="code" value="<?php echo isset($this->association)? $this->association->code:''; ?>" size="50" />
					</li>
					<li>
						<label>Mysql Host</label>
						<input type="text" name="airport_host" id="airport_host" value="<?php echo isset($this->association)?  $this->association->airport_host:''; ?>" size="80" />
					</li>
					<li>
						<label>Mysql User</label>
						<input type="text" name="airport_user" id="airport_user" value="<?php echo isset($this->association)?  $this->association->airport_user:''; ?>" size="40" />
					</li>
					<li>
						<label>Mysql Password</label>
						<input type="text" name="airport_password" id="airport_password" value="" size="40" />
					</li>
					<li>
						<label>Database Name</label>
						<input type="text" name="airport_database" id="airport_database" value="<?php echo isset($this->association)?  $this->association->airport_database:''; ?>" size="40" />
					</li>
					<li>
						<label>Enable</label>
						Yes <input type="radio" name="state" value="1" <?php echo isset($this->association) && (int)$this->association->state==1? 'checked="checked"' :''; ?>>
						No <input type="radio" name="state" value="0" <?php echo isset($this->association) && (int)$this->association->state==0? 'checked="checked"' :''; ?>>
					</li>						
				</ul>
			</div>	
				
		</div>
		
		<input type="hidden" name="task" value="association.save" /> 
		<input type="hidden" name="option" value="com_sfs" />		
		<input type="hidden" name="id" value="<?php echo JRequest::getInt('id')?>" />
				
		<?php echo JHtml::_('form.token'); ?>
		<div class="clr"></div>
	</form>	
			
</div>

