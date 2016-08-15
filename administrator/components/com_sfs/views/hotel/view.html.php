<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class SfsViewHotel extends JView
{	
	protected $item;
	protected $state;
	protected $servicing_airports;
	protected $hotel_room;

	public function display($tpl = null)
	{		
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->currency	= $this->get('Currency');
		$this->airports= $this->get('Airport');

		$this->createdUser	= $this->get('CreatedUser');
		JHtml::_('behavior.framework', true);
		$layout = $this->getLayout();
		$this->contacts = $this->get('Contacts');
		if($layout=='edit' || $layout=='freerelease') {
			$this->servicing_airports = $this->get('ServicingAirports');
			$this->hotel_room  = $this->get('HotelRoom');
			$this->taxes       = $this->get('HotelTaxes');
			$this->admins      = $this->get('Admins');
			$this->todayInventory      = $this->get('TodayInventory');

			$this->merchantFee      = $this->get('MerchantFee');
			$this->adminSetting     = $this->get('AdminSetting');			
		}				
		
		if($layout=='rooms') {
			$this->rooms_prices 	= $this->get('RoomsPrices');
			$this->contractedRates	= $this->get('ContractedRates');
			$this->tax     			= $this->get('HotelTaxes');
			$this->airlines     	= $this->get('Airlines');
		}
		
		if( $layout=='contacts') {
			$this->contacts = $this->item->getContacts();		
		}
		
		if ( $layout=='systememails' ) {
			$this->contacts = $this->get('Contacts');		
		}
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
		$layout = $this->getLayout();
		JRequest::setVar('hidemainmenu', true);				
		JToolBarHelper::title('Hotel : '.$this->item->name);
		
		$toolbar = JToolBar::getInstance('toolbar');		
		
		if($layout=='edit') {
			$toolbar->appendButton('Link', 'default', 'Booked, Blocked rooms', 'index.php?option=com_sfs&view=reservations&rtype=hotel&gid='.$this->item->id);
			$toolbar->appendButton('Link', 'stats', 'Rooms Management', 'index.php?option=com_sfs&view=hotel&layout=rooms&id='.$this->item->id);
			$toolbar->appendButton('Link', 'archive', 'Hotel Staff', 'index.php?option=com_sfs&view=hotel&layout=contacts&id='.$this->item->id);
			$toolbar->appendButton('Link', 'systememail', 'System Emails', 'index.php?option=com_sfs&view=hotel&layout=systememails&id='.$this->item->id);
			JToolBarHelper::divider();
			JToolBarHelper::apply('hotel.apply');			
			JToolBarHelper::cancel('hotel.cancel', 'JTOOLBAR_CLOSE');		
		}

		if($layout=='edit_ws') {
			$toolbar->appendButton('Link', 'default', 'Booked, Blocked rooms', 'index.php?option=com_sfs&view=reservations&rtype=hotel&gid='.$this->item->id);
			$toolbar->appendButton('Link', 'stats', 'Rooms Management', 'index.php?option=com_sfs&view=hotel&layout=rooms&id='.$this->item->id);
			$toolbar->appendButton('Link', 'archive', 'Hotel Staff', 'index.php?option=com_sfs&view=hotel&layout=contacts&id='.$this->item->id);
			$toolbar->appendButton('Link', 'systememail', 'System Emails', 'index.php?option=com_sfs&view=hotel&layout=systememails&id='.$this->item->id);
			JToolBarHelper::divider();
			JToolBarHelper::apply('hotel.applyWs');			
			JToolBarHelper::cancel('hotel.cancel', 'JTOOLBAR_CLOSE');		
		}

		if($layout=='rooms') {
			JToolBarHelper::title('Hotel Manager: Rooms Management of '.$this->item->name);
			$toolbar->appendButton('Link', 'back', 'Back', 'index.php?option=com_sfs&view=hotel&layout=edit&id='.$this->item->id);				
		}
		if($layout=='contacts') {
			JToolBarHelper::title('Hotel : '.$this->item->name.' - Staff');
			$toolbar->appendButton('Link', 'back', 'Back', 'index.php?option=com_sfs&view=hotel&layout=edit&id='.$this->item->id);				
		}
		if($layout=='systememails') {
			JToolBarHelper::title('Hotel : '.$this->item->name.' - Receive System emails');
			$toolbar->appendButton('Link', 'back', 'Back', 'index.php?option=com_sfs&view=hotel&layout=edit&id='.$this->item->id);				
		}
		
	}
}