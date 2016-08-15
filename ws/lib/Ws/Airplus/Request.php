<?php

class Ws_Airplus_Request {
	
	const TRANSTYPE_OTHER 	= 'Other';
	const TRANSTYPE_MICE 	= 'MICE';
	const TRANSTYPE_FLIGHT 	= 'Flight';
	const TRANSTYPE_RAIL 	= 'Rail';
	const TRANSTYPE_CAR 	= 'Car';
	const TRANSTYPE_HOTEL 	= 'Hotel';
	
	public $AE = null;
	public $AK = null;
	public $AU = null;
	public $BD = null;
	public $DS = null;
	public $IK = null;
	public $KS = null;
	public $PK = null;
	public $PR = null;
	public $RZ = null;
	
	public $ChargeFee = 0;
	
	public $BookForUserId = '';
	public $BookForUserName = '';
	public $CPNType = 'SP';
	
	# transtype
	# MICE | Hotel | Flight | Rail | Car | Other
	public $TransType = self::TRANSTYPE_OTHER; 
	public $GN = ''; # Hotel only : Guest name
	public $RN = 1; # Hotel only : room night
	public $CID = ''; # hotel start date
	public $COD = ''; # hotel end date
	
	# meal
	public $NM = '';
	public $FT = '';
	
	public $ValidFrom 		= null;
	public $ValidTo 		= null;
	public $CumulativeLimit	= 500;
	public $MaxTrans		= 5;
	
	# 840 = USD
	public $CurrencyCodes = array(840);
	
}