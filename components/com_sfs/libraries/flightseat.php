<?php
// No direct access
defined('_JEXEC') or die;

class FlightSeat extends JObject
{
	
	/**
	 * Unique id
	 *
	 * @var    integer
	 */	
	var $id = null;

	/**
	 * id of airline
	 *
	 * @var    integer
	 */	
	var $airline_id = null;
	
	/**
	 * id of user who's created the flight seat
	 * 
	 * @var    integer
	 */
	var $created_by = null;
	
	/**
	 * Flight Number
	 * 
	 * @var 	string
	 */
	var $flight_code = null;
	
	/**
	 * Number seats of the flight
	 * 
	 * @var    integer
	 */
	var $seats = null;
	
	/**
	 * Number seats that be issued by Airline
	 * 
	 * @var    integer
	 */
	var $seats_issued = null;
	
	/**
	 * Flight Class
	 * 
	 * @var 	string
	 */
	var $flight_class = null;
	
	/**
	 * Delay Code of the flight
	 * 
	 * @var 	string
	 */
	var $delay_code = null;
	
	/**
	 * Date the flight was created
	 *
	 * @var    datetime
	 * @since  11.1
	 */
	var $created = null;
	
	/**
	 * Flight Status
	 *
	 * @var    boolean
	 */
	var $is_expire = null;
	
	
	public function save( $data )
	{
		
	}
	
	/**
	 * Method to bind an associative array of data
	 *
	 * @param	array	$array	The associative array to bind to the object
	 *
	 * @return	boolean	True on success
	 * @since	2.5
	 */	
	public function bind( & $data )
	{						
		// Bind the array
		if ( ! $this->setProperties($data) ) {
			$this->setError(JText::_('Unable to bind array to flightseat object'));
			return false;
		}		
		// Make sure its an integer
		$this->id = (int) $this->id;		
		return true;
	}
	
	/**
	 * Method to perform sanity checks on the FlightSeat instance properties to ensure
	 * they are safe to store in the database.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
		
		if( empty($this->airline_id) )
		{
			$this->setError('AirlineId invalid');
			return false;
		}
		
		if( empty($this->created_by) )
		{
			$this->setError('Guest Unable to create flight seat');
			return false;
		}
		
		if( (int) $this->seats <= 0 ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_1'));	
			return false;		
		}
		if( strlen($this->flight_code) == 0 ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_2'));
			return false;
			
		}	
		if( strlen($this->delay_code) == 0 ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_3'));
			return false;						
		}			
		
		//check delay code
		$db = JFactory::getDbo();
		$query = 'SELECT COUNT(*) FROM #__sfs_delaycodes WHERE code='.$db->Quote($this->delay_code);
		$db->setQuery($query);
		
		if( ! $db->loadResult() ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_3'));
			return false;			
		}		
		
		return true;
	}
	
}




