<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';
 
class SfsControllerHotelProfile extends SFSController
{

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('taxes',		'saveTaxes');			
	}	
	
	public function saveAirports()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();
								
		$model = $this->getModel('HotelProfile');
						
		$result = $model->saveAirports();
		
		$post = JRequest::get('post');	

		if( $post['tmpl'] == 'component' )
		{
			$this->setRedirect('index.php?option=com_sfs&view=close&tmpl=component&closetype=airport');
			return true;
		}			
		
		if ($result) {					
		
			if( isset($post['save_close']) ) {
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111', false));
			}else if( isset($post['save_next']) ) {
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111', false));	
			}else{
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111', false));
			}	

			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111', false));		
			return true;			
		}				
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formairports&Itemid=113', false));
						
	}
	
	
		
	public function saveTaxes()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();
		
		$post = JRequest::get('post');
		
		$model = $this->getModel('HotelProfile');
		
		$result = $model->saveTaxes($post);
		$msg = '';

		
		if ($result) {								

			if( $post['save_close'] ) {
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&Itemid='.JRequest::getInt('Itemid'), false));
			}else if($post['save_next']){
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formairports&Itemid=113', false));				
			}else{
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtaxes&Itemid='.JRequest::getInt('Itemid'), false));
			}	

			

			return true;			
		} else {
			$msg = $model->getError();
		}
		

		if($post['save_close'] == 2){			
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=taxes&Itemid='.JRequest::getInt('Itemid'), false), $msg);				
			return false;
		}
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formairports&Itemid=113', false));				
		//$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtaxes&Itemid='.JRequest::getInt('Itemid'), false), $msg);				

	}	
	
	public function saveMealplan()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();

		$model = $this->getModel('HotelProfile');
		
		$post = JRequest::get('post');
		
		$result = $model->saveMealplan($post);
		
		if ($result) {						
			if( $post['save_close'] ) {
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&Itemid='.JRequest::getInt('Itemid'), false));
			}else if($post['save_next']){
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtransport&Itemid=130', false));	
			}else{
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111', false));
			}			
			return true;			
		}
		
		//$this->setMessage(JText::_(''));
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111', false));				
	}	
	
	public function saveTransport()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();

		$model = $this->getModel('HotelProfile');
		
		$post = JRequest::get('post');
		
		$result = $model->saveTransport($post);
				
		if ($result) {
			$hotel = SFactory::getHotel();	
			if( $hotel->isRegisterComplete() ) {
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&Itemid='.JRequest::getInt('Itemid'), false));
			} else {
				//$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=terms&Itemid='.JRequest::getInt('Itemid'), false));
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelregister&layout=registerdetail&Itemid=110', false));				
			}										
			return true;			
		}
	
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtransport&Itemid='.JRequest::getInt('Itemid'), false));				
	}	
	
	public function preview()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();

		$model = $this->getModel('HotelProfile');
		
		$post = JRequest::get('post');		
		$result = $model->saveTransport($post);
	
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formtransport&Itemid='.JRequest::getInt('Itemid').'&preview=1', false));		
	}
	
	public function confirm()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();
		
		// validate the checkbox right here but will do it later
		
		$post['confirm'] = JRequest::getVar('agree');
		if( empty($post['confirm']) ) {
			//$app->setUserState('register.confirm', 'false');
			$app->enqueueMessage('Please tick this box to approve General Terms and Conditions', 'warning');		
			$link = JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=terms&Itemid='.JRequest::getInt('Itemid'),false);			
			$this->setRedirect($link);
			return;			
		}
				
		$model = $this->getModel('HotelProfile');
		
		$result = $model->confirmTerms();
		
		if ($result) {		
			//$app->setUserState('com_sfs.hotelregister.registerdetail.data', null);	
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=thank&Itemid=173', false));
			return true;			
		}
		
		//$this->setMessage(JText::_(''));
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=terms&Itemid='.JRequest::getInt('Itemid'), false));				
	}	
	
	public function finish()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		// Initialise some variables
		$app		= JFactory::getApplication();
		
		$model = $this->getModel('HotelProfile');
		 		
		$result = $model->finishRegister();
		
		if ($result) {		
			$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'), false));
			return true;		
		}
		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'), false));				
	}	
	
	//lchung
	public function InsertGeolocation(){
		$app		= JFactory::getApplication();
		$model = $this->getModel('HotelProfile');		 		
		echo $result = $model->InsertGeolocation();
		exit;
	}
	//End lchung	
	
}

