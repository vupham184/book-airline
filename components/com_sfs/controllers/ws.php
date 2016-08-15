<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';

class SfsControllerWs extends SFSController
{
	public function display(){
		parent::display();
	}

	public function syncHotels(){
		$airportIndex = JRequest::getInt('airport_index', null);
		SfsWs::syncHotels($airportIndex);
        exit(0);
    }
    public function syncAllHotels(){
        $db    = JFactory::getDbo();
        $iatacodeTable = JTable::getInstance('IATACode', 'SfsTable');
        $iatacodeTable->load(array('type' => 2));
        $query = $iatacodeTable->getDbo()->getQuery();
        $db->setQuery($query);
        $airports = $db->loadObjectList();
        $i = -1;
        foreach($airports as $air)
        {
            $i++;
            $url = JURI::root()."index.php?option=com_sfs&task=ws.syncHotels&airport_index=" . $i;
            $command = "wget " . $url;
            shell_exec($command);
        }
        exit(0);
    }
    public function updateAirportLocationCacheFile(){

        $airportLocations = SfsWs::getAllAirportLocation();
        $cacheKey = 'airport-location';

        $cacheDir = JPATH_BASE . '/cache/com_sfs/ws-airport-location/';

        if(!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true); // make dir recursive
        }

        $cacheFile = $cacheDir . $cacheKey;
        #write cache
        file_put_contents($cacheFile, serialize($airportLocations), LOCK_EX);
        exit(0);
    }
    public function updateFileViaFTP() {
        // connect and login to FTP server
        $componentParams = &JComponentHelper::getParams('com_sfs');
        $ftp_server = $componentParams->get('ftp-server');
        $ftp_username = $componentParams->get('ftp-username');
        $ftp_password = $componentParams->get('ftp-password');
        $site_suffix = $componentParams->get('ftp-directory');
        $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, $ftp_username, $ftp_password);

        //$local_file = "D:/wamp/www/joomla-sfs/ws/lib/Ws/Adapter/data/Properties.txt";
        $local_file = JPATH_ROOT . DIRECTORY_SEPARATOR . "ws/lib/Ws/Adapter/data/Properties.txt";
        $server_file = $site_suffix. "/ws/lib/Ws/Adapter/data/Properties.txt";
        if(basename(JURI::root(true)) != $site_suffix)
        {
            // download server file
            if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII))
            {
                // close connection
                ftp_close($ftp_conn);
            }
            else
            {
                // close connection
                ftp_close($ftp_conn);
                echo "Error downloading $server_file.";exit();
            }
        }
    }

	public function syncHotelsUI(){
		$db    = JFactory::getDbo();
		$iatacodeTable = JTable::getInstance('IATACode', 'SfsTable');
		$iatacodeTable->load(array('type' => 2));
		$query = $iatacodeTable->getDbo()->getQuery();
		$db->setQuery($query);
		$airports = $db->loadObjectList();
		?>
		<html>
		<head>
			<script src="https://code.jquery.com/jquery-1.11.2.min.js" type="text/javascript"></script>
			<script>
				function loadLi(){
					var el = $('#airports').find('li.not-loaded:eq(0)'),
						i = el.attr('data-index'),
						id = el.attr('data-id'),
						name = el.attr('data-name');

					if(el.length) {
						el.find('.status').text('loading...').css('color', 'red');
						$.ajax({
							url: 'index.php?option=com_sfs&task=ws.syncHotels&airport_index=' + i,
							success: function() {
								el.find('.status').text('done').css('color', 'green');
								el.removeClass('not-loaded');
								loadLi();
							}
						});
					}
				}
				$(function(){
					$('#start').click(function(){
						loadLi();
						$('#start').attr('disabled', 'disabled');
					});
				});
			</script>
		</head>
		<body>
			<button id="start" type="button" >Start</button>
			<ul id="airports">
			<?php $i = -1;?>
			<?php foreach($airports as $air) : ?>
				<?php $i++?>
				<li class="not-loaded" data-index="<?php echo $i?>" data-id="<?php echo $air->code?>" data-name="<?php echo $air->name?>">
					<span class="name"><?php echo $air->name?></span>
					<span class="status"></span>
				</li>
			<?php endforeach;?>
			</ul>
		</body>
		</html>
		<?php
		exit(0);
	}

    public function airplusCall(){
    	$voucher_id = JRequest::getInt('voucher_id');
        $airline    = JRequest::getInt('airline');
        $hotel      = JRequest::getInt('hotel');
        
        $voucher = SVoucher::getInstance($voucher_id, 'id');
        
        $options    = array(
        		'type' => JRequest::getVar('options')
        );
        
        $result = SfsWs::airplusCall($airline, $hotel, $options);
        echo json_encode($result);
        exit(0);
    }
	#public function searchHotels() {
	#	SfsWs::searchHotels('LGW', date('Y-m-d'), 1, 1);
	#	exit(0);
	#}
}

