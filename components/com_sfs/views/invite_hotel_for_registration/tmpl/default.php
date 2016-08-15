<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$invite_hotel_for_registration = 0;

if( SFSAccess::isAirline($user) ) {
    $airline = SFactory::getAirline();
    $invite_hotel_for_registration = (int)$airline->params["invite_hotel_for_registration"];
}
if($invite_hotel_for_registration == 1 ):
?>
<script>
    jQuery.noConflict();
    jQuery(function($){
        /*$(".updatebutton").on("click", function(){
            var id = $(this).attr('data-id');
            var url = "index.php?option=com_sfs&view=airlinehotel&layout=update_inventory&Itemid=161&tmpl=component&id="+id;
            SqueezeBox.open(url, {handler: 'iframe', size: {x: 800, y: 470} });
        })*/
		$('#invite_hotel_for_registration').submit(function(e) {
            if ( $('input[name="email"]').val() == "" ) {
				alert('Please enter email');
				return false;
			}
        });
		
		$('#hotel_list').change(function(e) {
            if ( $('#hotel_list option').is(':selected') ) {
				
				$('#name_of_hotel').val( $('#hotel_list option:selected').text() );
				$('#sexe').val( $('#hotel_list option:selected').attr('data-gender') );
				$('#first_name_last_name').val( $('#hotel_list option:selected').attr('data-fullname') );
				$('#email').val( $('#hotel_list option:selected').attr('data-uemail') );
				$('#fax').val( $('#hotel_list option:selected').attr('data-fax') );
			}
        });
    })
</script>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3>Invite hotel for registration</h3>        
    </div>
</div>
<div id="sfs-wrapper" class="main">
	<div class="sfs-above-main search-results-title">
        <h3 class="pull-left">Invite hotel for registration</h3>
    </div>
    <div class="sfs-main-wrapper">
        <div class="sfs-orange-wrapper hotel-form">
        
            <div class="sfs-white-wrapper floatbox">

                    <form id="invite_hotel_for_registration" action="<?php echo JRoute::_('index.php?option=com_sfs&task=invite_hotel_for_registration.send'); ?>" method="post" class="form-validate">
                        <fieldset>
                        	<div id="sfs_formLogin">
                                <div class="label">
                                    Hotel List
                                </div>
                                <div style="width:50px; float:left">
                                <select name="hotel_list" id="hotel_list" > 
                                	<option value="">Choose Hotel</option>
                                	<?php foreach ($this->hotels as $item): ?>
                                	<option 
                                    data-fullname="<?php echo $item->name . ' ' . $item->surname;?>" 
                                    data-uemail="<?php echo $item->uemail;?>" 
                                    data-gender="<?php echo $item->gender;?>" 
                                    data-fax="<?php echo $item->fax;?>" 
                                    value="<?php echo $item->hid;?>"><?php echo $item->hname;?></option>
                                    <?php endforeach; ?>
                                </select>
                                </div>
                            </div>
                            
                            <div id="sfs_formLogin">
                                <div class="label">
                                    Name of Hotel
                                </div>
                                <div style="width:50px; float:left">
                                <input type="text" name="name_of_hotel" id="name_of_hotel" value="<?php ?>" class="required" />
                                </div>
                            </div>
                            
                            <div id="sfs_formLogin">
                                <div class="label">
                                    Sexe
                                </div>
                                <div style="width:50px; float:left">
                                	<input name="sexe" id="sexe" type="text" />
                                </div>
                            </div>
                            
                            <div id="sfs_formLogin">
                                <div class="label">
                                   First name and last name 
                                </div>
                                <div style="width:50px; float:left">
                                <input type="text" name="first_name_last_name" id="first_name_last_name" value="<?php ?>"/>
                                </div>
                            </div>
                            
                            <div id="sfs_formLogin">
                                <div class="label">
                                   Email*
                                </div>
                                <div style="width:50px; float:left">
                                <input type="email" name="email" id="email" value="<?php ?>" class="required" /></div>
                            </div>
                            
                            <div id="sfs_formLogin">
                                <div class="label">
                                   Fax
                                </div>
                                <div style="width:50px; float:left"><input type="text" name="fax" id="fax" value="<?php ?>" /></div>
                            </div>
                            
                            <!--<div id="sfs_formLogin">
                                <div class="label">
                                   Does the airline have a current contract with the hotel.
                                </div>
                                <div style="width:50px; float:left">
                                <input type="checkbox" name="does_the_airline" value="<?php ?>" /></div>
                            </div>-->
                            
                			
                			<div class="s-button float-right">
                            	<button type="submit" class="validate s-button">Invite Hotel</button>
                            </div>
                            <?php echo JHtml::_('form.token'); ?>
                        </fieldset>
                    </form>
                                        
            </div>
            
        </div>
    </div>

</div>
<?php endif;?>
