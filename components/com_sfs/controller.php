<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class SfsController extends JController {
	
	
	function __construct($config = array())
	{	
		parent::__construct($config);
	}	
	
    public function display($cachable = false, $urlparams = false) {
    	
        $cachable = false;
        
        $user = JFactory::getUser();

        $vName = JRequest::getCmd('view', 'home');
               
        JRequest::setVar('view', $vName);
                               
        $safeurlparams = array('catid'=>'INT', 'id'=>'INT', 'cid'=>'ARRAY', 'year'=>'INT', 'month'=>'INT', 'limit'=>'UINT', 'limitstart'=>'UINT',
			'showall'=>'INT', 'return'=>'BASE64', 'filter'=>'STRING', 'filter_order'=>'CMD', 'filter_order_Dir'=>'CMD', 'filter-search'=>'STRING', 'print'=>'BOOLEAN', 'lang'=>'CMD');
                
        parent::display($cachable, $safeurlparams);
      
        return $this;
    }    

    public function selecthotel() {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	$post = JRequest::get('post');
    	$session = JFactory::getSession();
    	$session->set('hotel_id',$post['hotel_id']);
    	$this->setRedirect( JRoute::_('index.php?option=com_sfs&view=dashboard',false) );
    }
	public function selectairline() {
    	JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
    	$post = JRequest::get('post');
    	$session = JFactory::getSession();
    	$session->set('airline_id',$post['airline_id']);
    	$this->setRedirect( JRoute::_('index.php?option=com_sfs&view=dashboard',false) );
    }
    
    
    public function faxTest()
    {
    	ob_start(); 
		require_once JPATH_COMPONENT.'/libraries/emails/hotelblockconfirm.php';			
		$bodyE = ob_get_clean();
		$faxAtt 	= JPATH_SITE.DS.'media'.DS.'sfs'.DS.'faxtest.html';			
		$bodyE 		= JString::str_ireplace('{date}', JHtml::_('date',JFactory::getDate(),"d-M-Y"), $bodyE);					
		JFile::write($faxAtt, $bodyE);
		
		$send = JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions','3227065665@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation', $bodyE, true,null,null,$faxAtt);
		$send = JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions','duylinh013@hotmail.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation', $bodyE, true,null,null,$faxAtt);
		
		if($send)
		{
			echo 'Sent';
		}
		
    }
	
	public function changeAirport()
	{
		$airport_id = JRequest::getVar('airport_id');
		$redirect_link = JRequest::getVar('redirect_link');

		//Change airport session
        $session = JFactory::getSession();
		//lchung 
		$setup_airport = (array)$session->get("setup_airport");
		if ( empty( $setup_airport ) )
        	$session->set("airport_current_id", $airport_id);

        //Redirect to old link
		$this->setRedirect( JRoute::_($redirect_link,false) );
	}
    
    public function changeCurrency()
    {
    	$currency_id = JRequest::getVar('currency_id');
    	$currency_code = JRequest::getVar('currency_code');
		$redirect_link = JRequest::getVar('redirect_link');
		// die($currency_code);
		$session = JFactory::getSession();

		$setup_airport = (array)$session->get("setup_airport");
		
		if ( empty( $setup_airport ) ){
        	$session->set("currency_numeric_code", $currency_id);
        	$session->set("currency_code", $currency_code);

		}

        //Redirect to old link
		$this->setRedirect( JRoute::_($redirect_link,false) );
    }
}

