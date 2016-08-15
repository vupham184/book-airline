<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableInventory extends JTable
{		
	var $id = null;
	var $hotel_id = null;
	var $sd_room_total = null;
	var $sd_room_rate = null;
	var $sd_room_rate_modified = null;
	var $sd_room_rank = null;
	var $sd_num_rank = null;
	var $t_room_total = null;
	var $t_room_rate = null;
	var $t_room_rate_modified = null;
	var $t_room_rank = null;
	var $t_num_rank = null;
	
	var $s_room_total = null;
	var $s_room_rate = null;
	var $s_room_rate_modified = null;
	var $s_room_rank = null;
	var $s_num_rank = null;
	
	var $q_room_total = null;
	var $q_room_rate = null;
	var $q_room_rate_modified = null;
	var $q_room_rank = null;
	var $q_num_rank = null;
	
	var $transport_included = null;
	var $created = null;
	var $created_by = null;
	var $modified = null;
	var $modified_by = null;
	var $date = null;
	var $booked_sdroom = null;
	var $booked_troom = null;
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_room_inventory', 'id', $db);		
	}
	
}
