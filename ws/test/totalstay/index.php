<?php

include dirname(__FILE__) . '/config.php';

$ws = Ws_Factory::createWS($config);

?>
<h1>::getAirports()</h1>
<?php 
$data = $ws->getAirports(5);
var_dump($data);
?>

<h1>::getBookingSources()</h1>
<?php 
$data = $ws->getBookingSources(5);
var_dump($data);
?>

<h1>::getCardTypes()</h1>
<?php 
$data = $ws->getCardTypes(5);
var_dump($data);
?>

<h1>::getCurrencies()</h1>
<?php 
$data = $ws->getCurrencies(5);
var_dump($data);
?>

<h1>::getExtraTypes()</h1>
<?php 
$data = $ws->getExtraTypes(5);
var_dump($data);
?>

<h1>::getFacilities()</h1>
<?php 
$data = $ws->getFacilities(5);
var_dump($data);
?>

<h1>::getProperties()</h1>
<?php 
$data = $ws->getProperties(5);
var_dump($data);
?>

<h1>::getPropertiesNearByAirportIATACode('TIA')</h1>
<?php 
$data = $ws->getPropertiesNearByAirportIATACode('TIA', 5);
var_dump($data);
?>

<h1>::getLocations()</h1>
<?php 
$data = $ws->getLocations(5);
var_dump($data);
?>

<h1>::getMealBasis()</h1>
<?php 
$data = $ws->getMealBasis(5);
var_dump($data);
?>

<h1>::getProductAttributes()</h1>
<?php 
$data = $ws->getProductAttributes(5);
var_dump($data);
?>

<h1>::getRoomTypes()</h1>
<?php 
$data = $ws->getRoomTypes(5);
var_dump($data);
?>

<h1>::getStarRatings()</h1>
<?php 
$data = $ws->getStarRatings(5);
var_dump($data);
?>

