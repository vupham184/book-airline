<?php
defined( '_JEXEC' ) or die();

define( 'YOURBASEPATH', dirname(__FILE__) );
$pageview = JRequest::getVar('view', '');

$sj_left = $this->countModules( 'left' );
if ( $sj_left ) {
    $divid = '';
} else {
    $divid = '-f';
}
$jpath = JPATH_BASE.'/components/com_sfs/models/';
//require_once YOURBASEPATH.DS.'libs'.DS.'renderer'.DS.'head.php';
//JHTML::_('behavior.mootools');
require_once( $jpath.'manager_template.php' );
$managerTemplate = new SfsModelMessage();

$user = JFactory::getUser();

if($user->id > 0){
    $air = SFactory::getAirline();
    $enableAPI = $air->params['communication_enabled'];  
}


session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
<script type="text/javascript">
    var siteurl = '<?php echo JURI::base()?>';
</script>
<style type="text/css">
    @import url('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
</style>
<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<?php /*<link href="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/css/template.gzip.php" rel="stylesheet" type="text/css" media="screen" /> */?>
<link href='//fonts.googleapis.com/css?family=Roboto:300,400,700,500|Roboto+Condensed:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/introjs.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/template-hdwebsoft.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/jmootips-min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/semantic.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/dropdown.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/system/js/fancybox/jquery.fancybox.css" type="text/css" />

<!-- <link rel="stylesheet" href="<?php echo $this->baseurl ?>/components/com_sfs/assets/css/individualpassengerpage.css" type="text/css" /> -->
<link rel="shortcut icon" href="<?php echo $this->baseurl ?>/components/com_sfs/assets/css/individualpassengerpage.css" />

<link rel="icon" id="favicon" href="" type="image/x-icon" />

<!--[if gte IE 7.0]>

	<link href="<?php //echo $this->baseurl;?>/templates/<?php //echo $this->template;?>/css/ie7.css" rel="stylesheet" type="text/css" media="screen" />
<![endif]-->

<!--[if gte IE 8.0]>
	<link href="<?php //echo $this->baseurl;?>/templates/<?php //echo $this->template;?>/css/ie8.css" rel="stylesheet" type="text/css" media="screen" />
<![endif]-->

<!-- <script src="<?php // echo $this->baseurl;?>/templates/<?php // echo $this->template;?>/js/jmootips-min.js" type="text/javascript"></script> -->
<script src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/js/intro.min.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/js/jquery.mobile-events.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/js/jquery.mobile-events.min.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl;?>/media/system/js/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/js/semantic.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/js/dropdown.js" type="text/javascript"></script>
<script src="<?php echo $this->baseurl;?>/templates/<?php echo $this->template;?>/js/jquery.form.js" type="text/javascript"></script>

<script type="text/javascript">
//  window.addEvent('load', function() {
//           var sfs_tooltip = new jmootips({
//              ToolTipClass: 'jmootips',
//              toolTipPosition:-2,
//              showDelay : 0,
//              sticky: false,
//              openOnClick: false,
//              closeMsg: 'Close',
//              loadingMsg: 'Loading ... please wait',
//              failMsg: '<b>Error:</b> Content could not be loaded',
//              duration : 100,
//              fadeDistance : 10,
//              fromTop : 5
//          });
//         });
//

jQuery.noConflict();
jQuery(document).ready(function($) {
    var $dropdownToggle = $("a.dropdownToggle");
    var $menu = $('div.dropdown-menu');
    var toggleMenu = function () {
        $dropdownToggle.toggleClass("dropdown-active");
        $menu.toggle( $dropdownToggle.hasClass('dropdown-active') );
    };
    var hideMenu = function(){
        $dropdownToggle.removeClass('dropdown-active');
        $menu.hide();
    };
    //<!-- Dropdown menu on pc -->
    if('ontouchstart' in window)
    {
        $dropdownToggle.on("tap", function(e){
            toggleMenu();
        });
        $("ul.menu-mainmenu li").on('tap',function(e){
            $("ul.menu-mainmenu li").not(this).removeClass("active clicked");
            var hasSub = $(this).find('ul > li').length > 0;
            if(hasSub) {
                if(!$(this).hasClass("clicked"))
                {
                    $(this).addClass("active clicked");
                    e.preventDefault();
                }
            } else {
                e.preventDefault();
                window.location.href = $(this).find('a').attr('href');
            }
        });
        $("body").on("tap",function() {
            hideMenu();
        });
        $('#main_menu').on("tap",function(e){
            e.stopPropagation();
        });
        $("body").on("taphold",function(e) {
            e.stopPropagation();
        });
        $('#main_menu').on("taphold",function(e) {
            e.stopPropagation();
        });
    }
    else
    {
        $dropdownToggle.on("click", function(e){
            toggleMenu();
        });
        $("body").click(function(e) {
            hideMenu();
        });
        $('#main_menu').click(function(e){
            e.stopPropagation();
        });
    }
    // check show/hide help icon
    var hasHelp = $('[data-step]').length;
    if(hasHelp) {
        $('#help-global-icon').show();
    }
    var showHelpDashboard = 'index.php?option=com_sfs&view=dashboard';

	
	$('.alert.alert-message .close').click(function(e) {
		$('#system-message-container').css('display','none');
	});
    $('.alert.alert-warning .close').click(function(e) {

        $('#system-message-container').css('display','none');
    });
});

</script>
<?php
    $data = $managerTemplate->getTemplate();

    if(!empty($data)){        
        $logo_img   = $data[0]->logo_airline_desktop; 
        $hearder_img = $data[0]->header_airline_desktop;
        $color_WR   = $data[0]->desk_color_wrapper;
        $color_MB   = $data[0]->desk_color_MB;
        $color_MT   = $data[0]->desk_color_MT;
        $color_MTO  = $data[0]->desk_color_MTO;        
        $color_MA   = $data[0]->desk_color_MA;
        $color_MAO  = $data[0]->desk_color_MAO;
        $color_SB   = $data[0]->desk_color_SB;
        $color_ST   = $data[0]->desk_color_ST;
        $color_STO  = $data[0]->desk_color_STO;
        $boxshadow  = "box";
    }else{
        $hearder_img = "templates/sfs_j16_hdwebsoft/images/new/retina-header-bg.png";
        $logo_img = "templates/sfs_j16_hdwebsoft/images/logo.jpg"; 
        $color_MB = "#1f1f1f";
        $boxshadow = "";

    }
        
?>
<style type="text/css">
#header{
    background-color: <?php echo $color_SB; ?>;   
}

#bg_footer{
    background: <?php echo $color_SB; ?> none repeat scroll 0 0;
    border-bottom: 5px solid #2f2f2f;
    float: left;
    padding: 20px 0 30px;
    width: 100%;
}

#wrapper{
    background: <?php echo $color_WR ; ?>;
    width: 100%;
    height: 160px;
}
#logo{
    position: absolute;top: 0;
}
.wellcomepart span{
    color: #fff !important;
}
/*.hd-sidebar{
    background: <?php echo $color_MB; ?> none repeat scroll 0 0;
    color: #fff;
    float: left;
    padding: 5px;
    position: absolute;
    top: 50px;
    width: 185px;
    z-index: 1000;
    box-shadow: 2px 2px 2px #888888;
}*/
<?php if($boxshadow == ""): ?>
    .hd-sidebar{
        background: <?php echo $color_MB; ?> none repeat scroll 0 0;
        color: #fff;
        float: left;
        padding: 5px;
        position: absolute;
        top: 50px;
        width: 185px;
        z-index: 1000;    
    }
    <?php else: ?>
    .hd-sidebar{
        background: <?php echo $color_MB; ?> none repeat scroll 0 0;
        color: #fff;
        float: left;
        padding: 5px;
        position: absolute;
        top: 50px;
        width: 185px;
        z-index: 1000;
        box-shadow: 2px 2px 2px #888888;
    }
<?php endif; ?>
.dropdownToggle {
    border-top: 1px solid rgba(204, 204, 204, 0.2);
    clear: both;
    color: <?php echo $color_MT; ?> !important;
    display: block;
    font-weight: 700;
    padding: 10px 0 7px 7px;
    position: relative;
    text-transform: uppercase;
}
ul.menu-mainmenu li a, ul.menu-mainmenu li button.button {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    color: <?php echo $color_MT; ?>;
    cursor: pointer;
    display: block;
    font-family: "Roboto Condensed",sans-serif;
    font-size: 13px;
    outline: medium none;
    overflow: hidden;
    padding: 0;
    text-decoration: none;
    transition: all 0.4s ease 0s;
    white-space: nowrap;
}

ul.menu-mainmenu > li::after {
    color: <?php echo $color_MTO; ?> !important;
    content: "";
    display: block;
    font: 22px/1 FontAwesome;
    position: absolute;
    right: 5px;
    top: 12px;
    transform: rotate(0deg);
    transition: all 0.4s ease 0s;
}

ul.menu-mainmenu li.active.parent > a{
    color: <?php echo $color_MTO; ?>;
}

ul.menu-mainmenu > li > ul {
    background: <?php echo $color_SB; ?> none repeat scroll 0 0;
    border-radius: 5px;
    display: block;
    left: 100%;
    min-width: 200px;
    opacity: 0;
    padding: 10px 0 !important;
    position: absolute;
    text-transform: none;
    top: -1000px;
    transition: all 0.4s ease 0s;
    z-index: 999;
}

ul.menu-mainmenu li.parent:hover > a, ul.menu-mainmenu li:hover > a
{
    color: <?php echo $color_MTO; ?>;
}


ul li.active.current  > a{
    color: <?php echo $color_STO; ?>;
}
ul.menu-mainmenu li.parent ul li:hover > a{
    color: <?php echo $color_STO; ?>;
}


#infosendmail{
    position: fixed;
    top: 50px;   
    right: 177px;    
}

#closeForm{
   /* position:absolute; */
    text-align: center; 
    width: 20px; height: 20px; 
    background:#cccccc; 
    margin: 15px 20px 15px 0;    
    color: #000;
    cursor: pointer;
    border-radius: 2px;
    z-index: 1000;
    float: right;
}

.notificationTitle{
    color: #6c6d6d;
    float: left;
    font-size: 17px;
    padding-left: 35px;
    padding-top: 12px;
    text-align: center;
    width: 90%;
}
#popupShow{
    display:none; 
    background:#d5e2e8; position: absolute; 
    right:0; height: 350px; width: 480px; 
    overflow: hidden;
    margin-top: 20px;

}

ul.menu-mainmenu > li::after {
    color: <?php echo $color_MTO; ?> !important;
    content: "";
    display: block;
    font: 22px/1 FontAwesome;
    position: absolute;
    right: 5px;
    top: 12px;
    transform: rotate(0deg);
    transition: all 0.4s ease 0s;
}

ul.menu-mainmenu li.active.parent > a{
    color: <?php echo $color_MTO; ?>;
}

ul.menu-mainmenu > li > ul {
    background: <?php echo $color_SB; ?> none repeat scroll 0 0;
    border-radius: 5px;
    display: block;
    left: 100%;
    min-width: 200px;
    opacity: 0;
    padding: 10px 0 !important;
    position: absolute;
    text-transform: none;
    top: -1000px;
    transition: all 0.4s ease 0s;
    z-index: 999;
}

ul.menu-mainmenu li.parent:hover > a, ul.menu-mainmenu li:hover > a
{
    color: <?php echo $color_MTO; ?>;
}


ul li.active.current  > a{
    color: <?php echo $color_STO; ?>;
}
ul.menu-mainmenu li.parent ul li:hover > a{
    color: <?php echo $color_STO; ?>;
}

h1, h3{position: relative;}
</style>
</head>
<body id="bd">
<?php $user = JFactory::getUser(); ?>
<?php
    $user = JFactory::getUser();
    $db   = JFactory::getDbo();

    $query = 'SELECT * FROM #__sfs_communication_mail WHERE status=1 AND emailTo = "' . $user->email . '"';
    $db->setQuery($query);
    $result = count($db->loadObjectList());

?>
<?php if($user->name != "") : ?>
<script type="text/javascript">
    
    jQuery(function($){
        setInterval(function(){
          $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=dashboard.resultNotifi'; ?>",
                type:"POST",                
                dataType: 'text',
                success:function(response){                     
                    if(response > 0){                           
                        $('#imgBlue').addClass('class_name');      
                        $('#imgBlue').attr('src','<?php echo JUri::root() . "media/media/images/notifi_red.png"; ?>');                        
                        $('#imgRed').attr('src','<?php echo JUri::root() . "media/media/images/notifi_red.png"; ?>');
                        $('#favicon').attr('href', '<?php echo $this->baseurl ?>/media/media/images/notifi.ico');    
                    }                    
                }
            });
        },10000);

        $('#closeForm').click(function(event) {   
            $("#imgBlue").attr("onClick", " ") ; 
            $("#imgRed").attr("onClick", " ") ;  
            $.ajax({
                url:"<?php echo JURI::base().'index.php?option=com_sfs&task=dashboard.updateStatus'; ?>",
                type:"POST",                
                dataType: 'text',
                success:function(data){  
                    $("#infoIframe").attr('src', '<?php echo JUri::root() ?>index.php?option=com_sfs&amp;view=dashboard&amp;layout=notification&amp;tmpl=component');
                    $('#popupShow').css({
                        'display': 'none'
                    });
                    setTimeout(function(){ 
                        $("#imgBlue").attr("onClick", "showPopup()") ; 
                        $("#imgRed").attr("onClick", "showPopup()") ;
                    },1000);
                      
                }
            });
            $('#favicon').attr('href', ''); 
            $('#imgRed').addClass('class_name');
            $('#imgRed').attr('src','<?php echo JUri::root() . "media/media/images/notifi_blue.png"; ?>');  
            $('#imgBlue').attr('src','<?php echo JUri::root() . "media/media/images/notifi_blue.png"; ?>');                
            
        });

    });

    function changeNotifi(){
        jQuery.ajax({
            url:"<?php echo JURI::base().'index.php?option=com_sfs&task=dashboard.updateStatus'; ?>",
            type:"POST",                
            dataType: 'json',
            success:function(response){  

            }
        });
        jQuery('#imgRed').addClass('class_name');
        jQuery('#imgRed').attr('src','<?php echo JUri::root() . "media/media/images/notifi_blue.png"; ?>');     
    }

    function showPopup(){          

        jQuery.ajax({
            url:'<?php echo JUri::root() ?>index.php?option=com_sfs&amp;view=dashboard&amp;layout=notification&amp;tmpl=component',
            type:"POST",                
            dataType: 'html',
            success:function(data){ 
                jQuery.ajax({
                    url:"<?php echo JURI::base().'index.php?option=com_sfs&task=dashboard.resultNotifi'; ?>",
                    type:"POST",                
                    dataType: 'text',
                    success:function(response){                     
                        if(response > 0){                           
                             jQuery("#infoIframe").attr('src', '<?php echo JUri::root() ?>index.php?option=com_sfs&amp;view=dashboard&amp;layout=notification&amp;tmpl=component');
                        }                    
                    }
                });

                jQuery('#popupShow').css({'display': 'block'}); 
                var valueHeight = jQuery("#infoIframe").contents().find("#mainNotifi").height() + 80;        
                if(valueHeight < 530){
                    jQuery("#popupShow").css({'height': 'auto'});
                }else{
                    jQuery("#popupShow").css({'height': '550px'});
                } 
            }
        });
                       
    }


</script>
<?php endif; ?>
<div id="background">

    <div id="header">
        <div class="container">
            <a class="pull-right helper" style="z-index: 0;" href="javascript:void(0);" id="help-global-icon" style="display: none" onclick="javascript:introJs().start()"><!-- <i class="fa fa-question-circle"></i> --></a>
            <?php if($enableAPI == 1) : ?>
                <div id="notifiEmail" style="float:right; position: absolute; right: 190px;margin-top: 10px;">
                    <?php if($user->id != 0) : ?>
                        <?php if($result > 0) : ?>                            
                            <script type="text/javascript">
                                jQuery('#favicon').attr('href', '<?php echo $this->baseurl ?>/media/media/images/notifi.ico');  
                            </script>
                            <img id="imgRed" onclick="showPopup()" style="cursor:pointer;" src="<?php echo JUri::root() . 'media/media/images/notifi_red.png'; ?>">
                        <?php else: ?>                           
                            <img id="imgBlue" style="cursor:pointer;" onclick="showPopup()" src="<?php echo JUri::root() . 'media/media/images/notifi_blue.png'; ?>">
                        <?php endif; ?>
                    
                    <?php endif  ?>
                    <div id="popupShow"  style="z-index: 1000;">

                        <div style="background:#d5e2e8 ; width:100%; float: left;border-bottom:1px solid #dfdfdf">
                            <div class="notificationTitle">Your Messages / Notifications</div>
                            <div id="closeForm">X</div>
                        </div>
                        
                        <div style="width:100%; float: left;">
                            <iframe id="infoIframe" style="width:480px; height:500px; border-radius: 3px;" src="<?php echo JUri::root() ?>index.php?option=com_sfs&amp;view=dashboard&amp;layout=notification&amp;tmpl=component"></iframe>
                        </div>
                        

                    </div>
                    
                </div>
            <?php endif; ?>
            
            <?php if ($this->countModules( 'top' )) : ?>
                <div id="header_right">
                    <div class="">
                        <jdoc:include type="modules" name="top" style="raw"/>
                    </div>
                </div>
            <?php endif; ?>
            <jdoc:include type="modules" name="airport" />

        </div>
        <?php if(!empty($data)) : ?>
            <div style="position: absolute; top: 64px;">
                <img src="<?php echo JRoute::_($hearder_img, false); ?>" width="100%" >
            </div>
        <?php endif; ?>
    </div>
    <div id="wrapper">
        <?php if(!empty($data)) : ?>
            <div style="position: absolute; top: 64px; z-index: -1000;">
                <img src="<?php echo JRoute::_($hearder_img, false); ?>" width="100%" >
            </div>
        <?php endif; ?>
        <div class="container clearfix">

            <div id="logo">
                <!-- <a href="index.php"><img src="<?php //echo $this->baseurl;?>/templates/<?php //echo $this->template;?>/images/logo.jpg" alt="Stranded Flight Solutions"/></a> -->
                <a href="index.php"><img src="<?php echo JRoute::_($logo_img, false); ?>" style="width:185px;"/></a>
            </div>

            <div class="hd-sidebar">
                <div id="main_menu">                    
                    <a href="javascript:void(0)" class="dropdownToggle">Menu</a>
                    <div class="dropdown-menu">
                        <jdoc:include type="modules" name="main_menu"/>
                    </div>
                </div>
            </div>
            <div id="wrap_container">
                <jdoc:include type="message" />
                <jdoc:include type="component" />
            </div>
            
        </div>
    </div>


		
</div>
<div id="bg_footer"><div id="wrap_footer">
    <?php if ($this->countModules( 'bottom' )) : ?>
        <div id="bottom_menu"><div class="padding">
            <jdoc:include type="modules" name="bottom" style="raw"/>
        </div></div>
    <?php endif; ?>
    <div id="footer"><div class="padding">
        &copy; <?php echo JFactory::getDate()->format('Y');?> SFS
    </div></div>
</div></div>
</body>
</html>