<?php
/**
 * @Enterprise: yagendoo media GmbH
 * @author: yagendoo Team
 * @url: http://www.yagendoo.com
 * @license: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright: yagendoo media GmbH
 *
 * Simple Wrapper Plugin for the Mobile-Detect Library:
 * https://github.com/serbanghita/Mobile-Detect
 * License: MIT License 
 */
/*defined('_JEXEC') or die;

class plgSystemYagendooismobile extends JPlugin
{
	public function onAfterInitialise()
	{
		require_once 'libs/Mobile_Detect/Mobile_Detect.php';
		return true;
	}
}*/
require_once 'Mobile_Detect.php';
class MobileDetector
{
	public static function isMobile()
	{
		$detect = new Mobile_Detect;
		return $detect->isMobile();
	}

	public static function getUserAgent()
	{
		$detect = new Mobile_Detect;
		return $detect->getUserAgent();
    }

    public static function getPhoneDevices()
	{
		$detect = new Mobile_Detect;
		return $detect->getPhoneDevices();
    }

    public static function getTabletDevices()
	{
		$detect = new Mobile_Detect;
		return $detect->getTabletDevices();
    }

    public static function isTablet($userAgent = null, $httpHeaders = null)
	{
		$detect = new Mobile_Detect;
		return $detect->isTablet($userAgent, $httpHeaders);
	}

    public static function is($key, $userAgent = null, $httpHeaders = null)
	{
		$detect = new Mobile_Detect;
		return $detect->is($key, $userAgent, $httpHeaders);
	}

    public static function getOperatingSystems()
	{
		$detect = new Mobile_Detect;
		return $detect->getOperatingSystems();
	}
}