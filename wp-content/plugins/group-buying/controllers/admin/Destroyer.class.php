<?php

/**
 * Record Controller
 *
 * @package GBS
 * @subpackage Base
 */
class Group_Buying_Destroy extends Group_Buying_Controller {

	const NONCE = 'gbs_destroyer_nonce';

	private static $instance;

	public static function init() {
		add_action( 'admin_head', array( get_class(), 'trash_warnings' ) );
		add_action( 'before_delete_post',  array( get_class(), 'destroyed_deal' ), 10, 1 );

		add_action( 'wp_ajax_gbs_deactivate_voucher',  array( get_class(), 'maybe_deactivate_voucher' ), 10, 0 );
		add_action( 'wp_ajax_gbs_void_payment',  array( get_class(), 'maybe_void_payment' ), 10, 0 );
		add_action( 'wp_ajax_gbs_destroyer',  array( get_class(), 'destroy' ), 10, 0 );

		add_action( 'init',  array( get_class(), 'suspension' ) );
	}

	function trash_warnings() {
		if ( isset( $_GET['post_status'] ) && isset( $_GET['post_type'] ) && $_GET['post_status'] == 'trash' && $_GET['post_type'] == 'gb_deal' ) {
			self::_e('<div class="error"><p>Permanantly deleting deals (i.e. emptying the "trash") will cause all associated orders, vouchers and payments for that deal (or deals) to be permanantly modified/deleted.</p></div>');
		}
	}

	public static function destroy() {
		// check for nonce
		if ( !isset( $_REQUEST['destroyer_nonce'] ) )
			die( 'Forget something?' );

		// Validate nonce
		$nonce = $_REQUEST['destroyer_nonce'];
		if ( !wp_verify_nonce( $nonce, self::NONCE ) )
			die ( 'Not going to fall for it!');

		// Check permissions
		if ( current_user_can( 'delete_posts' ) ) {
        	$id = $_REQUEST['id'];
        	$type = $_REQUEST['type'];
        	switch ( $type ) {
        		case 'voucher':
        			self::destroy_voucher( $id, TRUE, TRUE );
        			break;
        		case 'purchase':
        			self::destroy_purchase( $id );
        			break;
        		case 'account':
        			self::suspend_account( $id );
        			break;

        		default:
        			# code...
        			break;
        	}

        }
        exit();
	}

	public function destroyed_deal( $post_id ) {
		if ( !current_user_can( 'delete_posts' ) )
			return;

		if ( get_post_type( $post_id ) != Group_Buying_Deal::POST_TYPE )
			return;

		$deal = Group_Buying_Deal::get_instance( $post_id );

		if ( !is_a( $deal, 'Group_Buying_Deal' ) )
			return;

		$voucher_ids = Group_Buying_Voucher::get_vouchers_for_deal( $post_id );
		foreach ( $voucher_ids as $voucher_id ) {
			do_action( 'gb_log', __CLASS__ . '::' . __FUNCTION__ . ' - Voucher Destroyed', $voucher_id );
			self::destroy_voucher( $voucher_id, TRUE, TRUE );
		}
	}

	public static function maybe_deactivate_voucher() {
		if ( !isset( $_REQUEST['deactivate_voucher_nonce'] ) )
			wp_die( 'Forget something?' );

		$nonce = $_REQUEST['deactivate_voucher_nonce'];
		if ( !wp_verify_nonce( $nonce, self::NONCE ) )
        	wp_die( 'Not going to fall for it!' );

        if ( current_user_can( 'delete_posts' ) ) {

			$voucher_id = $_REQUEST['voucher_id'];
			$voucher = Group_Buying_Voucher::get_instance( $voucher_id );
			if ( !is_a( $voucher, 'Group_Buying_Voucher' ) )
				return;

			if ( $voucher->is_active() ) {
				$voucher->deactivate();
				do_action( 'gb_voucher_deactivated', $voucher_id );
			}
		}
	}

	/**
	 * Delete a voucher record.
	 * @param  integer $voucher_id         Voucher ID
	 * @param  boolean $destroy_related Destroy the related item within the purchase, which would delete the payment.
	 * @param  boolean $force_delete	If FALSE the record would be trashed.
	 * @return
	 */
	protected static function destroy_voucher( $voucher_id = 0, $destroy_related = TRUE, $force_delete = FALSE ) {
		if ( !$voucher_id )
			return;

		$voucher = Group_Buying_Voucher::get_instance( $voucher_id );
		if ( !is_a( $voucher, 'Group_Buying_Voucher' ) )
			return;

		// Deactivate if the voucher wont be deleted.
		if ( !$force_delete ) {
			$voucher->deactivate();
			return;
		}

		// Destroy the related purchase information
		if ( $destroy_related ) {
			$item_id = $voucher->get_deal_id();
			$item_data = $voucher->get_product_data();
			$purchase_id = $voucher->get_purchase_id();
			self::remove_item_from_purchases_and_payments( $item_id, $item_data, $purchase_id );
			self::reset_deal_purchase_numbers( $item_id );
		}

		// Delete the voucher
		wp_delete_post( $voucher_id, $force_delete );
		add_action( 'gb_voucher_destroyed', $voucher_id, $destroy_related, $force_delete );
	}

	/**
	 * Alter the purchase and payment records and remove the deal.
	 * @param  integer $item_id     Item ID
	 * @param  integer $purchase_id Purchase ID
	 * @return
	 */
	public static function remove_item_from_purchases_and_payments( $item_id = 0, $item_data = array(), $purchase_id = 0 ) {

		if ( !$purchase_id || !$item_id )
			return;

		do_action( 'gb_log', __CLASS__ . '::' . __FUNCTION__ . ' - item data', $item_data );

		// Get the purchase and it's items
		$purchase = Group_Buying_Purchase::get_instance( $purchase_id );

		if ( !is_a( $purchase, 'Group_Buying_Purchase') ) // Possibly already deleted
			return;

		$items = $purchase->get_products();
		do_action( 'gb_log', __CLASS__ . '::' . __FUNCTION__ . ' - pre purchase items', $items );
		// Purchase
		foreach ( $items as $key => $item ) {
			// Search for the matching item and delete it
			if ( $item_id == $item['deal_id'] ) {
				if ( count( array_diff( $item_data['data'], $item['data'] ) ) == 0 ) {
					// If purchased multiple items then just one needs to be removed.
					if ( $item['quantity'] > 1 ) {
						$original_qty = $items[$key]['quantity'];
						$original_price = $items[$key]['price'];
						$items[$key]['quantity'] = $original_qty-1;
						$items[$key]['price'] = ($original_price/$original_qty)*$items[$key]['quantity']; // (original_price/original_qty)*[new qty]
					}
					else {
						unset( $items[$key] );
					}
				}
			}
		}
		// Reset the products
		do_action( 'gb_log', __CLASS__ . '::' . __FUNCTION__ . ' - post purchase items', $items );
		$purchase->set_products( $items );

		// Payments
		$payment_ids = $purchase->get_payments();
		foreach ( $payment_ids as $payment_id ) {
			$payment = Group_Buying_Payment::get_instance( $payment_id );
			$items = $payment->get_deals();
			do_action( 'gb_log', __CLASS__ . '::' . __FUNCTION__ . ' - pre payment items', $items );
			// Search for the matching item and delete it
			foreach ( $items as $key_item_id => $purchase_item ) {
				if ( $item_id == $key_item_id ) {
					foreach ( $purchase_item as $key => $value ) {
						if ( count( array_diff( $item_data['data'], $value['data'] ) ) == 0 ) {
							// If purchased multiple items then just one needs to be removed.
							if ( $value['quantity'] > 1 ) {
								$original_qty = $value['quantity'];
								$original_price = $value['price'];
								$items[$key_item_id][$key]['quantity'] = $original_qty-1;
								$items[$key_item_id][$key]['price'] = ($original_price/$original_qty)*$items[$key_item_id][$key]['quantity']; // (original_price/original_qty)*[new qty]
							}
							else {
								unset( $items[$key_item_id] );
							}
						}
					}

				}
			}
			// Reset the deals of the payment
			do_action( 'gb_log', __CLASS__ . '::' . __FUNCTION__ . ' - post payment items', $items );
			$payment->set_deals( $items );
		}


	}

	/**
	 * Delete a purchase record.
	 * @param  integer $purchase_id         Purchase ID
	 * @param  boolean $destroy_related Destroy the related voucher(s) and payment(s).
	 * @param  boolean $force_delete	If FALSE the record would be trashed.
	 * @return
	 */
	protected static function destroy_purchase( $purchase_id = 0, $destroy_related = TRUE, $force_delete = FALSE ) {
		if ( !$purchase_id )
			return;

		$purchase = Group_Buying_Purchase::get_instance( $purchase_id );
		$items = $purchase->get_products();

		if ( $destroy_related ) {
			$payment_ids = $purchase->get_payments();
			foreach ( $payment_ids as $payment_id ) {
				self::reverse_payment( $payment_id, $force_delete, $force_delete );
			}
			$voucher_ids = $purchase->get_vouchers();
			foreach ( $voucher_ids as $voucher_id ) {
				self::destroy_voucher( $voucher_id, FALSE, $force_delete );
			}
		}

		// Delete the record
		wp_delete_post( $purchase_id, $force_delete );
		// Reset purchase counts
		foreach ( $items as $key => $item ) {
			self::reset_deal_purchase_numbers( $item['deal_id'] );
		}
		add_action( 'gb_purchase_destroyed', $purchase_id, $destroy_related, $force_delete );
	}

	public static function maybe_void_payment() {
		error_log( 'request' . print_r( $_REQUEST, TRUE ) );
		if ( !isset( $_REQUEST['void_payment_nonce'] ) )
			wp_die( 'Forget something?' );

		$nonce = $_REQUEST['void_payment_nonce'];
		if ( !wp_verify_nonce( $nonce, self::NONCE ) )
        	wp_die( 'Not going to fall for it!' );

        if ( current_user_can( 'delete_posts' ) ) {

			$payment_id = $_REQUEST['payment_id'];
			$data = ( isset( $_REQUEST['notes'] ) ) ? $_REQUEST['notes'] : '' ;
			self::void_payment( $payment_id, $data );
			do_action( 'gb_payment_voided', $payment_id );
		}
	}

	/**
	 * Destroy or Refund Payment.
	 * Default is to change the payment details so that it's marked as void
	 * @param  integet  $payment_id   Payment ID
	 * @param  boolean $destroy      Delete the post
	 * @param  boolean $force_delete If destroy is set than you might as well force delete the payment and not just trash it.
	 * @return
	 */
	public static function reverse_payment( $payment_id, $destroy = FALSE, $force_delete = FALSE ) {
		if ( $destroy ) {
			// Delete the record
			wp_delete_post( $payment_id, $force_delete );
			add_action( 'gb_destroy_payment', $payment_id, $destroy, $force_delete );
			return;
		}

		// Mark as refunded and change the
		$payment = Group_Buying_Payment::get_instance( $payment_id );
		if ( !is_a( $payment, 'Group_Buying_Payment' ) )
				return;

		$payment->set_status( Group_Buying_Payment::STATUS_REFUND );
		$payment->set_payment_method( self::__( 'Admin Reverse' ) );
		// Merge old data with new updated message
		$new_data = wp_parse_args( $payment->get_data(), array( 'updated' => sprintf( self::__( 'Reversed by User #%s on %s' ), get_current_user_id(), date( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ) ) ) ) );
		$payment->set_data( $new_data );

		add_action( 'gb_reverse_payment', $payment_id, $destroy, $new_data );
	}

	/**
	 * Void a payment
	 * @param  integet  $payment_id   Payment ID
	 * @return
	 */
	public static function void_payment( $payment_id, $new_data = '' ) {
		// Mark as refunded and change the
		$payment = Group_Buying_Payment::get_instance( $payment_id );
		if ( !is_a( $payment, 'Group_Buying_Payment' ) )
				return;

		$payment->set_status( Group_Buying_Payment::STATUS_VOID );
		$payment->set_payment_method( self::__( 'Admin Void' ) );
		// Merge old data with new updated message
		$new_data = wp_parse_args( $payment->get_data(), array( 'void_notes' => $new_data, 'updated' => sprintf( self::__( 'Voided by User #%s on %s' ), get_current_user_id(), date( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ) ) ) ) );
		$payment->set_data( $new_data );

		add_action( 'gb_void_payment', $payment_id, $destroy, $new_data );
	}

	public function reset_deal_purchase_numbers( $deal_id = 0 ) {
		if ( !$deal_id )
			return;

		$deal = Group_Buying_Deal::get_instance( $deal_id );
		return $deal->get_number_of_purchases( TRUE );
	}

	/**
	 * Marks the account as suspended
	 * @param  integer $account_id 		Account ID
	 * @param  boolean $force_delete    Delete the post
	 * @return
	 */
	public function suspend_account( $account_id = 0, $force_delete = FALSE ) {
  		if ( $force_delete ) {
			// Delete the record
			wp_delete_post( $account_id, $force_delete );
			add_action( 'gb_destroy_acount', $account_id, $force_delete );
			return;
		}
		$account = Group_Buying_Account::get_instance_by_id( $account_id );
		if ( $account->is_suspended() ) {
			$account->unsuspend();
			$unsuspended = TRUE;
		} else {
			$account->suspend();
		}
  		add_action( 'gb_destroy_acount', $account_id, $force_delete, $unsuspended );
	}

	public static function suspension() {
		$suspension_check = apply_filters( 'gb_suspension_check', TRUE );
		if ( !$suspension_check || is_admin() )
			return;

		if ( is_user_logged_in() && !current_user_can( 'delete_posts' ) ) {
			$account = Group_Buying_Account::get_instance();
			if ( $account->is_suspended() ) {
				wp_logout();
				$redirect_to = add_query_arg( array( 'account_suspended' => 1 ), home_url() );
				wp_redirect( $redirect_to );
				exit();
			}
		}
	}

	/*
	 * Singleton Design Pattern
	 * ------------------------------------------------------------- */
	private function __clone() {
		// cannot be cloned
		trigger_error( __CLASS__.' may not be cloned', E_USER_ERROR );
	}
	private function __sleep() {
		// cannot be serialized
		trigger_error( __CLASS__.' may not be serialized', E_USER_ERROR );
	}
	public static function get_instance() {
		if ( !( self::$instance && is_a( self::$instance, __CLASS__ ) ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}

}