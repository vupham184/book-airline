<?php
defined('_JEXEC') or die;

class STaxi extends JObject
{				
	protected $_db = null;
	
	public function __construct($identifier = 0, $db = null)
	{		
		$session = JFactory::getSession();
				
		$this->setDbo($db);
		$this->set('grouptype',5);
		
		if ( ! empty($identifier) && (int)$identifier > 0 ) {						
			$this->load($identifier);
		}
		else {
			$user = JFactory::getUser();			
			if( SFSAccess::isTaxi() ) 
			{
				$taxi_id = (int) $session->get('taxi_id',0);
				if( ! empty($taxi_id) ) {
					$this->load($taxi_id) ;
				} else {
					$this->load($user->id,'user') ;	
				}											
			} else {
				$this->id = 0;
			}								
		}
	}	
		
	public static function getInstance( $identifier = 0, $db = null )
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}
		
		if (empty($instances[$identifier])) {
			$instance	= new STaxi( $identifier , $db);
			$instances[$identifier]  = $instance;
		}

		return $instances[$identifier];
	}
	
	public function setDbo($db=null)
	{
		if($db==null)
		{
			$db = JFactory::getDbo();
		}	
		$this->_db = $db;		
	}
	
	public function getDbo()
	{
		return $this->_db;
	}
	
	
	public function load( $identifier , $type = 'pk' )
	{
		$pk = $identifier;	
		
		if( $type == 'user' ) 
		{
			$contact = SFactory::getContact( $identifier );
			if( ! empty($contact) ) 
			{
				$pk = $contact->group_id;
			} 			
		}

		$db 	= $this->getDbo();		
		$query	= $db->getQuery(true);
		
		$query->select('a.*,b.name AS country,c.name AS billing_country');
        $query->from('#__sfs_taxi_companies AS a');
        
        $query->leftJoin('#__sfs_country AS b ON b.id=a.country_id');
        $query->leftJoin('#__sfs_country AS c ON c.id=a.billing_country_id');
        
		$query->where('a.id='. (int) $pk);
		
		$db->setQuery($query);
		
		$taxi = $db->loadObject();
		
		if( $db->getErrorNum() )
		{
			throw new Exception($db->getErrorMsg());
		}
				
		if (empty($taxi)) {			
			throw new Exception('Taxi was not found',404);
		}
	
		$vars = get_object_vars($taxi);
				
		foreach ($vars as $key => $value) {
			$this->$key = $value;				
		}
		
		$registry = new JRegistry;
		$registry->loadString($this->notification);			
		$this->notification = $registry->toArray();
		
		$registry = new JRegistry;
		$registry->loadString($this->params);			
		$this->params = $registry->toArray();
				
		return true;
	}	
	
	public function getNotifications( $type = 'email')
	{
		if( is_array($this->notification ) && count($this->notification[$type]) )
		{
			return $this->notification[$type];				
		}
		return null;
	}
	
	public function getParam($name)
	{
		if($name && isset($this->params[$name]))
		{
			return $this->params[$name];
		}
		return null;
	}
	
}


