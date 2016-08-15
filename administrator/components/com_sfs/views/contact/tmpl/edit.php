<?php
// No direct access
defined('_JEXEC') or die();
$db = JFactory::getDbo();
require_once JPATH_ROOT . '/components/com_sfs/libraries/core.php';
require_once JPATH_ROOT . '/components/com_sfs/libraries/access.php';
require_once JPATH_ROOT . '/components/com_sfs/libraries/hotel.php';


JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');

$grouplabel = '';
$grouptext = '';

if($this->item->grouptype == 1) {
	$hotel = SHotel::getInstance( $this->item->group_id );
	$groupLabel = 'Hotel';
	$groupValue = $hotel->name;
} else if($this->item->grouptype == 2) {
	$groupLabel = 'Airline';
	$query = 'SELECT b.name FROM #__sfs_airline_details AS a INNER JOIN #__sfs_iatacodes AS b ON b.id=a.iatacode_id WHERE a.id='.$this->item->group_id;
	$db->setQuery($query);
	$groupValue = $db->loadResult();	
} else {
	$groupLabel = 'GH';
	$query = 'SELECT a.company_name FROM #__sfs_airline_details AS a WHERE a.id='.$this->item->group_id;
	$db->setQuery($query);
	$groupValue = $db->loadResult();	
}

$tmpl = JRequest::getVar('tmpl'); 
$jsonUrl  = JURI::base().'index.php?option=com_sfs&task=contact.generatesk&format=json';
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	var jsonuri = '<?php echo $jsonUrl ?>';
	$('generateKey').addEvent('click', function(e){
		e.stop();		
		new Request.JSON({url: jsonuri, onSuccess: function(result){			    
			if(result.secretkey) {
				$('secret_key').set('value',result.secretkey);
			}	    		    			   
		}}).send();
		
	});		
});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_sfs&view=contact&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="sfs-contact-form" class="form-validate">

<?php	
if( $tmpl == 'component' ):
?>
<fieldset>
	<div class="fltrt">
		<button type="submit">
			Save
		</button>			
		<button onclick="  window.parent.SqueezeBox.close();" type="button">
			Close
		</button>
	</div>
	<div class="configuration">
		Edit User
	</div>
</fieldset>
<?php endif;?>

<div class="current">

	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend>Details</legend>
			<ul class="adminformlist">
				<li>
					<label>Job Title</label>
					<input type="text" name="job_title" value="<?php echo $this->item->job_title;?>" size="30" class="required">
				</li>							
				<li>
					<label>First Name</label>
					<input type="text" name="name" value="<?php echo $this->item->name;?>" size="30" class="required">
				</li>
				<li>
					<label>Last Name</label>
					<input type="text" name="surname" value="<?php echo $this->item->surname;?>" size="30">
				</li>				
				
				<li>
					<label>Email</label>
					<input type="text" name="email" value="<?php echo $this->item->email;?>" size="50">
				</li>
				<li>
					<label>Telephone</label>
					<input type="text" name="telephone" value="<?php echo $this->item->telephone;?>" size="30">
				</li>				
				<li>
					<label>Fax</label>
					<input type="text" name="fax" value="<?php echo $this->item->fax;?>" size="30">
				</li>
				<li>	
					<label>Mobile</label>
					<input type="text" name="mobile" value="<?php echo $this->item->mobile;?>" size="30">
				</li>
				<li>	
					<label><?php echo $groupLabel;?></label>
					<input type="text" class="readonly" readonly="readonly" value="<?php echo $groupValue;?>" size="30">
				</li>	
				<li>	
					<label><?php echo $groupLabel;?> Admin</label>
					<input type="text" class="readonly" readonly="readonly" value="<?php echo $this->item->is_admin ? 'Yes':'No';?>" size="30">
				</li>																																																								
			</ul>
			<div class="clr"></div>	
		</fieldset>
	</div>
		
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend>Secret Key</legend>
			<div>
				<p style="margin-bottom: 10px;padding-bottom:0;">
					<input type="text" name="secret_key" id="secret_key" value="<?php echo $this->item->secret_key;?>" style="width:90%" readonly="readonly">
				</p>			
				<button type="button" id="generateKey">Generate</button>				
			</div>
		</fieldset>		
		<fieldset class="adminform">
			<legend>Redirect URL</legend>
			<div>
				<p style="margin-bottom: 10px;padding-bottom:0;">
					<input type="text" name="return_url" id="return_url" value="<?php echo $this->item->return_url;?>" style="width:90%">
				</p>											
			</div>
		</fieldset>		
	</div>
	
	<div class="clr"></div>	
</div>
	
	<input type="hidden" name="id" value="<?php echo $this->item->id;?>" />
	<input type="hidden" name="user_id" value="<?php echo $this->item->user_id;?>" />
	<input type="hidden" name="grouptype" value="<?php echo $this->item->grouptype;?>" />
	<input type="hidden" name="group_id" value="<?php echo $this->item->group_id;?>" />
	<input type="hidden" name="contact_type" value="<?php echo $this->item->contact_type;?>" />
	<input type="hidden" name="task" value="contact.save">
	
	<?php	
	if( $tmpl == 'component' ):
	?>
	<input type="hidden" name="tmpl" value="component">
	<?php endif;?>
	
	<?php echo JHtml::_('form.token'); ?>
	
</form>

<div class="clr"></div>
