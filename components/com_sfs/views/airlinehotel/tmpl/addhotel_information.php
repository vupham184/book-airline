<legend>
    <span class="text_legend">Hotel</span>
</legend>
<fieldset>
    <div class="col w80 pull-left p20">
            <div class="form-group">
                <label><?php echo JText::_('COM_SFS_HOTEL_NAME')?></label>
                <div class="col w60">
                    <input type="text" size="30" class="required" name="hotel[name]"/>
                </div>
            </div>
            <div class="form-group">
                <label>Stars</label>
                <div class="col w60">
                    <div class="ui dropdown selection" style="min-width: 155px">
                        <input type="hidden" name="hotel[star]" value="3">
                        <div class="default text" style="color: #000">
                            <i class="star icon"></i>
                            <i class="star icon"></i>
                            <i class="star icon"></i>
                        </div>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item" data-value="3">
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                            </div>
                            <div class="item" data-value="4">
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                            </div>
                            <div class="item" data-value="5">
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                                <i class="star icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <div class="col w60">
                    <input type="text" size="30" name="hotel[address]"/>
                </div>
            </div>
            <div class="form-group">
                <label>City</label>
                <div class="col w60">
                    <input type="text" size="30" name="hotel[city]"/>
                </div>
            </div>
            <div class="form-group">
                <label>Telephone</label>
                <div class="col w60">
                    <input type="text" size="30" name="hotel[phone]"/>
                </div>
            </div>
            <div class="form-group">
                <label>Fax</label>
                <div class="col w60">
                    <div class="col w30 pull-left" style="padding: 0;">
                        <input type="text" size="30" class="required" name="hotel[fax_code]"/>
                    </div>
                    <div class="col w70" style="padding-left: 15px; padding-right: 0;">
                        <input type="text" size="30" class="required" name="hotel[fax_number]"/>
                    </div>
                    <div class="col w30 pull-left" style="padding: 0;">
                        <small class="help-block">int. code <br/>(example: 001)</small>
                    </div>
                    <div class="col w70" style="padding-left: 15px; padding-right: 0;">
                        <small class="help-block">local number (without the first 0)</small>
                    </div>

                </div>
            </div>
            <div class="form-group">
                <label>Email address</label>
                <div class="col w60">
                    <input type="text" size="30" class="required" name="hotel[email]"/>
                </div>
            </div>
        </div>

</fieldset>