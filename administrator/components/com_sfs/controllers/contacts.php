<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Countries Controller
 */
class SfsControllerContacts extends JControllerAdmin
{
    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('sticky_unpublish',    'sticky_publish');
    }
    
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Contact', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    
    /**
     * @since    1.6
     */
    public function sticky_publish()
    {
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$ids    	= JRequest::getVar('cid', array(), '', 'array');
		$hotels    	= JRequest::getVar('hotel_id', array(), '', 'array');
		$id     	= (int) $ids[0];
		$hotel_id   = (int) $hotels[$id];
		
		
		if( $id <= 0 || $hotel_id <= 0 ){
			JError::raiseWarning(500, JText::_('COM_SFS_NO_CONTACT_SELECTED'));
		}else{
			// Get the model.
            $model    = $this->getModel();
			
			// Change the state of the records.
            if ( !$model->setPrimary($id, $hotel_id) ) {
                JError::raiseWarning(500, $model->getError());
            } else {
                $this->setMessage(JText::_('COM_SFS_CONTACT_PRIMARY_WAS_SET'));
            }
		}
		
        $this->setRedirect('index.php?option=com_sfs&view=contacts');
    }
}
