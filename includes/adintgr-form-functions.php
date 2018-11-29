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

	} else {
		if ( is_string( $atts ) ) {
			$atts = explode( ' ', $atts, 2 );
		}

		$id = (int) array_shift( $atts );
		$form = wpadintgr_get_form_by_old_id( $id );
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

	$loop = count($args['selectors']);
	$properties['selectors'] = [];
	for ($i = 0; $i < $loop; $i++) {
		$properties['selectors'][] = array(
			'check' => isset($args['check'][$i]) ? $args['check'][$i] : "",

			'name' => isset($args['name'][$i]) ? $args['name'][$i] : "",
			'slug' => isset($args['slug'][$i]) ? $args['slug'][$i] : "",
			'url' => isset($args['url'][$i]) ? $args['url'][$i] : "",

			'type' => isset($args['type'][$i]) ? $args['type'][$i] : "",

			'media_comment' => isset($args['media_comment'][$i]) ? $args['media_comment'][$i] : "",
			'media_type' => isset($args['media_type'][$i]) ? $args['media_type'][$i] : "",
			'media_placeid' => isset($args['media_placeid'][$i]) ? $args['media_placeid'][$i] : "",
			'media_uaclass' => isset($args['media_uaclass'][$i]) ? $args['media_uaclass'][$i] : "",
			'media_sub1' => isset($args['media_sub1'][$i]) ? $args['media_sub1'][$i] : "",
			'media_sub2' => isset($args['media_sub2'][$i]) ? $args['media_sub2'][$i] : "",
			'media_sub3' => isset($args['media_sub3'][$i]) ? $args['media_sub3'][$i] : "",
			'media_target' => isset($args['media_target'][$i]) ? $args['media_target'][$i] : "",
		);
	}

	$form->set_properties( $properties );

	do_action( 'wpadintgr_save_form', $form, $args, $context );

	if ( 'save' == $context ) {
		$form->save();
	}

	return $form;
}
