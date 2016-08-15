<?php
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class SfsModelTimezone extends JModelItem
{
		
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	public function &getItem ( $pk = null )
	{
		if( $this->_item === null ) {
			$user = JFactory::getUser();
			$db   = $this->getDbo();
			$query  = 'SELECT a.*,u.email FROM #__sfs_contacts AS a';
			$query .= ' INNER JOIN #__users AS u ON u.id=a.user_id';
			$query .= ' WHERE a.user_id='.$user->id.' AND u.block=0';
			$db->setQuery($query,0,1);
			$this->_item = $db->loadObject();
			if( ! $this->_item ) {
				throw new JException($db->getErrorMsg());
			}			
		}
		return $this->_item ;
	}	

}

