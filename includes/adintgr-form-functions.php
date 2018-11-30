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
			'selector_slug' => isset($args['selector_slug'][$i]) ? $args['selector_slug'][$i] : "",
			'selector_title' => isset($args['selector_title'][$i]) ? $args['selector_title'][$i] : "",
			
			'selector_exit_check' => isset($args['selector_exit_check'][$i]) ? $args['selector_exit_check'][$i] : "",
			'exit_type' => isset($args['exit_type'][$i]) ? $args['exit_type'][$i] : "",
			'exit_url' => isset($args['exit_url'][$i]) ? $args['exit_url'][$i] : "",
			'exit_media_header' => isset($args['exit_media_header'][$i]) ? $args['exit_media_header'][$i] : "",
			'exit_media_comment' => isset($args['exit_media_comment'][$i]) ? $args['exit_media_comment'][$i] : "",
			'exit_media_type' => isset($args['exit_media_type'][$i]) ? $args['exit_media_type'][$i] : "",
			'exit_media_placeid' => isset($args['exit_media_placeid'][$i]) ? $args['exit_media_placeid'][$i] : "",
			'exit_media_uaclass' => isset($args['exit_media_uaclass'][$i]) ? $args['exit_media_uaclass'][$i] : "",
			'exit_media_sub1' => isset($args['exit_media_sub1'][$i]) ? $args['exit_media_sub1'][$i] : "",
			'exit_media_sub2' => isset($args['exit_media_sub2'][$i]) ? $args['exit_media_sub2'][$i] : "",
			'exit_media_sub3' => isset($args['exit_media_sub3'][$i]) ? $args['exit_media_sub3'][$i] : "",
			'exit_media_code' => isset($args['exit_media_code'][$i]) ? $args['exit_media_code'][$i] : "",
			
			'selector_type' => isset($args['selector_type'][$i]) ? $args['selector_type'][$i] : "",
			
			'leave_type' => isset($args['leave_type'][$i]) ? $args['leave_type'][$i] : "",
			'leave_url' => isset($args['leave_url'][$i]) ? $args['leave_url'][$i] : "",
			'leave_media_header' => isset($args['leave_media_header'][$i]) ? $args['leave_media_header'][$i] : "",
			'leave_media_comment' => isset($args['leave_media_comment'][$i]) ? $args['leave_media_comment'][$i] : "",
			'leave_media_type' => isset($args['leave_media_type'][$i]) ? $args['leave_media_type'][$i] : "",
			'leave_media_placeid' => isset($args['leave_media_placeid'][$i]) ? $args['leave_media_placeid'][$i] : "",
			'leave_media_uaclass' => isset($args['leave_media_uaclass'][$i]) ? $args['leave_media_uaclass'][$i] : "",
			'leave_media_sub1' => isset($args['leave_media_sub1'][$i]) ? $args['leave_media_sub1'][$i] : "",
			'leave_media_sub2' => isset($args['leave_media_sub2'][$i]) ? $args['leave_media_sub2'][$i] : "",
			'leave_media_sub3' => isset($args['leave_media_sub3'][$i]) ? $args['leave_media_sub3'][$i] : "",
			'leave_media_code' => isset($args['leave_media_code'][$i]) ? $args['leave_media_code'][$i] : "",
			
			'leave_exit_check' => isset($args['leave_exit_check'][$i]) ? $args['leave_exit_check'][$i] : "",
			'leave_exit_type' => isset($args['leave_exit_type'][$i]) ? $args['leave_exit_type'][$i] : "",
			'leave_exit_url' => isset($args['leave_exit_url'][$i]) ? $args['leave_exit_url'][$i] : "",
			'leave_exit_media_header' => isset($args['leave_exit_media_header'][$i]) ? $args['leave_exit_media_header'][$i] : "",
			'leave_exit_media_comment' => isset($args['leave_exit_media_comment'][$i]) ? $args['leave_exit_media_comment'][$i] : "",
			'leave_exit_media_type' => isset($args['leave_exit_media_type'][$i]) ? $args['leave_exit_media_type'][$i] : "",
			'leave_exit_media_placeid' => isset($args['leave_exit_media_placeid'][$i]) ? $args['leave_exit_media_placeid'][$i] : "",
			'leave_exit_media_uaclass' => isset($args['leave_exit_media_uaclass'][$i]) ? $args['leave_exit_media_uaclass'][$i] : "",
			'leave_exit_media_sub1' => isset($args['leave_exit_media_sub1'][$i]) ? $args['leave_exit_media_sub1'][$i] : "",
			'leave_exit_media_sub2' => isset($args['leave_exit_media_sub2'][$i]) ? $args['leave_exit_media_sub2'][$i] : "",
			'leave_exit_media_sub3' => isset($args['leave_exit_media_sub3'][$i]) ? $args['leave_exit_media_sub3'][$i] : "",
			'leave_exit_media_code' => isset($args['leave_exit_media_code'][$i]) ? $args['leave_exit_media_code'][$i] : "",
			
			'popup_type' => isset($args['popup_type'][$i]) ? $args['popup_type'][$i] : "",
			'popup_url' => isset($args['popup_url'][$i]) ? $args['popup_url'][$i] : "",
			'popup_media_header' => isset($args['popup_media_header'][$i]) ? $args['popup_media_header'][$i] : "",
			'popup_media_comment' => isset($args['popup_media_comment'][$i]) ? $args['popup_media_comment'][$i] : "",
			'popup_media_type' => isset($args['popup_media_type'][$i]) ? $args['popup_media_type'][$i] : "",
			'popup_media_placeid' => isset($args['popup_media_placeid'][$i]) ? $args['popup_media_placeid'][$i] : "",
			'popup_media_uaclass' => isset($args['popup_media_uaclass'][$i]) ? $args['popup_media_uaclass'][$i] : "",
			'popup_media_sub1' => isset($args['popup_media_sub1'][$i]) ? $args['popup_media_sub1'][$i] : "",
			'popup_media_sub2' => isset($args['popup_media_sub2'][$i]) ? $args['popup_media_sub2'][$i] : "",
			'popup_media_sub3' => isset($args['popup_media_sub3'][$i]) ? $args['popup_media_sub3'][$i] : "",
			'popup_media_code' => isset($args['popup_media_code'][$i]) ? $args['popup_media_code'][$i] : "",
			'leave_exit_check' => isset($args['leave_exit_check'][$i]) ? $args['leave_exit_check'][$i] : "",
			
			'popup_exit_check' => isset($args['popup_exit_check'][$i]) ? $args['popup_exit_check'][$i] : "",
			'popup_exit_type' => isset($args['popup_exit_type'][$i]) ? $args['popup_exit_type'][$i] : "",
			'popup_exit_url' => isset($args['popup_exit_url'][$i]) ? $args['popup_exit_url'][$i] : "",
			'popup_exit_media_header' => isset($args['popup_exit_media_header'][$i]) ? $args['popup_exit_media_header'][$i] : "",
			'popup_exit_media_comment' => isset($args['popup_exit_media_comment'][$i]) ? $args['popup_exit_media_comment'][$i] : "",
			'popup_exit_media_type' => isset($args['popup_exit_media_type'][$i]) ? $args['popup_exit_media_type'][$i] : "",
			'popup_exit_media_placeid' => isset($args['popup_exit_media_placeid'][$i]) ? $args['popup_exit_media_placeid'][$i] : "",
			'popup_exit_media_uaclass' => isset($args['popup_exit_media_uaclass'][$i]) ? $args['popup_exit_media_uaclass'][$i] : "",
			'popup_exit_media_sub1' => isset($args['popup_exit_media_sub1'][$i]) ? $args['popup_exit_media_sub1'][$i] : "",
			'popup_exit_media_sub2' => isset($args['popup_exit_media_sub2'][$i]) ? $args['popup_exit_media_sub2'][$i] : "",
			'popup_exit_media_sub3' => isset($args['popup_exit_media_sub3'][$i]) ? $args['popup_exit_media_sub3'][$i] : "",
			'popup_exit_media_code' => isset($args['popup_exit_media_code'][$i]) ? $args['popup_exit_media_code'][$i] : "",
		);
	}

	$form->set_properties( $properties );

	do_action( 'wpadintgr_save_form', $form, $args, $context );

	if ( 'save' == $context ) {
		$form->save();
	}

	return $form;
}