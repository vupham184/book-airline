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
//we need to import the jomla framework since we are operating outside of it
define( 'DS', DIRECTORY_SEPARATOR );
//where we are
$current_dir = dirname(__FILE__);
//we know the path from root so we remove it to get the root
$base_folder = str_replace('\administrator\modules\mod_quickitem_pro','',str_replace('/administrator/modules/mod_quickitem_pro','',$current_dir));
//inlude this file in the joomla framework
define( '_JEXEC', 1 );
define( 'JPATH_BASE', $base_folder );
require_once( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE.DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE.DS.'libraries'.DS.'joomla'.DS.'factory.php' );
$mainframe =& JFactory::getApplication('administrator');
$mainframe->initialise();
// no direct access
defined('_JEXEC') or die('Restricted access');
//load language
$language =& JFactory::getLanguage();
$language->load('mod_quickitem_pro' , JPATH_ADMINISTRATOR, $language->getTag(), true);
//retrieve post varaibles 
$dofunc = JRequest::getVar('dofunc','catsList','request','WORD');
$selected = JRequest::getVar('selected','-1','request','STRING');
//include libraries
//var_dump();
$database = JFactory::getDBO();	
//get our list depending on dofunc
switch ($dofunc) {
	case 'catsList':
		if($selected=='content'){
			//html sections list
			$query = 'SELECT id, title FROM #__sections WHERE published = 1 AND scope = "content" AND title NOT LIKE "FLEXIcontent" ORDER BY title';
			$database->setQuery($query);
			$options = $database->loadObjectList();
//			$secList = array();
//			$secList[] = JHTML::_('select.option', '-1', JText::_('MOD_QIP_SELECT_SECTION'), 'id', 'title');
//			$secList[] = JHTML::_('select.option', '0', JText::_('MOD_QIP_UNCATEGORIZED'), 'id', 'title');
//			foreach($options as $option){
//				$secList[] = JHTML::_('select.option', $option->id, $option->title, 'id', 'title');
//			}
//			$sections = JHTML::_('select.genericlist',  $secList, 'sectionid', 'class="inputbox" onchange="javascript:getSecCatsList()"', 'id', 'title', '-1');//
			//default cats html list
			$cats[] = JHTML::_('select.option', '-1', JText::_('MOD_QIP_SELECT_SECTION'), 'id', 'title');
			$cats[] = JHTML::_('select.option', '0', JText::_('MOD_QIP_UNCATEGORIZED'), 'id', 'title');
			$cats = JHTML::_('select.genericlist',JHtml::_('category.categories','com_content')  , 'catid', 'class="inputbox"', 'value', 'text', '-1');
			echo '<div class="quickadd_categories">
						<span class="quickadd_key"><label for="catid">'.JText::_("MOD_QIP_CATEGORY").'</label></span>
						<span>'.$cats.'</span>
				</span>
				</div>';
			exit();
		//if K2 is installed we get the k2 hierarchical list of categories			
		}else if(($selected=="k2")&&(file_exists(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_k2'.DS.'admin.k2.php'))){
			$query = 'SELECT m.* FROM #__k2_categories m WHERE published = 1 ORDER BY parent, ordering';
			$database->setQuery( $query );
			$mitems = $database->loadObjectList();
			$children = array();
			if ( $mitems )
			{
				foreach ( $mitems as $v )
				{
					$pt = $v->parent;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push( $list, $v );
					$children[$pt] = $list;
				}
			}
			$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
			$mitems = array();
			$mitems[] = JHTML::_('select.option',  '-1', JText::_("MOD_QIP_SELECT_CATEGORY") );
			foreach ( $list as $item ) {
				$mitems[] = JHTML::_('select.option',  $item->id, $item->treename );
			}
			$cats = JHTML::_('select.genericlist',  $mitems, 'catid', 'class="inputbox"', 'value', 'text', '-1' );
			echo '<span class="quickadd_key"><label for="catid">'.JText::_("MOD_QIP_CATEGORY").'</label></span>
					<span>'.$cats.'</span>';
			exit();
		// if FLEXIcontent is installed we get the FLEXIcontent hierarchical category list
		}else if(($selected=="flexicontent")&&(file_exists(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_flexicontent'.DS.'admin.flexicontent.php'))){
			$fparams =& JComponentHelper::getParams('com_flexicontent');
			if (!defined('FLEXI_SELECT_CAT')) define('FLEXI_SELECT_CAT', '- Select Category -');
			if (!defined('FLEXI_SECTION')) define('FLEXI_SECTION', $fparams->get('flexi_section'));
			if (!defined('FLEXI_ACCESS')) define('FLEXI_ACCESS', (JPluginHelper::isEnabled('system', 'flexiaccess') && version_compare(PHP_VERSION, '5.0.0', '>')) ? 1 : 0);
			// Load the category class
			require_once (JPATH_SITE.DS.'components'.DS.'com_flexicontent'.DS.'classes'.DS.'flexicontent.categories.php');
			// Load the type field class
			require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_flexicontent'.DS.'models'.DS.'fields.php');
			$categories = flexicontent_cats::getCategoriesTree(0);
			$cats = flexicontent_cats::buildcatselect($categories, 'catid', '-1', '2', 'class="inputbox"');
			$flexicontent_types = new FlexicontentModelFields;
			$flexitypes = $flexicontent_types->getTypeslist();
			$types = $flexicontent_types->buildtypesselect($flexitypes, 'type_id', '-1', '2', 'class="inputbox"');
			echo '<span class="quickadd_cat">
					<span class="quickadd_key"><label for="catid">'.JText::_("MOD_QIP_CATEGORY").'</label></span>
					<span>'.$cats.'</span>
					</span>
					<span class="quickadd_type">
					<span class="quickadd_key"><label for="type_id">'.JText::_("MOD_QIP_FLEXI_TYPE").'</label></span>
					<span>'.$types.'</span>							
					</span>';
			exit();
		}else{
			//default cats html list
			$cats[] = JHTML::_('select.option', '-1', JText::_('MOD_QIP_SELECT_CONTENT_TYPE'), 'id', 'title');
			$cats = JHTML::_('select.genericlist',  $cats, 'cid', 'class="inputbox" ', 'id', 'title', '-1');
			echo '<span>
					<span class="quickadd_key"><label for="cid">'.JText::_("MOD_QIP_CATEGORY").'</label></span>
					<span>'.$cats.'</span>					
					</span>';
			exit();
		}
	break;
	case 'secCatsList':
		if($selected>0){
			//html sections list
			$query = 'SELECT id, title FROM #__categories WHERE published = 1 AND section = '.$selected.' ORDER BY title';
			$database->setQuery($query);
			$options = $database->loadObjectList();
			$cats = array();
			$cats[] = JHTML::_('select.option', '-1', JText::_('MOD_QIP_SELECT_CATEGORY'), 'id', 'title');
			foreach($options as $option){
				$cats[] = JHTML::_('select.option', $option->id, $option->title, 'id', 'title');
			}
			$cats = JHTML::_('select.genericlist',  $cats, 'catid', 'class="inputbox"', 'id', 'title', '-1');
		}else if($selected==0){
			$cats[] = JHTML::_('select.option', '0', JText::_('MOD_QIP_UNCATEGORIZED'), 'id', 'title');
			$cats = JHTML::_('select.genericlist',  $cats, 'catid', 'class="inputbox"', 'id', 'title', '0');
		}else{
			//default cats with no sections html list
			$cats[] = JHTML::_('select.option', '-1', JText::_('MOD_QIP_SELECT_SECTION'), 'id', 'title');
			$cats = JHTML::_('select.genericlist',  $cats, 'catid', 'class="inputbox" ', 'id', 'title', '-1');	
		}
		echo '<span class="quickadd_cats">
				<span class="quickadd_key"><label for="catid">'.JText::_("MOD_QIP_CATEGORY").'</label></span>
				<span>'.$cats.'</span>
			</span>';
		exit();
	break;
	case 'pubFeatList':
		$frontpage = JHTML::_('select.booleanlist', 'frontpage', '', 0);
		// build the html radio buttons for featured
		$featured = JHTML::_('select.booleanlist', 'featured', '', 0);
		// build the html radio buttons for state
		$state = JHTML::_('select.booleanlist', 'state', '', 0);
		// build the html radio buttons for published
		$published = JHTML::_('select.booleanlist', 'published', '', 0);
		// build the html radio buttons for frontpage
		$featBox = '<input type="checkbox" value="1" id="featured" name="featured">';
		if($selected=="k2"){
			echo '<span class="quickadd_published_featured">
			<span class="quickadd_published">
				<span class="quickadd_key"><label for="published">'.JText::_( 'MOD_QIP_PUBLISHED' ).'</label></span>
				<span class="quickadd_input">'.$published.'</span>
			</span>
			<span class="quickadd_featured">
				<span class="quickadd_key"><label for="featured">'.JText::_( 'MOD_QIP_FEATURED' ).'</label></span>
				<span class="quickadd_input">'.$featBox.'</span>
			</span>
			</table>';
			exit();
		}else if($selected=="flexicontent"){
			echo '<span class="quickadd_published_featured">
			<span class="quickadd_published">
				<span class="quickadd_key"><label for="published">'.JText::_( 'MOD_QIP_PUBLISHED' ).'</label></span>
				<span class="quickadd_input">'.$state.'</span>
			</span>
			<span class="quickadd_featured">
				<span class="quickadd_key"><label for="featured">&nbsp;</label></span>
				<span class="quickadd_input">&nbsp;</span>
			</span>
			</span>';
			exit();		
		}else{
			echo '<span class="quickadd_published_featured">
			<span class="quickadd_published">
				<span class="quickadd_key"><label for="published">'.JText::_( 'MOD_QIP_PUBLISHED' ).'</label></span>
				<span class="quickadd_input">'.$state.'</span>
			</span>
			<span class="quickadd_featured">
				<span class="quickadd_key"><label for="featured">'.JText::_( 'MOD_QIP_FEATURED' ).'</label></span>
				<span class="quickadd_input">'.$featured.'</span>
			</span>
			</span>';
			exit();
		}
	break;
}
