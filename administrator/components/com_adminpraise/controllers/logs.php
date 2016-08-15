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
jimport('joomla.application.component.controller');

class AdminpraiseControllerLogs extends AdminpraiseController {
	public function display() {
		$document = JFactory::getDocument();
		$viewType = $document->getType();

		$viewName = JRequest::getVar('view', 'logs');
		$layout = JRequest::getVar('layout', 'default');

		$view = &$this->getView($viewName, $viewType);

		$view->setToolBar();

		$view->setLayout($layout);
		$view->display();
	}

	public function remove() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Get some variables from the request
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );

		$model =& $this->getModel( 'logs' );
		if ($n = $model->remove($cid)) {
			$msg = JText::sprintf( 'COM_ADMINPRAISE_FILES_REMOVED', $n );
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect( 'index.php?option=com_adminpraise&view=logs',	$msg );
	}

	public function download() {

		jimport('joomla.filesystem.file');

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$filename = JPATH_ROOT.DS.'logs'.DS.$cid[0];

		if (JFile::exists($filename)) {
        ini_set('zlib.output_compression', 'Off');
 
        // IE FIX : FireFox Compatible 
        header("Content-Type: application/application/octet-stream\n");
        header("Content-Disposition: attachment; filename=\"$cid[0]\"");
        // Read It 
				$content = JFile::read($filename);
        // Print It
        echo $content;
        exit;
    } else {
        return 0;
    }

  }
}
