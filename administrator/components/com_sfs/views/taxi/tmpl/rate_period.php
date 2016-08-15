<?php
defined('_JEXEC') or die;
require_once JPATH_ROOT.DS.'components'.DS.'com_sfs'.DS.'helpers'.DS.'date.php';
?>

<table>
	<tr>
		<td><?php echo JText::_('COM_SFS_TAXI_NIGHT_FARE_FROM');?></td>
		<td>
			<?php
			if( $this->taxi->fare_from ) {
				$sst_array = explode(':', $this->taxi->fare_from );
				$selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
			} else {
				$selectTimeArray = SfsHelperDate::getSelect24TimeField('6','30');
			}		
			?>
			HH:<select name="fare_from_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select> 
			MM:<select name="fare_from_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>
		</td>
		<td>
			Until
		</td>
		<td>
			<?php
			if( $this->taxi->fare_until ) {
				$sst_array = explode(':', $this->taxi->fare_until );
				$selectTimeArray = SfsHelperDate::getSelect24TimeField($sst_array[0],$sst_array[1]);
			} else {
				$selectTimeArray = SfsHelperDate::getSelect24TimeField('22','30');
			}		
			?>
			HH:<select name="fare_until_h" class="smaller-size"><?php echo $selectTimeArray[0]->html;?></select> 
			MM:<select name="fare_until_m" class="smaller-size"><?php echo $selectTimeArray[1]->html;?></select>
		</td>
	</tr>
	
	<tr>
		<td>
			<?php echo JText::_('COM_SFS_TAXI_WEEK_END_FARE_ON');?>
		</td>
		<td colspan="3">
			<table cellspacing="0" cellpadding="0" border="0">
	        	<tr>
	        		<td style="padding-right:5px"><?php echo JText::_('MON')?></td>
	        		<td style="padding-right:5px"><?php echo JText::_('TUE')?></td>
	        		<td style="padding-right:5px"><?php echo JText::_('WED')?></td>
	        		<td style="padding-right:5px"><?php echo JText::_('THU')?></td>
	        		<td style="padding-right:5px"><?php echo JText::_('FRI')?></td>
	        		<td style="padding-right:5px"><?php echo JText::_('SAT')?></td>
	        		<td style="padding-right:5px"><?php echo JText::_('SUN')?></td>
	        	</tr>
	        	<tr>
	        		<?php
	        		$available_days = array();
	        		if( !empty($this->taxi->available_days) ){
	        			$available_days = explode(',', $this->taxi->available_days);
	        			JArrayHelper::toInteger($available_days);
	        		}
	        		for ($i=1;$i<=7;$i++) :        		
	        		?>        	
	        		<td>
	        			<input type="checkbox" name="wdays[]" value="<?php echo $i;?>" <?php echo in_array($i, $available_days)? 'checked="checked"':''?> >
	        		</td>	
	        		<?php endfor;?>
	        	</tr>
	        </table>
		</td>
	</tr>
	
</table>


