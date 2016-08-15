<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewFindhotelonmap extends JView
{    
    protected $params;
    protected $state;
    protected $hotel;
    protected $user;

    public function display($tpl = null)
    {    
		$app = JFactory::getApplication();
		$this->user		   = JFactory::getUser();
		//check permision
    	if( SFSAccess::isHotel( $this->user ) )
    	{    		      
			$hotel = SFactory::getHotel(); 		                      
        	$this->hotel 	   = $hotel;            
        	
    		if( (int)$this->hotel->block==1 ) {	    		
	    		$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid=103',false) );
	    		return false;
    		}
        	
    	} else {
    		$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    		$app->redirect( $url );        		
    		return false;    		
    	}     
			
    	/*$app = JFactory::getApplication();
    	
		$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');    	
    	$this->user		   = JFactory::getUser();
    	
    	//check permision
    	if( SFSAccess::isHotel( $this->user ) )
    	{    		       		                      
        	$this->hotel 	   = $this->get('Hotel');            
        	
    		if( (int)$this->hotel->block==1 ) {	    		
	    		$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid=103',false) );
	    		return false;
    		}
        	
    	} else {
    		$url = JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false);
    		$app->redirect( $url );        		
    		return false;    		
    	}     	
    	    	
       	$this->processLayout( $this->getLayout() );	    
       	
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));      
        
        $this->prepareDocument();
        */
        parent::display($tpl);
    }
}

