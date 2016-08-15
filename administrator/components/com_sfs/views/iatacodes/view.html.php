<?php
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewIatacodes extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}


	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('IATA codes Manager'));

		JToolBarHelper::addNew('iatacode.add','JTOOLBAR_NEW');

		JToolBarHelper::editList('iatacode.edit','JTOOLBAR_EDIT');


		JToolBarHelper::divider();
		JToolBarHelper::custom('iatacodes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('iatacodes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();


		if ($this->state->get('filter.published') == -2) {
			JToolBarHelper::deleteList('', 'iatacodes.delete','JTOOLBAR_EMPTY_TRASH');
				
		}
		else {
			JToolBarHelper::trash('iatacodes.trash','JTOOLBAR_TRASH');
		}

	}
}
