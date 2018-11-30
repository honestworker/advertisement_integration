<?php

/**
* Plugin Name: Advertisement Integration
* Plugin URI:  https://github.com/honestworker/advertisement_integration
* Description: .
* Author:      honestworker
* Author URI:  https://github.com/honestworker
* Version:     1.0
*/

define( 'WPADINTGR_VERSION', '1.0' );
define( 'WPADINTGR_PLUGIN', __FILE__ );
define( 'WPADINTGR_PLUGIN_BASENAME', plugin_basename( WPADINTGR_PLUGIN ) );
define( 'WPADINTGR_PLUGIN_NAME', trim( dirname( WPADINTGR_PLUGIN_BASENAME ), '/' ) );
define( 'WPADINTGR_PLUGIN_DIR', untrailingslashit( dirname( WPADINTGR_PLUGIN ) ) );

if ( ! defined( 'WPADINTGR_LOAD_JS' ) ) {
	define( 'WPADINTGR_LOAD_JS', true );
}

if ( ! defined( 'WPADINTGR_LOAD_CSS' ) ) {
	define( 'WPADINTGR_LOAD_CSS', true );
}

if ( ! defined( 'WPADINTGR_ADMIN_READ_CAPABILITY' ) ) {
	define( 'WPADINTGR_ADMIN_READ_CAPABILITY', 'edit_posts' );
}

if ( ! defined( 'WPADINTGR_ADMIN_READ_WRITE_CAPABILITY' ) ) {
	define( 'WPADINTGR_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );
}

if ( ! defined( 'WPADINTGR_VERIFY_NONCE' ) ) {
	define( 'WPADINTGR_VERIFY_NONCE', false );
}

define( 'WPADINTGR_PLUGIN_URL', untrailingslashit( plugins_url( '', WPADINTGR_PLUGIN ) ) );

require_once WPADINTGR_PLUGIN_DIR . '/includes/functions.php';
require_once WPADINTGR_PLUGIN_DIR . '/includes/l10n.php';
require_once WPADINTGR_PLUGIN_DIR . '/includes/capabilities.php';
require_once WPADINTGR_PLUGIN_DIR . '/includes/adintgr-form.php';
require_once WPADINTGR_PLUGIN_DIR . '/includes/adintgr-form-functions.php';
require_once WPADINTGR_PLUGIN_DIR . '/includes/adintgr-page.php';
require_once WPADINTGR_PLUGIN_DIR . '/includes/adintgr-page-functions.php';
require_once WPADINTGR_PLUGIN_DIR . '/includes/submission.php';

if ( is_admin() ) {
	require_once WPADINTGR_PLUGIN_DIR . '/admin/admin.php';
} else {
	wp_enqueue_style( 'adintgr-form', wpadintgr_plugin_url( 'includes/css/style.css' ), array(), WPADINTGR_VERSION, 'all' );

	if ( wpadintgr_is_rtl() ) {
		wp_enqueue_style( 'adintgr-form-rtl', wpadintgr_plugin_url( 'includes/css/style-rtl.css' ), array(), WPADINTGR_VERSION, 'all' );
	}

	wp_enqueue_script( 'wpadintgr-frontend', wpadintgr_plugin_url( 'includes/js/frontend.js' ), array( 'jquery' ), WPADINTGR_VERSION, true );
}

add_action( 'plugins_loaded', 'wpadintgr_load' );
function wpadintgr_load() {
	wpadintgr_load_textdomain();

	/* Shortcodes */
	add_shortcode( 'adintgr-form', 'wpadintgr_form_func' );
	add_shortcode( 'adintgr-page', 'wpadintgr_page_func' );
}

add_action( 'init', 'wpadintgr_init' );
function wpadintgr_init() {
	wpadintgr_get_request_uri();
	wpadintgr_register_post_types();

	do_action( 'wpadintgr_init' );
}

?>