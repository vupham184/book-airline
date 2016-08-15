/**
 * 
 */
 
function sfsPopupCenter(pageURL,title,w,h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	//var props = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no";
	//props += "width="+w+", height="+h+", top="+top+", left="+left; 
	win = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	return win;
}
					
var SFSCore = new Class({
	
	processVoucher : function(element) {
		var emailVForm   = document.id('voucherPrintForm'),
		emailVFormResult = document.id('voucherPrintFormResult');

		var voucherPrintFormValidate = new Form.Validator(emailVForm);

		new Form.Request(emailVForm, emailVFormResult, {
		    requestOptions: {
		    	useSpinner: false
		    },			
		    onSend: function(){
		    	
		    	var insertNamesForm1   = document.id('insertNamesForm');
				
		    	if(  insertNamesForm1 ){
					var insertFormRequest1 = new Form.Request(insertNamesForm1, $('testre'),  {					
					    requestOptions: {
					    	useSpinner: false
					    },	
					    resetForm : false				    
					});	
					insertFormRequest1.send();
		    	}
				
		    	emailVForm.setStyle('display','none');
		    	$('closeVoucherPrintForm').setStyle('display','none');
		    	$('v-spinner').setStyle('display','block');
		    },
		    onComplete: function(){		    	
		    	$('sfs-voucher-print-form').destroy();
		    	//$('v-spinner').setStyle('display','none');
		    	//$('closeVoucherPrintForm').setStyle('display','block');
		    }
		});	
		
		$('closeVoucherPrintForm').addEvent('click', function(event) {
			$('sfs-voucher-print-form').destroy();
		});
			
	}
	
});


var TextLimiter = new Class({
    
    //implements
    Implements: [Options, Events],

    //options
    options: {
        textAreaClass: 'limiter',
        textLengthAttr: 'rel',
        counterId : 'counter_div',
        leftPosition:0,
        topPosition:0
    },
    //initialization
    initialize: function(options) {
        this.setOptions(options);
        this.AddDiv();
        this.assignEvents();
    },
    assignEvents: function() {
        $$('.'+this.options.textAreaClass).each(function(ele){
            var $this = this;
            ele.addEvents({
                focus: function(){
                    if(ele.retrieve('pos-left'))
                    {
                        var posleft = ele.retrieve('pos-left');    
                    }
                    else
                    {
                        var posleft = parseInt(ele.getPosition().x)+parseInt(ele.getSize().x) +'px';
                        ele.store('pos-left', posleft);
                    }
                    if(ele.retrieve('pos-top'))
                    {
                        var postop = ele.retrieve('pos-top');    
                    }
                    else
                    {
                        var postop = parseInt(ele.getPosition().y)+parseInt(ele.getSize().y)-parseInt($($this.options.counterId).getStyle('height').toInt())+ 'px';
                        ele.store('pos-top', postop);
                    }
                    
                    $($this.options.counterId).setStyles({
                        'left': posleft,
                        'top' : postop,
                        'visibility':'visible'
                    });

                    lncnt = ele.value.split('\n').length;
                    
                    $($this.options.counterId).innerHTML = parseInt(ele.get($this.options.textLengthAttr))-parseInt(lncnt);
                    $($this.options.counterId).innerHTML = 'Lines Limit: '+$($this.options.counterId).innerHTML;
                    
                },
                blur: function(){
                    $($this.options.counterId).setStyle('visibility','visible');
                },
                keyup : function(){
                	lncnt = ele.value.split('\n').length;
                	
                    if( parseInt(lncnt) > parseInt(ele.get($this.options.textLengthAttr)) )
                    {       
                    	lastbreakpost = 0;
                		for (var i = 0; i < ele.value.length; i++){
                			if( ele.value[i] == '\n' ){
                				lastbreakpost = i;
                			}	
                		}
                        ele.value = ele.value.substring(0,lastbreakpost);
                    }
                    else
                    {                    	
                        $($this.options.counterId).innerHTML = parseInt(ele.get($this.options.textLengthAttr))-parseInt(lncnt);
                        $($this.options.counterId).innerHTML = $($this.options.counterId).innerHTML;
                        $($this.options.counterId).innerHTML = 'Lines Limit: '+$($this.options.counterId).innerHTML;
                    }
                    
                }
               
            });

            
                        
        },this);    
    },
    AddDiv: function() {
        // Adding the the div with the given id which will behave for the limiter
        var CounterDiv  = new Element('div', {id: this.options.counterId});            
        $$('body').adopt(CounterDiv);
        CounterDiv.setStyles({
            'opacity': 0.5,
            'visibility': 'hidden'
        });
    }
    
});

function linecnt(selected){
	var text = document.getElementById('input').value;
	if(selected != 0) text = selected;
	var textlength = text.length;
	var lncnt = text.split('\n').length;
	if(textlength > 0) document.getElementById('line_count').value = lncnt; else document.getElementById('line_count').value = '0';
}

