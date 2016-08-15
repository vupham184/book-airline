<?php
defined('_JEXEC') or die;?>



<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $this->hotel->name;?></h3>        
    </div>
</div>


<div id="sfs-wrapper" class="main">
    <div class="sfs-orange-wrapper">
    <div class="sfs-white-wrapper">

        <fieldset class="airport fs-14" style="padding: 20px 60px !important;">
            <p>
            <?php echo 'Dear '.$this->user->name; ?>
            </p>

            <div class="welcome-desc">Below you will
                fill the different data items that we received from your hotel.
                This data is currently offered to our clients, so making sure that
                this is the correct data is helpful for all of us.</div>
            <p>
            <?php if( isset($this->billing) && $this->billing ) : ?>
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile','contactdetail'));?>">General information loaded</a>
            <?php else: ?>
            	<a href="index.php?option=com_sfs&view=hotelregister&layout=registerdetail&Itemid=110">General information is not loaded</a>
            <?php endif;?>    
            </p>

            <p>
    		<?php if( isset($this->airports) ) : ?>
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile','airports'));?>">Airports loaded</a>
            <?php else : ?>
                <a href="<?php echo JRoute::_(SfsHelperRoute::getSFSRoute('hotelprofile','formairports'));?>">Airports are not loaded yet</a>
            <?php endif; ?>
            </p>



            <p>
            <?php if( isset($this->taxes) ) : ?>
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile','taxes') );?>">Taxes loaded</a>
            <?php else : ?>
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile','formtaxes'));?>">Taxes is not loaded yet</a>
            <?php endif; ?>
            </p>


            <p>
            <?php if( isset( $this->mealplan ) ) : ?>
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile','mealplans') );?>">Menus loaded</a>
            <?php else : ?>
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile','formmealplans') );?>">Menus is not loaded yet</a>
            <?php endif; ?>
            </p>

            <p>
            <?php if( isset($this->transport) ) : ?>
                <a href="<?php echo JRoute::_( SfsHelperRoute::getSFSRoute('hotelprofile','transport'));?>">Transport loaded</a>
            <?php else : ?>
                <a href="<?php echo JRoute::_(SfsHelperRoute::getSFSRoute('hotelprofile','formtransport'));?>">Transport are not loaded yet</a>
            <?php endif; ?>
            </p>

            <p>
            	<a href="<?php echo JRoute::_(SfsHelperRoute::getSFSRoute('report','hotel'));?>">Overview reports</a>
            </p>

            <p>Thank you for verifying this information.</p>
            <p>Kind regards,</p>
            <p><img src="images/banners/SFS_email_closing_2.png" border="0" alt="" /><br /><em><br />Email: <a href="mailto:BRU@sfs-web.com">hotel_support@sfs-web.com</a></em><br /><em>Telephone: +31 35 678 1255</em></p>

        </fieldset>

    </div>
    </div>

    <div class="sfs-below-main">
        <ul class="menu-command float-left">
            <li><a
                href="<?php echo JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'));?>"
                class="btn orange lg">Back</a></li>
        </ul>
    </div>
</div>




