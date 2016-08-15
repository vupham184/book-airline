<?php
defined('_JEXEC') or die;
$room = unserialize($this->reservation->ws_room_type);
$wsRoomType = Ws_Do_Search_RoomTypeResult::fromString($room[0]["roomType"]);
$mealPlan = $wsRoomType->MealBasisName;
?>
<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $this->airline->name;?>: Details name loading</h3>
    </div>
</div>
<div id="sfs-wrapper" class="main">
    
	<div class="sfs-main-wrapper bottom-border-radius clear" style="padding:0px 1px 20px 1px; margin-bottom:15px;">
        <div class="sfs-white-wrapper orange-top-border floatbox" style="padding:10px 20px 20px 20px;">
        
            <div class="ec-left float-left"> 
            	<div class="floatbox pd">       
                
                    <div class="customer-information">
					    <div class="billing-detail">
					        <span><?php echo $this->hotel->name; ?></span>
					        <span>Registered Name: <?php echo $this->hotel->billing_name; ?></span>
					        <span><?php if($this->hotel->billing_address) echo $this->hotel->billing_address.","; ?></span>
					        <span><?php if($this->hotel->billing_zipcode) echo $this->hotel->billing_zipcode.","; ?></span>
					        <span><?php if($this->hotel->billing_city) echo $this->hotel->billing_city.","; ?><?php echo !empty($this->hotel->billing_state) ? $this->hotel->billing_state.',':''; ?> <?php echo $this->hotel->billing_country; ?></span>
					        <span>Ph: <?php echo $this->hotel->telephone ; ?></span>
					        <span>Fax: <?php echo $this->hotel->fax 	; ?></span>
					        <span>TVA number: <?php echo $this->hotel->tva_number; ?></span>
					    </div>
                        <?php if(empty($this->hotel->ws_id)) : ?>
					    <div class="contact-information">
					        <span>Sales contact: <?php echo $this->contact->gender.' '.$this->contact->name.' '.$this->contact->surname ?></span>
					        <span>Direct Ph: <?php echo $this->contact->telephone; ?></span>
					        <span>Email: <?php echo $this->contact->email; ?></span>
					    </div>
                        <?php endif;?>
					</div>
                    
                    <table cellpadding="0" cellspacing="0" width="100%" class="blocked-rooms">
                        <tr>
                            <th class="br-c1">Rooms</th>
                            <th class="br-c2">Gross rates</th>
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
                            <td><span class="v-pd"><?php echo $this->initial_rooms[1] ;?></span></td>
                        </tr>
                        <?php endif;?>
                        <tr class="<?php echo $class==null?'first':'';?>">
                            <td>Single/Double price:</td>
                            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->sd_rate;?></td>        
                            <td><span class="v-pd"><?php echo $this->initial_rooms[2] ;?></span></td>
                        </tr>     
                        <tr>
                            <td>Triple price:</td>
                            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->t_rate;?></td>        
                            <td><span class="v-pd"><?php echo $this->initial_rooms[3] ;?></span></td>
                        </tr>  
                        <?php if($this->reservation->q_room): ?>
                        <tr>
                            <td>Quad price:</td>
                            <td><?php echo $this->hotel->currency; ?> <?php echo $this->reservation->q_rate;?></td>        
                            <td><span class="v-pd"><?php echo $this->initial_rooms[4] ;?></span></td>
                        </tr>  
                        <?php endif;?>    
                    </table>    
                    
 					<div style="padding-top:25px;">
                    <table cellpadding="0" cellspacing="0" width="100%" class="blocked-rooms">
                    <?php if(empty($this->hotel->ws_id)) : ?>
                        <tr>
                            <th class="br-c1">Mealplans</th>
                            <th class="br-c2">Gross</th>
                        </tr>
                        
                        <?php if($this->reservation->breakfast):?>
                        <tr class="first">
                            <td width="145">Breakfast price:</td>
                            <td><?php echo $this->hotel->currency ;?> <?php echo $this->reservation->breakfast ;?></td>        
                        </tr>
                        <?php else:?>
                        <tr class="first">
                            <td width="145">Breakfast price:</td>
                            <td>not available</td>        
                        </tr>
                        <?php endif;?>                        
                        <?php if($this->reservation->lunch):?>
                        <tr>
                            <td width="145">Lunch price:</td>
                            <td><?php echo $this->hotel->currency ;?> <?php echo $this->reservation->lunch ;?></td>        
                        </tr>
                        <?php else :?>
                        <tr>
                            <td width="145">Lunch price:</td>
                            <td>not available</td>        
                        </tr>
                        <?php endif;?>                                
                        <?php if( (int)$this->reservation->course_type > 0) : ?>
					    <tr>
                            <td>Dinner price:</td>
                            <td><?php echo $this->hotel->currency.' '.$this->reservation->mealplan;?></td>        
                        </tr> 
					    <?php else :?>
					    <tr>
					    	<td>Dinner price:</td>
					        <td>not available</td>                
					    </tr>
					    <?php endif;?>
                    <?php else:?>
                        <tr>
                            <th class="br-c1">MealPlans</th>
                            <td><?php echo $mealPlan?></td>
                        </tr>
                    <?php endif;?>
                    </table>          
                    </div>                            

                </div>
            </div>
            
            <div class="ec-right float-right">
            	<div class="floatbox pd">       
                    <div class="blockcode-information floatbox">
                        <div class="floatbox clear">
                            <span class="l-title">Flight Number:</span><span><?php echo $this->reservation->_vouchers[0]->flight_code;?></span>
                        </div>
                        <div class="floatbox clear">
                            <span class="l-title">Roomblock code:</span><span><?php echo $this->reservation->blockcode;?></span>
                        </div>    
                        <div class="floatbox clear">
                            <span class="l-title">Roomblock Status:</span><span><?php echo SFSCore::$blockStatus[$this->reservation->status];?></span>
                        </div>
                        <div class="floatbox clear">
                            <span class="l-title">Date</span><span><?php echo JHTML::_('date', $this->reservation->blockdate, JText::_('DATE_FORMAT_LC3') );?></span>
                        </div>        
                    </div>    	         
                </div>
            </div>
        </div>
    </div>
    <div class="main-bottom-block">
        <div class="pull-left">
            <a class="btn orange sm" href="<?php echo JRoute::_('index.php?option=com_sfs&view=airblock&Itemid='.JRequest::getInt('Itemid'))?>"><?php echo JText::_('COM_SFS_BACK')?></a>
        </div>
    </div>
</div>