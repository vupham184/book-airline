<style>
    .form-group label{
        width: 110px !important;
    }
</style>
<legend>
    <span class="text_legend">Room management</span>
</legend>
<fieldset>
    <div class="col w80 pull-left p20">
        <div class="form-group">
            <label></label>
            <div class="col w60">
                <div class="col w30">
                    <label>Number of rooms*</label>
                </div>
                <div class="col w30 pull-left p10">
                    <label>Roomrate**</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Single</label>
            <div class="col w60">
                <div class="col w30">
                    <input type="text" name="rooms[sroom]" class="validate-digits smaller-size">
                </div>
                <div class="col w30 pull-left p10">
                    <input type="text" name="rooms[srate]" class="validate-digits smaller-size">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Double</label>
            <div class="col w60">
                <div class="col w30">
                    <input type="text" name="rooms[sdroom]" class="validate-digits smaller-size">
                </div>
                <div class="col w30 pull-left p10">
                    <input type="text" name="rooms[sdrate]" class=validate-digits smaller-size">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Triple</label>
            <div class="col w60">
                <div class="col w30">
                    <input type="text" name="rooms[troom]" class="validate-digits smaller-size">
                </div>
                <div class="col w30 pull-left p10">
                    <input type="text" name="rooms[trate]" class="validate-digits smaller-size">
                </div>
            </div>
        </div>

        <div class="form-group">
        <label>Quadruple</label>
        <div class="col w60">
            <div class="col w30">
                <input type="text" name="rooms[qroom]" class="validate-digits smaller-size">
            </div>
            <div class="col w30 pull-left p10">
                <input type="text" name="rooms[qrate]" class="validate-digits smaller-size">
            </div>
        </div>
    </div>

        <div class="form-group">
            <div class="col w80 pull-left">
                <small class="help-block">
                    * This is the number of rooms, you have agreed upon for this specific booking
                </small>
            </div>
        </div>

        <div class="form-group">
            <div class="col w80 pull-left">
                <small class="help-block">
                    ** This is the roomrate for this first booking at this accommodation
                </small>
            </div>
        </div>
    </div>
</fieldset>

