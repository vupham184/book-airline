<?php
// No direct access
defined('_JEXEC') or die;

class SfsTableTaxidetail extends JTable
{	
	public function __construct(& $db)
	{
		parent::__construct('#__sfs_taxi_details', 'id', $db);
	}

}
