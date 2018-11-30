<?php

function wpadintgr_page( $id, $index ) {
    return WPAdIntgr_Page::get_instance( $id, $index );
}

function wpadintgr_get_page_by_title( $title ) {
	$page = get_page_by_title( $title, OBJECT, WPAdIntgr_Page::post_type );

	if ( $page ) {
		return wpadintgr_page( $page->ID );
	}

	return null;
}

function wpadintgr_page_func( $atts, $content = null, $code = '' ) {
	if ( is_feed() ) {
		return '[adintgr-page]';
	}
    
	if ( 'adintgr-page' == $code ) {
		$atts = shortcode_atts(
			array(
				'id' => 0,
				'title' => '',
				'index' => '',
			),
			$atts, 'wpadintgr'
		);
		
		$id = (int) $atts['id'];
		$title = trim( $atts['title'] );
		$index = trim( $atts['index'] );
		
		if ( ! $page = wpadintgr_page( $id, $index ) ) {
			$page = wpadintgr_get_page_by_title( $title );
		}
	}

	if ( ! $page ) {
		return '[adintgr-page 404 "Not Found"]';
	}
	
	return $page->page_html( $atts );
}
