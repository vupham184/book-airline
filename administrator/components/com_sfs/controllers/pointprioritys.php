<?php
defined('_JEXEC') or die;

class SfsControllerPointprioritys extends JControllerLegacy
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}	
	
	public function getModel($name = 'Pointprioritys', $prefix = 'SfsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function delete(){
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');
		if($cid){
			$db 	= JFactory::getDbo();			
			if($cid)	{
				$query = "DELETE FROM #__sfs_point_priority WHERE id in (".implode(",", $cid).")";
				$db->setQuery($query);
				$db->query();
			}		
		}
		$link = 'index.php?option=com_sfs&view=pointprioritys';
		$this->setRedirect($link,$msg);	
	}
	
}