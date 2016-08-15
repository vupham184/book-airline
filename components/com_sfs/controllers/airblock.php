<?php
// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class SfsControllerAirblock extends JController
{	
	
	public function __construct()
	{
		parent::__construct();
		$this->registerTask('exportr', 'exportRoomingList');
	}
	
	function filter()
	{
		$post['blockcode']  	= JRequest::getVar('blockcode');
		//lchung
		$post['flightcode']  	= JRequest::getVar('flightcode');
		//End lchung
		$post['blockstatus']  	= JRequest::getVar('blockstatus');
		$post['date_from']	= JRequest::getVar('date_from');
		$post['date_to']	= JRequest::getVar('date_to');		
		
				
		// set Itemid id for links from menu
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_sfs&view=airblock');

		if(isset($items[0])) {
			$post['Itemid'] = $items[0]->id;
		} else if (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
			$post['Itemid'] = JRequest::getInt('Itemid');
		}
		unset($post['task']);
		unset($post['submit']);

		$uri = JURI::getInstance();
		$uri->setQuery($post);
		
		$uri->setVar('option', 'com_sfs');
		$uri->setVar('view', 'airblock');
				
		$this->setRedirect(JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false),$msg);
	}
	
	public function exportRoomingList()
	{					
		$user = JFactory::getUser();
		
		if( ! SFSAccess::check($user, 'a.admin') )
		{
			JFactory::getApplication()->close();
		}
		
		$airline = SFactory::getAirline();
		
		$id = (int)JRequest::getInt('exportid');
		
		$reservation = SReservation::getInstance($id);
		
		$hotel = SHotel::getInstance($reservation->hotel_id);
		
		if( isset($reservation) && $reservation->id > 0 ) {
			
			$fileName = 'Roominglist_'.$reservation->blockcode.'.csv';
			
			$passengers = $reservation->getPassengers();
					
			
			header( 'Content-Type: text/csv' );
	        header( 'Content-Disposition: attachment;filename='.$fileName);
	         
			$fp = fopen('php://output', 'w');
			
			
					
			fputcsv($fp, array('SFS-web Roominglist'));
			fputcsv($fp, array('Blockcode',$reservation->blockcode));
			fputcsv($fp, array('Hotel',$hotel->name));
			fputcsv($fp, array(''));
			
			$headerArr = array('Flightnumber','First Name','Last Name','Voucher Number', 'Type of Room', 'Roomprice', 'Breakfastprice', 'dinnerprice');

			fputcsv($fp, $headerArr);
			
			foreach ($passengers as $passenger)
			{

				$array = array();
				$array[] = $passenger->flight_code;
				
				$array[] = $passenger->first_name;
				$array[] = $passenger->last_name;
				$array[] = $passenger->code;
				switch ( (int)$passenger->room_type )
				{
					case 1:
						$array[] = 'Single';
						$array[] = $reservation->sd_rate;
						break;
					case 2:
						$array[] = 'Double';
						$array[] = $reservation->sd_rate;
						break;
					case 3:
						$array[] = 'Triple';
						$array[] = $reservation->t_rate;
						break;		
					default:
						$array[] = 'Triple';
						$array[] = $reservation->t_rate;
						break;	
				}
				$array[] = $reservation->breakfast;
				$array[] = $reservation->mealplan;	
				fputcsv($fp, $array);		
			}
			fclose($fp);
		}
	
		JFactory::getApplication()->close();
	}	
	
	//lchung
	public function getModel($name = 'Airblock', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function saveInvoiceNumberComment() {
		$POST	= JRequest::getVar('airblock', array(), 'post', 'array');
		echo $this->getModel()->saveInvoiceNumberComment($POST);
		exit;
	}
	
	public function saveAllInvoiceNumberComment() {
		$POST	= JRequest::getVar('airblock', array(), 'post', 'array');
		echo $this->getModel()->saveAllInvoiceNumberComment($POST);
		exit;
	}
	
	public function saveInvoiceStatus() {
		$data['invoice_status']	= JRequest::getVar('invoice_status', 0);
		$data['passenger_id']	= JRequest::getVar('passenger_id', 0);
		echo $this->getModel()->saveInvoiceStatus($data);
		exit;
	}
	
	public function MarkSelectionStatus() {
		$data['colum']	= JRequest::getVar('colum', "");
		$data['value']	= JRequest::getVar('value', 0);
		echo $this->getModel()->saveMarkSelectionStatus($data);
		exit;
	}
	//End lchung

}


