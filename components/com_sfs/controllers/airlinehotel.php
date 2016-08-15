<?php
defined('_JEXEC') or die();    
jimport('joomla.application.component.controller');
jimport('joomla.filter.filteroutput');
 
class SfsControllerAirlineHotel extends JController
{

    public function add()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        // Initialise some variables
        $app		= JFactory::getApplication();
        $SiteName	= $app->getCfg('sitename');

        $hotel	    = JRequest::getVar('hotel', array(), 'post', 'array');
        $rooms	    = JRequest::getVar('rooms', array(), 'post', 'array');
        $mealplan	    = JRequest::getVar('mealplan', array(), 'post', 'array');

        $hotel['chain_id'] 	 = JRequest::getInt('chain_id' , 0);

        $model = $this->getModel('AirlineHotel','SfsModel');

        // Attempt to save the data.
        $return	= $model->addHotel($hotel, $rooms, $mealplan);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $error = (string)$model->getError();

            $app->setUserState('com_sfs.airlinehotel.hotel', $hotel);
            $app->setUserState('com_sfs.airlinehotel.rooms', $rooms);

            // Redirect back to the edit screen.
            $this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airlinehotel&Itemid='.JRequest::getInt('Itemid'), false));
            return false;
        }

        // Flush the data from the session.
        $app->setUserState('com_sfs.airlinehotel.hotel', null);
        $app->setUserState('com_sfs.airlinehotel.rooms', null);

                $this->setRedirect(JRoute::_('index.php?option=com_sfs&view=handler&layout=flightform&Itemid=118', false));
        return true;
    }

    public function reservation()
    {
        $hotelID 	 = JRequest::getInt('hotel_id' , 0);
        $rooms	    = JRequest::getVar('rooms', array(), 'post', 'array');

        $model = $this->getModel('AirlineHotel','SfsModel');
        // Attempt to save the data.
        $return	= $model->reservation($hotelID, $rooms);

        if ($return === false) {
            // Redirect back to the edit screen.
            $this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airlinehotel&Itemid='.JRequest::getInt('Itemid'), false));
            return false;
        }
        return true;
    }
}

