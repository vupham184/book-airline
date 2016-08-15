<?php
defined('_JEXEC') or die();

require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';
 
class SfsControllerRoomloading extends SFSController
{

	public function __construct($config = array())
	{
		parent::__construct($config);		
		$this->registerTask('save', 'savePrices');	
	}
				
	public function savePrices()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();
								
		$model = $this->getModel('Roomloading');
		
		$post = JRequest::get('post');
		
		$result = $model->savePrices($post);
		
		$errors	= $model->getErrors();	
		
		if( count($errors) ) {			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}			
		} 
		
		if ($result) {									
			if( count($errors) ) {							
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=roomloading&Itemid='.$post['Itemid'], false));			
				return true;				
			}else {
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=roomloading&layout=thankyou&Itemid='.$post['Itemid'], false));			
				return true;				
			}				
		}			
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=roomloading&Itemid='.$post['Itemid'], false));			
	}

	
}

