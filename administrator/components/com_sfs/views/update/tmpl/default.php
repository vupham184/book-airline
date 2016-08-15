<?php
defined('_JEXEC') or die;?>

<div class="clr"></div>

<div class="width-100 fltlft">
	<fieldset class="adminform">
		<legend>Sfs Update</legend>
		<?php if( $this->currentXMLVersion == $this->currentDBVersion ) : ?>
			<div>
				<i>Your extesion is up to date</i>
			</div>
		<?php else : ?>
		<table>
			<tr>
				<td style="padding:5px;">Current version is</td>
				<td style="padding:5px;"><strong><?php echo $this->currentXMLVersion;?></strong></td>
			</tr>
			<tr>
				<td style="padding:5px;">Latest version is</td>
				<td style="padding:5px;"><strong><?php echo $this->currentDBVersion;?></strong></td>
			</tr>
			<tr>
				<td style="padding:5px;"></td>
				<td style="padding:5px;">
					<?php echo $this->loadTemplate('form');?>					
				</td>
			</tr>
		</table>	
		<?php endif;?>	
	</fieldset>
</div>			

<div class="clr"></div>