<?php
defined('JPATH_PLATFORM') or die;

jimport('joomla.database.table');

class JTableFlightseat extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 *
	 * @since   11.1
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_flights_seats', 'id', $db);
	}

}
