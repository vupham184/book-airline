<?php
defined('_JEXEC') or die;
?>
<!--<table cellpadding="0" cellspacing="0" border="0" class="roomloading">-->
	
<tr valign="top">
<?php foreach ( $this->rooms_prices as $key => $value ) :	
?>
	<td>
	<div class="roomloading-column floatbox" style="text-align: center;">
		available for
		<div>
		<?php echo date('d-',strtotime($key)).substr(date( 'F' , strtotime($key) ),0,3).'-'.date( JText::_('y') , strtotime($key) );?>
		</div>
	</div>
	</td>
<?php
endforeach;?>
</tr>	

<?php 
foreach ( $this->contractedRates as $contractedRate ):?>

	<tr valign="top">
	<?php foreach ( $this->rooms_prices as $key => $value ) :
		$checked = 'checked="checked"';
		 if( count($contractedRate->exclude_dates) && in_array($key, $contractedRate->exclude_dates) ) $checked = '';
	?>
	<td nowrap="nowrap">
		<div class="roomloading-column floatbox" style="text-align: center;">		
			<div style="padding-top:2px;">
				<input type="checkbox" name="airlineContractedRates[<?php echo $contractedRate->airline_id?>][<?php echo $key?>]" <?php echo $checked?> value="<?php echo $key?>" />
			</div>
		</div>	
	</td>
	<?php
	endforeach;?>
	</tr>

<?php 
endforeach;
?>
	</tbody>
</table>