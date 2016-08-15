<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewVouchers extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

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
		
		$this->airlines 	= $this->get('Airlines');
		$this->hotels    	= $this->get('Hotels');
		
		$this->voucher		= $this->get('voucher');
			
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
		}

		parent::display($tpl);
	}

	protected function addToolbar()
	{		
		$user		= JFactory::getUser();
		JToolBarHelper::title(JText::_('Vouchers'));
	}
}
