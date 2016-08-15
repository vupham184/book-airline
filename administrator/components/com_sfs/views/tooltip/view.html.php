<?php
defined('_JEXEC') or die();

class SfsViewTooltip extends JViewLegacy
{

	public function display($tpl = null)
	{		
		$this->tooltip = $this->get('Tooltip');
						
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		JToolBarHelper::title('Tooltip System');	
		$toolbar = JToolBar::getInstance('toolbar');	
		
		$layout	= $this->getLayout();
		
		if($layout=='airline')
		{
			$toolbar->appendButton('Link', 'busactive', 'Airline', 'index.php?option=com_sfs&view=tooltip&layout=airline');	
		} else {
			$toolbar->appendButton('Link', 'bus', 'Airline', 'index.php?option=com_sfs&view=tooltip&layout=airline');
		}
		if($layout=='hotel')
		{
			$toolbar->appendButton('Link', 'taxiactive', 'Hotel', 'index.php?option=com_sfs&view=tooltip&layout=hotel');	
		} else {
			$toolbar->appendButton('Link', 'taxi', 'Hotel', 'index.php?option=com_sfs&view=tooltip&layout=hotel');
		}	
	}
	
	public function getField($label,$name,$tooltip)
	{
		$text = '';
		$checked = '';
		$position = 2;
		$sticky = 0;
		if( isset($tooltip[$name]) ){
			$text = $tooltip[$name]['text'];
			if( isset($tooltip[$name]['enable'])  ){
				$checked = ' checked="checked"';
			}
			if( isset($tooltip[$name]['position'])  ){
				$position = (int) $tooltip[$name]['position'];
			}
			if( isset($tooltip[$name]['sticky'])  ){
				$sticky = 1;
			}					
		}
		ob_start();
		?>
		<tr>
			<td width="15%"><?php echo $label?></td>
			<td width="520">
				<textarea name="tooltips[<?php echo $name?>][text]" style="width:500px;height:120px;"><?php echo $text;?></textarea>
			</td>		
			<td>
				<input type="checkbox" name="tooltips[<?php echo $name?>][enable]" value="1" <?php echo $checked?>> Enable
			</td>
		</tr>
		<?php 
		return ob_get_clean();
	}	
}


