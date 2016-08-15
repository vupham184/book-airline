<?php
defined('_JEXEC') or die();
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>
<script src="<?php echo JURI::base(); ?>/templates/sfs_j16_hdwebsoft/js/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(function ($) {
        $("#btnBook").on("click", function () {
            var sroom   = $("[name='rooms[sroom]']").val();
            var sdroom  = $("[name='rooms[sdroom]']").val();
            var troom   = $("[name='rooms[troom]']").val();
            var qroom   = $("[name='rooms[qroom]']").val();
            if((!sroom || sroom == 0) && (!sdroom || sdroom == 0) && (!troom ||troom == 0) && (!qroom || qroom == 0)){
                alert ("Please input at least 1 room!");
            }else{
                $('#printRequestSpinner').addClass('ajax-Spinner');
                this.disabled = true;
                $("#reservationForm").ajaxSubmit({
                    url: 'index.php',
                    success: function () {
                        $('#printRequestSpinner').removeClass('ajax-Spinner');
                        window.top.location.href = "index.php?option=com_sfs&view=handler&layout=flightform&Itemid=118";
                        self.close();
                    }
                });
            }
        });
        $("#edit-contracted-rates").on("click", function(){
            $(this).hide();
            $("[name='rooms[srate]']").prev('span').hide();
            $("[name='rooms[sdrate]']").prev('span').hide();
            $("[name='rooms[trate]']").prev('span').hide();
            $("[name='rooms[qrate]']").prev('span').hide();
            $("[name='rooms[srate]']").prop('type', 'text');
            $("[name='rooms[sdrate]']").prop('type', 'text');
            $("[name='rooms[trate]']").prop('type', 'text');
            $("[name='rooms[qrate]']").prop('type', 'text');
        })
    });
</script>
<style>
    i.star {
        background: none;
        margin-right: 0 !important;
    }

    i.icon {
        height: 1.5em;
    }

    .block-group .block {
        padding: 15px 30px 10px 30px !important;
    }

    .help-block {
        margin-top: 0;
    }
    label.contracted-rate{
        margin-right: 0 !important;
        width: 110px !important;
    }
    input{
        width: 55px;
    }
</style>

<div id="booking-form-wrapper">

    <form id="reservationForm" action="<?php echo JRoute::_('index.php?option=com_sfs')?>" method="post"
          class="sfs-form form-vertical register-form">
        <div class="block-group">
            <div class="block border orange">
                <fieldset>
                    <legend style="margin-bottom: 10px">
                        Room management
                        <a onclick="window.parent.SqueezeBox.close();" class="sfs-button float-right"
                           style="font: inherit; font-size: 14px; margin-top: -3px; margin-right: -5px">Close</a>
                    </legend>
                    <div class="row r10 clearfix">
                        <div class="col w30">
                            <div style="color: #26bac4;">
                                <h3 style="display: inline-block; font-weight: bold">
                                    <?php echo $this->hotel->name;?>
                                </h3>
                                <span class="star star<?php echo $this->hotel->star;?>"></span>
                            </div>
                            <div style="margin-top: 15px">
                                <b>Telephone:</b> <span><?php echo $this->hotel->telephone; ?></span>
                            </div>
                            <div>
                                <b>Address:</b>
                                <span><?php echo $this->hotel->address; ?></span>
                            </div>
                        </div>
                        <?php if ($this->hotel->isContractedRates): ?>
                        <div class="col w70">
                            <div class="form-group">
                                <div class="col w70  pull-left p20">
                                    <div class="col w40">
                                        <label class="contracted-rate">Number of rooms*</label>
                                    </div>
                                    <div class="col w30  pull-left" style="margin-left: 20px; text-align: center">
                                        <label class="contracted-rate">Roomrate**</label>
                                    </div>
                                    <div class="col w10  pull-left" style="padding-top: 10px">
                                        <a href="javascript:void(0)" id="edit-contracted-rates">Edit</a>
                                    </div>
                                </div>
                            </div>


                            <?php if ((int)$this->hotel->s_rate): ?>
                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Single</label>
                                    </div>
                                    <div class="col w70  pull-left">
                                        <div class="col w50" style="margin-left: 20px">
                                            <input type="text" name="rooms[sroom]" value="">
                                        </div>
                                        <div class="col w40 pull-left">
                                            <span class="help-block" style="font-size: 16px;line-height: 40px"><?php echo $this->hotel->currency_symbol.$this->hotel->s_rate; ?></span>
                                            <input type="hidden" name="rooms[srate]"
                                                   value="<?php echo $this->hotel->s_rate; ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


                            <?php if ((int)$this->hotel->sd_rate): ?>
                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Single/Double</label>
                                    </div>
                                    <div class="col w70  pull-left">
                                        <div class="col w50" style="margin-left: 20px">
                                            <input type="text" name="rooms[sdroom]" value="">
                                        </div>
                                        <div class="col w40 pull-left">
                                            <span class="help-block" style="font-size: 16px;line-height: 40px"><?php echo $this->hotel->currency_symbol.$this->hotel->sd_rate; ?></span>
                                            <input type="hidden" name="rooms[sdrate]"
                                                   value="<?php echo $this->hotel->sd_rate; ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


                            <?php if ((int)$this->hotel->t_rate): ?>
                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Triple</label>
                                    </div>
                                    <div class="col w70  pull-left">
                                        <div class="col w50" style="margin-left: 20px">
                                            <input type="text" name="rooms[troom]" value="">
                                        </div>
                                        <div class="col w40 pull-left">
                                            <span class="help-block" style="font-size: 16px;line-height: 40px"><?php echo $this->hotel->currency_symbol.$this->hotel->t_rate; ?></span>
                                            <input type="hidden" name="rooms[trate]"
                                                   value="<?php echo $this->hotel->t_rate; ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ((int)$this->hotel->q_rate): ?>
                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Quadruple</label>
                                    </div>

                                    <div class="col w70  pull-left">
                                        <div class="col w50" style="margin-left: 20px">
                                            <input type="text" name="rooms[qroom]" value="">
                                        </div>
                                        <div class="col w40 pull-left">
                                            <span class="help-block" style="font-size: 16px;line-height: 40px"><?php echo $this->hotel->currency_symbol.$this->hotel->q_rate; ?></span>
                                            <input type="hidden" name="rooms[qrate]"
                                                   value="<?php echo $this->hotel->q_rate; ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php else: ?>
                            <div class="col w70">
                                <div class="form-group">
                                    <div class="col w70  pull-left p20">
                                        <div class="col w40">
                                            <label class="contracted-rate">Number of rooms*</label>
                                        </div>
                                        <div class="col w30  pull-left" style="margin-left: 20px; text-align: center">
                                            <label class="contracted-rate">Roomrate**</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Single</label>
                                    </div>
                                    <div class="col w70  pull-left">
                                        <div class="col w40" style="margin-left: 20px">
                                            <input type="text" name="rooms[sroom]" value="">
                                        </div>
                                        <div class="col w40 pull-left" style="margin-left: 25px">
                                            <input type="text" name="rooms[srate]" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Single/Double</label>
                                    </div>
                                    <div class="col w70  pull-left">
                                        <div class="col w40" style="margin-left: 20px">
                                            <input type="text" name="rooms[sdroom]" value="">
                                        </div>
                                        <div class="col w40 pull-left" style="margin-left: 25px">
                                            <input type="text" name="rooms[sdrate]" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Triple</label>
                                    </div>
                                    <div class="col w70  pull-left">
                                        <div class="col w40" style="margin-left: 20px">
                                            <input type="text" name="rooms[troom]" value="">
                                        </div>
                                        <div class="col w40 pull-left" style="margin-left: 25px">
                                            <input type="text" name="rooms[trate]" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col w20  pull-left">
                                        <label style="width: 30px;">Quadruple</label>
                                    </div>
                                    <div class="col w70  pull-left">
                                        <div class="col w40" style="margin-left: 20px">
                                            <input type="text" name="rooms[qroom]" value="">
                                        </div>
                                        <div class="col w40 pull-left" style="margin-left: 25px">
                                            <input type="text" name="rooms[qrate]" value="">
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <small class="help-block">
                                        * This is the number of rooms, you have agreed upon for this specific booking
                                    </small>
                                </div>

                                <div>
                                    <small class="help-block">
                                        ** This is the roomrate for this first booking at this accommodation
                                    </small>
                                </div>
                            </div>
                        </div>
                </fieldset>
            </div>

        </div>
        <div class="form-group">
            <div class="pull-right">
                <div id="printRequestSpinner" style="display: inline-block"></div>
                <button type="button" class="btn orange lg" id="btnBook">Book now and issue vouchers</button>
            </div>
        </div>
        <input type="hidden" name="option" value="com_sfs"/>
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>"/>
        <input type="hidden" name="hotel_id" value="<?php echo $this->hotel->id; ?>"/>
        <input type="hidden" name="task" value="airlinehotel.reservation"/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>