<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHTML::_('behavior.modal', 'a.modal', array('onClose'=>'\function(){supdate();}'));
$this->length_unit  = array('km'=>JText::_('UNIT_KM'), 'mi'=>JText::_('UNIT_MI'));
$hotel = SFactory::getHotel();
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	var airportForm = document.id('airportForm');
	airportForm.getElements('[type=text], select').each(function(el){
    	new OverText(el);
	});
	new Form.Validator(airportForm);
});
</script>

<script>
	function supdate(){
	}
	
	function showSqueezeBox( airport_id, numberI ){
		var sUrl  = '<?php echo JRoute::_('index.php?option=com_sfs&view=findhotelonmap&tmpl=component');?>&airport_id=' + airport_id + "&numberI=" + numberI;
		SqueezeBox.open( sUrl,
			{
				handler: 'iframe',
				size: {x: 720, y: 460},
				closable: false,
				onClose: function(){
					//alert("onClose");
					///window.location.href = '<?php echo $this->ordering_url?>';
				}
			}
		);
	}
	
    function changeDistanceAirport(hotel_location,from_location,to_location,cla,lat,long, airport_id){
        if( hotel_location == "Select Location" ) {
            alert("Please select a Hotel location");
        }
        else {             
            jQuery.get("<?php echo JRoute::_('index.php?option=com_sfs&task=getdistance.getDistance');?>", {'from_location':from_location, 'to_location': to_location, 'lat': lat, 'long': long, 'airport_id' : airport_id}, 
            function( data ){
                if ( data.status == 'OK'){  
                    if( data.status_sub == 'NOT_FOUND' ){
                        alert(data.status_sub);
                        jQuery('#airport' + cla + 'distance_unit option[value="'+data.distance.textKM+'"]').removeAttr("selected");
                        jQuery('.distance-unit' + cla).val("");
                    }
                    else {
                        if(data.status_sub == 'ZERO_RESULTS'){
                            data.distance.text = '0';
                        }
                        jQuery('#airport' + cla + 'distance_unit option[value="'+data.distance.textKM+'"]').attr("selected", true);
                        jQuery('.distance-unit' + cla).val(data.distance.text);
                    }
                }
                else {
                    alert(data.status);
                }
            },'json');
            
        }//End else
    }

	//jQuery.noConflict();
    jQuery(function ($) {
		$('.find-hotel-on-map').click(function(e) {
			var data_id = $(this).attr("data-id");
            var airport_id = $('#airport'+data_id+'code option:selected').val();
			if ( airport_id > 0 && airport_id != "" ) {
				showSqueezeBox( airport_id, data_id );
			}
			else {
				alert("Please select a Nearest Airport Code");
			}
        });

        $('#airport1code').change(function(event) {
            var lat = $("#geo_location_latitude0").val();
            var long = $("#geo_location_longitude0").val();           
            
            var cla = $(this).attr("id").replace("airport", "").replace("code","");
            var hotel_location = $('#hotel_location option:selected').text();
            //var from_location = $('#airport0code option:selected').text();
            var from_location = "<?php echo $hotel->address; ?>";
            var to_location = $("option:selected",this).text();
            if(from_location == "Select Airport"){
                alert("Please select a Nearest Airport Code");
            }else{
                changeDistanceAirport(hotel_location,from_location,to_location,cla,lat,long);
            }
        });

        $('#airport2code').change(function(event) {
            var lat = $("#geo_location_latitude0").val();
            var long = $("#geo_location_longitude0").val();

            var cla = $(this).attr("id").replace("airport", "").replace("code","");
            var hotel_location = $('#hotel_location option:selected').text();
            //var from_location = $("option:selected",this).text();
            var from_location = "<?php echo $hotel->address; ?>";
            var to_location = $("option:selected",this).text(); 
            if(to_location == "Select Airport"){
                alert("Please select a Nearest Airport Code");
            }else{
                changeDistanceAirport(hotel_location,from_location,to_location,cla,lat,long);  
            }
                     
        });
		
		//lchung add 09-03-2016
		$('.change-value-distance').change(function(e) {
            var lat = $("#geo_location_latitude0").val();
            var long = $("#geo_location_longitude0").val();
			var cla = $(this).attr("id").replace("airport", "").replace("code","");
            var hotel_location = $('#hotel_location option:selected').text();
			var from_location = "<?php echo $hotel->address; ?>";
            var to_location = $("option:selected",this).text();
			var airport_id = $("option:selected",this).val();
			changeDistanceAirport(hotel_location,from_location,to_location,cla,lat,long, airport_id);			
        });
		//End lchung add 09-03-2016
	});
</script>

<?php if($this->hotel->step_completed < 9) : ?>
<?php $title = JText::sprintf('COM_SFS_LABLE_HOTEL_SIGNUP_STEP', 2) . JText::_('COM_SFS_LABLE_AIRPORTS'); ?>
            <?php else : ?>
<?php $title = $this->hotel->name.' - '.JText::_('COM_SFS_LABLE_AIRPORTS'); ?>
             <?php endif; ?>

<?php
    	$text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_04'));
    	$text = empty($text) ? JText::_('COM_SFS_HOTEL_AIRPORT_REGISTER_DESC') : $text;
    	?>
    	
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $title?></h3>        
    </div>
</div>

<div class="main">
    <?php echo $text?>
    <div id="hotel-registraion" class="sfs-wrapper airports<?php echo $this->pageclass_sfx?>" style="overflow: visible;">

        <?php if( ! $this->hotel->isRegisterComplete()) :?>
    	    <h1 class="page-title" style="text-align:center">
    	    	<?php echo $this->hotel->name; ?>
    	    </h1>
            
    	    <?php echo $this->progressBar(1); ?>

            <div class="clear"></div>
        <?php endif; ?>        

        <form id="airportForm" name="airportForm" action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile&layout=formmealplans&Itemid=111'); ?>" method="post" class="form-validate sfs-form form-vertical register-form">            
            <div class="block-group">
				<?php
                    $this->aIndex = 0;

                    if( count( $this->airports) ) :
                        foreach ($this->airports as &$airport) :
                            $this->airport = $airport;
                            echo $this->loadTemplate('form');
                            $this->aIndex++;
                        endforeach;
                        if(  $this->aIndex < 3 ) :
                        	$this->airport = JTable::getInstance('HotelAirport', 'JTable');
                        	while ( $this->aIndex < 3 ) {
                            	echo $this->loadTemplate('form');
                            	$this->aIndex++;
                        	}
                        endif;
                    else :
                        $this->airport = JTable::getInstance('HotelAirport', 'JTable');
                        while ( $this->aIndex < 3) :
                            echo $this->loadTemplate('form');
                            $this->aIndex++;
                        endwhile;
                    endif;
                ?>
                <?php if( count( $this->airports) >= 3 ) :
                ?>
                
                <div class="form-group">
                    <label><?php echo JText::_("COM_SFS_ADD_MORE_AIRPORT_CODE");?> :</label>                       
                	<a href="index.php?option=com_sfs&view=hotelprofile&layout=addairport&tmpl=component&Itemid=<?php echo JRequest::getInt('Itemid')?>" rel="{handler: 'iframe', size: {x: 675, y: 280}}" class="modal btn orange sm    ">
                			+ <?php echo JText::_('COM_SFS_ADD_AIRPORT');?>
                	</a>
                </div>    
                    
                <?php endif;?>
            </div>

            <div class="wrap-col clearfix">
                <div class="col w50">
                    <?php
                        $text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_05'));
                        echo empty($text) ? JText::_('COM_SFS_HOTEL_AIRPORT_NOTE') : $text;
                    ?>
                </div>

                <div class="col w50">
                    <div class="form-group btn-group">
                        <button type="submit" class="btn orange lg pull-right" name="save_next"><?php echo JText::_('COM_SFS_NEXT_STEP');?></button>
                        <?php if( $this->hotel->isRegisterComplete()) :?>                   
                            <button type="submit" class="btn orange lg pull-right" name="save_close"><?php echo JText::_('COM_SFS_SAVE_AND_CLOSE');?></button>                  
                        <?php endif;?>
                    
                    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>">
                    <input type="hidden" name="task" value="hotelprofile.saveAirports" />
                    <?php echo JHtml::_('form.token'); ?>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
</div>