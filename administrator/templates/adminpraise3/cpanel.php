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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$template_path = dirname(__FILE__);
require_once($template_path.'/lib/stainless.php');
$stainless = AdminPraise3Tools::getInstance();

// redirect to fallback template for fallback components
$fallbackComponents = $stainless->get('fallbackComponents');
$fallbackTemplate = $stainless->get('fallbackTemplate');
if((in_array($stainless->get('option'), $fallbackComponents)) && $fallbackTemplate){ 
$this->template = $fallbackTemplate;
$params_ini = file_get_contents(JPATH_ROOT.DS.'administrator'.DS.'templates'.DS.$fallbackTemplate.DS.'params.ini');
$active_params = new JParameter($params_ini);

foreach($active_params->_registry['_default']['data'] as $name=>$value) :
$this->params->set($name, $value);
endforeach;

if($fallbackTemplate == "stainless"){
	print '<style type="text/css">div.icon a{height:90px !important;}</style>';
	$this->params->set('switchSidebar',$active_params->get('switchSidebar'));
	$this->params->set('showSidebar',$active_params->get('showSidebar'));
}
require_once('templates'.DS.$fallbackTemplate.DS.'index.php');
return;
}

require_once(dirname(__FILE__).DS.'index.php');
?>