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
	add_settings_section( 'tekserve_repair_status_settings_section', 'Tekserve Repair Status', 'tekserve_repair_status_settings_section_callback', 'general' );

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



//callbacks for admin fields

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




/* Shortcode for tekserve repair status checker
e.g. [repairstatus]
******************/

function repair_status_checker( $atts ) {

	//set 'prefilled' to false by default
	$prefilled = false;
	
	//check for 'key', get sro & zip from link to prefill form, set 'prefilled' to true
	parse_str( $_SERVER['QUERY_STRING'], $query );
	if( !empty( $query['key'] ) && ( empty( $sro ) || empty( $zip ) ) ) {
	
		$key = base64_decode( $query['key'] );
		parse_str( $key , $query );
		$sro = $query['sro'];
		$sro1 = substr( $sro, 0, 1);
		$sro2 = substr( $sro, 1, 3);
		$sro3 = substr( $sro, 4, 3);
		$zip = $query['zip'];
		$prefilled = true;
	
	}	//end if( !empty( $query['key'] ) && ( empty( $sro ) || empty( $zip ) ) )
	
	//load js and pass uri and 'whether prefilled' to script
	wp_register_script( 'tekserverepairstatusjs', plugins_url( '/statuscheck.js', __FILE__ ), array( 'jquery' ) );
	$plugin = plugins_url( '', __FILE__ );
	$theme = get_stylesheet_directory_uri();
	$status_uris = array(
		'pluginUri'	=> $plugin,
		'themeUri'	=> $theme,
		'prefilled'	=> $prefilled
	);
	wp_localize_script( 'tekserverepairstatusjs', 'tekRepairStatusUris', $status_uris );
	wp_enqueue_script( 'tekserverepairstatusjs' );
	
	//output html. there are better ways to do this, but leaving it for now.
	return "<a id='repairstatus' name='repairstatus'></a><div id='status-wrapper'><div id='status-content'><p>Please use the form below to check the status of your repair at Tekserve. Just enter your invoice number (found in the upper right corner of your receipt) and billing zip code, then click 'SUBMIT'.</p><img id='statusimg' src='' /><div id='fail-msg' style='display:none'><p style='padding:5px 0px'>The information you provided does not match what we have on record.<br />Please double check your information and try again. If it still isn't working for you, call us at: 212.929.3645</p><input onclick='javascript:document.location.reload()' class='button' type='button' value='Try Again'></input></div><div style='display:none' class='customer-info'><ul><li id='customer-info'><h3>Customer Info</h3><div class='info'></p></li><li id='product-info'></li>
<li class='repair-details'><h3>Details</h3>
<ul class='repair-details'>
<li style='display: none'><p>During the first 1-3 business days, your repair will be processed and assigned to a technician.</p></li>
<li style='display: none'><p>A technician will work on your repair during this time. This will include confirming your issue, ordering replacement parts (if needed), and replacing the affected parts.</p></li>
<li style='display: none'><p>We are confirming that we resolved the issue.</p></li>
<li style='display: none'><p>Call Customer Support at 212.929.3645 for more information regarding this repair.</p></li>
<li style='display: none'><p>The repair is done and has been picked up.</p></li>
<li style='display: none'><p>The repair is done. It is ready to be picked up, if you have not made other arrangements.</p></li>
</ul></li></ul><p><input onclick=\"document.location.href = document.location.href.split('?')[0]+'#repairstatus'\" class=\"button\" type=\"button\" value=\"Start Over\"></p></div><form class='status-front' id='status-front' method='get'><p><span class='label'>Invoice #</span> <a href='javascript:showExampleSRO();'>What's this?</a></p>
<div id='whats-sro' style='display: none; text-align: left; font-size: 16px; font-weight: normal;' ><div style='background-image: url(" . $plugin . "/sroexample.jpg); background-position: left top; background-size: 100%; float: right; min-height: 150px; width: 48%; min-width: 300px; max-width: 100%; margin-left: 1em; background-repeat: no-repeat;' class='sro-example'>&nbsp;</div>Your Invoice # (also known as a Service Request Order number or SRO number) is the largest number on any repair receipt or invoice from Tekserve. The number is seven digits long and located in the upper right corner of your receipt as shown.</div>
<hr style='clear: both; visibility: hidden;'>
<p class='statusField'><input class='limit' name='sro1' id='sro1' type='text' value='" . $sro1 . "' maxlength='1' size='1' tabindex='1' onkeyup='checkLen(this,this.value)'></input> - <input class='limit' name='sro2' id='sro2' type='text' value='" . $sro2 . "' maxlength='3' size='3' tabindex='2' onkeyup='checkLen(this,this.value)'></input> - <input class='limit' name='sro3' id='sro3' type='text' value='" . $sro3 . "' maxlength='3' size='3' tabindex='3' onkeyup='checkLen(this,this.value)'></input></p><p><span class='label'>Billing ZIP Code</span></p><p><input class='limit' name='zip' id='zip' type='text'  value='" . $zip . "' maxlength='5' size='5' tabindex='4' onkeyup='checkLen(this,this.value)' /></p><div class='buttons'><button type='button' class='positive'>Submit</button></div></form></div></div></div><div></div>
";

}	//end repair_status_checker( $atts )
add_shortcode( 'repairstatus', 'repair_status_checker' );

/* URL endpoint handler for /servicestatus
e.g. http://www.tekserve.com/servicestatus/?key=c3JvPTMwNjAxMjEmemlwPTEwMDEy
******************/

//currently handled by js after shortcode parses querystring and creates JS vars//
//would be nice to set a url handler for specific url, and add to an admin setting, but letting it be for now//
