<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';

class SfsControllerRooming extends SfsController
{
	public function save()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		$user = JFactory::getUser();

		// Make sure that just the hotel group can process this task
		if( ! SFSAccess::isHotel($user) ) {
			return false;
		}

		$app		= JFactory::getApplication();

		$model = $this->getModel('Rooming');

		$blockcode  = 	$model->getState('rooming.code');
		$airport	= 	$model->getState('rooming.airport');

		$link  =  'index.php?option=com_sfs&view=rooming&code='.$blockcode;
		$link = JRoute::_($link, false);

		if($airport)
		{
			$link .= '&airport='.$airport;
		}
		$link .= '&Itemid='.JRequest::getInt('Itemid').'&is_save=1';

		if( $model->check() )
		{
			// print_r(JRequest::getVar( 'vouchers' , array() , 'post' , 'array' ));
			// die();
			$result = $model->save();

			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			if(count($errors)) {
				for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
					if (JError::isError($errors[$i])) {
						$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
					} else {
						$app->enqueueMessage($errors[$i], 'warning');
					}
				}
			}
			$app->setUserState('com_sfs.rooming.data', null);
			$this->setRedirect($link);
		} else {
			$app->setUserState('com_sfs.rooming.data', null);

			$vouchers = JRequest::getVar( 'vouchers' , array() , 'post' , 'array' );
			$app->setUserState('com_sfs.rooming.data', $vouchers);

			$msg = $model->getError();
			$this->setRedirect( $link, (string)$msg );
		}

		return $this;
	}

	public function csvupload()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		$user = JFactory::getUser();

		// Make sure that just the hotel group can process this task
		if( ! SFSAccess::isHotel($user) ) {
			return false;
		}
		$app		= JFactory::getApplication();

		$model = $this->getModel('Rooming','SfsModel');


		if( $model->check() )
		{
			$model->saveFromCsv();

			// Get the validation messages.
			$errors	= $model->getErrors();
			// Push up to three validation messages out to the user.
			if(count($errors)) {
				for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
					if (JError::isError($errors[$i])) {
						$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
					} else {
						$app->enqueueMessage($errors[$i], 'warning');
					}
				}
			}

			$app->setUserState('com_sfs.rooming.data', null);
			$link = JRoute::_('index.php?option=com_sfs&view=rooming&code='.$model->getState('rooming.code'), false);
			$this->setRedirect($link);

		} else {
			$app->setUserState('com_sfs.rooming.data', null);
			// get an error. we need to throw an exception as an notification to the hotel contact
			$link = JRoute::_('index.php?option=com_sfs&view=rooming', false);
			$msg = $model->getError();
			$this->setRedirect( $link, (string) $msg );
			return;
		}
	}

	public function confirm()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		$user = JFactory::getUser();

		// Make sure that just the hotel group can process this task
		if( ! SFSAccess::isHotel($user) ) {
			return false;
		}
		$app		= JFactory::getApplication();

		$model = $this->getModel('Rooming','SfsModel');

		$blockcode  = 	$model->getState('rooming.code');
		$airport	= 	$model->getState('rooming.airport');

		$link  =  'index.php?option=com_sfs&view=rooming&code='.$blockcode;
		$link = JRoute::_($link, false);

		if($airport)
		{
			$link .= '&airport='.$airport;
		}
		$link .= '&Itemid='.JRequest::getInt('Itemid');

		if( $model->check() )
		{
			if($model->save()) {

				$result = $model->confirm();

				if(!$result) {
					$app->setUserState('com_sfs.rooming.data', null);
					$vouchers = JRequest::getVar( 'vouchers' , array() , 'post' , 'array' );
					$app->setUserState('com_sfs.rooming.data', $vouchers);
					$this->setRedirect($link,'Empty data. You can not send this block.');
				} else {
					$app->setUserState('com_sfs.rooming.data', null);
					$link = JRoute::_('index.php?option=com_sfs&view=block&Itemid='.JRequest::getInt('Itemid'), false);
					$this->setRedirect($link,'You have been successfully confirmed block '.$blockcode);
				}
			} else {
				// Get the validation messages.
				$errors	= $model->getErrors();
				// Push up to three validation messages out to the user.
				if(count($errors)) {
					for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
						if (JError::isError($errors[$i])) {
							$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
						} else {
							$app->enqueueMessage($errors[$i], 'warning');
						}
					}
				}
				$app->setUserState('com_sfs.rooming.data', null);
				$this->setRedirect($link);
			}
		} else{
			$app->setUserState('com_sfs.rooming.data', null);
			$vouchers = JRequest::getVar( 'vouchers' , array() , 'post' , 'array' );
			$app->setUserState('com_sfs.rooming.data', $vouchers);

			$msg = $model->getError();
			$this->setRedirect( $link, (string)$msg );
			return;
		}

		return $this;
	}

	public function airlineAccept()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		$airline = SFactory::getAirline();
		$user 	 = JFactory::getUser();

		// Make sure that just the airline group can process this task
		if( SFSAccess::check($user, 'a.admin') )
		{
			$reservationId = JRequest::getInt('id');
			$model = $this->getModel('Rooming','SfsModel',array('ignore_request' => true));

			if( $reservationId > 0 ) {

				$link = JRoute::_('index.php?option=com_sfs&view=airblock&layout=detail&id='.$reservationId.'&Itemid='.JRequest::getInt('Itemid'), false);

				$result = $model->approveBlock();

				if($result)
				{
					$this->setRedirect( $link, 'This is send as approved. The hotel will prepare the official invoice.');
				} else {
					$this->setRedirect( $link, (string)$model->getError() );
					return false;
				}

				return $this;

				/*
				if( SFSCore::updateBlockStatus( $id, 'A' ) ) {

					$reservation = SReservation::getInstance($id);
					//email to hotel
					SEmail::airlineChangeStatusTo('A', $reservation);

					$this->setRedirect( $link, 'This is send as approved. The hotel will prepare the official invoice.');
					return true;
				} else {
					$this->setRedirect( $link );
					return false;
				}
				*/
			}
		}
		jexit();
	}

	public function requestvoucher()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		$db		 = JFactory::getDbo();
		$user	 = JFactory::getUser();
		// Make sure that just the hotel group can process this task
		if( ! SFSAccess::isHotel($user) ) {
			return false;
		}

		$app		= JFactory::getApplication();

		$model = $this->getModel('Rooming','SfsModel');
		$msg='';
		if( $model->check() )
		{
			$result = $model->requestVoucher();
			if( !$result) {
				$msg = $model->getError();
			} else {
				$msg = 'The minimum guarantee voucher is requested ';
			}
		}
		$link = JRoute::_('index.php?option=com_sfs&view=rooming&code='.$model->getState('rooming.code'), false);
		$this->setRedirect( $link, (string)$msg );
		return false;
	}

	public function getPassengersByVoucher(){
		$json = array();
		$model = $this->getModel('Rooming','SfsModel');
		$voucher_code = JRequest::getVar('voucher_code');
		$block_code = JRequest::getVar('block_code');
		$passengers = $model->getPassengersByVouchers($voucher_code,$block_code);
		if(empty($passengers)) {
			$json = array(
				'error' => 1,
				'message' => 'Found nothing.'
			);
		} else {
			foreach($passengers as $passenger)
			{
				array_push($json,array(
                    'voucher_code' => $passenger->code,
					'first_name' => $passenger->first_name,
					'last_name' => $passenger->last_name,
				));
			}
		}

		echo json_encode($json);
		exit(0);
	}
}

