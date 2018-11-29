<?php

class WPAdIntgr_Submission {

	private static $instance;

	private $form;
	private $status = 'init';
	private $posted_data = array();
	private $meta = array();
	private $consent = array();

	private function __construct() {}

	public static function get_instance( WPAdIntgr_Form $form = null, $args = '' ) {
		$args = wp_parse_args( $args, array(
			'skip_mail' => false,
		) );

		if ( empty( self::$instance ) ) {
			if ( null == $form ) {
				return null;
			}

			self::$instance = new self;
			self::$instance->form = $form;
			self::$instance->setup_posted_data();
			self::$instance->submit();
		} elseif ( null != $form ) {
			return null;
		}

		return self::$instance;
	}

	public static function is_restful() {
		return defined( 'REST_REQUEST' ) && REST_REQUEST;
	}

	public function get_status() {
		return $this->status;
	}

	public function set_status( $status ) {
		if ( preg_match( '/^[a-z][0-9a-z_]+$/', $status ) ) {
			$this->status = $status;
			return true;
		}

		return false;
	}

	public function is( $status ) {
		return $this->status == $status;
	}

	public function get_form() {
		return $this->form;
	}

	public function get_posted_data( $name = '' ) {
		if ( ! empty( $name ) ) {
			if ( isset( $this->posted_data[$name] ) ) {
				return $this->posted_data[$name];
			} else {
				return null;
			}
		}

		return $this->posted_data;
	}

	private function setup_posted_data() {
		$posted_data = (array) $_POST;
		$posted_data = array_diff_key( $posted_data, array( '_wpnonce' => '' ) );
		$posted_data = $this->sanitize_posted_data( $posted_data );

        $this->posted_data = apply_filters( 'wpadintgr_posted_data', $posted_data );

		return $this->posted_data;
	}

	private function sanitize_posted_data( $value ) {
		if ( is_array( $value ) ) {
			$value = array_map( array( $this, 'sanitize_posted_data' ), $value );
		} elseif ( is_string( $value ) ) {
			$value = wp_check_invalid_utf8( $value );
			$value = wp_kses_no_null( $value );
		}

		return $value;
	}

	private function submit() {
		if ( ! $this->is( 'init' ) ) {
			return $this->status;
		}

		$this->meta = array(
			'remote_ip' => $this->get_remote_ip_addr(),
			'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] )
				? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '',
			'url' => $this->get_request_url(),
			'timestamp' => current_time( 'timestamp' ),
			'container_post_id' => isset( $_POST['_wpadintgr_container_post'] )
				? (int) $_POST['_wpadintgr_container_post'] : 0,
			'current_user_id' => get_current_user_id(),
		);

		$form = $this->form;

		$this->remove_uploaded_files();

		return $this->status;
	}

	private function get_remote_ip_addr() {
		$ip_addr = '';

		if ( isset( $_SERVER['REMOTE_ADDR'] ) && WP_Http::is_ip_address( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip_addr = $_SERVER['REMOTE_ADDR'];
		}

		return apply_filters( 'wpadintgr_remote_ip_addr', $ip_addr );
	}

	private function get_request_url() {
		$home_url = untrailingslashit( home_url() );

		if ( self::is_restful() ) {
			$referer = isset( $_SERVER['HTTP_REFERER'] )
				? trim( $_SERVER['HTTP_REFERER'] ) : '';

			if ( $referer && 0 === strpos( $referer, $home_url ) ) {
				return esc_url_raw( $referer );
			}
		}

		$url = preg_replace( '%(?<!:|/)/.*$%', '', $home_url ) . wpadintgr_get_request_uri();

		return $url;
	}

	private function verify_nonce() {
		if ( ! $this->form->nonce_is_active() ) {
			return true;
		}

		return wpadintgr_verify_nonce( $_POST['_wpnonce'] );
	}

	public function get_meta( $name ) {
		if ( isset( $this->meta[$name] ) ) {
			return $this->meta[$name];
		}
	}
}
