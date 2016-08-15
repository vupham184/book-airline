<?php
defined('_JEXEC') or die;

class SfsControllerAjax extends JControllerLegacy
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);		
		$this->registerTask('rank',			'calculateRanking');
		$this->registerTask('nrank',		'calculateNumberRanking');												
	}		
	
	public function hotelcheck()
	{		
		$id = JRequest::getVar('id');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('id');
		$query->from('#__users');
		
		if( ! is_numeric($id) ) {
			$query->where( 'username='. $db->Quote( trim($id) ) );	
		} else {
			$query->where('id='. (int) $id );
		}
				
		$db->setQuery($query);
		
		$result = $db->loadResult();
				
		if( ! empty($result) ) {
			
			$user = JFactory::getUser($result);
			
			require_once JPATH_ROOT . '/components/com_sfs/libraries/access.php';
			
			if( SFSAccess::isHotel($user) ) {
				echo 'ok';	
			} else {
				echo 'nok';	
			}
			
			
		} else {
			echo 'nok';	
		}		
	}
	
	public function airlinecheck()
	{		
		$id = JRequest::getVar('id');
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('id');
		$query->from('#__users');
		
		if( ! is_numeric($id) ) {
			$query->where( 'username='. $db->Quote( trim($id) ) );	
		} else {
			$query->where('id='. (int) $id );
		}
				
		$db->setQuery($query);
		
		$result = $db->loadResult();
				
		if( ! empty($result) ) {
			
			$user = JFactory::getUser($result);
			
			require_once JPATH_ROOT . '/components/com_sfs/libraries/access.php';
			
			if( SFSAccess::isAirline($user) ) {
				echo 'ok';	
			} else {
				echo 'nok';	
			}
			
			
		} else {
			echo 'nok';	
		}		
	}	
	
	/**
	 * Ajax method to calculate marketplace ranking	 
	 * 
	 */
	public function calculateRanking()
	{		
		require_once JPATH_COMPONENT.'/helpers/ranking.php';
		
		$date 		= JRequest::getVar('date');
		$rate 		= JRequest::getVar('rate');
		$rtype 		= JRequest::getVar('rtype');
		$transport  = JRequest::getVar('transport');
		$id 		= JRequest::getVar('id');
		
		$ranking = new SRanking( 
			array(
				'hotel_id' => $id, 'date' =>  $date, 'price' => $rate, 'roomtype' => $rtype, 'transport' => $transport				
			)
		);
		
		$result = $ranking->checkRankingBy('rate');
		
		if( $result !== null ) echo (int) $result;
		
		JFactory::getApplication()->close();				
	}
	
	/**
	 * Ajax method to calculate marketplace room total ranking	 
	 * 
	 */
	public function calculateNumberRanking()
	{		
		require_once JPATH_COMPONENT.'/helpers/ranking.php';
		
		$date 		= JRequest::getVar('date');
		$roomNumber = JRequest::getVar('rate');
		$rtype 		= JRequest::getVar('rtype');
		$transport 	= JRequest::getVar('transport');
		$id 		= JRequest::getVar('id');
	
		$ranking = new SRanking( 
			array(
				'hotel_id' => $id,'date' =>  $date, 'number_rooms' => $roomNumber, 'roomtype' => $rtype, 'transport' => $transport				
			)
		);
		
		$result = $ranking->checkRankingBy('total');
		
		if( $result !== null ) echo (int) $result;
		
		JFactory::getApplication()->close();
		
	}	
	
	public function saveTranportTypes()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$db = JFactory::getDbo();
		
		$row = new stdClass();
		
		$id    = JRequest::getInt('id', 0);
		$name  = JRequest::getVar('name');
		$seats = JRequest::getVar('seats');	
		$rate = JRequest::getVar('rate');		

		if( $id && $name && $seats)
		{
			$row->group_transportation_id = $id;
			$row->name  = $name;
			$row->seats = $seats;
			$row->rate  = $rate;
			
			if($db->insertObject('#__sfs_group_transportation_types', $row,'id'))
			{
				
			}
		}
		
		$query = 'SELECT * FROM #__sfs_group_transportation_types WHERE group_transportation_id='.(int)$id;
		$db->setQuery($query);
		$types = $db->loadObjectList();
		
		if(count($types)):				
		?>
			<table class="adminlist">
				<tr>
					<th width="50">ID</th>
					<th width="20%">Name</th>
					<th width="20%">seats</th>	
					<th>Rate</th>						
				</tr>
				<?php foreach ($types as $type) : ?>
				<tr>
					<td>
						<?php echo $type->id;?>
					</td>
					<td>
						<?php echo $type->name;?>
					</td>
					<td>
						<?php echo $type->seats;?>
					</td>	
					<td>
						<?php echo $type->rate;?>
					</td>					
				</tr>
				<?php endforeach;?>
			</table>
		<?php 	
		endif;		
		JFactory::getApplication()->close();
	}
	
	
}


