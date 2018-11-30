<?php

add_filter( 'map_meta_cap', 'wpadintgr_map_meta_cap', 10, 4 );

function wpadintgr_map_meta_cap( $caps, $cap, $user_id, $args ) {
	$meta_caps = array(
		'wpadintgr_edit_form' => WPADINTGR_ADMIN_READ_WRITE_CAPABILITY,
		'wpadintgr_edit_forms' => WPADINTGR_ADMIN_READ_WRITE_CAPABILITY,
		'wpadintgr_read_forms' => WPADINTGR_ADMIN_READ_CAPABILITY,
		'wpadintgr_delete_form' => WPADINTGR_ADMIN_READ_WRITE_CAPABILITY,
		'wpadintgr_submit' => 'read',
	);

	$meta_caps = apply_filters( 'wpadintgr_map_meta_cap', $meta_caps );

	$caps = array_diff( $caps, array_keys( $meta_caps ) );

	if ( isset( $meta_caps[$cap] ) ) {
		$caps[] = $meta_caps[$cap];
	}

	return $caps;
}