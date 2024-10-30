<?php 
/**
Plugin Name: Choyal Subscription Popup - MailChimp Support
Plugin URI: https://wordpress.org/plugins/choyal-subscription-popup/
Description: Choyal Subscription Popup fully customizable popup. Full control over popup heading, text, popup background overlay and background image. Awesome popup box design's is available. Also Support support mailchimp and own wordpress database of all subscribers. Show/Hide popup and enable/disable mailchimp from plugin setting option.
Author: Girdhari choyal
Version: 2.0
Author URI: https://about.me/gchoyal
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'CSP_ASSETS_PATH', plugin_dir_url( __FILE__ ) . 'assets/' );
define( 'CSP_MAILCHIMP_API_KEY', get_option('mail-chimp-api-key') );
define( 'CSP_MAILCHIMP_SELECTED_LIST_ID', get_option('mail-chimp-list-id') );
	
function csp_enqueue_style() {
	
	$disablePopup = get_option('disable-popup');
	
	if( !$disablePopup || $disablePopup == 'no' ){
		
		wp_enqueue_style( 'csp-style', CSP_ASSETS_PATH. 'css/style.css', false ); 
	
	}
	
}

function csp_enqueue_script() {
	
	$disablePopup = get_option('disable-popup');
	
	if( !$disablePopup || $disablePopup == 'no' ){
		
		wp_enqueue_script( 'csp-js-validate', CSP_ASSETS_PATH. 'js/jquery.validate.min.js', array('jquery', 'csp-js'), '1.0.0', true );
		wp_enqueue_script( 'csp-js', CSP_ASSETS_PATH. 'js/script.js', array('jquery'), '1.0.0', true );
		
		wp_localize_script( 'csp-js', 'csp_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce('csp-nonce')
		));
	
	}
	
}

add_action( 'wp_enqueue_scripts', 'csp_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'csp_enqueue_script' );

function csp_model_html(){
	
	$titlePopup = esc_html( stripslashes( get_option('csp-popup-title') ) );
	$textPopup = esc_textarea( stripslashes( get_option('csp-popup-text') ) );
	
	$disablePopup = get_option('disable-popup');
	$disableFnameLname = get_option('disable-fname-lname');
	
	if( !$disablePopup || $disablePopup == 'no' ){
	
	?>
	
	<a class="csp_subscribe_btn" href="#" title="subscribe" >
		<img src="<?php echo CSP_ASSETS_PATH.'/img/mail.png'; ?>" class="csp_btn_img_icon" >
	</a>
	
	<div class="csp_overlay" >
	
		<div class="csp_model" >
	
			<div class="csp_content" >
				
				<div class="csp_close"></div>
				
				<div class="csp_api_msg"></div>
				
				<?php if( $titlePopup != '' ){ ?>
					<h1><?php echo $titlePopup; ?></h1>
				<?php } ?>
				
				<?php if( $textPopup != '' ){ ?>
					<p><?php echo $textPopup; ?></p>
				<?php } ?>
				
				<form action="" method="post" id="email-subscription-form" >
					
					<?php if( !$disableFnameLname || $disableFnameLname == 'no' ){ ?>
					
					<div class="scp_half_row" >
					
						<div class="csp_row">
							<label for="csp_fname">First Name</label>
						</div>
						
						<div class="csp_row csp_row_input">
							<input type="text" id="csp_fname" name="csp_fname" class="csp_input" required>
						</div>
					
					</div>
					
					<div class="scp_half_row" >
					
						<div class="csp_row">
							<label for="csp_lname">Last Name</label>
						</div>
						
						<div class="csp_row csp_row_input">
							<input type="text" id="csp_lname" name="csp_lname" class="csp_input" required>
						</div>
						
					</div>
					
					<?php } ?>
					
					<div class="csp_row">
						<label for="csp_email">Email</label>
					</div>
					
					<div class="csp_row csp_row_input">
						<input type="email" id="csp_email" name="csp_email" class="csp_input" required>
					</div>
					
					<div class="csp_row">
						<button type="submit" name="csp_submit" class="csp_btn" >Subscribe Now <img class="csp_loader" src="<?php echo CSP_ASSETS_PATH.'/img/ripple.svg'; ?>" onerror="this.src='<?php echo CSP_ASSETS_PATH.'/img/ripple.gif'; ?>'"></button>
					</div>
					
				</form>
	
			</div>
	
		</div>
	
	</div>
	
	<?php
	
	}
	
}

add_action( 'wp_footer', 'csp_model_html' );

function csp_design_setting(){
	
	if( esc_html( get_option('csp-popup-overlay') ) == 'yes' ){ 
	
		$overlay = true;
		
	}else{
		
		$overlay = false;
		
	}
	
	if( floatval( get_option('csp-background-overlay-transparency') ) > 0 ){

		$opacity = floatval( get_option('csp-background-overlay-transparency') ); 
		
	}else{

		$opacity = '0.4'; 
		
	}
	
	$style = '<style>';
	
	if( $overlay ){
		
		$style .= '.csp_overlay{
					width: 100%;
					height:1200px;
					position: fixed;
					background: rgba(0,0,0,'. $opacity .');
					z-index: 99999999;
					top: 0;
					left: 0;
				}';
	
	}
	
	$style .= '</style>';
	
	echo $style;
	
}

add_action( 'wp_head', 'csp_design_setting' );


function csp_submit() {
	
	check_ajax_referer( 'csp-nonce', 'security' );
	
	global $wpdb;
	
	$subscribersTable = $wpdb->prefix . 'csp_subscribers';
	
	if( is_array($_POST['data']) ){
		
		//Get settings
		$mailChimpActivate = get_option('mail-chimp-activate');
		
		$formData = $_POST['data'];

		$email_key = csp_is_field( $formData, 'csp_email' );
		$fname = '';
		$lname = '';
		
		$email = sanitize_email( $formData[$email_key]['value'] );
		
		//Email Address
		if( is_email( $email ) ){
			
			$fname_key = csp_is_field( $formData, 'csp_fname' );
			$lname_key = csp_is_field( $formData, 'csp_lname' );
			
			if( isset($fname_key) ){
				$fname = sanitize_text_field( $formData[$fname_key]['value'] );
			
			}
			
			if( isset($lname_key) ){
				
				$lname = sanitize_text_field( $formData[$lname_key]['value'] );
			
			}
			
			$mailChimpEmailValidation = true;
			
			
			if( $mailChimpActivate == 'yes' ){ //MailChimp Subscribe
					
				
				$mailChimpSubData = csp_mailchimp_subscription( 'subscribed',  $email, $fname, $lname );
				
				if( $mailChimpSubData['code'] == 400 ){
					
					$mailChimpEmailValidation = false;
					
				}
				
				
			}
			
			//Insert into DB 
			$wpdb->get_results('SELECT * FROM '. $subscribersTable .' WHERE email="'. $email .'"');
			
			if( $mailChimpEmailValidation == true ){
					
				if( $wpdb->num_rows > 0 ) { 
			
					//$response['response']['code'] 
					$subscriptionStatus = array( 'operation' => 'error', 'msg' => '<p class="csp-popup-error-msg" >Your already our subscriber.</p>' );
					
				} else {
					
					$wpdb->insert( $subscribersTable, 
						array( 
							'fname' => $fname, 
							'lname' => $lname,
							'email' => $email
						), 
						array( 
							'%s', 
							'%s',
							'%s'
						) 
					);
					
					$subscriptionStatus = array( 'operation' => 'success', 'msg' => '<p class="csp-popup-success-msg" >You have successfully subscribed.</p>'  );
					
				}
			
			}else{
				
				$subscriptionStatus = array( 'operation' => 'error', 'msg' => $mailChimpSubData['msg'] );
				
			}
			
			wp_send_json( $subscriptionStatus );
			
		}
		
	
	}
	
	die(0);
	
}

add_action( 'wp_ajax_nopriv_csp_submit', 'csp_submit' );
add_action( 'wp_ajax_csp_submit', 'csp_submit' );

//mailchimp Subscribe and unsubscribe 
function csp_mailchimp_subscription( $status, $email, $fname = '', $lname = '' ){
	
	// status: unsubscribed, subscribed, cleaned, pending
	
	$argsBody = array(
			'email_address' => $email,
			'status'        => $status
		);
	
	if( $fname!='' || $lname!='' ){
		
		$argsBody['merge_fields'] = array( 
				'FNAME' => $fname,
				'LNAME' => $lname
			);
		
	}
	
	$args = array(
		'method' => 'PUT',
		'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( 'user:'. CSP_MAILCHIMP_API_KEY )
		),
		'body' => json_encode( $argsBody )
	);
	
	$response = wp_remote_post( 'https://' . substr(CSP_MAILCHIMP_API_KEY,strpos(CSP_MAILCHIMP_API_KEY,'-')+1) . '.api.mailchimp.com/3.0/lists/' . CSP_MAILCHIMP_SELECTED_LIST_ID . '/members/' . md5(strtolower($email)), $args );
	 
	$body = json_decode( $response['body'] );
	 
	if ( $response['response']['code'] == 200 && $body->status == $status ) {
		
		return array( 'operation' => 'success', 'code' => $response['response']['code'], 'msg' => '<p class="csp-popup-success-msg" >You have successfully ' . $status . '.</p>'  );
		
	} else {
		
		//$response['response']['code'] 
		return array( 'operation' => 'error', 'code' => $response['response']['code'], 'msg' => '<p class="csp-popup-error-msg" >' . $body->detail. '</p>' );
	
	}
	
}

//Setting page 
function csp_register_setting_menu_page() {
	
    add_menu_page(
        'Subscription Popup Setting',
        'CSP Settings',
        'manage_options',
        'csp-setting',
        'csp_settings',
        plugins_url( 'choyal-subscription-popup/assets/img/mail-admin-icon.png' ),
        6
    );
	
	add_submenu_page(
        'csp-setting',
        'CSP Subscribers',
        'Subscribers List',
        'manage_options',
        'csp-subscribers',
        'csp_subscribers_submenu' );
		
	add_submenu_page(
        'csp-setting',
        'CSP Design',
        'Popup Design',
        'manage_options',
        'csp-designs',
        'csp_design_submenu' );	
	
}
add_action( 'admin_menu', 'csp_register_setting_menu_page' );

//Subscribers listing page 
function csp_subscribers_submenu() {
	
	global $wpdb;
	
	$subscribersTable = $wpdb->prefix . 'csp_subscribers';
	
	$cspTotalSubscribers = $wpdb->get_var( 'SELECT COUNT(*) FROM '. $subscribersTable );
	
	$cspArySubscribers = $wpdb->get_results( 'SELECT * FROM '. $subscribersTable .' ORDER BY id DESC LIMIT 10' );
	
	$mailChimpActivate = esc_html( get_option('mail-chimp-activate') );
	
	$disableFnameLname = esc_html( get_option('disable-fname-lname') );
		
    include_once('csp-subscribers.php');
	
}

//Popup Design's
function csp_design_submenu(){
	
	if( !current_user_can('administrator') ){
		
		echo 'Access Denied!';	
		die();	
			
	}

	//Handle Setting Form 
	if( isset($_POST['submit']) ){
		
		if ( !isset( $_POST['csp-setting'] ) || !wp_verify_nonce( $_POST['csp-setting'], 'csp-setting-security' ) ){

		   print 'Sorry, your nonce did not verify.';
		   exit;

		}
		
		$arySettings = array();
		
		//popup 
		
		if( sanitize_text_field( $_POST['csp_popup_overlay'] )!='' ){
		
			$arySettings['csp-popup-overlay'] = sanitize_text_field( $_POST['csp_popup_overlay'] );
			
		}else{
			
			$arySettings['csp-popup-overlay'] = 'no';
			
		}
		
		$arySettings['csp-background-overlay-transparency'] = floatval( $_POST['csp_background_overlay_transparency'] );
		
		//save setting in option table
		
		foreach( $arySettings as $keySetting => $valueSetting ){
			
			if ( get_option( $keySetting ) !== false ) {
				update_option( $keySetting, $valueSetting );
			} else {
				$deprecated = null;
				$autoload = 'no';
				add_option( $keySetting, $valueSetting, $deprecated, $autoload );
			}
			
		}
		
	}
	
	include_once('csp-designs.php');
	
}

//subscribers listing Ajax call 
function csp_subscribers_load_more(){
	
	check_ajax_referer( 'csp-security-subscribers', 'security' );
	
	if( !current_user_can('administrator') ){
		
		echo 'Access Denied!';	
		die();	
			
	}
	
	global $wpdb;
	
	$subscribersTable = $wpdb->prefix . 'csp_subscribers';
	
	if( is_array( $_POST['data'] ) ){
		
		$offset = intval( $_POST['data']['cresults'] );
		
		$cspArySubscribers = $wpdb->get_results( 'SELECT * FROM '. $subscribersTable .' ORDER BY id DESC LIMIT '. $offset .',10' );
		
		$aryData = array(
					'cresults' => count($cspArySubscribers),
					'sdata' => $cspArySubscribers
				);
				
		wp_send_json( $aryData );		
		
	}
	
	die(0);
	
}

add_action( 'wp_ajax_csp_subscribers_load_more', 'csp_subscribers_load_more' );

function csp_settings(){
	
	if( !current_user_can('administrator') ){
		
		echo 'Access Denied!';	
		die();	
			
	}

	//Handle Setting Form 
	if( isset($_POST['submit']) ){
		
		if ( !isset( $_POST['csp-setting'] ) || !wp_verify_nonce( $_POST['csp-setting'], 'csp-setting-security' ) ){

		   print 'Sorry, your nonce did not verify.';
		   exit;

		}
		
		$arySettings = array();
		
		//popup 
		
		$arySettings['csp-popup-title'] = sanitize_text_field( stripslashes( $_POST['csp_popup_title'] ) );
		
		$arySettings['csp-popup-text'] = sanitize_textarea_field( stripslashes( $_POST['csp_popup_text'] ) );
		
		if( sanitize_text_field( $_POST['csp_popup_disable'] )!='' ){
		
			$arySettings['disable-popup'] = sanitize_text_field( $_POST['csp_popup_disable'] );
			
		}else{
			
			$arySettings['disable-popup'] = 'no';
			
		}
		
		if( sanitize_text_field( $_POST['csp_popup_fname_lname'] )!='' ){
		
			$arySettings['disable-fname-lname'] = sanitize_text_field( $_POST['csp_popup_fname_lname'] );
		
		}else{
			
			$arySettings['disable-fname-lname'] = 'no';
			
		}
		
		if( sanitize_text_field( $_POST['csp_popup_mailchimp_integration'] )!='' ){
		
			$arySettings['mail-chimp-activate'] = sanitize_text_field( $_POST['csp_popup_mailchimp_integration'] );
		
		}else{
			
			$arySettings['mail-chimp-activate'] = 'no';
			
		}
		
		//MailChimp 
		if( trim( strip_tags( $_POST['csp_mailchimp_api_key']) )!='' ){
		
			$arySettings['mail-chimp-api-key'] = trim( strip_tags( $_POST['csp_mailchimp_api_key'] ) );
		
		}
		
		if( trim( strip_tags( $_POST['csp_mailchimp_list_id'] ) )!='' ){
		
			$arySettings['mail-chimp-list-id'] = trim( strip_tags( $_POST['csp_mailchimp_list_id'] ) );
		
		}
		
		
		
		//save setting in option table
		
		foreach( $arySettings as $keySetting => $valueSetting ){
			
			if ( get_option( $keySetting ) !== false ) {
				update_option( $keySetting, $valueSetting );
			} else {
				$deprecated = null;
				$autoload = 'no';
				add_option( $keySetting, $valueSetting, $deprecated, $autoload );
			}
			
		}
		
	}
	
	include_once('csp-settings.php');
	
}

//Check if field exist and return key
function csp_is_field( $formData, $fieldname ){ //Arg1 array of form data, field name to check  
	
	foreach($formData as $key => $value){
		
		if(is_array($value) && $value['name'] == $fieldname){
			  
			  return $key;
			  
		}
		
	}
	
	return false;
	
}

//Create Subscribers table 
function csp_subscribers_install() {
	
	global $wpdb;

	$table_name = $wpdb->prefix . 'csp_subscribers';
	
	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ){
			
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			fname varchar(250) DEFAULT '' NOT NULL,
			lname varchar(250) DEFAULT '' NOT NULL,
			email varchar(250) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
	}
	
}

register_activation_hook( __FILE__, 'csp_subscribers_install' );

//Ajax function of admin subscriber add
function csp_admin_add_subscriber(){
	
	check_ajax_referer( 'csp-security-subscribers', 'security' );
	
	if( !current_user_can('administrator') ){
		
		echo 'Access Denied!';	
		die();	
			
	}
	
	global $wpdb;
	
	$subscribersTable = $wpdb->prefix . 'csp_subscribers';
	
	if( is_array($_POST['data']) ){
		
		//Get settings
		$mailChimpActivate = sanitize_text_field( get_option('mail-chimp-activate') );
		
		$formData = $_POST['data'];

		$email_key = csp_is_field( $formData, 'csp_email' );
		$fname = '';
		$lname = '';
		
		$email = sanitize_email( $formData[$email_key]['value'] );
			
			
		//Email Address
		if( isset($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false ){
			
			$fname_key = csp_is_field( $formData, 'csp_fname' );
			$lname_key = csp_is_field( $formData, 'csp_lname' );
			$mailchimp_key = csp_is_field( $formData, 'csp_mailchimp' );
			
			if( isset($fname_key) ){
				$fname = sanitize_text_field( $formData[$fname_key]['value'] );
			
			}
			
			if( isset($lname_key) ){
				
				$lname = sanitize_text_field( $formData[$lname_key]['value'] );
			
			}
			
			if( isset($mailchimp_key) ){
				
				$mailchimp = trim( strip_tags( $formData[$mailchimp_key]['value'] ) );
			
			}
			
			
			$mailChimpEmailValidation = true;
			
			if( ( !$mailChimpActivate || $mailChimpActivate == 'yes') && ( isset($mailchimp) && $mailchimp == 'yes'  ) ){ //MailChimp Subscribe
					
				
				$mailChimpSubData = csp_mailchimp_subscription( 'subscribed',  $email, $fname, $lname );
				
				if( $mailChimpSubData['code'] == 400 ){
					
					$mailChimpEmailValidation = false;
					
				}
				
				
			}
			
			//Insert into DB 
			$wpdb->get_results('SELECT * FROM '. $subscribersTable .' WHERE email="'. $email .'"');
			
			if( $mailChimpEmailValidation == true ){
					
				if( $wpdb->num_rows > 0 ) { 
			
					//$response['response']['code'] 
					$subscriptionStatus = array( 'operation' => 'error', 'msg' => '<p class="csp-popup-error-msg" >Email address already exist in subscriber list.</p>' );
					
				} else {
					
					$wpdb->insert( $subscribersTable, 
						array( 
							'fname' => $fname, 
							'lname' => $lname,
							'email' => $email
						), 
						array( 
							'%s', 
							'%s',
							'%s'
						) 
					);
					
					$subscriptionStatus = array( 'operation' => 'success', 'msg' => '<p class="csp-popup-success-msg" >Email address addded successfully to subscriber list.</p>'  );
					
				}
			
			}else{
				
				$subscriptionStatus = array( 'operation' => 'error', 'msg' => $mailChimpSubData['msg'] );
				
			}
			
			wp_send_json( $subscriptionStatus );
			
		}
		
	
	}
	
	die(0);
	
}

add_action( 'wp_ajax_csp_admin_add_subscriber', 'csp_admin_add_subscriber' );

//Delete Subscriber
function csp_admin_delete_subscriber(){
	
	check_ajax_referer( 'csp-security-subscribers', 'security' );
	
	if( !current_user_can('administrator') ){
		
		echo 'Access Denied!';	
		die();	
			
	}
	
	global $wpdb;
	
	$subscribersTable = $wpdb->prefix . 'csp_subscribers';
	
	if( is_array($_POST['data']) ){
		
		$subscriberID = intval( $_POST['data']['subscriberDeleteForm'] );
		$removeMailchimp = sanitize_text_field( $_POST['data']['csp_delete_removefrom_mailchimp'] );

		if( isset($removeMailchimp) && $removeMailchimp == 'yes' ){
			
			//get email address using id
			$subscriberMail = $wpdb->get_row('SELECT email FROM '. $subscribersTable .' WHERE id="'. $subscriberID .'"');
			
			if( $wpdb->num_rows > 0 ) { 
			
				$args = array(
					'method' => 'DELETE',
					'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( 'user:'. CSP_MAILCHIMP_API_KEY )
					)
				);
				 
				wp_remote_post( 'https://' . substr(CSP_MAILCHIMP_API_KEY,strpos(CSP_MAILCHIMP_API_KEY,'-')+1) . '.api.mailchimp.com/3.0/lists/' . CSP_MAILCHIMP_SELECTED_LIST_ID . '/members/' . md5(strtolower( $subscriberMail->email )), $args );
				
			}
			
		}
		
		
		$wpdb->delete( $subscribersTable, array( 'id' => $subscriberID ), array( '%d' ) );
		
		echo '<p class="csp-popup-success-msg" >Subscriber deleted successfully.</p>';

	}
	
	die(0);
	
}

add_action( 'wp_ajax_csp_admin_delete_subscriber', 'csp_admin_delete_subscriber' );

//Bulk delete subscriber
function csp_admin_bulk_delete_subscribers(){
	
	check_ajax_referer( 'csp-security-subscribers', 'security' );
	
	if( !current_user_can('administrator') ){
		
		echo 'Access Denied!';	
		die();	
			
	}
	
	global $wpdb;
	
	$subscribersTable = $wpdb->prefix . 'csp_subscribers';
	
	if( is_array($_POST['data']) ){
		
		$aryEmails = $_POST['data']['emails'];
		$removeMailchimp = sanitize_text_field( $_POST['data']['bulk-delete-mailchimp'] );

		if( isset($removeMailchimp) && $removeMailchimp == 'yes' ){
			
			if( is_array($aryEmails) ){
					
				foreach( $aryEmails as $emailToDelete ){
					
					$args = array(
						'method' => 'DELETE',
						'headers' => array(
							'Authorization' => 'Basic ' . base64_encode( 'user:'. CSP_MAILCHIMP_API_KEY )
						)
					);
					 
					wp_remote_post( 'https://' . substr(CSP_MAILCHIMP_API_KEY,strpos(CSP_MAILCHIMP_API_KEY,'-')+1) . '.api.mailchimp.com/3.0/lists/' . CSP_MAILCHIMP_SELECTED_LIST_ID . '/members/' . md5(strtolower( sanitize_email($emailToDelete) )), $args );
					
				}
				
			}
			
		}
		
		$aryEmailN = array();
		
		foreach( $aryEmails as $sEmail ){
			
			$aryEmailN[] = "'". sanitize_email($sEmail) ."'";
			
		}
		
		$aryEmails = implode( ',', $aryEmailN );
		
		$wpdb->query( "DELETE FROM ". $subscribersTable ." WHERE email IN(".$aryEmails.")" );

		echo '<p class="csp-popup-success-msg" >Subscribers deleted successfully.</p>';

	}
	
	
	die(0);
	
}

add_action( 'wp_ajax_csp_admin_bulk_delete_subscribers', 'csp_admin_bulk_delete_subscribers' );

//Search subscriber ajax
function csp_search_subscribers(){
	
	check_ajax_referer( 'csp-security-subscribers', 'security' );
	
	if( !current_user_can('administrator') ){
		
		echo 'Access Denied!';	
		die();	
			
	}
	
	$searchTerm = sanitize_text_field( $_POST['data'][0]['value'] );
	
	global $wpdb;
	
	$subscribersTable = $wpdb->prefix . 'csp_subscribers';
	
	$subscriberMail = $wpdb->get_results('SELECT * FROM '. $subscribersTable .' WHERE (email LIKE "%'.$searchTerm. '%" OR fname LIKE "%'.$searchTerm.'%" OR lname LIKE "%'.$searchTerm.'%")' );
			
	if( $wpdb->num_rows > 0 ) { 
	
		foreach ( $subscriberMail as $cspsubscriber ) { ?>
			
			<tr id="post-2" class="iedit author-self level-0 post-2 type-page status-publish hentry">
				
				<th scope="row" class="check-column">			
				
					<input class="subscriber-chk" type="checkbox" name="subscriber[]" value="<?php if ( is_email( $cspsubscriber->email ) ) { echo $cspsubscriber->email; } ?>">
					
				</th>
				
				<td class="has-row-actions column-primary">
				
					<span><?php if ( is_email( $cspsubscriber->email ) ) { echo $cspsubscriber->email; } ?></span>

					<div class="row-actions"> <span class="trash"><a href="#<?php echo intval( $cspsubscriber->id ); ?>" sub-id="<?php echo intval( $cspsubscriber->id ); ?>" class="csp-delete-sub" >Delete</a></span> </div>

				</td>
		 
				<td><span><?php echo esc_html( $cspsubscriber->fname ); ?></span></td>
				
				<td>		
					<span><?php echo esc_html( $cspsubscriber->lname ); ?></span>
				</td>
				
			</tr>
		
			<?php }
	
	}else{
		
		echo '<p>No result found for term "'. $searchTerm .'".</p>';
		
	}
	
	die(0);
	
}

add_action( 'wp_ajax_csp_search_subscribers', 'csp_search_subscribers' );