<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class SfsControllerCodecanyon extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('apply', 'save');		
	}

		
	public function cancel() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$this->setRedirect('index.php?option=com_sfs');
	}

	public function save() {
		
		$fileName = $_FILES['path_file']['name'];
		$fileTemp = $_FILES['path_file']['tmp_name'];
		
		$validFileExts = explode(',', 'jpeg,jpg,png,gif');

		//First check if the file has the right extension, we need jpg only
		if ( in_array(strtolower(JFile::getExt($fileName)), $validFileExts) ){
		   
		   	$uploadPath = JPATH_SITE.DS.'codecanyon'.DS.'images'.DS.$fileName;
	 		if(!JFile::upload($fileTemp, $uploadPath)) 
			{
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=codecanyon&error=1',false));
				//echo JText::_( 'ERROR MOVING FILE' );
					return;
			}else{
				$model		= $this->getModel('Codecanyon','SfsModel');		
		 		$model->getItem();
		 		$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=codecanyon&suss=1',false));
			}

		} else {
		   $this->setRedirect(JRoute::_('index.php?option=com_sfs&view=codecanyon&error=1',false));
		}
	



		// if($_FILES['path_file']['name'] != NULL){
			
		// 	$path = "../codecanyon/images/";
		// 	$name = $_FILES['path_file']['name'];
		// 	$tmp_name = $_FILES['path_file']['tmp_name'];
		// 	//move_uploaded_file($tmp_name,$path.$name);
		// 	if(move_uploaded_file($tmp_name,$path.$name))
		// 	{
		// 		$model		= $this->getModel('Codecanyon','SfsModel');		
		// 		$model->getItem();
		// 		//$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airplusreporting&suss=1',false));
		// 	}else{
		// 		//$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airplusreporting&suss=0',false));
		// 	}			
		// }
		
	}

	

	public function newAdmin() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model		= $this->getModel('Hotel','SfsModel');

		if( $model->newAdmin() ) {
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'));
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'),$msg);
		}

		return true;
	}	

	public function import()
	{		
		if(isset($_POST['ok'])){			
			if($_FILES['userfile']['name'] != NULL){
				$path = "../report/airplus/";
				$name = $_FILES['userfile']['name'];
				$tmp_name = $_FILES['userfile']['tmp_name'];
				//move_uploaded_file($tmp_name,$path.$name);
				if(move_uploaded_file($tmp_name,$path.$name))
				{
					$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airplusreporting&suss=1',false));
				}else{
					$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=airplusreporting&suss=0',false));
				}
				// header("Location: ./index.php?option=com_sfs&view=airplusreporting");
				
				// exit();
			}
		}
	}

}
