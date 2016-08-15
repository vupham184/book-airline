<?php
defined('_JEXEC') or die;

class SfsCron extends JObject
{
	
	public function doExecute()
	{		
		//update status from tentative to approve after X day
		$this->updateBlockstatus();
	}
	
	private function updateBlockstatus()
	{
		$db = JFactory::getDBO();
				
		$query = $db->getQuery(true);
		$query->select('a.id,a.params');
		$query->from('#__sfs_airline_details AS a');
		
		$db->setQuery($query);
		
		$airlines = $db->loadObjectList();
		
		if(count($airlines))
		{
			$now = JFactory::getDate()->toSql();
			$now = JString::substr($now, 0,10);
			
			foreach ($airlines as $airline)
			{
				// clear prev query
				$query->clear();
		
				$registry = new JRegistry();
				$registry->loadString($airline->params);
				
				$days = $registry->get('number_day_update_status', 7);
				
				if( (int) $days <= 1 ) {			
					continue;
				}
										
				$query->select('a.id');
				$query->from('#__sfs_reservations AS a');
				$query->where('a.airline_id='.$airline->id);
				$query->where('a.status='.$db->quote('T'));
				$query->where('DATEDIFF('.$db->quote($now).',a.blockdate) >= '.$days);
				
				$db->setQuery($query);
				
				$reservations = $db->loadResultArray();
				
				if(count($reservations))
				{			
					$updateQuery  = 'UPDATE #__sfs_reservations SET status='.$db->quote('A');
					$updateQuery .= ' WHERE id='.implode(' OR id=', $reservations);
					$db->setQuery($updateQuery);
					$db->query();
				}
		
			}	
		}
		
	}
	
	
}

