<?php
defined('_JEXEC') or die;

class SfsViewClose extends JViewLegacy
{

	function display($tpl = null)
	{
		// close a modal window
		$closetype 	= JRequest::getVar('closetype');
		$itemid 	= JRequest::getInt('Itemid');
		
		$closeScript='';
		
		switch ($closetype) {
			case 'registerdetail':
				$link = JRoute::_(SfsHelperRoute::getSFSRoute('hotelregister','registerdetail'),false);
				$closeScript = 'window.parent.location = "'.$link.'"';
				break;
			case 'airport':
				$closeScript = 'window.parent.location = "'.JURI::root().'index.php?option=com_sfs&view=hotelprofile&layout=formairports&Itemid='.$itemid.'"';
				break;
			case 'airaddcontact':
				$closeScript = 'window.parent.location = "'.JURI::root().'index.php?option=com_sfs&view=airlineregister&layout=contacts&Itemid='.$itemid.'"';
				break;
			case 'pairlineaddcontact':
				$closeScript = 'window.parent.location = "'.JURI::root().'index.php?option=com_sfs&view=airlineprofile&layout=editcontacts&Itemid='.$itemid.'"';
				break;
			case 'closechallenge':
				$id = JRequest::getInt('id');
				$closeScript = 'window.parent.location = "'.JURI::root().'index.php?option=com_sfs&view=airblock&layout=detail&id='.(int)$id.'&Itemid='.$itemid.'"';
				break;
			case 'closemtentative':
				$id = JRequest::getInt('id');
				$airport = JRequest::getVar('airport');
				$closeScript = 'window.parent.location = "'.JURI::root().'index.php?option=com_sfs&view=block&layout=tentative&blockid='.(int)$id.'&airport='.$airport.'&Itemid='.$itemid.'"';
				break;
			case 'cancelvoucher':
				$id = JRequest::getInt('bookingid');
				$hotelid = JRequest::getInt('hotelid');
				$nightdate = JRequest::getVar('nightdate');
				$association_id = JRequest::getInt('association_id');
				
				$url = 'index.php?option=com_sfs&view=match&layout=vouchers&hotelid='.$hotelid.'&nightdate='.$nightdate.'&reservationid='.$id.'&association_id='.$association_id.'&Itemid='.$itemid;
				
				$closeScript = 'window.parent.location = "'.JURI::root().$url.'"';				
				break;	
			case 'removetaxi':				
				$closeScript = 'window.parent.location = "'.JURI::root().'index.php?option=com_sfs&view=taxi&layout=edit&Itemid='.$itemid.'"';							
				break;							
			default:
				$reload 	 = JRequest::getInt('reload');
				
				if($reload)
				{
					JFactory::getDocument()->addScriptDeclaration('
						window.parent.location.href=window.parent.location.href;
						window.parent.SqueezeBox.close();
					');
					return;	
				}	
				
				$closeScript = 'window.parent.SqueezeBox.close();';				
				break;	
		}		
		JFactory::getDocument()->addScriptDeclaration($closeScript);		
	}
}
