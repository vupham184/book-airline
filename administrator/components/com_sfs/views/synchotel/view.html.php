<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class SfsViewSyncHotel extends JView
{
    public function display($tpl = null)
    {
        $this->addToolbar();
        $this->airports = $this->get("AirportList");
        parent::display($tpl);
    }
    protected function addToolbar()
    {
        JToolBarHelper::title('SFS Control Panel', 'cpanel.png');
        JToolBarHelper::preferences('com_sfs');
    }
}
