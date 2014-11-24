<?php
/* Server MySQL data functions 
******************/

function _checkStatus( $sro, $zip ) {

	$_server = get_option( 'tekserve_repair_status_server_setting' );
	$_login = get_option( 'tekserve_repair_status_server_login_setting' );
	$_pass = get_option( 'tekserve_repair_status_server_password_setting' );
	$_db = get_option( 'tekserve_repair_status_server_db_setting' );
	$_query = "SELECT * FROM status WHERE SROnumber='" . $sro . "' AND ZIP='" . $zip . "' LIMIT 1";
	$status_array = array();
	
	$mysqli = new mysqli( $_server, $_login, $_pass, $_db );
	
	if ($mysqli->connect_error) {
		
		$status_array['error'] = 'Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		return $status_array['error'];
				
	}// end if ($mysqli->connect_error)

	if ( $result = $mysqli->query( $_query ) ) {

		$status_array = $result->fetch_assoc();
		$result->close();
		$mysqli->close();
		return $status_array;

	}// end if ($result = $mysqli->query( $_query ))


} // end function _checkStatus( $sronum, $zipcode )

function returnRepairStatus( $sro, $zip ) {

	$status = _checkStatus( $sro, $zip );
	$json = json_encode( $status );
	if( $status == null || $json == null ) {
	
		return false;
		
	}
	else {
	
		return $json;
		
	}// end if( $status == null || $json == null )
	
} // end function returnRepairStatus( $sro, $zip )