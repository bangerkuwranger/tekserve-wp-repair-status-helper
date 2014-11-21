<?php
header('Content-type: application/json');
parse_str( $_SERVER['QUERY_STRING'], $query );
// include wp-load
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/tekserve-repair-status-helper/tekserve-repair-status-helper.php' ;

$sro1 = $query['sro1'];
$sro2 = $query['sro2'];
$sro3 = $query['sro3'];
$zip = $query['zip'];
$statusp = null;

if(!empty($sro1) && !empty($sro2) && !empty($sro3) && !empty($sro3)){

	$sro = $sro1.$sro2.$sro3;
	$statusp = returnRepairStatus( $sro, $zipcode );

}

echo $query['callback'] . '(' . $statusp . ');';