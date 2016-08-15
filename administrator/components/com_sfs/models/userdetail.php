<?php
defined('_JEXEC') or die;

class SfsModelUserdetail extends JModelList
{

	protected function getUserQuery()
	{
		return $id = JRequest::getVar('id');
	}
}