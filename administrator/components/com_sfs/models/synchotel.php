<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Airport Model
 */
class SfsModelSyncHotel extends JModel
{
    public function getTable($type = 'IATACode', $prefix = 'SfsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getAirportList()
    {
        $db    = JFactory::getDbo();
        $iatacodeTable = JTable::getInstance('Iatacode', 'SfsTable');
        $iatacodeTable->load(array('type' => 2));
        $query = $iatacodeTable->getDbo()->getQuery();
        $db->setQuery($query);
        $airports = $db->loadObjectList();
        return $airports;
    }
}
