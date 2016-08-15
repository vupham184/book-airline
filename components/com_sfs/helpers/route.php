<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

abstract class SfsHelperRoute
{
	protected static $lookup;
	
	public static function &getItems()
	{
		static $items;

		// Get the menu items for this component.
		if (!isset($items)) {
			// Include the site app in case we are loading this from the admin.
			require_once JPATH_SITE.'/includes/application.php';

			$app	= JFactory::getApplication();
			$menu	= $app->getMenu();
			$com	= JComponentHelper::getComponent('com_sfs');
			$items	= $menu->getItems('component_id', $com->id);

			// If no items found, set to empty array.
			if (!$items) {
				$items = array();
			}
		}

		return $items;
	}	
	
	public static function getSFSRoute($view=null,$layout=null) 
	{
		$link = 'index.php?option=com_sfs';
		if($view) {
			$link .= '&view='.$view;
			if($layout)
			{
				$link .= '&layout='.$layout;
			}	

			$items	= self::getItems();
			$itemid	= null;
	
			foreach ($items as $item) {
				if($view && $layout) {
					if (isset($item->query['view']) && $item->query['view'] === $view && isset($item->query['layout']) && $item->query['layout'] === $layout) {
						$itemid = $item->id;
						
						break;
					}					
				} else if($view) {
					if (isset($item->query['view']) && $item->query['view'] === $view) {
						$itemid = $item->id;
						break;
					}					
				}				
			}				
			if ( $itemid ) {
				$link .= '&Itemid='.$itemid;
			}	
			return $link;		
		}		
		return 'index.php?option=com_sfs&view=home';
	}

	/**
	 * @param	int	The route of the content item
	 */
	public static function getArticleRoute($id, $catid = 0)
	{
		$needles = array(
			'article'  => array((int) $id)
		);
		//Create the link
		$link = 'index.php?option=com_content&view=article&id='. $id;
		if ((int)$catid > 1)
		{
			$categories = JCategories::getInstance('Content');
			$category = $categories->get((int)$catid);
			if($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		elseif ($item = self::_findItem()) {
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	
	public static function getFormRoute($id)
	{
		//Create the link
		if ($id) {
			$link = 'index.php?option=com_content&task=article.edit&a_id='. $id;
		} else {
			$link = 'index.php?option=com_content&task=article.edit&a_id=0';
		}

		return $link;
	}

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_sfs');
			$items		= $menus->getItems('component_id', $component->id);
			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					if (!isset(self::$lookup[$view])) {
						self::$lookup[$view] = array();
					}
					if (isset($item->query['id'])) {
						self::$lookup[$view][$item->query['id']] = $item->id;
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach($ids as $id)
					{
						if (isset(self::$lookup[$view][(int)$id])) {
							return self::$lookup[$view][(int)$id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active && $active->component == 'com_sfs') {
				return $active->id;
			}
		}

		return null;
	}
}
