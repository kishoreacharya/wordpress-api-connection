<?php
/**
 * Theme Functions
 *.
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URI', get_template_directory_uri() );

define( 'THEME_NAME', 'betheme' );
define( 'THEME_VERSION', '16.6' );

define( 'LIBS_DIR', THEME_DIR. '/functions' );
define( 'LIBS_URI', THEME_URI. '/functions' );
define( 'LANG_DIR', THEME_DIR. '/languages' );

add_filter( 'widget_text', 'do_shortcode' );

add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );


/* ---------------------------------------------------------------------------
 * White Label
 * IMPORTANT: We recommend the use of Child Theme to change this
 * --------------------------------------------------------------------------- */
defined( 'WHITE_LABEL' ) or define( 'WHITE_LABEL', false );


/* ---------------------------------------------------------------------------
 * Loads Theme Textdomain
 * --------------------------------------------------------------------------- */
load_theme_textdomain( 'betheme',  LANG_DIR );
load_theme_textdomain( 'mfn-opts', LANG_DIR );


/* ---------------------------------------------------------------------------
 * Loads the Options Panel
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_admin_scripts' ) )
{
	function mfn_admin_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}
}
add_action( 'wp_enqueue_scripts', 'mfn_admin_scripts' );
add_action( 'admin_enqueue_scripts', 'mfn_admin_scripts' );

require( THEME_DIR .'/muffin-options/theme-options.php' );

$theme_disable = mfn_opts_get( 'theme-disable' );


/* ---------------------------------------------------------------------------
 * Loads Theme Functions
 * --------------------------------------------------------------------------- */

// Functions ------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-functions.php' );

// Header ---------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-head.php' );

// Menu -----------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-menu.php' );
if( ! isset( $theme_disable['mega-menu'] ) ){
	require_once( LIBS_DIR .'/theme-mega-menu.php' );
}

// Muffin Builder -------------------------------------------------------------
require_once( LIBS_DIR .'/builder/fields.php' );
require_once( LIBS_DIR .'/builder/back.php' );
require_once( LIBS_DIR .'/builder/front.php' );

// Custom post types ----------------------------------------------------------
$post_types_disable = mfn_opts_get( 'post-type-disable' );

if( ! isset( $post_types_disable['client'] ) ){
	require_once( LIBS_DIR .'/meta-client.php' );
}
if( ! isset( $post_types_disable['offer'] ) ){
	require_once( LIBS_DIR .'/meta-offer.php' );
}
if( ! isset( $post_types_disable['portfolio'] ) ){
	require_once( LIBS_DIR .'/meta-portfolio.php' );
}
if( ! isset( $post_types_disable['slide'] ) ){
	require_once( LIBS_DIR .'/meta-slide.php' );
}
if( ! isset( $post_types_disable['testimonial'] ) ){
	require_once( LIBS_DIR .'/meta-testimonial.php' );
}

if( ! isset( $post_types_disable['layout'] ) ){
	require_once( LIBS_DIR .'/meta-layout.php' );
}
if( ! isset( $post_types_disable['template'] ) ){
	require_once( LIBS_DIR .'/meta-template.php' );
}

require_once( LIBS_DIR .'/meta-page.php' );
require_once( LIBS_DIR .'/meta-post.php' );

// Content ----------------------------------------------------------------------
require_once( THEME_DIR .'/includes/content-post.php' );
require_once( THEME_DIR .'/includes/content-portfolio.php' );

// Shortcodes -------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-shortcodes.php' );

// Hooks ------------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-hooks.php' );

// Widgets ----------------------------------------------------------------------
require_once( LIBS_DIR .'/widget-functions.php' );

require_once( LIBS_DIR .'/widget-flickr.php' );
require_once( LIBS_DIR .'/widget-login.php' );
require_once( LIBS_DIR .'/widget-menu.php' );
require_once( LIBS_DIR .'/widget-recent-comments.php' );
require_once( LIBS_DIR .'/widget-recent-posts.php' );
require_once( LIBS_DIR .'/widget-tag-cloud.php' );

// TinyMCE ----------------------------------------------------------------------
require_once( LIBS_DIR .'/tinymce/tinymce.php' );

// Plugins ----------------------------------------------------------------------
if( ! isset( $theme_disable['demo-data'] ) ){
	require_once( LIBS_DIR .'/importer/import.php' );
}

require_once( LIBS_DIR .'/system-status.php' );

require_once( LIBS_DIR .'/class-love.php' );
require_once( LIBS_DIR .'/class-tgm-plugin-activation.php' );

require_once( LIBS_DIR .'/plugins/visual-composer.php' );

// WooCommerce specified functions
if( function_exists( 'is_woocommerce' ) ){
	require_once( LIBS_DIR .'/theme-woocommerce.php' );
}

// Disable responsive images in WP 4.4+ if Retina.js enabled
if( mfn_opts_get( 'retina-js' ) ){
	add_filter( 'wp_calculate_image_srcset', '__return_false' );
}

// Hide activation and update specific parts ------------------------------------

// Slider Revolution
if( ! mfn_opts_get( 'plugin-rev' ) ){
	if( function_exists( 'set_revslider_as_theme' ) ){
		set_revslider_as_theme();
	}
}

// LayerSlider
if( ! mfn_opts_get( 'plugin-layer' ) ){
	add_action('layerslider_ready', 'mfn_layerslider_overrides');
	function mfn_layerslider_overrides() {
		// Disable auto-updates
		$GLOBALS['lsAutoUpdateBox'] = false;
	}
}

// Visual Composer
if( ! mfn_opts_get( 'plugin-visual' ) ){
	add_action( 'vc_before_init', 'mfn_vcSetAsTheme' );
	function mfn_vcSetAsTheme() {
		vc_set_as_theme();
	}
}

/* create company  */

add_action( 'wpcf7_before_send_mail', 'cf7_validate_api', 10, 3 );


function cf7_validate_api( $cf7,&$abort, $submission) {

	if ( $cf7->id() !== 23 ) //CF7 post-id from admin settings;
		return;

	$errMsg = '';
	$customer_contact_get_fields = [];
	$customer_contact_fields = [];

	$submission = WPCF7_Submission::get_instance();
	$postedData = $submission->get_posted_data();
	$fields = [];

	//-----API posting------

	$api_username = 'testapiusername';
	$api_key = '';
	$api_url = '';

	$fields['username'] = $api_username;
	$fields['key'] = $api_key;
	$fields['endpoint'] = 'test-endpoint';

	if($postedData['company_type'][0]=='Privatperson'){
		$fields['firstname'] = $postedData['firstname_private'];
		$fields['lastname'] = $postedData['lastname_private'];
		$fields['phone_work'] = $postedData['phone_private'];
		$fields['email'] = $postedData['email_private'];
		$fields['address_1'] = $postedData['address_private'];
		$fields['zip'] = $postedData['zip_private'];
		$fields['town'] = $postedData['town_private'];
		$fields['country'] = 'Sverige';
	}

	if($fields['type'] == 1){
		$org_array=explode("-",$fields['org']);
		if(strlen($org_array[0])!=6 || strlen($org_array[1])!=4){
			$error_message='OBS! se till att formatet för fältet "organisationsnummer" är enligt följande: XXXXXX-XXXX';
			$errMsg=$error_message;
		}
	}

	if(empty($errMsg)){
		$params = [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'body' => $fields,
		];
		$response = wp_remote_post($api_url, $params);
	}

	//------------------

	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
		$errMsg = "Something went wrong:\n{$error_message}";

	} else {
		$customer_result = json_decode($response['body'], true);

		if ($response['response']['code'] != 200) {
			if(!empty($error_message)){
				$errMsg = 'Något gick fel!<br>Felmeddelande: ' . $error_message;
			}else{
				$error_message = $response['response']['code'] . ': ' . $response['response']['message'];
				$errMsg = 'Något gick fel!<br>Felmeddelande: ' . $error_message;
			}

		} elseif ($customer_result['success'] == 'false') {
			if ($customer_result['message'] == 'Field "org" is incorrect [Organisation number already exists]') {
				$errMsg = 'OBS! Ditt företag är redan registrerad, kontakta vår support.';
			}else {
				$errMsg = $customer_result['message'];
			}

		} elseif ($customer_result['success'] == 'true') {

			if (!empty($customer_result['response']['customer'])) {

				$customer_contact_get_fields['endpoint'] = 'test-end-point';
				$customer_contact_get_fields['username'] = $api_username;
				$customer_contact_get_fields['key'] = $api_key;
				$customer_contact_get_fields['email'] = $fields['email'];
				$customer_contact_get_response = wp_remote_post($api_url, [
					'body' => $customer_contact_get_fields,
				]);

				if (is_wp_error($customer_contact_get_response)) {
					$error_message = $customer_contact_get_response->get_error_message();
					$errMsg = 'Något gick fel! ' . $error_message;
				} else {
					if ($customer_contact_get_response['response']['code'] != 200) {
						$error_message = $customer_contact_get_response['response']['code'] . ': ' . $customer_contact_get_response['response']['message'];
						$errMsg = 'Något gick fel!<br>Felmeddelande: ' . $error_message;

					}
				}

				$customer_contact_fields['endpoint'] = 'test-end-point';
				$customer_contact_fields['username'] = $api_username;
				$customer_contact_fields['key'] = $api_key;
				$customer_contact_fields['customer'] = $customer_result['response']['customer'];
				$customer_contact_fields['firstname'] = $fields['firstname'];
				$customer_contact_fields['lastname'] = $fields['lastname'];
				$customer_contact_fields['email'] = $fields['email'];
				$customer_contact_fields['phone_mobile'] = $fields['phone'];
				$customer_contact_response = wp_remote_post($api_url, [
					'body' => $customer_contact_fields,
				]);

				if (is_wp_error($customer_contact_response)) {
					$error_message = $customer_contact_response->get_error_message();
					$errMsg = 'Något gick fel! ' . $error_message;
				} else {
					if ($customer_contact_response['response']['code'] != 200) {
						$error_message = $customer_contact_response['response']['code'] . ': ' . $customer_contact_response['response']['message'];
						$errMsg = 'Något gick fel!<br>Felmeddelande: ' . $error_message;
					}

				}

			}

		}
	}

	if ($errMsg) { //do not send mail;
		$abort = true;
		$submission->set_response( $cf7->message( 'validation_failed' ) ); //msg from admin settings;
		$submission->set_response( $cf7->filter_message($errMsg) ); //custom msg;
	}

}
