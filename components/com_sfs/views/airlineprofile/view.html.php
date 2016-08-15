<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewAirlineProfile extends JView
{    
    protected $params;
    protected $state;
    protected $airline;
    protected $contacts;    

    public function display($tpl = null)
    {    	
    	$app = JFactory::getApplication();
    	
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');    	
    	$this->user		   = JFactory::getUser();
    	
    	//check permision
    	if( SFSAccess::isAirline($this->user ) )
    	{    		       		                      
        	$this->airline 	   = SFactory::getAirline();
        	$this->contacts    = $this->airline->getContacts();            	    
    	} else {
    		$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    		$app->redirect( $url );        		
    		return false;    		
    	}     	
    	
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));      
        
        //$this->prepareDocument();
        
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
            $this->params->def('page_heading', JText::_('COM_SFS_HOTEL_PROFILE'));
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
