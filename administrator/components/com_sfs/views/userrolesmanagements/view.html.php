<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewUserrolesmanagements extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $airportcodes;
	protected $airline_airports;
	

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
		$this->airportcodes		= $this->get('AirportCodes');
		$this->airline_airports		= $this->get('AirlineAirports');
		
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
		JToolBarHelper::save('userrolesmanagements.save', 'Save');	
	}
}
