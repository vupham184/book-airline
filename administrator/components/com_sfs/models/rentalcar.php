<?php
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

//class SfsModelRentalcar extends JModelLegacy{
class SfsModelRentalcar extends JModelAdmin{
protected $text_prefix = 'com_sfs';
	public function getTable($type = 'Rentalcar', $prefix = 'SfsTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getCodeAirports(){		
		$db = $this->getDbo();			
		$query = 'SELECT  * FROM #__sfs_iatacodes';						
		$query .= ' WHERE type=2';
		$db->setQuery($query);
		$result = $db->loadObjectList();
		if( $error = $db->getErrorMsg() ){
				throw new Exception($error);
		}
		return $result;
	}
	/*public function getItem(){
		$id=JRequest::getInt('id',0);		
		if($id){
			$db = $this->getDbo();			
			$query = 'SELECT  * FROM #__sfs_company_rental_car';						
			$query .= ' WHERE id='.$id;
			$db->setQuery($query);
			$result = $db->loadObject();
			if( $error = $db->getErrorMsg() ){
					throw new Exception($error);
			}	
		}		
		return $result;	
	}*/
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sfs.rentalcar', 'rentalcar', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_sfs.edit.rentalcar.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}

		return $data;
	}
	
	public function save($data)
	{		
		$fileName = $_FILES['logo']['name'];
		$fileTemp = $_FILES['logo']['tmp_name'];		
		$validFileExts = explode(',', 'jpeg,jpg,png,gif');		
		if($_FILES['logo']['name']){
				if (in_array(strtolower(JFile::getExt($fileName)), $validFileExts) ){
						$new_name=time()."_".$fileName;
						$uploadPath = JPATH_SITE.DS.'components'.DS.'com_sfs'.DS.'upload'.DS.'rental_car'.DS.$new_name;
						if(!JFile::upload($fileTemp, $uploadPath)) {
							$this->setError('Upload image error');
							return false;
						}
						else{
							$link_logo='components/com_sfs/upload/rental_car/'.$new_name;							
						}
					}
					else{			
							$this->setError('Upload image error');
							return false;
					}	
				$data['logo']=$link_logo;		
			}		
			
		if (parent::save($data)) {						
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_sfs&view=rentalcars');
			//return true;
		}
		return false;
	}
}
