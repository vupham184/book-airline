<?php
defined('_JEXEC') or die;

class SfsModelTooltip extends JModelLegacy
{

	protected function populateState()
	{
		
	}
	
	public function getTooltip()
	{
		$layout = JRequest::getVar('layout');
		$db = $this->getDbo();
		
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_options AS a');		
		$query->where('a.option_name='.$db->quote($layout.'_tooltips'));	
						
		$db->setQuery($query);
		
		$tooltip = $db->loadObject();
		
		if($tooltip)
		{
			$registry = new JRegistry();
			$registry->loadString($tooltip->option_value);
			
			$tooltip = $registry->toArray();			
			
			return $tooltip;
		}
		return null;
	}
	
	
	public function saveTooltip($tooltipType)
	{
		$db = $this->getDbo();
			
		$tooltips = JRequest::getVar('tooltips', array(), 'post', 'array');
		$registry = new JRegistry();
		$registry->loadArray($tooltips);
		
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_options AS a');
		$query->where('a.option_name='.$db->quote($tooltipType.'_tooltips'));
		
		$db->setQuery($query);
		
		$airlineTooltip = $db->loadObject();
		
		if($airlineTooltip)
		{			
			$airlineTooltip->option_value = $registry->toString();
			$db->updateObject('#__sfs_options', $airlineTooltip, 'id');
		} else {
			$row = new stdClass();			
			$row->option_name  = $tooltipType.'_tooltips';
			$row->option_value = $registry->toString();
			$db->insertObject('#__sfs_options', $row, 'id');
		}
		
		
		return true;
	}

}

