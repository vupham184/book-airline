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
defined('_JEXEC') or die();
jimport('joomla.application.component.view');
jimport('joomla.filesystem.file');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'library' . DS . 'joomla' . DS . 'html' . DS . 'parameter.php');

class AdminpraiseViewSettings extends AdminpraiseView {
	public function display() {

		$this->form		= $this->get('Form');

		$this->assignRef('templateFile', $ini);
		$this->assignRef('form', $this->form);
		parent::display();
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JToolBarHelper::apply('settings.apply');
		JToolBarHelper::save('settings.save');
		JToolBarHelper::custom('settings.back', 'back.png', 'back_f2.png', 'COM_ADMINPRAISE_BACK', false, false);
	}
}
