<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewAirlineHotel extends JView
{    
	
    protected $params;
    protected $state;
    protected $hotel;
    protected $inventory;
    protected $result;

    public function display($tpl = null)
    {
    	$app  = JFactory::getApplication();

        // Get the view data.
        $this->state       = $this->get('State');
        $this->params      = $this->state->get('params');
        $this->user        = JFactory::getUser();
        $this->hotels 	  = $this->get('Data');


        $hotel_id = JRequest::getInt('id');
        if( $this->getLayout() != 'default') {
            if( ! $hotel_id ) {
                if( $this->getLayout() != 'addhotel') {
                    $url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
                    $app->redirect( $url );
                }
            }  else {
                if(( $this->getLayout() == 'reservation' ))
                {
                    $this->state->set('hotel.id',$hotel_id);
                    $this->hotel = $this->get('Hotel');
                    $this->contact = $this->get('Contact');
                }
                else {
                    $url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
                    $app->redirect( $url );
                }
            }
        }

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

        $this->prepareDocument();

        parent::display($tpl);
    }

    protected function prepareDocument()
    {
        $app        = JFactory::getApplication();
        $menus      = $app->getMenu();
        $title      = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_SFS_HOTEL_REGISTRATION'));
        }

        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0)) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        $this->document->setTitle($title);
    }
}
