<?php
defined('_JEXEC') or die;
JHTML::_('behavior.modal');

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
    	            <?php echo $this->loadTemplate('hotel');?>            
	                <?php echo $this->loadTemplate('rooms');?>
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
                </div>
            </div>
        </div>     		
    </div>    

<div class="sfs-wrapper rooms<?php echo $this->pageclass_sfx?>">

<div class="detailcom">
	<h3>KLM AMS: Details name loading</h3>
    
	<div class="sfs-blue-wrapper">
    	<div class="detail-left">
        	<div class="detailhotel">
            	<div class="infofrom">
                    <p><?php echo $this->hotel->name; ?></p>
                    <p><?php echo $this->hotel->address; ?>, <?php echo $this->hotel->city; ?></p>
                    <p><?php echo $this->hotel->country; ?></p>
                    <p>Ph: <?php echo $this->hotel->main_telephone ; ?></p>
                    <p>Fax: <?php echo $this->hotel->main_fax 	; ?></p>
                </div>               
            </div>
            
            <div class="detailrooms">
            	<div class="title">
                	<div class="titlerooms block">
                    	<span>Rooms</span>
                        <span>Gross rates</span>
                    </div>                  
                </div>
                <div class="clear"></div>
                
                <div class="detail_item">
                	<div class="priceitem block">
                        <span>Single price:</span>
                        <span><?php echo $this->hotel->currency; ?> <?php echo $this->roomblock->sd_room_rate;?></span>
                    </div>                               
                </div>
                 <div class="clear"></div>
                <div class="detail_item">
                	<div class="priceitem block">
                        <span>Double price:</span>
                        <span><?php echo $this->hotel->currency; ?> <?php echo $this->roomblock->sd_room_rate;?></span>
                    </div>   
                </div>
                 <div class="clear"></div>
                <div class="detail_item">
                	<div class="priceitem block">
                        <span>Triple Price:</span>
                        <span><?php echo $this->hotel->currency; ?> <?php echo $this->roomblock->t_room_rate;?></span>
                    </div>   
                </div>
                 <div class="clear"></div>
            </div>
        </div>
        
        <div class="detail-right">
        	<div class="roomblock">
            	<p><span class="spanr">Roomblock code:</span><span><?php echo $this->roomblock->blockcode;?></span></p>
                <p><span class="spanr">Roomblock Status:</span><span><?php echo SFSCore::$blockStatus[$this->roomblock->status];?></span></p>
                <p><span>Date</span><span><?php echo JHTML::_('date', $this->roomblock->blocked_date, JText::_('DATE_FORMAT_LC3') );?></span></p>
            </div>                                             
        </div>
    </div>
    

</div>

</div>

</div>