<?php
defined('_JEXEC') or die;
require_once dirname(__FILE__).'/helper.php';
$user	= JFactory::getUser();
if ( !$user->get('guest')) {
    require_once JPATH_SITE.'/components/com_sfs/libraries/core.php';
    // Include the syndicate functions only once
    SFSCore::getInstance()->render( true );
    if ( SFSAccess::check( $user, 'airline' ) || SFSAccess::check( $user, 'gh' ))
    {

        $input = JFactory::getApplication()->input;
        $view = $input->get('view');
        $layout = $input->get('layout');
        $airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();

        
        $airline_currency_code = $airline->currency_code;
        $currency_list = array();

        $objCurr = modSfsChangeCurrencyHelper::getAirlineCurrencyData();
        if (count($objCurr)) {
            foreach($objCurr as $curr)
            {
                $obj        = new stdClass();
                $obj->id    = $curr->id;
                $obj->code  = $curr->code;
                $obj->name  = $curr->name;
                $obj->flag  = $curr->flag;
                $currency_list[] = $obj;
            }
            
            require JModuleHelper::getLayoutPath('mod_sfs_change_currency', $params->get('layout', 'default'));
        }

    }
}
?>

