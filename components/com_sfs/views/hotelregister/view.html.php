<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewHotelRegister extends JView
{    
	
    protected $params;
    protected $state;
    protected $hotel;

    public function display($tpl = null)
    {
    	$app  = JFactory::getApplication();
    	
        // Get the view data.
        $this->state       = $this->get('State');
        $this->params      = $this->state->get('params');
        $this->user        = JFactory::getUser();     	
      	
        if( ! $this->user->id ) {
        	if( $this->getLayout() != 'default') {
				$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    			$app->redirect( $url );       
    			return; 		
        	}			
			$this->data = (array)$app->getUserState('com_sfs.hotelregister.data', array());        	
        }  else {
	    	// check if user logged in and is Hotel group	    	
	    	if( (int) SFSAccess::check($this->user , 'h.admin') && ( $this->getLayout() == 'registerdetail' || $this->getLayout()=='addcontact'|| $this->getLayout()=='ajaxcontact') )
	    	{    		
				$this->hotel = SFactory::getHotel();
				$this->contacts = $this->hotel->getContacts();    			
	    	} else {
				$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    			$app->redirect( $url ); 	    		
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

    function progressBar( $current=0 )
    {
        $current = ($current < 0) ? 0 : $current;
        $html = '<div id="hotel_step">';        
        $i = 0;
        while ($i <= 6) {
        	$html .= '<span class="hotel_step hotel_step_' . $i . ( ($i <= $current) ? ' active' : '' ) . '">';
			$html .= JText::_('COM_SFS_HOTEL_STEP_'.$i);
            $html .= '</span>';        		
        	$i++;
        }        
        $html .= '<br style="clear:both;"/></div>';
        return $html;
    }       
  
}
