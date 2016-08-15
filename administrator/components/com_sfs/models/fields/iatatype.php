<?php
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldIatatype extends JFormField
{
	protected $type = 'Iatatype';

	protected function getInput()
	{
		$airline_selected = $airport_selected = $terminal_selected = '';
		
		if($this->value == 1) {
			$airline_selected = ' selected="selected"';
		} else if ($this->value==2) {
			$airport_selected = ' selected="selected"';
		} else if ($this->value==3) {
			$terminal_selected = ' selected="selected"';
		} else {
			$app = JFactory::getApplication();
			$itype = $app->getUserStateFromRequest('com_sfs.iatacodes.filter.type','filter_type');
			if($itype == 1) {
				$airline_selected = ' selected="selected"';
			} else if ($itype==2) {
				$airport_selected = ' selected="selected"';
			} else if ($itype==3) {
				$terminal_selected = ' selected="selected"';
			}							
		}
		
		$html = '<select class="inputbox" name="jform[type]" id="jformtype">';
		$html .= '<option value="">Select Type</option>';
		$html .= '<option value="1"'.$airline_selected.'>Airline</option>';
		$html .= '<option value="2"'.$airport_selected.'>Airport</option>';
		$html .= '<option value="3"'.$terminal_selected.'>Terminal</option>';
		$html .= '</select>';
		return $html;
	}

}
