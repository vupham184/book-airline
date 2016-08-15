<?php 
defined('_JEXEC') or die;

class SfsControllerTrainlist extends JControllerLegacy
{
	public function saveAirlineTrain()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		$model	= $this->getModel('Trainlist','SfsModel');
		
		$result = $model->saveAirlineTrain();
		$result ? 'sucess':'unsucess';
		$link = 'index.php?option=com_sfs&view=close&reload=1';
		$this->setRedirect($link,$result);
		return $this;
	}
	public function editAirlineTrain()
	{
		$model	= $this->getModel('Trainlist','SfsModel');
		$result = $model->editAirlineTrain();
		$link = 'index.php?option=com_sfs&view=close&reload=1';
		$result ? 'sucess':'unsucess';
		$this->setRedirect($link,$result);
		return $this;
	}
	public function deleteAirlineTrain(){
		$link = 'index.php?option=com_sfs&view=trainlists';
		$cid = JRequest::getVar('cid',array(),'','array');
		$arrData = implode(',',$cid);
		$model = $this->getModel('Trainlists','SfsModel');
		$result= $model->deleteAirlineTrain($arrData);
		$messager =  $result ? 'Delete Success': 'Not Delete';
		$this->setRedirect($link,$messager);
	}
}
?>