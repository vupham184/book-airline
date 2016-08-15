<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');
$print = JRequest::getInt('print');
$billing = $this->airline->billing_details;
$document = JFactory::getDocument();
$document->addStylesheet( JURI::base().'components/com_sfs/assets/css/print.css', 'text/css' , 'print' );
?>
<div class="heading-block descript clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $this->hotel->name?>: Details name loading</h3>
    </div>
</div>

<div id="sfs-wrapper" class="main">
    <div class="main-top-block clearfix">
    <!-- Print modal -->
        <?php
        $printUrl = 'index.php?option=com_sfs&view=block&layout=print&blockid='.$this->reservation->id;
        if( $this->state->get('block.airport') )
        {
            $printUrl .= '&airport='.$this->state->get('block.airport');
        }
        $printUrl .= '&tmpl=component&print=1';
        ?>
        <a href="<?php echo $printUrl?>" class="btn orange sm pull-right modal" rel="{handler: 'iframe', size: {x: 860, y: 600}}">Print</a>
    <!-- End print modal -->    
    </div>

	<div class="width100 float-right" style="display:none" id="sfslogo">
    <img src="<?php echo JURI::base(); ?>components/com_sfs/assets/images/logo.jpg" width="223px" height="86px" />
    </div>

	<div class="main bottom-border-radius clear" style="padding:0px 1px 20px 1px; margin-bottom:15px;">
        <div class="sfs-white-wrapper orange-top-border floatbox" style="padding:10px 20px 20px 20px;">
            <div class="ec-left float-left">
            	<div class="floatbox pd">
                    <div class="customer-information">
                        <div class="billing-detail">
                            <span><?php echo $this->airline->name; ?></span>
                            <span>Registered Name: <?php echo $billing->name; ?></span>
                            <span><?php echo $billing->address; ?>, </span>
                            <span><?php echo $billing->zipcode; ?>, </span>
                            <span><?php echo $billing->city; ?>, <?php echo !empty($billing->state_name) ? $billing->state_name.',':''; ?><?php echo $billing->country_name; ?></span>
                            <span>Ph: <?php echo $this->airline->telephone ; ?></span>
                            <span>TVA number: <?php echo $billing->tva_number; ?></span>
                        </div>
                        <div class="contact-information">
                            <span>Sales contact: <?php echo $this->contact->gender.' '.$this->contact->name.' '.$this->contact->surname ?></span>
                            <span>Direct Ph: <?php echo $this->contact->telephone; ?></span>
                            <span>Email: <?php echo $this->contact->email; ?></span>
                        </div>
                    </div>
                    <table cellpadding="0" cellspacing="0" width="100%" class="blocked-rooms">
                        <tr>
                            <th class="br-c1">Rooms</th>
                            <th class="br-c2">Gross rates</th>
                            <th class="br-c3">Picked up rooms</th>
                            <th class="br-c4">Initial rooms</th>
                        </tr>
						<?php
						$class = null;
						if($this->reservation->s_room):
							$class = 'first';
						?>
						<tr class="<?php echo $class?>">
							<td>Single price:</td>
							<td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->s_rate;?></td>        
					        <td><span class="v-pd"><?php echo $this->picked_rooms[1]?></span></td>
					        <td><span class="v-pd"><?php echo $this->initial_rooms[1];?></span></td>
						</tr>
						<?php endif;?>                           
                        <tr class="<?php echo $class==null?'first':'';?>">
                            <td>Single/Double price:</td>
                            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->sd_rate;?></td>
                            <td><span class="v-pd"><?php echo $this->picked_rooms[2];?></span></td>
                            <td><span class="v-pd"><?php echo $this->initial_rooms[2] ;?></span></td>
                        </tr>
                        <tr>
                            <td>Triple price:</td>
                            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->t_rate;?></td>
                            <td><span class="v-pd"><?php echo $this->picked_rooms[3];?></span></td>
                            <td><span class="v-pd"><?php echo $this->initial_rooms[3] ;?></span></td>
                        </tr>
						<?php if($this->reservation->q_room): ?>
					    <tr>
					    	<td>Quad price:</td>
					        <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->q_rate;?></td>        
					        <td><span class="v-pd"><?php echo $this->picked_rooms[4];?></span></td>
					        <td><span class="v-pd"><?php echo $this->initial_rooms[4] ;?></span></td>
					    </tr>   
					    <?php endif;?>                         
                    </table>

					<?php					
					$estimateChargedRooms = $this->picked_rooms[1]+$this->picked_rooms[2] + $this->picked_rooms[3] + $this->picked_rooms[4];
					if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 ){
					 	$estimateChargedRooms = $estimateChargedRooms + (int)$this->guaranteeVoucher->issued;
					}
					?>

                    <div class="rooms-total floatbox">
                        <div class="float-left" style="width:60%">Total initial blocked rooms:</div>
                        <div class="float-left">
                            <?php
                                $initial_rooms = 0;
                                foreach ($this->initial_rooms as $value) :
                                    $initial_rooms += $value;
                                endforeach;
                                echo $initial_rooms;
                            ?>
                        </div>
                        <div class="clear"></div>
                        <div class="float-left" style="width:60%">Total picked up (used) rooms:</div>
                        <div class="float-left">
                            <?php
                                $picked_rooms = 0;
                                foreach ($this->picked_rooms as $value) :
                                    $picked_rooms += $value;
                                endforeach;
                                echo $picked_rooms;
                            ?>
                        </div>
                        <div class="clear"></div>
					    <div class="float-left" style="width:60%">Total estimated charged rooms:</div>
					    <div class="float-left">
					        <?php
					            echo $estimateChargedRooms;
					        ?>
					    </div>
					    
					    <div class="clear"></div>
					    
					    <div class="floatbox" style="margin-top:15px;">
						    <div class="float-left" style="width:60%">free release percentage:</div>
						    <div class="float-left">
						        <?php
						            echo (int)$this->hotel->getTaxes()->percent_release_policy.'%';
						        ?>
						    </div>
					    </div>
                    </div>
                </div>
            </div>

            <div class="ec-right float-right">
            	<div class="floatbox pd">
                    <div class="blockcode-information floatbox">
                        <div class="floatbox clear">
                            <span class="l-title">Roomblock code:</span><span><?php echo $this->reservation->blockcode;?></span>
                        </div>
                        <div class="floatbox clear">
                            <span class="l-title">Roomblock Status:</span><span><?php echo SFSCore::$blockStatus[$this->reservation->status];?></span>
                        </div>
                        <div class="floatbox clear">
                            <span class="l-title">Date</span><span><?php echo JHTML::_('date', $this->reservation->booked_date, JText::_('DATE_FORMAT_LC3') );?></span>
                        </div>
                    </div>

                    <div class="estimate-information">
                    
                    	<?php if($this->reservation->payment_type == 'passenger' ):?>
						<div style="padding-bottom:5px">
							<strong>Below charges have been paid directly by passenger to Hotel.</strong>
						</div>
						<?php endif;?>

                        <div class="estimate-title">Estimated charges</div>

                        <div class="estimate-detail floatbox">
                            <div class="floatbox clear">
                                <span class="l-title">Estimated total gross room charge:</span><span><?php echo $this->hotel->currency; ?> <?php echo $this->total_room_charge; ?></span>
                            </div>
                            <div class="floatbox clear">
                                <span class="l-title">Estimated total gross mealplan charge:</span><span><?php echo $this->hotel->currency; ?> <?php echo $this->total_mealplan_charge ;?></span>
                            </div>
                            <div class="floatbox clear">
                                <span class="l-title">Estimated total gross invoice charge:</span><span><?php echo $this->hotel->currency; ?> <?php echo $this->total_invoice_charge ;?></span>
                            </div>
                        </div>
                    </div>

                    <table cellpadding="0" cellspacing="0" width="100%" class="blocked-rooms">
                        <tr>
                            <th class="br-c1">Mealplans</th>
                            <th class="br-c2">Gross rates</th>
                            <th class="br-c3">Picked up mealplans</th>
                        </tr>
                        <tr class="first">
                            <td>Breakfast price:</td>
                            <td>
                            <?php if($this->reservation->breakfast):?>
                            <?php echo $this->hotel->currency ;?> <?php echo $this->reservation->breakfast ;?>
				            <?php else:?>
				        		N/A
				        	<?php endif;?>
                            </td>
                            <td><span class="v-pd">
                            <?php 
					       	if($this->reservation->breakfast):
					        	echo $this->picked_breakfasts ;
					        else :
					        	echo 'N/A';
					        endif;	
					        ?>
                            </span></td>
                        </tr>
                        
                        <tr>
					    	<td>Lunch price:</td>
					        <td>
					        	<?php if($this->reservation->lunch):?>
					        	<?php echo $this->hotel->currency ;?> <?php echo $this->reservation->lunch ;?>
					        	<?php else:?>
					        		N/A
					        	<?php endif;?>
					        </td>        
					        <td><span class="v-pd">
					        <?php 
					       	if($this->reservation->lunch):
					        	echo $this->picked_lunchs ;
					        else :
					        	echo 'N/A';
					        endif;	
					        ?>
					        </span></td>
					    </tr>

						<?php if( (int)$this->reservation->course_type > 0) : ?>
					    <tr>
					    	<td>Dinner price:</td>
					        <td><?php echo $this->hotel->currency.' '.$this->reservation->mealplan;?></td>
					        <td><span class="v-pd"><?php echo $this->picked_mealplans ;?></span></td>
					    </tr>
					    <?php else :?>
					    <tr>
					    	<td>Dinner price:</td>
					        <td>N/A</td>
					        <td><span class="v-pd">N/A</span></td>      
					    </tr>
					    <?php endif;?>

                    </table>
                </div>
            </div>
        </div>

        <div class="passengers">
            <div class="p-title">
                <span class="order">&nbsp;</span>
                <span>First name</span>
                <span>Last name</span>
                <span>Voucher number</span>
                <span class="vfb">Mealplan</span>
    		    <span class="vfb">Lunch</span>
    		    <span class="vfb">Breakfast</span>
            </div>
            <?php
            $i = 0;
            foreach ( $this->passengers as $item ) : ?>
            <div class="passenger">
                <span class="order"><?php echo ++$i; ;?></span>
                <span><?php echo $item->first_name;?></span>
                <span><?php echo $item->last_name ;?></span>
                <span><?php echo $item->code ;?></span>
                <span class="vfb"><?php echo (int)$item->mealplan ? 'Included':'No';?></span>
       			<span class="vfb"><?php echo (int)$item->lunch ? 'Included':'No';?></span>
       			<span class="vfb"><?php echo (int)$item->breakfast ? 'Included':'No';?></span>  
            </div>
            <?php endforeach ; ?>
            
            <?php
		    if( !empty($this->guaranteeVoucher) && (int)$this->guaranteeVoucher->issued > 0 )
		    {
		    	$j=0;	
		    	while ($j < $this->guaranteeVoucher->issued)
		    	{
		    		?>
		    		<div class="passenger">
				        <span class="order"><?php echo ++$i; ;?></span>
				        <span>No show</span>
				        <span>No show</span>
				        <span><?php echo $this->guaranteeVoucher->code ;?></span>
				    </div>
		    		<?php 
		    		$j++;
		    	}
		    }
		    ?>
            
        </div>
    </div>

    <div class="floatbox clear" style="padding-bottom:10px;">
    	<a href="<?php echo JRoute::_( 'index.php?option=com_sfs&view=block&Itemid='.JRequest::getInt('Itemid') );?>" class="small-button float-right" style="margin-right:25px; text-decoration:none;">Close</a>
    </div>

    <div class="clear"></div>

	<span class="width100 float-right" style="display:none;text-align:right" id="sfstime"><?php echo date("d F Y, H:i:s",time()); ?></span>

    <?php if ( count($this->messages) ) :?>
        <div class="correspondence">
        <div class="sfs-above-main">
            <h3>Correspondence About Block Code</h3>
        </div>

        <div class="sfs-main-wrapper" style="padding:0px 1px 1px 1px;">
            <div class="sfs-white-wrapper floatbox">
                <?php
                $prev = null;
                $floatd = 0;
                foreach ( $this->messages as $message ):
                    if(isset($prev) && $prev != $message->type ){
                        $floatd = 1 - $floatd;
                    }
                    ?>
                    <div class="message-block <?php echo ($floatd==1)? 'float-right':'float-left';?>">
                        <p class="sendby">
                            Sent by <?php echo $message->type==2 ? 'you' : $message->from_name  ;?> on: <span class="datesend"><?php echo JHTML::_('date', $message->posted_date ,JText::_('DATE_FORMAT_LC2') );?></span>
                        </p>
                        <div class="message-block-body">
                            <div class="message-subject">RE: challenge for block code <?php echo $this->reservation->blockcode;?></div>
                            <?php echo $message->body; ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <?php
                    $prev =  $message->type;
                endforeach;
                ?>

            </div>
        </div>

        <div class="sfs-below-main" style="padding:10px 20px 10px 10px;">
            <a href="<?php echo JRoute::_('index.php?option=com_sfs&view=message&tmpl=component&mlayout=hchallenge&bookingid='.$this->reservation->id.'&airport='.JRequest::getVar('airport'))?>" rel="{handler: 'iframe', size: {x: 675, y: 500}}" class="modal small-button float-right">
            	Send Message
            </a>
        </div>


        </div>
      <div class="floatbox clear" style="padding-top:10px;">
         <a href="<?php echo JRoute::_( 'index.php?option=com_sfs&view=block&Itemid='.JRequest::getInt('Itemid') );?>" class="small-button float-right" style="margin-right:25px; text-decoration:none;">Close</a>
        </div>

 <?php endif;?>



</div>