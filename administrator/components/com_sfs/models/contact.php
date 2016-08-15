<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');


class SfsModelContact extends JModelItem
{

	protected function populateState()
	{
		$pk = JRequest::getInt('id');
		$this->setState('contact.id',$pk);		
	}	
	
	public function getItem(){
				
		if ($this->_item === null) {
			$this->_item = array();
		}				
		$pk = (int) $this->getState('contact.id');
		
		if ( ! isset($this->_item[$pk]) ) {
			$db   = $this->getDbo();
				
			$query  = 'SELECT a.*,u.email FROM #__sfs_contacts AS a';
			$query .= ' LEFT JOIN #__users AS u ON u.id=a.user_id';
			$query .= ' WHERE a.id='.$pk;
			
			$db->setQuery($query);
			
			
			$this->_item[$pk] = $db->loadObject();
			
			if( $error = $db->getErrorMsg() ) {
				throw new Exception($error);
			}
			
			if( empty($this->_item[$pk]) ) {
				throw new Exception( $db->getErrorMsg() );
			}				
		}
		
		return $this->_item[$pk];	
	}

}
