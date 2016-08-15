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
defined('_JEXEC') or die('Restricted access');
class modQuickAddProHelper {
	function getContentLists() {
		$mainframe = &JFactory::getApplication();
		$database = JFactory::getDBO();	
		// build the html list for content type
		$itemtype[] = JHTML::_('select.option', '-1', JText::_('MOD_QIP_SELECT_CONTENT_TYPE'), 'id', 'title');
		$itemtype[] = JHTML::_('select.option', 'content', JText::_('MOD_QIP_JOOMLA_ARTICLE'), 'id', 'title');
		//if K2 is installed add it to the list
		if(file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_k2'.DS.'admin.k2.php')){
			$itemtype[] = JHTML::_('select.option', 'k2', JText::_('MOD_QIP_K2_ITEM'), 'id', 'title');
		}
		//if FLEXIcontent is installed add it to the list
		if(file_exists(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_flexicontent'.DS.'admin.flexicontent.php')){
			$itemtype[] = JHTML::_('select.option', 'flexicontent', JText::_('MOD_QIP_FLEXICONTENT_ITEM'), 'id', 'title');
		}
		$lists['itemtype'] = JHTML::_('select.genericlist',  $itemtype, 'itemtype', 'class="inputbox"', 'id', 'title', 'default');
		//default cats html list
		$cats[] = JHTML::_('select.option', '-1', JText::_('MOD_QIP_SELECT_CONTENT_TYPE'), 'id', 'title');
		$lists['default'] = JHTML::_('select.genericlist',  $cats, 'catid', 'class="inputbox"', 'id', 'title', '-1');
		// build the html editor				
		$editor =& JFactory::getEditor();
		$params = array( 'smilies'=> '0' ,
                 'style'  => '1' ,  
                 'layer'  => '0' , 
                 'table'  => '0' ,
                 'mode'  => 'simple' ,
                 'clear_entities'=>'0'
                 );
		$lists['editor'] = $editor->display( 'text', '', '99%', '150', '50', '30', true, null,null,null, $params );
		// build the html radio buttons for featured
		$lists['featured'] = JHTML::_('select.booleanlist', 'featured', '', 0);
		// build the html radio buttons for state
		$lists['state'] = JHTML::_('select.booleanlist', 'state', '', 0);
		// build the html radio buttons for published
		$lists['published'] = JHTML::_('select.booleanlist', 'published', '', 0);
		
$js = "		
//Ajax function to change dropdown lists
quickItemProToken = '" . JUtility::getToken() ."'
var changeDynaList = function(cssid, dofunc, selected){
	var changeDiv = document.getElementById(cssid);
	var gotoUrl = '". JURI::base().'modules/mod_quickitem_pro/include.php'."';
	var addtoUrl = 'selected='+selected+'&dofunc='+dofunc;
	var myRequest = new Request({
		method: 'post', 
		url: gotoUrl,
		onRequest: function() { changeDiv.innerHTML='<div class=\"pleasewait\"> Loading...</div>'; },
		onSuccess: function(response) { changeDiv.innerHTML=response; },
		onFailure: function() { changeDiv.innerHTML='<div class=\"error\">Error!</div>'; }		
	});
	myRequest.send(addtoUrl);
}
//Ajax  function to submit form data
var submitForm = function(formdata){
	var changeDiv = $('quickadd_message');
	var gotoUrl = '". JURI::base().'index.php'."';
	var addtoUrl = formdata;
	var myRequest = new Request({
		method: 'post',
		data: formdata,
		url: gotoUrl,
		onRequest: function() { changeDiv.innerHTML='<div class=\"pleasewait\"> Loading...</div>'; },
		onSuccess: function(response) { 
			changeDiv.innerHTML='<div class=\"info\">Sucessfully Saved!</div>'; 
			clearForm();
		},	
		onFailure: function() { changeDiv.innerHTML='<div class=\"error\">Failed to Save!</div>'; }
	});
	myRequest.send();
}";
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration($js);
		return $lists;
	}
}
