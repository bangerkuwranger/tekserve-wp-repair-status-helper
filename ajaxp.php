<?php

/*****************

This file is called by statuscheck.js in function queryAjax() to return 

*****************/

header('Content-type: application/json');
parse_str( $_SERVER['QUERY_STRING'], $query );
// include wp-load
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/tekserve-repair-status-helper/ajaxhelper.php' ;

$sro = $query['sro'];
$zip = $query['zip'];
$statusp = null;

if( !empty( $query['key'] ) && ( empty( $sro ) || empty( $zip ) ) ) {
	$key = base64_decode( $query['key'] );
	parse_str( $key , $query );
	var_dump($key);

	$sro = $query['sro'];
	$sro1 = substr( $sro, 0, 1);
	$sro2 = substr( $sro, 1, 3);
	$sro3 = substr( $sro, 4, 3);
	$zip = $query['zip'];
	$statusp = returnRepairStatus( $sro, $zip );
	echo $sro1.'-'.$sro2.'-'.$sro3;
}

else if( !empty($sro) && !empty($zip) ) {

	$statusp = returnRepairStatus( $sro, $zip );

}// end if( !empty($sro) && !empty($zip) )



echo $query['callback'] . '(' . $statusp . ');';