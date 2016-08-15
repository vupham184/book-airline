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

// Check to ensure this file is within the rest of the framework
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class AdminpraiseView extends JView {
	
	public function __construct($config = array()) {
		parent::__construct($config);
		
		$this->addDefaultCss();
		$this->addDefaultJs();
	}
	
	public function addDefaultCss() {
		$document = JFactory::getDocument();
		
		$path = JURI::root() . 'media/com_adminpraise/css/general.css';
		$document->addStyleSheet($path);
	}
	
	public function addDefaultJs() {
		$document = JFactory::getDocument();
		
		$script  = "window.addEvent('domready', function(){ ";
		$script .= ' adminpraise = { ';
		$script .= ' root : "' . JURI::root() . '",';
		$script .= ' confirmDelete : "' .JText::_('COM_ADMINPRAISE_CONFIRM_DELETE') .'", ';	
		$script .= ' resetToDefaultWarning : "' . JText::_('COM_ADMINPRAISE_RESET_TO_DEFAULT_WARNING') . '", ';
		$script .= ' }';
		$script .= '});';
		
		$document->addScriptDeclaration($script);
	}

}