<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewUserroles extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $menus;
	protected $airlines;
	

	/**
	 * Display the view
	 *
	 * @return	void
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->menus		= $this->get('Menus');
		$this->airlines		= $this->get('Airlines');
		
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
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->appendButton('Link', 'new', 'New', 'javascript:void(0);');
		$toolbar->appendButton('Link', 'apply', 'apply', 'javascript:void(0);');
		$toolbar->appendButton('Link', 'save', 'JTOOLBAR_SAVE', 'javascript:void(0);');
		//$toolbar->appendButton('Link', 'cancel', 'JTOOLBAR_CLOSE', 'javascript:void(0);');
		/*
		JToolBarHelper::apply('airline.apply');
		JToolBarHelper::save('airline.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::cancel('airline.cancel', 'JTOOLBAR_CLOSE');		
		*/
		
	}
}
