<?php
defined('_JEXEC') or die();

class SfsViewSearch extends JViewLegacy
{	
	protected $state;	
	protected $user;
	protected $result;
	protected $hotelsNoRoomLoading;

	function display($tpl = null)
	{	
		$app = JFactory::getApplication();
		
        $this->user = JFactory::getUser();
                
		if( ! SFSAccess::check( $this->user, 'a.admin') ) {
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;	
		}

		// Assign data to the view
		$this->state	  = $this->get('State');
		$this->result 	  = $this->get('Data');
        $this->hotelsNoRoomLoading 	  = $this->get('HotelsNoRoomLoading');
		$this->tooltip 	  = SFactory::getTooltips('airline');		
		$distance = $this->get("DistanceOfUser");
		
                if(!empty($this->result)){
                    foreach($this->result as &$result){
                        if(!empty($result->distance)){
                            if( $distance == 1 ){
                                $result->distance = round((floatval($result->distance) / 1.6093440), 2);
                                $result->distance_unit = 'mi';
                            }
							else {
								$result->distance_unit = 'km';
							}
                        }
                    }
                }
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$session = JFactory::getSession();
		$bookingError = $session->get('booking.error');
		if($bookingError)
		{
			$session->clear('booking.error');
			JError::raiseWarning(403, $bookingError);
		}
		
		$style='class="inputbox"';
		
		$startDateList 	= SfsHelperDate::getSearchDate('start',$style);		
		$endDateList 	= SfsHelperDate::getSearchDate('end',$style);
						
		
		$this->assignRef('start_date_list', $startDateList);
		$this->assignRef('end_date_list', $endDateList);
		
		// Display the view
		$this->wsCached = $this->state->get('filter.ws_id_list');
		$wsOnly = $this->state->get('filter.ws_only');
		$this->assignRef('wshoteladd', $this->state->get('wshoteladd'));		
		$wsMap = JRequest::getInt('ws_map', 0);
		if($wsOnly || ($wsOnly==0 && JRequest::getInt('select_sort'))) {			
			$tpl = 'results_body';
			if($wsMap)
	        {
	            echo json_encode($this->result);exit(0);
	        }
		}
		$show_all=false;
		if($wsOnly){
			$show_all=true;			
		}	
		$this->assignRef('show_all', $show_all);	
		parent::display($tpl);
		if($wsOnly) {
			exit(0);
		}
		if($wsOnly==0 && JRequest::getInt('select_sort')){
			exit(0);
		}
	}
	
	
}
