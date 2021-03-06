<?php


/**
 * Development Class
 */
class Group_Buying_Dev_Logs extends Group_Buying {
	const LOG_TYPE = 'dev_log';
	const ERROR_TYPE = 'gb_error';
	const LOG_OPTION = 'gb_record_logs_option';
	private static $record_logs;
	private static $recorded_logs = array();
	private static $recorded_errors = array();

	public function init() {
		// Admin option
		self::$record_logs = (bool)get_option( self::LOG_OPTION, 0 );
		add_action( 'admin_init', array( get_class(), 'register_settings_fields' ), 500, 0 );

		// after
		add_action( 'init', array( get_class(), 'record_stored_logs_and_errors' ), PHP_INT_MAX );

		// action to log
		add_action( 'gb_log', array( get_class(), 'log' ), 10, 2 );
		add_action( 'gb_error', array( get_class(), 'error' ), 10, 2 );

		// purge old logs
		add_action( 'gb_cron', array( get_class(), 'purge_old_logs' ) );
	}

	public static function log( $subject, $data = array() ) {

		if ( self::DEBUG ) {
			error_log( '+++' . $subject . ' +++++++++++++++++++++' );
			if ( !empty( $data ) ) {
				error_log( print_r( $data, TRUE ) );
				// error_log( '--------------------- ' . $subject . ' END ---------------------' );
			}
		}

		if ( self::$record_logs ) {
			if ( function_exists( 'wp_get_current_user' ) ) {
				self::record_log( $subject, $data );
			}
			else {
				self::$recorded_logs[$subject] = $data;
			}
		}
	}

	public static function error( $subject, $data = array() ) {

		if ( self::DEBUG ) {
			error_log( '--- ' . $subject . ' ---------------------' );
			if ( !empty( $data ) ) {
				error_log( print_r( $data, TRUE ) );
				// error_log( '--------------------- ' . $subject . ' END ---------------------' );
			}
		}

		if ( function_exists( 'wp_get_current_user' ) ) {
			self::record_log( $subject, $data, TRUE );
		}
		else {
			self::$recorded_errors[$subject] = $data;
		}
	}

	public function record_stored_logs_and_errors() {
		// record logs
		if ( !empty( self::$recorded_logs ) ) {
			foreach ( self::$recorded_logs as $subject => $data ) {
				self::record_log( $subject, $data );
			}
			// empty
			self::$recorded_logs = array();
		}
		// records errors
		if ( !empty( self::$recorded_errors ) ) {
			foreach ( self::$recorded_errors as $subject => $data ) {
				self::record_log( $subject, $data , TRUE );
			}
			// empty
			self::$recorded_errors = array();
		}
	}

	public function record_log( $subject = '', $data = array(), $error = FALSE, $associate_id = 0 ) {
		$type = ( $error ) ? self::ERROR_TYPE : self::LOG_TYPE ;
		do_action( 'gb_new_record',
			$data,
			$type,
			$subject,
			1,
			$associate_id );
	}

	public function purge_old_logs() {
		$args = array(
			'post_type' => Group_Buying_Record::POST_TYPE,
			'post_status' => 'any',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'tax_query' => array(
							array(
								'taxonomy' => Group_Buying_Record::TAXONOMY,
								'field' => 'id',
								'terms' => self::LOG_TYPE 
							)
						)
		);

		add_filter( 'posts_where', array( get_class(), 'filter_where_with_when' ) ); // add filter to base return on dates
		$records = new WP_Query( $args );
		remove_filter( 'posts_where', array( get_class(), 'filter_where_with_when' ) ); // Remove filter
		foreach ( $records->posts as $record_id ) {
			wp_delete_post( $record_id, TRUE );
		}
	}

	public function filter_where_with_when( $where = '' ) {
		// posts 15+ old
		$offset = apply_filters( 'gb_logs_purge_filter_delay', date( 'Y-m-d', strtotime( '-15 days' ) ), $where );
		$where .= " AND post_date <= '" . $offset . "'";
		return $where;
	}


	public static function register_settings_fields() {
		$page = Group_Buying_UI::get_settings_page();
		$section = 'gb_developer';
		add_settings_section( $section, self::__( 'Advanced' ), array( get_class(), 'display_settings_section' ), $page );
		// Settings
		register_setting( $page, self::LOG_OPTION );
		// Fields
		add_settings_field( self::LOG_OPTION, self::__( 'Save Logs' ), array( get_class(), 'display_option' ), $page, $section );

	}

	public static function display_option() {
		printf( '<input type="checkbox" name="%s" value="1" %s />&nbsp;%s', self::LOG_OPTION, checked( '1', self::$record_logs, FALSE ), self::__( 'Save all logs as a gbs records (dev_log).' ) );
		printf( '<p class="description">%s</p>', self::__( 'GBS Records are found under Tools within the admin.' ) );
	}

}
