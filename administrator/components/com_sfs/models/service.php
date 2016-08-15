<?php
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

//class SfsModelRentalcar extends JModelLegacy{
class SfsModelService extends JModelAdmin{
protected $text_prefix = 'com_sfs';
	public function getTable($type = 'Services', $prefix = 'SfsTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sfs.service', 'service', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sfs.edit.service.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}

		return $data;
	}
	
	public function save($data)
	{		
		$fileName = $_FILES['logo']['name'];
		$fileTemp = $_FILES['logo']['tmp_name'];		
		$fileNameicon_service = $_FILES['icon_service']['name'];
		$fileTempicon_service = $_FILES['icon_service']['tmp_name'];	
		$validFileExts = explode(',', 'jpeg,jpg,png,gif');		
		if($_FILES['logo']['name']){
				if (in_array(strtolower(JFile::getExt($fileName)), $validFileExts) ){
						$new_name=time()."_".$fileName;
						$uploadPath = JPATH_SITE.DS.'media'.DS.'media'.DS.'images'.DS.'select-pass-icons'.DS.$new_name;
						if(!JFile::upload($fileTemp, $uploadPath)) {
							$this->setError('Upload image error');
							return false;
						}
						else{
							$link_logo='media/media/images/select-pass-icons/'.$new_name;							
						}
					}
					else{			
							$this->setError('Upload image error');
							return false;
					}	
				$data['logo']=$link_logo;		
			}
			if($_FILES['icon_service']['name']){
				if (in_array(strtolower(JFile::getExt($fileNameicon_service)), $validFileExts) ){
						$new_name=time()."_".$fileNameicon_service;
						$uploadPath = JPATH_SITE.DS.'media'.DS.'media'.DS.'images'.DS.'select-pass-icons'.DS.$new_name;
						if(!JFile::upload($fileTempicon_service, $uploadPath)) {
							$this->setError('Upload image error');
							return false;
						}
						else{
							$link_icon_service='media/media/images/select-pass-icons/'.$new_name;							
						}
					}
					else{			
							$this->setError('Upload image error');
							return false;
					}	
				$data['icon_service']=$link_icon_service;		
			}
			if($data->create_date=='')
			{
				$data['create_date']=date('Y-m-d');
			}				
		if (parent::save($data)) {						
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_sfs&view=services');
			//return true;
		}
		return false;
	}
}
