<?php
/**
 * @package		AdminPraise3
 * @author		AdminPraise http://www.adminpraise.com
 * @copyright	Copyright (c) 2008 - 2011 Pixel Praise LLC. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
 
 /**
 *    This file is part of AdminPraise.
 *    
 *    AdminPraise is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with AdminPraise.  If not, see <http://www.gnu.org/licenses/>.
 *
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemAdminpraise_Autoeditor extends JPlugin
{
	public function __construct(&$subject, $config)  {
		parent::__construct($subject, $config);
	}
	
	public function onAfterInitialise() {

		if($this->isTemplateEnabled()) {
			$mobileDevice = $this->isMobileDevice();
			if($mobileDevice[0]) {
				$config = JFactory::getConfig();
				$config->setValue('config.editor', 'sce');
			}
		}
		
	}
	
	/**
	 * @return array (boolean, string) - Array with boolean mobile browser, and string type of mobile browser
	 */
	private function isMobileDevice() {
		$iphone = true;
		$ipad = true;
		$android = true;
		$opera = true;
		$blackberry = true;
		$palm = true;
		$windows = true;


		$mobileBrowser = false;
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$accept = $_SERVER['HTTP_ACCEPT'];

		switch (true) {
			case (preg_match('/ipad/i', $user_agent)); // ipad
				$mobileBrowser = $ipad;
				$type = 'iPad';
				break;

			case (preg_match('/ipod/i', $user_agent) || preg_match('/iphone/i', $user_agent)); // iphone or ipod 
				$mobileBrowser = $iphone;
				$type = 'iPhoneIpod';
				break;

			case (preg_match('/android/i', $user_agent));  // android 
				$mobileBrowser = $android;
				$type = 'Android';
				break; 

			case (preg_match('/opera mini/i', $user_agent)); //opera mini 
				$mobileBrowser = $opera;
				$type = 'OperaMini';
				break; 

			case (preg_match('/blackberry/i', $user_agent)); // blackberry 
				$mobileBrowser = $blackberry;
				$type = 'Blackberry';
				break; 

			case (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i', $user_agent)); // palm os 
				$mobileBrowser = $palm;
				$type = 'Palm';
				break;

			case (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i', $user_agent)); // windows mobile 
				$mobileBrowser = $windows;
				$type = 'WindowsSmartphone';
				break; 

			case (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i', $user_agent)); 
				$mobileBrowser = true;
				$type = 'Other';
				break; 

			case ((strpos($accept, 'text/vnd.wap.wml') > 0) || (strpos($accept, 'application/vnd.wap.xhtml+xml') > 0)); // is the device showing signs of support for text/vnd.wap.wml or application/vnd.wap.xhtml+xml
				$mobileBrowser = true;
				$type = 'Other';
				break;

			case (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])); // is the device giving us a HTTP_X_WAP_PROFILE or HTTP_PROFILE header - only mobile devices would do this
				$mobileBrowser = true;
				$type = 'Other';
				break; 

			case (in_array(strtolower(substr($user_agent, 0, 4)),  array('w3c ','acs-','alav',
				'alca','amoi','andr','audi','avan','benq','bird','blac','blaz','brew','cell',
				'cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi',
				'keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef',
				'mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm','pana','pant',
				'phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send',
				'seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo',
				'teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi',
				'wapp','wapr','webc','winw','winw','xda','xda-')
					));
				$mobileBrowser = true;
				$type = 'Other';
				break; 

			default;
				$mobileBrowser = false;
				$type = '';
				break;
		}

		return array($mobileBrowser, $type);
	}
	/**
	 * Checks if the adminpraise3 template is enabled
	 * @return boolean 
	 */
	private function isTemplateEnabled() {
		$enabled = false;
		
		$db = JFactory::getDBO();
		$query	= $db->getQuery(true);
		$query->select(
				'a.template'
		);
		$query->from('`#__template_styles` AS a');

		// Filter by extension enabled
		$query->join('LEFT', '`#__extensions` AS e ON e.element = a.template');
		$query->where('e.enabled = 1');
		$query->where('a.template = ' . $db->Quote('adminpraise3'));
		
		$db->setQuery($query);

		if ($db->loadObject()) {
			$enabled = true;
		}
		
		return $enabled;
	}
	
}
?>