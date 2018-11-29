<?php

function wpadintgr_plugin_path( $path = '' ) {
	return path_join( WPADINTGR_PLUGIN_DIR, trim( $path, '/' ) );
}

function wpadintgr_plugin_url( $path = '' ) {
	$url = plugins_url( $path, WPADINTGR_PLUGIN );

	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}

function wpadintgr_get_request_uri() {
	static $request_uri = '';

	if ( empty( $request_uri ) ) {
		$request_uri = add_query_arg( array() );
	}

	return esc_url_raw( $request_uri );
}

function wpadintgr_register_post_types() {
	if ( class_exists( 'WPAdIntgr_Form' ) ) {
		WPAdIntgr_Form::register_post_type();
		return true;
	} else {
		return false;
	}
}

function wpadintgr_verify_nonce( $nonce, $action = 'wp_rest' ) {
	return wp_verify_nonce( $nonce, $action );
}

function wpadintgr_create_nonce( $action = 'wp_rest' ) {
	return wp_create_nonce( $action );
}

function wpadintgr_format_atts( $atts ) {
	$html = '';

	$prioritized_atts = array( 'type', 'name', 'value' );

	foreach ( $prioritized_atts as $att ) {
		if ( isset( $atts[$att] ) ) {
			$value = trim( $atts[$att] );
			$html .= sprintf( ' %s="%s"', $att, esc_attr( $value ) );
			unset( $atts[$att] );
		}
	}

	foreach ( $atts as $key => $value ) {
		$key = strtolower( trim( $key ) );

		if ( ! preg_match( '/^[a-z_:][a-z_:.0-9-]*$/', $key ) ) {
			continue;
		}

		$value = trim( $value );

		if ( '' !== $value ) {
			$html .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
		}
	}

	$html = trim( $html );

	return $html;
}

function wpadintgr_array_flatten( $input ) {
	if ( ! is_array( $input ) ) {
		return array( $input );
	}

	$output = array();

	foreach ( $input as $value ) {
		$output = array_merge( $output, wpadintgr_array_flatten( $value ) );
	}

	return $output;
}

function wpadintgr_flat_join( $input ) {
	$input = wpadintgr_array_flatten( $input );
	$output = array();

	foreach ( (array) $input as $value ) {
		$output[] = trim( (string) $value );
	}

	return implode( ', ', $output );
}

function wpadintgr_normalize_newline( $text, $to = "\n" ) {
	if ( ! is_string( $text ) ) {
		return $text;
	}

	$nls = array( "\r\n", "\r", "\n" );

	if ( ! in_array( $to, $nls ) ) {
		return $text;
	}

	return str_replace( $nls, $to, $text );
}

function wpadintgr_normalize_newline_deep( $arr, $to = "\n" ) {
	if ( is_array( $arr ) ) {
		$result = array();

		foreach ( $arr as $key => $text ) {
			$result[$key] = wpadintgr_normalize_newline_deep( $text, $to );
		}

		return $result;
	}

	return wpadintgr_normalize_newline( $arr, $to );
}

function wpadintgr_strip_newline( $str ) {
	$str = (string) $str;
	$str = str_replace( array( "\r", "\n" ), '', $str );
	return trim( $str );
}

function wpadintgr_is_localhost() {
	$server_name = strtolower( $_SERVER['SERVER_NAME'] );
	return in_array( $server_name, array( 'localhost', '127.0.0.1' ) );
}

function wpadintgr_deprecated_function( $function, $version, $replacement ) {
	$trigger_error = apply_filters( 'deprecated_function_trigger_error', true );

	if ( WP_DEBUG && $trigger_error ) {
		if ( function_exists( '__' ) ) {
			trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Ad Integration Form version %2$s! Use %3$s instead.', 'adintgr-form' ), $function, $version, $replacement ) );
		} else {
			trigger_error( sprintf( '%1$s is <strong>deprecated</strong> since Ad Integration Form version %2$s! Use %3$s instead.', $function, $version, $replacement ) );
		}
	}
}
