<?php
header('Content-type: application/json');
parse_str( $_SERVER['QUERY_STRING'], $query );
// include wp-load
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/tekserve-repair-status-helper/ajaxhelper.php' ;

// $sro1 = $query['sro1'];
// $sro2 = $query['sro2'];
// $sro3 = $query['sro3'];
$sro = $query['sro'];
$zip = $query['zip'];
$statusp = null;

if( !empty($sro) && !empty($zip) ) {

	$statusp = returnRepairStatus( $sro, $zip );

}// end if( !empty($sro) && !empty($zip) )

echo $query['callback'] . '(' . $statusp . ');';