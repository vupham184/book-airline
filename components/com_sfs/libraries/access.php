<?php
// No direct access
defined('_JEXEC') or die;

abstract class SFSAccess 
{
	private static $_user_groups	 = null;
    private static $_is_main_contact = null;
    private static $_is_bus 		 = null;
    private static $_is_taxi 		 = null;
    
    public static function check( $user , $action ) 
    {    	    					
    	if( isset($user) ) {
    		if ( !($user instanceof JUser)) {
    			$user = JFactory::getUser();
    		}
    	} else {
    		$user = JFactory::getUser();	
    	}
    	    	    	  
    	$user_role = null;
		
		$groups = JAccess::getGroupsByUser($user->id,false);
		//echo $action.'<br>';
		//print_r( $groups );
		//die;
		$groupId = (int)$groups[0];
		  			
		switch ( $action ) {
			case 'hotel' :					
				if( in_array(13, $groups) ) {
					$user_role = 13;
				} else if( in_array(12, $groups) ) {
					$user_role = 12;
				} else {
					$user_role = null;
				}													
				break;
			case 'h.admin':
				if( in_array(13, $groups) ) {
					$user_role = 13;
				} else {
					$user_role = null;
				}					
				break;
			case 'airline' :
				if( in_array(11, $groups) ) {
					$user_role = 11;
				} else if( in_array(9, $groups) ) {
					$user_role = 9;
				} else if( in_array(15, $groups) ) {
					$user_role = 15;
				} else if( in_array(14, $groups) ) {
					$user_role = 14;
				} else if( in_array(18, $groups) ) {
                    $user_role = 18;
                }
				else {
					$user_role = null;
				}					
				break;					
			case 'a.admin':
				if( in_array(11, $groups) ) {
					$user_role = 11;
				} else if(in_array(15, $groups)) {
					$user_role = 15;
				}				
				break;	
			case 'gh':
				if( in_array(15, $groups) ) {
					$user_role = 15;
				} else if( in_array(14, $groups) ) {
					$user_role = 14;
				}	echo $user_role;			
				break;	
			case 'bus' :					
				$busGroupId = self::getUserGroup('Bus');						
				if( $groupId == $busGroupId ) {
					$user_role = $groupId;
				}												
				break;	
			case 'taxi' :					
				$taxiGroupId = self::getUserGroup('Taxi');
				if( $groupId == $taxiGroupId ) {
					$user_role = $groupId;
				}												
				break;	
			case 'airline_accounting' :					
				if( in_array(19, $groups) ) {
                    $user_role = 19; //Airline accounting
                }	
				else {
					$user_role = null;
				}											
				break;		
            case 'station':
                if( in_array(18, $groups) ) {
                    $user_role = 18;
                } else {
                    $user_role = null;
                }            
                break;  		
			default :
				break;
		}		
		return $user_role;	
    }

    public static function isAirline($user = null) {
    	$result = null;
        if ($user == null) {
            $user = JFactory::getUser();
        }
        if ($user->id) {
        	$result = SFSAccess::check($user, 'airline');         
        }        
        return $result;
    }

    public static function isHotel($user = null) {
    	$result = null;
        if ($user == null) {
            $user = JFactory::getUser();
        }
        if ($user->id) {
        	$result = SFSAccess::check($user, 'hotel');         
        }        
        return $result;
    }
    
 	public static function isBus() 
 	{    	
 		if(self::$_is_bus == null)
 		{
 			$user = JFactory::getUser();
        	self::$_is_bus = SFSAccess::check($user, 'bus');	
 		}
 		       
        return self::$_is_bus;
    }
    
	public static function isTaxi() 
 	{    	
 		if(self::$_is_taxi == null)
 		{
 			$user = JFactory::getUser();
        	self::$_is_taxi = SFSAccess::check($user, 'taxi');	
 		}
 		       
        return self::$_is_taxi;
    }

    public static function isMainContact() 
    {
        if (self::$_is_main_contact === null) {
            $user = JFactory::getUser();
            $db = JFactory::getDbo();
            $db->setQuery('SELECT COUNT(*) FROM #__sfs_contacts WHERE is_admin=1 AND user_id=' . $user->id);
            $result = $db->loadResult();
            self::$_is_main_contact = (int) $result > 0 ? 1 : 0;
        }
        return self::$_is_main_contact;
    }
    
    private static function getUserGroup($groupName=null)
    {
    	if( self::$_user_groups === null )
    	{
    		$db = JFactory::getDbo();
    		$db->setQuery('SELECT a.* FROM #__usergroups AS a');
    		self::$_user_groups = $db->loadAssocList('title','id');       				
    	}
    	
    	if($groupName && self::$_user_groups[$groupName])
    	{
    		return (int) self::$_user_groups[$groupName];
    	} 
    	
    	return self::$_user_groups;
    }
	
	//lchung
	public static function isAirlineAccounting($user = null) {
    	$result = null;
        if ($user == null) {
            $user = JFactory::getUser();
        }
        if ($user->id) {
        	$result = SFSAccess::check($user, 'airline_accounting');         
        }        
        return $result;
    }
	//End lchung

}

