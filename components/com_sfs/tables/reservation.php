<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableReservation extends JTable
{
    var $id = null;
    var $blockcode = null;
    var $blockdate = null;
    var $airline_id = null;
    var $booked_by = null;
    var $hotel_id = null;
    var $room_id = null;
    var $booked_date = null;
    var $approved_date = null;

    var $s_room = null;
    var $sd_room = null;
    var $t_room = null;
    var $q_room = null;
    var $s_rate = null;
    var $sd_rate = null;
    var $t_rate = null;
    var $q_rate = null;
    var $s_room_issued = null;
    var $sd_room_issued = null;
    var $t_room_issued = null;
    var $q_room_issued = null;
    var $ws_s_rate = null;
    var $ws_sd_rate = null;
    var $ws_t_rate = null;
    var $ws_q_rate = null;
    var $claimed_rooms = null;

    var $revenue_booked = null;
    var $transport = null;
    var $breakfast = null;
    var $lunch = null;
    var $mealplan = null;
    var $percent_release_policy = null;
    var $course_type = null;
    var $status = null;
    var $expired = null;
    var $payment_type = null;
    var $hotel_user_id = null;
    var $association_id = null;
    var $ws_room_type = null;
    var $ws_prebooking = null;
    var $ws_booking = null;
    var $ws_room = null;
    var $airport_code = null;
    var $url_code = null;


    public function __construct(&$db)
    {
        parent::__construct('#__sfs_reservations', 'id', $db);
    }

}
