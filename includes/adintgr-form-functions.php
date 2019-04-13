<?php

function wpadintgr_form( $id ) {
	return WPAdIntgr_Form::get_instance( $id );
}

function wpadintgr_get_form_by_title( $title ) {
	$page = get_page_by_title( $title, OBJECT, WPAdIntgr_Form::post_type );

	if ( $page ) {
		return wpadintgr_form( $page->ID );
	}

	return null;
}

function wpadintgr_get_current_form() {
	if ( $current = WPAdIntgr_Form::get_current() ) {
		return $current;
	}
}

function wpadintgr_is_posted() {
	if ( ! $form = wpadintgr_get_current_form() ) {
		return false;
	}

	return $form->is_posted();
}

function wpadintgr_form_func( $atts, $content = null, $code = '' ) {
	if ( is_feed() ) {
		return '[adintgr-form]';
	}

	if ( 'adintgr-form' == $code ) {
		$atts = shortcode_atts(
			array(
				'id' => 0,
				'title' => '',
			),
			$atts, 'wpadintgr'
		);

		$id = (int) $atts['id'];
		$title = trim( $atts['title'] );

		if ( ! $form = wpadintgr_form( $id ) ) {
			$form = wpadintgr_get_form_by_title( $title );
		}
	}

	if ( ! $form ) {
		return '[adintgr-form 404 "Not Found"]';
	}
	
	return $form->form_html( $atts );
}

function wpadintgr_save_form( $args = '', $context = 'save' ) {
	$args = wp_parse_args( $args, array(
		'id' => -1,
		'title' => null,
		'locale' => null,
		'selectors' => null,
		'custom_code' => null,
		'custom_color' => null,
	));

	$args['id'] = (int) $args['id'];

	if ( -1 == $args['id'] ) {
		$form = WPAdIntgr_Form::get_template();
	} else {
		$form = wpadintgr_form( $args['id'] );
	}

	if ( empty( $form ) ) {
		return false;
	}

	if ( null !== $args['title'] ) {
		$form->set_title( $args['title'] );
	}

	if ( null !== $args['locale'] ) {
		$form->set_locale( $args['locale'] );
	}

	$properties = $form->get_properties();

	$loop = count($args['selector_name']);
	$properties[ 'selectors' ] = [];
	for ($i = 0; $i < $loop; $i++) {
		$properties[ 'selectors' ][] = array(
			'selector_check' => isset($args['selector_check'][$i]) ? $args['selector_check'][$i] : "",
			
			'selector_name' => isset($args['selector_name'][$i]) ? $args['selector_name'][$i] : "",
			
			'selector_exit_check' => isset($args['selector_exit_check'][$i]) ? $args['selector_exit_check'][$i] : "",
			'main_exit_period' => isset($args['main_exit_period'][$i]) ? $args['main_exit_period'][$i] : "",
			'main_exit_type' => isset($args['main_exit_type'][$i]) ? $args['main_exit_type'][$i] : "",
			'main_exit_url' => isset($args['main_exit_url'][$i]) ? $args['main_exit_url'][$i] : "",
			'main_exit_header' => isset($args['main_exit_header'][$i]) ? $args['main_exit_header'][$i] : "",
			'main_exit_code' => isset($args['main_exit_code'][$i]) ? $args['main_exit_code'][$i] : "",

			'main_exit_media_comment' => isset($args['main_exit_media_comment'][$i]) ? $args['main_exit_media_comment'][$i] : "",
			'main_exit_media_type' => isset($args['main_exit_media_type'][$i]) ? $args['main_exit_media_type'][$i] : "",
			'main_exit_media_placeid' => isset($args['main_exit_media_placeid'][$i]) ? $args['main_exit_media_placeid'][$i] : "",
			'main_exit_media_uaclass' => isset($args['main_exit_media_uaclass'][$i]) ? $args['main_exit_media_uaclass'][$i] : "",
			'main_exit_media_sub1' => isset($args['main_exit_media_sub1'][$i]) ? $args['main_exit_media_sub1'][$i] : "",
			'main_exit_media_sub2' => isset($args['main_exit_media_sub2'][$i]) ? $args['main_exit_media_sub2'][$i] : "",
			'main_exit_media_sub3' => isset($args['main_exit_media_sub3'][$i]) ? $args['main_exit_media_sub3'][$i] : "",
			
			'main_exit_insurance_token' => isset($args['main_exit_insurance_token'][$i]) ? $args['main_exit_insurance_token'][$i] : "",
			'main_exit_insurance_userid' => isset($args['main_exit_insurance_userid'][$i]) ? $args['main_exit_insurance_userid'][$i] : "",
			'main_exit_insurance_type' => isset($args['main_exit_insurance_type'][$i]) ? $args['main_exit_insurance_type'][$i] : "",
			'main_exit_insurance_state' => isset($args['main_exit_insurance_state'][$i]) ? $args['main_exit_insurance_state'][$i] : "",
			
			'selector_type' => isset($args['selector_type'][$i]) ? $args['selector_type'][$i] : "",
			
			// Leave Page
			'leave_type' => isset($args['leave_type'][$i]) ? $args['leave_type'][$i] : "",
			'leave_url' => isset($args['leave_url'][$i]) ? $args['leave_url'][$i] : "",
			'leave_header' => isset($args['leave_header'][$i]) ? $args['leave_header'][$i] : "",
			'leave_code' => isset($args['leave_code'][$i]) ? $args['leave_code'][$i] : "",

			'leave_media_comment' => isset($args['leave_media_comment'][$i]) ? $args['leave_media_comment'][$i] : "",
			'leave_media_type' => isset($args['leave_media_type'][$i]) ? $args['leave_media_type'][$i] : "",
			'leave_media_placeid' => isset($args['leave_media_placeid'][$i]) ? $args['leave_media_placeid'][$i] : "",
			'leave_media_uaclass' => isset($args['leave_media_uaclass'][$i]) ? $args['leave_media_uaclass'][$i] : "",
			'leave_media_sub1' => isset($args['leave_media_sub1'][$i]) ? $args['leave_media_sub1'][$i] : "",
			'leave_media_sub2' => isset($args['leave_media_sub2'][$i]) ? $args['leave_media_sub2'][$i] : "",
			'leave_media_sub3' => isset($args['leave_media_sub3'][$i]) ? $args['leave_media_sub3'][$i] : "",

			'leave_insurance_token' => isset($args['leave_insurance_token'][$i]) ? $args['leave_insurance_token'][$i] : "",
			'leave_insurance_userid' => isset($args['leave_insurance_userid'][$i]) ? $args['leave_insurance_userid'][$i] : "",
			'leave_insurance_type' => isset($args['leave_insurance_type'][$i]) ? $args['leave_insurance_type'][$i] : "",
			'leave_insurance_state' => isset($args['leave_insurance_state'][$i]) ? $args['leave_insurance_state'][$i] : "",
			
			'leave_exit_check' => isset($args['leave_exit_check'][$i]) ? $args['leave_exit_check'][$i] : "",
			'leave_exit_period' => isset($args['leave_exit_period'][$i]) ? $args['leave_exit_period'][$i] : "",
			'leave_exit_type' => isset($args['leave_exit_type'][$i]) ? $args['leave_exit_type'][$i] : "",
			'leave_exit_url' => isset($args['leave_exit_url'][$i]) ? $args['leave_exit_url'][$i] : "",
			'leave_exit_header' => isset($args['leave_exit_header'][$i]) ? $args['leave_exit_header'][$i] : "",
			'leave_exit_code' => isset($args['leave_exit_code'][$i]) ? $args['leave_exit_code'][$i] : "",

			'leave_exit_media_comment' => isset($args['leave_exit_media_comment'][$i]) ? $args['leave_exit_media_comment'][$i] : "",
			'leave_exit_media_type' => isset($args['leave_exit_media_type'][$i]) ? $args['leave_exit_media_type'][$i] : "",
			'leave_exit_media_placeid' => isset($args['leave_exit_media_placeid'][$i]) ? $args['leave_exit_media_placeid'][$i] : "",
			'leave_exit_media_uaclass' => isset($args['leave_exit_media_uaclass'][$i]) ? $args['leave_exit_media_uaclass'][$i] : "",
			'leave_exit_media_sub1' => isset($args['leave_exit_media_sub1'][$i]) ? $args['leave_exit_media_sub1'][$i] : "",
			'leave_exit_media_sub2' => isset($args['leave_exit_media_sub2'][$i]) ? $args['leave_exit_media_sub2'][$i] : "",
			'leave_exit_media_sub3' => isset($args['leave_exit_media_sub3'][$i]) ? $args['leave_exit_media_sub3'][$i] : "",

			'leave_exit_insurance_token' => isset($args['leave_exit_insurance_token'][$i]) ? $args['leave_exit_insurance_token'][$i] : "",
			'leave_exit_insurance_userid' => isset($args['leave_exit_insurance_userid'][$i]) ? $args['leave_exit_insurance_userid'][$i] : "",
			'leave_exit_insurance_type' => isset($args['leave_exit_insurance_type'][$i]) ? $args['leave_exit_insurance_type'][$i] : "",
			'leave_exit_insurance_state' => isset($args['leave_exit_insurance_state'][$i]) ? $args['leave_exit_insurance_state'][$i] : "",
			
			// Popup Page
			'popup_type' => isset($args['popup_type'][$i]) ? $args['popup_type'][$i] : "",
			'popup_url' => isset($args['popup_url'][$i]) ? $args['popup_url'][$i] : "",
			'popup_header' => isset($args['popup_header'][$i]) ? $args['popup_header'][$i] : "",
			'popup_code' => isset($args['popup_code'][$i]) ? $args['popup_code'][$i] : "",

			'popup_media_comment' => isset($args['popup_media_comment'][$i]) ? $args['popup_media_comment'][$i] : "",
			'popup_media_type' => isset($args['popup_media_type'][$i]) ? $args['popup_media_type'][$i] : "",
			'popup_media_placeid' => isset($args['popup_media_placeid'][$i]) ? $args['popup_media_placeid'][$i] : "",
			'popup_media_uaclass' => isset($args['popup_media_uaclass'][$i]) ? $args['popup_media_uaclass'][$i] : "",
			'popup_media_sub1' => isset($args['popup_media_sub1'][$i]) ? $args['popup_media_sub1'][$i] : "",
			'popup_media_sub2' => isset($args['popup_media_sub2'][$i]) ? $args['popup_media_sub2'][$i] : "",
			'popup_media_sub3' => isset($args['popup_media_sub3'][$i]) ? $args['popup_media_sub3'][$i] : "",

			'popup_insurance_token' => isset($args['popup_insurance_token'][$i]) ? $args['popup_insurance_token'][$i] : "",
			'popup_insurance_userid' => isset($args['popup_insurance_userid'][$i]) ? $args['popup_insurance_userid'][$i] : "",
			'popup_insurance_type' => isset($args['popup_insurance_type'][$i]) ? $args['popup_insurance_type'][$i] : "",
			'popup_insurance_state' => isset($args['popup_insurance_state'][$i]) ? $args['popup_insurance_state'][$i] : "",
			
			'popup_exit_check' => isset($args['popup_exit_check'][$i]) ? $args['popup_exit_check'][$i] : "",
			'popup_exit_period' => isset($args['popup_exit_period'][$i]) ? $args['popup_exit_period'][$i] : "",
			'popup_exit_type' => isset($args['popup_exit_type'][$i]) ? $args['popup_exit_type'][$i] : "",
			'popup_exit_url' => isset($args['popup_exit_url'][$i]) ? $args['popup_exit_url'][$i] : "",
			'popup_exit_header' => isset($args['popup_exit_header'][$i]) ? $args['popup_exit_header'][$i] : "",
			'popup_exit_code' => isset($args['popup_exit_code'][$i]) ? $args['popup_exit_code'][$i] : "",

			'popup_exit_media_comment' => isset($args['popup_exit_media_comment'][$i]) ? $args['popup_exit_media_comment'][$i] : "",
			'popup_exit_media_type' => isset($args['popup_exit_media_type'][$i]) ? $args['popup_exit_media_type'][$i] : "",
			'popup_exit_media_placeid' => isset($args['popup_exit_media_placeid'][$i]) ? $args['popup_exit_media_placeid'][$i] : "",
			'popup_exit_media_uaclass' => isset($args['popup_exit_media_uaclass'][$i]) ? $args['popup_exit_media_uaclass'][$i] : "",
			'popup_exit_media_sub1' => isset($args['popup_exit_media_sub1'][$i]) ? $args['popup_exit_media_sub1'][$i] : "",
			'popup_exit_media_sub2' => isset($args['popup_exit_media_sub2'][$i]) ? $args['popup_exit_media_sub2'][$i] : "",
			'popup_exit_media_sub3' => isset($args['popup_exit_media_sub3'][$i]) ? $args['popup_exit_media_sub3'][$i] : "",

			'popup_exit_insurance_token' => isset($args['popup_exit_insurance_token'][$i]) ? $args['popup_exit_insurance_token'][$i] : "",
			'popup_exit_insurance_userid' => isset($args['popup_exit_insurance_userid'][$i]) ? $args['popup_exit_insurance_userid'][$i] : "",
			'popup_exit_insurance_type' => isset($args['popup_exit_insurance_type'][$i]) ? $args['popup_exit_insurance_type'][$i] : "",
			'popup_exit_insurance_state' => isset($args['popup_exit_insurance_state'][$i]) ? $args['popup_exit_insurance_state'][$i] : "",
		);
	}

	$properties[ 'custom_code' ] = $args['custom_code'];
	$properties[ 'custom_color' ] = $args['custom_color'];


	$form->set_properties( $properties );

	do_action( 'wpadintgr_save_form', $form, $args, $context );

	if ( 'save' == $context ) {
		$form->save();
	}

	return $form;
}