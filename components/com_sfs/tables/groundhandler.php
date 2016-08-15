<?php
defined('_JEXEC') or die();
jimport('joomla.database.table');

class JTableGroundhandler extends JTable
{	
	var $id=null;
	var $user_id=null;
	var $published=null;
	var $created= null;
	var $company_name=null;
	var $airport_id=null;
	var $time_zone=null;
	var $office_address1=null;
	var $office_address2=null;
	var $office_city=null;
	var $office_state=null;
	var $office_zipcode=null;
	var $office_country=null;
	var $office_phone=null;
	var $billing_name=null;
	var $billing_address1=null;
	var $billing_address2=null;
	var $billing_city=null;
	var $billing_state=null;
	var $billing_zipcode=null;
	var $billing_country=null;
	var $billing_tvanumber=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__sfs_groundhandlers','id', $db );
	}
	
}
?>