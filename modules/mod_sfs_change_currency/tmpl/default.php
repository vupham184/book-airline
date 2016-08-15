<?php
defined('_JEXEC') or die;

?>
<script>
    jQuery.noConflict();
    jQuery(function($){

        $(document).ready(function(){
            $('#form-change-currency .dropdown').dropdown({
                onChange: function (val,code) {
                	
					$('input[name="currency_code"]').remove();

                    var $form = $(".curr #form-change-currency");

                    $('<input />').attr('type', 'hidden')
                        .attr('name', "currency_code")
                        .attr('value', code)
                        .appendTo($form);

                   $form.submit();
                }
            });   	
        })
    })
</script>
<style>
	.curr span.text{
		margin-right: 30px
	} 
</style>
<?php if (count($currency_list)): ?>
	<div class="curr" style=" float: right; margin-top: 20px;">
	   <form action="<?php echo JRoute::_('index.php');?>" method="post" id="form-change-currency">
	       
	        <div class="ui floating dropdown labeled search icon button" style=" top: 15px;" data-step="1" >
				<span class="text" data-value= '<?php echo $airline_currency_id; ?>' data-code = "<?php echo $airline_currency_code; ?>">
				<?php echo $airline_currency_code;?>	
				</span>

		        <div class="menu">
	                <?php foreach ($currency_list as $key => $value): ?>
	                    <?php if($airline_currency_code == $value->code) :?>

	                        <div class="item " data-value="<?php echo $value->id;?>" data-code = "<?php echo $value->code; ?>">
	                        	<?php if (isset($value->flag) && !empty($value->flag)): ?>
	                        		<?php if ($value->code == 'EUR'): ?>
	                        			<img src="<?php echo JURI::root().'images/flag_icons/europeanunion.png'; ?>" alt="">
	                        		<?php else: ?>
	                        			<img src="<?php echo JURI::root().$value->flag; ?>" alt="">
	                        		<?php endif ?>
	                        	<?php endif ?>
	                        	<strong><?php echo $value->code; ?></strong>
	                        </div>

	                    <?php else:?>

	                        <div class="item" data-value="<?php echo $value->id;?>" data-code = "<?php echo $value->code; ?>">
	                        	<?php if (isset($value->flag) && !empty($value->flag)): ?>
	                        		<?php if ($value->code == 'EUR'): ?>
	                        			<img src="<?php echo JURI::root().'images/flag_icons/europeanunion.png'; ?>" alt="">
	                        		<?php else: ?>
	                        			<img src="<?php echo JURI::root().$value->flag; ?>" alt="">
	                        		<?php endif ?>
	                        	<?php endif ?>
	                        	<?php echo $value->code; ?>
	                        </div>
	                        
	                    <?php endif?>
	                <?php endforeach;?>
	            </div>
	            <i class="fa fa-caret-down" aria-hidden="true"></i>

	        </div>
	        
	        <input type="hidden" name="task" value="changeCurrency" />
	        <input type="hidden" name="type" value="<?php echo $type?>" />
	        <input type="hidden" name="redirect_link" value="<?php echo JUri::getInstance(); ?>" />
	    </form>
	</div>

<?php endif ?>
