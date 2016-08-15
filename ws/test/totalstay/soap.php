<?php

include dirname(__FILE__) . '/config.php';

$ws = Ws_Factory::createWS($config);

?>
<h1>::Try post()</h1>
<?php 

$r = $ws->getPropertyByPreferenceID(68779)
?>
	<?php print_r($r);?>
