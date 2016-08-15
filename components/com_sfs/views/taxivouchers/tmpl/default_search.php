<?php
defined('_JEXEC') or die;
?>
<div>
    <div class="fs-16 midmarginbottom"><?php echo JText::_('COM_SFS_SEARCH');?></div>
</div>
<div class="clear"></div>
    
<div class="dateon float-left">
    <div class="fs-14"><?php echo JText::_('COM_SFS_FROM');?>:</div>
    <?php SfsHelperField::getCalendar('date_from', $this->state->get('block.from'));?>
</div>

<div class="dateto float-left">
    <div class="fs-14"><?php echo JText::_('COM_SFS_TO');?>:</div>
    <?php SfsHelperField::getCalendar('date_to', $this->state->get('block.to'));?>
</div>

<div class="blockcode float-left">
    <div class="fs-14"><?php echo JText::_('COM_SFS_BLOCK_CODE');?>:</div>
    <input type="text" name="blockcode" class="inputbox" value="<?php echo $this->state->get('block.code')?>" />
</div>
     
<?php
$companies = $this->airline->getTaxiCompanies();
if( count($companies) > 1 ) :
?>
<div class="air-status float-left">
    <div class="fs-14">Select taxi company:</div>
    <select name="taxi_id" style="width:120px; padding:2px;">
    	<option value=""></option>
    	<?php foreach ($companies as $company) : ?>
    		<option value="<?php echo $company->taxi_id;?>"<?php if( (int)$this->state->get('block.taxi_id') == (int)$company->taxi_id ) echo ' selected="selected"'; ?>>
    			<?php echo $company->name;?>
    		</option>
        <?php endforeach;?>
    </select>
</div>
<?php endif;?>
<div class="buttons float-left">
    <button type="submit" class="btn orange lg" ><?php echo JText::_('COM_SFS_SEARCH');?></button>
    <button type="reset" class="btn orange lg"><?php echo JText::_('COM_SFS_RESET');?></button>
</div>
