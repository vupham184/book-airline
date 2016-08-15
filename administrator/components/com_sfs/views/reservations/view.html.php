<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewReservations extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$this->airlines 	= $this->get('Airlines');
		$this->hotels    	= $this->get('Hotels');
		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{	
		JToolBarHelper::title('Booked, Blocked rooms');	
		$toolbar = JToolBar::getInstance('toolbar');
		
		$filter_search = JRequest::getVar('filter_search');
		$date_start  = JRequest::getVar('date_start');
		$date_end  = JRequest::getVar('date_end');
		$filter_ws_room = JRequest::getVar('filter_ws_room');
		$filter_airline_id = JRequest::getVar('filter_airline_id');
		$filter_hotel_id = JRequest::getVar('filter_hotel_id');
		$filter_block_status = JRequest::getVar('filter_block_status');

		$toolbar->appendButton('Link', 'make_report', 'Make Report', "index.php?option=com_sfs&view=makereport&filter_search=$filter_search&date_start=$date_start&date_end=$date_end&ws_room=$filter_ws_room&airline_id=$filter_airline_id&hotel_id=$filter_hotel_id&status=$filter_block_status");
		if ( $filter_ws_room == 'WS' ) {
			$toolbar->appendButton('Link', 'make_report vouchers-download-pdf', 'Vouchers Download PDF', "javascript:void(0);");
			$toolbar->appendButton('Link', 'make_report vouchers-download-excel', 'Vouchers Download Excel', "javascript:void(0);");
			///$toolbar->appendButton('Link', 'make_report', 'Vouchers Download', "index.php?option=com_sfs&task=vouchersdownload.vouchers_download&filter_search=$filter_search&date_start=$date_start&date_end=$date_end&ws_room=$filter_ws_room&airline_id=$filter_airline_id&hotel_id=$filter_hotel_id&status=$filter_block_status");
		}
		//wsvouchersdownload
	}
}
