<?php
defined('_JEXEC') or die();

error_reporting(0);
ini_set('display_errors', 0);

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT.'/libraries/core.php';
require_once JPATH_COMPONENT.'/libraries/SfsUtil.php';
require_once JPATH_COMPONENT.'/ws/SfsWs.php';
require_once JPATH_COMPONENT.'/libraries/codecanyon.php';

// Render the sfs application.
SFSCore::getInstance()->render( true );

#test
SfsWs::init();
#END test

// Load template per airline
$user = JFactory::getUser();
if ($user->id)
{
    $app = JFactory::getApplication();
    $db = JFactory::getDbo();
    $db->setQuery('SELECT airline_id FROM #__sfs_airline_user_map WHERE user_id='.(int)$user->id);
    $airline_id = $db->loadResult();

    if ($airline_id)
    {
        $db->setQuery('SELECT params FROM #__sfs_airline_details WHERE id='.(int)$airline_id);
        $params = $db->loadResult();
        $params = json_decode($params);
        if($params->template_id)
        {
            $db->setQuery('SELECT template FROM #__template_styles WHERE id='.(int)$params->template_id);
            $template_airline = $db->loadResult();
            if ($template_airline)
            {
                $app->setTemplate($template_airline);
            }
        }
    }
}

JFactory::getDocument()->addScriptDeclaration("

function squeezeBoxFixTop () {
    var counter = 1;
    var looper = setInterval(function()
    {
        var top = parseInt(jQuery('#sbox-window',window.top.document).css('top'));
        counter++;
        if(top < 0)
        {
            jQuery('#sbox-window').css('top', 0);
        }

        if (counter >= 10)
        {
            clearInterval(looper);
        }
    }, 1000);
}
function iframeModalAutoSize() {
		var frame = jQuery('#sbox-window iframe', window.top.document);
		if(!frame.length) {
			return;
		}
        var height = jQuery('body').outerHeight();

        frame.height(height);

        window.top.SqueezeBox && window.top.SqueezeBox.resize({y: height - 20});

//        setTimeout(function(){
//	        var framebox = jQuery('#sbox-window', window.top.document);
//			if(framebox.length) {
//				var top = parseInt(framebox.css('top'));
//
//				if(top < 0) {
//					framebox.css('top', '0px');
//				}
//			}
//		}, 1000);
}


window.addEvent('domready', function(){
	if(window.SqueezeBox) {
		window.SqueezeBox.presets.onOpen = function(){
			squeezeBoxFixTop();
		};
		window.SqueezeBox.presets.onShow = function(){
			squeezeBoxFixTop();
		};
		window.SqueezeBox.presets.onResize = function(){
			squeezeBoxFixTop();
		};
	}
});");

$controller = JController::getInstance('Sfs');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
