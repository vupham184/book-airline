<?php
defined('_JEXEC') or die;

class SBus extends JObject
{				

	protected $_db = null;
	protected $_profiles = null;
	
	public function __construct($identifier = 0, $db = null)
	{		
		$session = JFactory::getSession();
				
		$this->setDbo($db);
		$this->set('grouptype',4);
		
		if ( ! empty($identifier) && (int)$identifier > 0 ) {						
			$this->load($identifier);
		}
		else {
			$user = JFactory::getUser();			
			if( SFSAccess::isBus() ) 
			{
				$bus_id = (int) $session->get('bus_id',0);
				if( ! empty($bus_id) ) {
					$this->load($bus_id) ;
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
			$instance	= new SBus( $identifier , $db);
			$instances[$identifier]  = $instance;
		}

		return $instances[$identifier];
	}
	
	public function getTable()
	{
		return JTable::getInstance('Bus', 'JTable');
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
	
	public function getProfiles()
	{
		if( (int)$this->id > 0 && $this->_profiles == null )
		{
			$db 	= $this->getDbo();		
			$query	= $db->getQuery(true);
		
			$query->select('a.*');
			$query->from('#__sfs_group_transportation_types AS a');
			$query->where('a.group_transportation_id='.(int)$this->id);
			$query->where('a.published=1');	
			
			$db->setQuery($query);
			
			$this->_profiles = $db->loadObjectList();
			
			if( $db->getErrorNum() )
			{
				$this->setError($db->getErrorMsg());
				return false;	
			}			
		}
		
		
		return $this->_profiles;
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
		
		$query->select('a.*,c.name AS country_name');		
        $query->from('#__sfs_group_transportations AS a');
        $query->leftJoin('#__sfs_country AS c ON c.id=a.country_id');             
		$query->where('a.id='. (int) $pk);
		
		$db->setQuery($query);
		
		$bus = $db->loadObject();
		
		if( $db->getErrorNum() )
		{
			throw new Exception($db->getErrorMsg());
		}
				
		if (empty($bus)) {			
			throw new Exception('Bus was not found',404);
		}
	
		$vars = get_object_vars($bus);
				
		foreach ($vars as $key => $value) {
			$this->$key = $value;				
		}
		
		$registry = new JRegistry;
		$registry->loadString($this->notification);			
		$this->notification = $registry->toArray();
				
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
	
}


