<?php
defined('_JEXEC') or die;

class SfsModelContractdetails extends JModelLegacy
{	
	
    protected function populateState()
    {
        $app  		 = JFactory::getApplication();        
        $params    	 = $app->getParams('com_sfs');   
        		
        // Load the parameters.
        $this->setState('params', $params);
    }   
    
}
