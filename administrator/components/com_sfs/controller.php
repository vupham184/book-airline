<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of Hotel component
 */
class SfsController extends JController
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false) 
	{
		// set default view if not set
		$view = JRequest::getCmd('view', 'cpanel'); 
		JRequest::setVar('view', $view);

		// call parent behavior
		parent::display($cachable);

		// Set the submenu
		SfsHelper::addSubmenu($view);
	}
	
	public function updatefields()
	{
		return;
		$db = JFactory::getDbo();
		
		$query = 'SELECT * FROM #__sfs_flights_seats';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		//
		$i = 0;
		foreach ($rows as $row)
		{
			if( ! $row->from_date  ){
				$date = sfsHelper::getATDate($row->created,'Y-m-d');			
				$nextDate = sfsHelper::getATNextDate('Y-m-d', $date);
				$query = 'UPDATE #__sfs_flights_seats SET from_date = '.$db->quote($date).', end_date = '.$db->quote($nextDate);
				$query .= ' WHERE id='.$row->id;
				$db->setQuery($query);
				$db->query();	
				$i++;
			}			
		}
		
		echo 'Successfully updated '.$i.' rows';
		
	}
	
	public function importlunch()
	{
		return;
		$db = JFactory::getDbo();
		
		$query = 'SELECT * FROM #__sfs_reservations';
		$db->setQuery($query);
		
		$revs = $db->loadObjectList();
		foreach ( $revs as $r) {
			$breakfast = 0;
			$mealplan  = 0;
			
			if( $r->breakfast ){
				$breakfast = 1;
			}
			if( $r->mealplan ){
				$mealplan = 1;
			}
			$updateQuery = 'UPDATE #__sfs_voucher_codes SET breakfast='.$breakfast.',mealplan='.$mealplan.' WHERE booking_id='.$r->id;
			$db->setQuery($updateQuery);
			$db->query();
		}
		die;
	}
	
	public function restart() {
		return;
		$db = JFactory::getDbo();		
		$query = 'TRUNCATE `jos_sfs_airline_details`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_airline_user_map`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_billing_details`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_blockstatus_tracking`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_contacts`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_flights_seats`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_groundhandlers`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_groundhandlers_airlines`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_hotel`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_hotel_airports`';
		$db->setQuery($query);
		$db->query();		
		$query = 'TRUNCATE `jos_sfs_hotel_mealplans`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_hotel_room_details`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_hotel_taxes`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_hotel_user_map`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_messages`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_reservations`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_room_inventory`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_voucher_codes`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_voucher_details`';
		$db->setQuery($query);
		$db->query();
		$query = 'TRUNCATE `jos_sfs_hotel_transports`';
		$db->setQuery($query);
		$db->query();	
		$query = 'TRUNCATE TABLE `jos_sfs_voucher_requests`';
		$db->setQuery($query);	
		$query = 'TRUNCATE TABLE `jos_sfs_fake_vouchers`';
		$db->setQuery($query);			
	}
	
	public function updateblockdate()
	{
		return;
		$db = JFactory::getDbo();	
		
		$query  = 'SELECT a.id,b.date,a.blockdate FROM #__sfs_reservations AS a';
		$query .= ' INNER JOIN #__sfs_room_inventory AS b ON b.id = a.room_id';
		$query .= ' WHERE a.blockdate='.$db->quote('0000-00-00');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		if(count($rows))
		{								
			foreach ($rows as $row )
			{
				$query = 'UPDATE #__sfs_reservations SET blockdate='.$db->quote($row->date).' WHERE id='.$row->id;				
				$db->setQuery($query);
				$db->query();					
			}						
		}
		echo 'Successfully';
	}

    public function changeAirport()
    {
        $airport_id = JRequest::getVar('airport_id');
        $redirect_link = JRequest::getVar('redirect_link');
        //Change airport session
        $session = JFactory::getSession();
        $session->set("airport_current_id", $airport_id);
        //Redirect to old link
        $this->setRedirect( JRoute::_($redirect_link,false) );
    }
}

