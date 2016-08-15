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
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.parameter.element');
class JFormFieldAdminpraisemenutype extends JFormFieldList {

	/**
	 * Element name
	 *
	 * @var		string
	 */
	public $_name = 'adminpraisemenutype';

    protected function getOptions() {
        require_once( JPATH_ADMINISTRATOR . '/components/com_adminpraise/helpers/menu.php' );
        $types = AdminpraiseMenuHelper::getMenuTypes();

        foreach($types as $key => $value) {
            $options[] = array(
                'text' => $value->title,
                'value' => $value->menutype);
        }
        return $options;
    }
}
