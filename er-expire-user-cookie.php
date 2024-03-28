<?php
/**
 * Plugin Name:     Er Expire User Cookie
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Expire Auth Cookie after defined amount of time.
 * Author:          Elliott Richmond
 * Author URI:      https://elliottrichmond.co.uk
 * Text Domain:     er-expire-user-cookie
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Er_Expire_User_Cookie
 */

// Die if called directly.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Hook this function to the 'auth_cookie_expiration' action
add_filter ( 'auth_cookie_expiration', 'elliottrichmond_login_session', 10, 3 );
function elliottrichmond_login_session( $expire, $user_id, $remember ) {
    $remember = false; // Turn off remember me for all logins
    return 300; // Set login session limit in seconds, 300 = 5 minutes
}

// Hook this function to the 'init' action
add_action( 'init', 'elliottrichmond_reset_cookie_expiration' );
function elliottrichmond_reset_cookie_expiration() {

    if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
        return; // return if this is the wp heartbeat request
    }

    if( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ) {
        return; // return if user logged themselves out
    }

    if( isset( $_GET['loggedout'] ) && $_GET['loggedout'] == 'true' ) {
        return; // return if user is loggedout
    }

    if ( is_user_logged_in() ) { // Check if the user is logged in
        wp_set_auth_cookie( get_current_user_id(), false ); // Extend the user's authentication cookie
    }
    
}
