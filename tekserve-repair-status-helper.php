<?php
/**
 * Plugin Name: Tekserve Repair Status Helper for Wordpress
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Support structure to get repair status data and set server
 * Version: 0.1
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 */
/*
The MIT License (MIT)
Copyright (c) 2014 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/* Server Settings 
******************/

function tekserve_repair_status_settings() {

	//create settings section
	add_settings_section( 'tekserve_repair_status_settings_section', 'Tekserve Repair Status', 'tekserve_repair_status_settings_section_callback', 'general');

	//create setting options field for server address
	add_settings_field( 'tekserve_repair_status_server_setting', 'Server Address', 'tekserve_repair_status_server_setting_callback', 'general', 'tekserve_repair_status_settings_section' );
	
	//register server address setting
	register_setting( 'general', 'tekserve_repair_status_server_setting' );
	
	//create setting options field for server login
	add_settings_field( 'tekserve_repair_status_server_login_setting', 'Server Login Account Name', 'tekserve_repair_status_server_login_setting_callback', 'general', 'tekserve_repair_status_settings_section' );
	
	//register server login setting
	register_setting( 'general', 'tekserve_repair_status_server_login_setting' );
	
	//create setting options field for server password
	add_settings_field( 'tekserve_repair_status_server_password_setting', 'Server Login Password', 'tekserve_repair_status_server_password_setting_callback', 'general', 'tekserve_repair_status_settings_section' );
	
	//register server password setting
	register_setting( 'general', 'tekserve_repair_status_server_password_setting' );
	
	//create setting options field for db name
	add_settings_field( 'tekserve_repair_status_server_db_setting', 'Server Database Name', 'tekserve_repair_status_server_db_setting_callback', 'general', 'tekserve_repair_status_settings_section' );
	
	//register db name setting
	register_setting( 'general', 'tekserve_repair_status_server_db_setting' );

}// end function tekserve_repair_status_settings()

//generate settings menu in admin
add_action( 'admin_init', 'tekserve_repair_status_settings' );

function tekserve_repair_status_settings_section_callback() {

	echo '<p>Enter the server and database info for the Repair Status MySQL server. Login and Pass are for MySQL, not server itself.</p>';

}// end function tekserve_repair_status_settings_section_callback()

function tekserve_repair_status_server_setting_callback() {

		echo "<p><input size='90' name='tekserve_repair_status_server_setting' id='tekserve_repair_status_server_setting' type='url' value='" . get_option( 'tekserve_repair_status_server_setting' ) . "' /></p>";

}// end function tekserve_repair_status_server_setting_callback()

function tekserve_repair_status_server_login_setting_callback() {

		echo "<p><input size='90' name='tekserve_repair_status_server_login_setting' id='tekserve_repair_status_server_login_setting' value='" . get_option( 'tekserve_repair_status_server_login_setting' ) . "' /></p>";

}// end function tekserve_repair_status_server_login_setting_callback()

function tekserve_repair_status_server_password_setting_callback() {

		echo "<p><input size='90' name='tekserve_repair_status_server_password_setting' id='tekserve_repair_status_server_password_setting' type='password' value='" . get_option( 'tekserve_repair_status_server_password_setting' ) . "' /></p>";

}// end function tekserve_repair_status_server_password_setting_callback()

function tekserve_repair_status_server_db_setting_callback() {

		echo "<p><input size='90' name='tekserve_repair_status_server_db_setting' id='tekserve_repair_status_server_db_setting' value='" . get_option( 'tekserve_repair_status_server_db_setting' ) . "' /></p>";

}// end function tekserve_repair_status_server_db_setting_callback()

/* Server MySQL data functions 
******************/

function _checkStatus( $sro, $zip ) {

	$_server = get_option( 'tekserve_repair_status_server_setting' );
	$_login = get_option( 'tekserve_repair_status_server_login_setting' );
	$_pass = get_option( 'tekserve_repair_status_server_password_setting' );
	$_db = get_option( 'tekserve_repair_status_server_db_setting' );
	$_query = "SELECT * FROM status WHERE SROnumber='" . $sro . "' AND ZIP='" . $zip . "' LIMIT 1";
	$status_array = array();
	
	mysqli_report(MYSQLI_REPORT_STRICT);
	try {
		$mysqli = new mysqli( $_server, $_login, $_pass, $_db );
	}
	catch( Exception $e ) {

// 		if ($mysqli->connect_error) {
		
			$status_array['error'] = 'Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
				
// 		}// end if ($mysqli->connect_error)
		
		return $status_array['error'];
	} 

	if ( $result = $mysqli->query( $_query ) ) {

		$status_array = $result->fetch_assoc();
		$result->close();

	}// end if ($result = $mysqli->query( $_query ))

	//return 'Success... ' . $mysqli->host_info . "\n";

	$mysqli->close();
	
	return $status_array;

} // end function _checkStatus( $sronum, $zipcode )

function returnRepairStatus( $sro, $zip ) {

	$status = _checkStatus( $sro, $zip );
	$json = json_encode( $status );
	
	return $json;
// 	return $status;
	
} // end function returnRepairStatus( $sro, $zip )