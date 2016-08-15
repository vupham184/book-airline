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

        $airport_current_id = $airline_current->id;
        $airport_current_code = $airline_current->code;

        if($airline->grouptype == 2 || ($airline->grouptype == 3 && (!($view == "airlineprofile" && $layout == "changeairline" )) && !($view == "featured")))
        {

            if(!(($view == "handler" && $layout == "search" )|| ($view == "handler" && $layout == "flightform" ) || $view == "search" || $view == "match"))
            {
                $all_option = new stdClass();
                $all_option->id = -1;
                $all_option->code = "All Airports";
                $airport_list[] = $all_option;
            }

            foreach(modSfsChangeAirportHelper::getAirlineAirportData() as $airport)
            {
                $obj = new stdClass();
                $obj->id = $airport->id;
                $obj->code = $airport->code;
                $airport_list[] = $obj;
            }
        }
        require JModuleHelper::getLayoutPath('mod_sfs_change_airport', $params->get('layout', 'default'));

    }
}


