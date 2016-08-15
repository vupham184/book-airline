<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewRooming extends JView
{
    protected $params;
    protected $state;
    protected $hotel;

    public function display($tpl = null)
    {
    	$app  = JFactory::getApplication();

    	$this->user = JFactory::getUser();

		if( ! SFSAccess::isHotel( $this->user ) )
    	{
    		$app->redirect( JRoute::_('index.php?option=com_sfs&view=home',false) );
    		return false;
    	}

    	$this->hotel 	   = SFactory::getHotel();

    	$this->state       = $this->get('State');
        $this->params      = $this->state->get('params');

    	if( !$this->hotel->isRegisterComplete() || $this->hotel->isBlock() ) {
    		$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid=103',false) );
    		return false;
    	}

		$this->block_code  = $this->get('Reservation');
		$this->airline 	   = $this->get('Airline');
		$this->bookedAirlineContact = $this->airline->booked_user;
        $this->items       = $this->get('TracePassengers');
        $this->items_saved = $this->get('Passengers');
        $this->VoucherByBlockcode = $this->get('VoucherByBlockcode');
        
		$this->guest_count = $this->get('GuestTotal');
		$this->guaranteeVoucher = $this->get('GuaranteeVoucher');
		$this->guaranteeIssuedVouchers = array();

		if( $this->guaranteeVoucher->issued > 0)
		{
			for( $i=0; $i < (int)$this->guaranteeVoucher->issued ; $i++ ) {
				$this->guaranteeIssuedVouchers[$i] = $this->guaranteeVoucher->code;
			}
		}

        if ( $this->getLayout() == 'challenge' ) {
        	$this->messages = $this->get('Messages');
        }

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        parent::display($tpl);
    }

}
