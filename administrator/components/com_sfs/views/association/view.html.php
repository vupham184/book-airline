<?php
defined('_JEXEC') or die;

class SfsViewAssociation extends JViewLegacy
{
	protected $items;	
	protected $state;
	protected $association;

	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');		
		$this->state		= $this->get('State');
						
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		if ($this->getLayout() == 'edit') {
			$id = JRequest::getInt('id');
			foreach ($this->items as $item)
			{
				if($id == $item->id)
				{
					$this->association = $item;
					break;
				}
			}	
		}
		

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'edit') {
			$this->addToolbar();
		}

		parent::display($tpl);
	}

	
	protected function addToolbar()
	{				
		JToolBarHelper::title(JText::_('Airport Associations'));
		$toolbar = JToolBar::getInstance('toolbar');	
		$toolbar->appendButton('Popup', 'new', 'New', 'index.php?option=com_sfs&view=association&layout=edit&tmpl=component','750','550','','','');		
	}
}
