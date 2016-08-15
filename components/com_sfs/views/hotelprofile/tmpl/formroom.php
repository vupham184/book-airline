<?php
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>


<div class="heading-block clearfix">
    <div class="heading-block-wrap">
        <h3><?php echo $this->hotel->name; ?></h3>
    </div>
</div>

<div id="hotel-registraion" class="main">

    <?php if( ! $this->hotel->isRegisterComplete()) :?>
        

        <?php echo $this->progressBar(2); ?>

        <div class="clear"></div>


        <p>
        	<?php
        	 	$text = SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_06'));
        	 	echo empty($text) ? JText::_('COM_SFS_HOTEL_ROOMS_DESC') : $text;
        	 ?>
		</p>

        <div class="clear"></div>
    <?php endif; ?>    

    <form action="<?php echo JRoute::_('index.php?option=com_sfs&view=hotelprofile'); ?>" method="post" class="form-validate sfs-form form-vertical register-form">        
        <div class="block-group">
            <div class="block style-2 orange"> 
                <fieldset>
                    <?php if($this->hotel->step_completed < 9) : ?>         
                        <legend><?php echo JText::sprintf('COM_SFS_STEP', 3); ?><?php echo JText::_('COM_SFS_LABLE_ROOMS'); ?></legend>
                    <?php else : ?>
                        <legend><?php echo $this->hotel->name.' - '.JText::_('COM_SFS_LABLE_ROOMS'); ?></legend>
                    <?php endif; ?>  
                    <div class="col w80 pull-left p20">
                        <div class="form-group">
                            <label><?php echo JText::_('COM_SFS_LABEL_TOTAL_ROOMS'); ?></label>
                            <div class="col w10">
                                <input name="room_total" value="<?php echo is_object($this->room) ? $this->room->total : ''; ?>" class="required validate-numeric" size="6"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo JText::_('COM_SFS_LABEL_NUMBER_OF_STANDARD_ROOMS'); ?></label>
                            <div class="col w10">
                                <input name="standard" value="<?php echo is_object($this->room) ? $this->room->standard : ''; ?>" class="required validate-numeric" size="6"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo JText::_('COM_SFS_LABEL_AVERAGE_SIZE_STANDARD_ROOMS'); ?></label>
                            <div class="col w60">
                                <div class="row r10 clearfix">
                                    <div class="col w20">
                                        <input value="<?php echo is_object($this->room) ? $this->room->standard_size : ''; ?>" name="standard_size" class="required validate-numeric" size="6" style="width: 60px;" />
                                    </div>
                                    <div class="col w40">
                    					<?php
                    						$square = array(
                    							array('val'=>'square_feet', 'txt'=>'Square feet'),
                    							array('val'=>'square_meters', 'txt'=>'Square meters')
                    						);
                    						echo JHTML::_('select.genericlist', $square, 'standard_size_unit', 'class="inputbox"', 'val', 'txt', is_object($this->room) ? $this->room->standard_size_unit : '' );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                  
                </fieldset>
            </div>
        </div>
    
        <div class="form-group">        	
        		<?php
        			$text =  SfsHelper::getIntroTextOfArticle($this->params->get('article_page_1_07'));
        			echo empty($text) ? JText::_('COM_SFS_HOTEL_ROOMS_NOTE') : $text;
        		?>        	
        	<button type="submit" class="validate btn orange sm pull-right" name="save_next">Next step &gt;&gt;</button>        	
        </div>

        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>">

        <input type="hidden" name="id" value="<?php echo is_object($this->room) ? $this->room->id : '0'; ?>" />
        <input type="hidden" name="task" value="hotelprofile.room" />

        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>