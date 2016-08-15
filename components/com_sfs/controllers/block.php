<?php
defined('_JEXEC') or die;

class SfsControllerBlock extends JControllerLegacy
{
	public function archive()
	{
		$id = JRequest::getInt('id');
		$msg = '';
		if($id) {			
			if ( SFSCore::updateBlockStatus( $id, 'R' ) )
			{
				$msg = 'Block archived';
			}
		}
		$link = JRoute::_('index.php?option=com_sfs&view=block&blockstatus=A&Itemid='.JRequest::getInt('Itemid'), false);
		$this->setRedirect( $link, $msg );		
	}	
	function filter()
	{
		$post['blockcode']  	= JRequest::getVar('blockcode');
		$post['blockstatus']  	= JRequest::getVar('blockstatus');
		$post['date_from']	= JRequest::getVar('date_from');
		$post['date_to']	= JRequest::getVar('date_to');		
		
				
		// set Itemid id for links from menu
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_sfs&view=block');

		if(isset($items[0])) {
			$post['Itemid'] = $items[0]->id;
		} else if (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
			$post['Itemid'] = JRequest::getInt('Itemid');
		}
		unset($post['task']);
		unset($post['submit']);

		$uri = JURI::getInstance();
		$uri->setQuery($post);
		
		$uri->setVar('option', 'com_sfs');
		$uri->setVar('view', 'block');
		
		
		$this->setRedirect(JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false),$msg);
	}	
}
