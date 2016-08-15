<?php
defined('_JEXEC') or die;
$user	= JFactory::getUser();

if ( !$user->get('guest') ) {
	
	require_once JPATH_SITE.'/components/com_sfs/libraries/core.php';
	
	SFSCore::getInstance()->render( true );
	
	if ( SFSAccess::check( $user, 'airline' ) && ! SFSAccess::check( $user, 'gh' ) ) {
		$airline = SFactory::getAirline();
		require JModuleHelper::getLayoutPath('mod_sfsuser', 'airline');	
	} else if( SFSAccess::check( $user, 'gh' ) ) {		
		$airline = SFactory::getAirline();
		require JModuleHelper::getLayoutPath('mod_sfsuser', 'gh');	
	} else if(SFSAccess::check( $user, 'hotel' )) {
		$hotel = SFactory::getHotel();
		require JModuleHelper::getLayoutPath('mod_sfsuser', 'hotel');	
	}
	
	if( SFSAccess::isBus() )
	{
		$bus = SFactory::getBus();
		require JModuleHelper::getLayoutPath('mod_sfsuser', 'bus');	
	}
		
}


