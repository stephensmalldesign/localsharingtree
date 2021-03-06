<?php

/**
 * This function file is loaded after the parent theme's function file. It's a great way to override functions, e.g. add_image_size sizes.
 *
 *
 */

// Include Custom functions

require_once('customfunctions/sf-additional-notifications/SF_AdditionalNotifications.class.php');
require_once('customfunctions/sf-account-fields/SF_AccountFields.class.php');
require_once('customfunctions/sf-merchant-fields/SF_merchantFields.class.php');
require_once('customfunctions/sf-featured-deal/SF_Featured_Deals.class.php');
require_once('customfunctions/sf-deal-fields/SF_DealFields.class.php');

// Account Registration fields
add_filter('gb_account_register_contact_info_fields', 'custom_fields_changes', 999);
add_filter('gb_account_edit_contact_fields', 'custom_fields_changes', 999);
//add_filter('gb_checkout_fields_billing', 'custom_fields_changes', 999);
//add_filter('gb_checkout_fields_shipping', 'custom_fields_changes', 999);
function custom_fields_changes($fields) {
	/*
	if (isset($fields['first_name'])) {
		$fields['first_name']['attributes'] = array_merge((array)$fields['first_name']['attributes'], array('maxlength' => 100));
	}
	if (isset($fields['last_name'])) {
		$fields['last_name']['attributes'] = array_merge((array)$fields['last_name']['attributes'], array('maxlength' => 100));
	}
	*/
	if (isset($fields['street'])) {
		$fields['street']['required'] = FALSE;
	}
	if (isset($fields['city'])) { 
		$fields['city']['required'] = FALSE;
	}
	if (isset($fields['zone'])) { //state, county
		$fields['zone']['required'] = FALSE;
	}
	if (isset($fields['postal_code'])) { //zip, postcode
		$fields['postal_code']['required'] = FALSE;
	}
	if (isset($fields['country'])) { //country 
		$fields['country']['required'] = FALSE;
	}
	
	return $fields;
	
}

// Disable Guest Purchases
add_filter( 'gb_account_register_user_fields', 'remove_guest_registration_field', 100, 1 );
function remove_guest_registration_field( $fields = array() ) {
unset($fields['guest_purchase']);
return $fields;
}

//Relace scripts
add_action( 'wp_print_scripts', 'custom_gbs_scripts_changes', 50 );
function custom_gbs_scripts_changes() {
	wp_dequeue_script( 'gbs-jquery-template');
	wp_deregister_script( 'gbs-jquery-template');
}
add_action( 'init', 'custom_gbs_theme_register_scripts' );
function custom_gbs_theme_register_scripts() {
	wp_register_script( 'custom-gbs-jquery-template', get_stylesheet_directory_uri().'/js/custom-jquery.template.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ), gb_ptheme_current_version(), false );
}
add_action( 'wp_enqueue_scripts', 'custom_wp_enqueue_scripts' );
function custom_wp_enqueue_scripts() {
	wp_enqueue_script('custom-gbs-jquery-template');
}


//Custom footer scripts
add_action('wp_footer', 'custom_footer_scripts');
function custom_footer_scripts() {
	//Message banner close X 
	// (NOT USED NOW - Replaced with messages that appear in lighboxes - see: js/custom-jquery.template.js)
	?>
    <script type="text/javascript">
	/* After page is complete */
	jQuery(window).load(function() {
		jQuery('#message_banner').on("click", function() { 
			jQuery(this).hide();
		});
	});
	</script>
    <?php	
	//Add popup for requiring login before adding to cart
	if ( !is_user_logged_in() ) { 
	/*
	?>
    <script type="text/javascript">
		//On click show lighbox for login
		jQuery(window).load(function() {
			jQuery("#trigger_fancybox_message_banner").fancybox({
				'content': '<div class="fancybox_message_banner" style="display: block; !important">' + jQuery("#trigger_fancybox_message_banner").html() + '</div>',
				'hideOnOverlayClick': true,
				'hideOnContentClick': false,
				'showCloseButton': true,
				'autoDimensions': true,
				'autoScale': true,
				'overlayColor': '#000000',
				'width': 700,
				'height': 200,
				'overlayOpacity': 0.8,
				'padding': 0
			});
		});
	</script>
    <?php
	*/
	}
}

//Require Login before Adding to cart
add_action( 'parse_request', 'custom_force_login_before_cart', 1, 1);
function custom_force_login_before_cart(WP $wp) {
	if ( !is_user_logged_in() ) {
		if ( gb_on_cart_page() || gb_on_checkout_page() ) {
			Group_Buying_Controller::set_message( gb__( 'In order to Purchase a Deal, You Must Register as a User, or Log In First.' ) );
			Group_Buying_Controller::login_required();
		}
	}
}


//Change home from Subscription Landing
remove_action( 'pre_gbs_head', 'gb_redirect_from_home' );
add_action( 'pre_gbs_head', 'custom_gb_redirect_away_from_home' );
function custom_gb_redirect_away_from_home() {
	
	if ( !is_user_logged_in() && gb_force_login_option() != 'false' ) {
		if (
			( is_home() && 'subscriptions' == gb_force_login_option() ) ||
			gb_on_login_page() ||
			gb_on_reset_password_page() ) {
			return;
		} else {
			gb_set_message( gb__( 'Force Login Activated, Membership Required.' ) );
			gb_login_required();
			return;
		}
	}
	
	
	if ( is_home() || is_front_page() ) {
		
		//if redirecting to featured
		if ( isset($_GET['featured']) ) {
			$featured_deal_link = gb_get_latest_deal_link();
			wp_redirect( $featured_deal_link );
			exit();
		}
		
		//logged in, send to home
		if ( is_user_logged_in() ) {
				
			//$deals_link = gb_get_deals_link( gb_get_location_preference() );
			//$deals_link = gb_get_latest_deal_link();
			
			wp_redirect( site_url('/home/') );
			exit();
			
		} else {
			
			//Not logged in, but location set
			if ( isset($_GET['location']) && term_exists( $_GET[ 'location' ]) ) {
				//$deals_link = gb_get_deals_link( $_GET['location'] );
				//wp_redirect( $deals_link  );
				wp_redirect( site_url('/home/') );
				exit();
			} elseif ( isset($_COOKIE['gb_location_preference']) && term_exists( $_COOKIE[ 'gb_location_preference' ]) ) {
				//$deals_link = gb_get_deals_link( $_COOKIE[ 'gb_location_preference' ] );
				//wp_redirect( $deals_link  );
				wp_redirect( site_url('/home/') );
				exit();
			}
			
		}
	}
	
	return;	
}

//Handle Merchant Register & Submit Deal links & redirect
add_action( 'init', 'custom_handle_merchant_register_deals', 99 );
function custom_handle_merchant_register_deals() {
	if ( !isset( $_GET['action_page'] ) ) return;
	
	//Register merchant
	if ( $_GET['action_page'] == 'register_merchant' ) {
		if ( !is_user_logged_in() ) {
			Group_Buying_Controller::set_message( gb__( 'In order to Register Your Business, You Must Register as a User, or Log In First.' ), Group_Buying_Controller::MESSAGE_STATUS_INFO );
			//Redirect to login
			Group_Buying_Controller::login_required();
		} else {
			//Already has merchant?
			$has_merchant = gb_get_merchants_by_account( get_current_user_id() );
			if ( empty($has_merchant) ) {
				wp_redirect( gb_get_merchant_registration_url() );
				exit();
			} else {
				Group_Buying_Controller::set_message( gb__( 'You have already registered your business, if you need to make changes, you can do so on your Account page.' ), Group_Buying_Controller::MESSAGE_STATUS_INFO );
				//wp_redirect( gb_get_merchant_account_url() );
				wp_redirect( gb_get_account_url() );
				exit();
					
			}
		}
		
	}
	
	//Deal submit
	if ( $_GET['action_page'] == 'deal_submit' ) {
		if ( !is_user_logged_in() ) {
			Group_Buying_Controller::set_message( gb__( 'You must register a User and as a Business first or log in.' ), Group_Buying_Controller::MESSAGE_STATUS_INFO );
			//Redirect to login
			Group_Buying_Controller::login_required();
		} else {
			//Already has merchant?
			$has_merchant = gb_get_merchants_by_account( get_current_user_id() );
			if ( empty($has_merchant) ) {
				Group_Buying_Controller::set_message( gb__( 'In Order to Run a Deal, You Must Register Your Business First.' ), Group_Buying_Controller::MESSAGE_STATUS_INFO );
				wp_redirect( gb_get_merchant_registration_url() );
				exit();
			} else {
				//Redirect to deal submit
				wp_redirect( gb_get_deal_submission_url() );
				exit();
						
			}
		}
		
	}
}


//Change upload limit on Business submit deal form
add_filter('gb_validate_deal_submission', 'custom_uploadlimit_gb_validate_deal_submission', 10, 2);
function custom_uploadlimit_gb_validate_deal_submission($errors, $post) {
	if ( !empty( $_FILES['gb_deal_thumbnail']['name'] ) ) {
		//Check size
		$max_bytes = ( 1048576 * 3 ); // bytes in MB * # of MB
		//$min_bytes = ( 1024 * 10 ); // bytes in KB * # of KB
		$uploaded_filesize = intval($_FILES['gb_deal_thumbnail']['size']);
		//if ($uploaded_filesize > $max_bytes || $uploaded_filesize < $min_bytes ) {
		if ($uploaded_filesize > $max_bytes) {
			$errors[] = sprintf( gb__( '"%s" file size cannot be greater than 3MB.' ), gb__( 'Deal Image' ) );
		}
	}
	return $errors;
}


//Adjust voucher previews to match voucher changes
add_filter('gb_voucher_preview_content', 'custom_gb_voucher_preview_content', 10, 2);
function custom_gb_voucher_preview_content( $content, $deal_id ) {
	$deal = Group_Buying_Deal::get_instance( $deal_id );
	
	$price = gb_get_formatted_money( gb_get_price($deal_id));
	$content = str_replace( '<?php gb_formatted_money( gb_get_price( gb_get_vouchers_deal_id() ) ); ?>', $price, $content );
	
	$worth = gb_get_formatted_money( gb_get_deal_worth($deal_id));
	$content = str_replace( '<?php gb_formatted_money( gb_get_deal_worth( gb_get_vouchers_deal_id() ) ); ?>', $worth, $content );
	
	$excerpt = gb_get_rss_excerpt($deal_id);
	$content = str_replace( '<?php gb_rss_excerpt( gb_get_vouchers_deal_id() ); ?>', $excerpt, $content );
	
	//If we have expiration comments
	$expiration = '';
	if ( class_exists('SF_Deal_Fields') ) {
		$expiration = SF_Deal_Fields::get_sf_custom_deal_field($deal_id, 'voucher_expiration_comments');
	}
	if ( empty($expiration) ) {
		$expiration = ( $deal->get_voucher_expiration_date() ) ? $deal->get_voucher_expiration_date() : time()+60*60*24*14;
		$expiration = date( $format, $expiration );
	}
	$content = str_replace( '<?php gb_voucher_expiration_date(get_the_ID()); ?>', $expiration, $content );
	
	//merchant title
	if ( gb_has_merchant($deal_id) ) {
		$merchant_id = gb_get_merchant_id($deal_id);
		$merchant = '<div class="clearfix"><p class="title" style="font-size: larger;">'.gb_get_merchant_name( $merchant_id ).'</p></div>';
	} else {
		$merchant = '';
	}
	$content = str_replace( '<div class="clearfix"><p class="title" style="font-size: larger;"><?php gb_merchant_name( gb_get_merchant_id(gb_get_vouchers_deal_id()) ); ?></p></div>', $merchant, $content );
	
	
	
	
	return $content;
}

//Add Admin option to Redeem voucher
add_filter('gb_mngt_vouchers_columns', 'custom_gb_mngt_vouchers_columns', 10, 1);
function custom_gb_mngt_vouchers_columns($columns) {
	//Rebuild claimed column (keep existing functionality)
	unset($columns['claimed']);
	$columns['claimed_custom'] = gb__( 'Redeemed' );
	return $columns;	
}
add_filter('gb_mngt_vouchers_column_claimed_custom', 'custom_gb_mngt_vouchers_column_claimed', 0, 1);
function custom_gb_mngt_vouchers_column_claimed($item) {
	$voucher = Group_Buying_Voucher::get_instance( $item->ID );
	$claim_date = $voucher->get_claimed_date();
	$status = '';
	if ( $claim_date ) {
		$status = '<p>' . mysql2date( get_option( 'date_format' ).' @ '.get_option( 'time_format' ), $claim_date ) . '</p>';
		$status .= '<p><span id="'.$item->ID.'_unclaim_result"></span><a href="javascript:void(0)" class="gb_unclaim button disabled" id="'.$item->ID.'_unclaim" ref="'.$item->ID.'">'.gb__( 'Remove Redemption' ).'</a></p>';
	} else {
		//Add claim option
		$status = '<p><span id="'.$item->ID.'_claim_result"></span><a href="javascript:void(0)" class="gb_claim button disabled" id="'.$item->ID.'_claim" ref="'.$item->ID.'">'.gb__( 'Mark as Redeemed' ).'</a></p>';
	}
	return $status;
}
add_action('admin_footer', 'custom_admin_footer_scripts');
function custom_admin_footer_scripts() {
	//Add claim ajax call (unclaim already exists in GBS)
	?>
    <script type="text/javascript" charset="utf-8">
			jQuery(document).ready(function($){
				jQuery(".gb_claim").on('click', function(event) {
					event.preventDefault();
						if(confirm("Are you sure you want to mark as Redeemed?")){
							var $claim_button = $( this ),
							claim_voucher_id = $claim_button.attr( 'ref' );
							$( "#"+claim_voucher_id+"_claim" ).fadeOut('slow');
							$.post( ajaxurl, { action: 'gb_mark_voucher', voucher_id: claim_voucher_id, mark_voucher: 1 },
								function( data ) {
										$( "#"+claim_voucher_id+"_claim_result" ).append( '<?php gb_e( "Voucher marked as Redeemed." ) ?>' ).fadeIn();
									}
								);
						} else {
							// nothing to do.
						}
				});
			});
		</script>
    <?php	
}

