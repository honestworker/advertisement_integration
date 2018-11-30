<?php

function wpadintgr_current_action() {
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
		return $_REQUEST['action'];
	}

	return false;
}

add_action( 'admin_init', 'wpadintgr_admin_init' );
function wpadintgr_admin_init() {
	do_action( 'wpadintgr_admin_init' );
}

add_action( 'admin_menu', 'wpadintgr_admin_menu', 9 );
function wpadintgr_admin_menu() {
	global $_wp_last_object_menu;

	$_wp_last_object_menu++;

	add_menu_page( __( 'Ad Integration Form', 'adintgr-form' ), __( 'Ad Integration', 'adintgr-form' ), 'wpadintgr_read_forms', 'wpadintgr',  'wpadintgr_admin_management_page', 'dashicons-paperclip', $_wp_last_object_menu );

    $edit = add_submenu_page( 'wpadintgr', __( 'Edit Ad Integration Form', 'adintgr-form' ), __( 'Ad Integration Forms', 'adintgr-form' ), 'wpadintgr_read_forms', 'wpadintgr', 'wpadintgr_admin_management_page' );
	add_action( 'load-' . $edit, 'wpadintgr_load_form_admin' );

    $addnew = add_submenu_page( 'wpadintgr', __( 'Add New Ad Integration Form', 'adintgr-form' ), __( 'Add New', 'adintgr-form' ), 'wpadintgr_edit_forms', 'wpadintgr-new', 'wpadintgr_admin_add_new_page' );
	add_action( 'load-' . $addnew, 'wpadintgr_load_form_admin' );
}

function wpadintgr_load_form_admin() {
	global $plugin_page;

	$action = wpadintgr_current_action();

	if ( 'save' == $action ) {
		$id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : '-1';
		check_admin_referer( 'wpadintgr-save-form_' . $id );

		if ( ! current_user_can( 'wpadintgr_edit_form', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', 'adintgr-form' ) );
		}

		$args = $_REQUEST;
		$args['id'] = $id;
		$args['title'] = isset( $_POST['post_title'] ) ? $_POST['post_title'] : null;
		$args['locale'] = isset( $_POST['wpadintgr-locale'] ) ? $_POST['wpadintgr-locale'] : null;

        $form = wpadintgr_save_form( $args );

		$query = array(
			'post' => $form ? $form->id() : 0
		);
		$redirect_to = add_query_arg( $query, menu_page_url( 'wpadintgr', false ) );
        
        wp_safe_redirect( $redirect_to );
		exit();
	} else if ( 'copy' == $action ) {
		$id = empty( $_POST['post_ID'] ) ? absint( $_REQUEST['post'] ) : absint( $_POST['post_ID'] );

		check_admin_referer( 'wpadintgr-copy-form_' . $id );

		if ( ! current_user_can( 'wpadintgr_edit_form', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', 'adintgr-form' ) );
		}

		$query = array();
		if ( $form = wpadintgr_form( $id ) ) {
			$new_form = $form->copy();
			$new_form->save();
			$query['post'] = $new_form->id();
		}
		$redirect_to = add_query_arg( $query, menu_page_url( 'wpadintgr', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	} else if ( 'delete' == $action ) {
		if ( ! empty( $_POST['post_ID'] ) ) {
			check_admin_referer( 'wpadintgr-delete-form_' . $_POST['post_ID'] );
		} elseif ( ! is_array( $_REQUEST['post'] ) ) {
			check_admin_referer( 'wpadintgr-delete-form_' . $_REQUEST['post'] );
		} else {
			check_admin_referer( 'bulk-posts' );
		}

		$posts = empty( $_POST['post_ID'] ) ? (array) $_REQUEST['post'] : (array) $_POST['post_ID'];

		$deleted = 0;
		foreach ( $posts as $post ) {
			$post = WPAdIntgr_Form::get_instance( $post );
			if ( empty( $post ) ) {
				continue;
			}
			if ( ! current_user_can( 'wpadintgr_delete_form', $post->id() ) ) {
				wp_die( __( 'You are not allowed to delete this item.', 'adintgr-form' ) );
			}
			if ( ! $post->delete() ) {
				wp_die( __( 'Error in deleting.', 'adintgr-form' ) );
			}
			$deleted += 1;
		}

		$query = array();
		$redirect_to = add_query_arg( $query, menu_page_url( 'wpadintgr', false ) );

		wp_safe_redirect( $redirect_to );
		exit();
	}

	$_GET['post'] = isset( $_GET['post'] ) ? $_GET['post'] : '';

	$post = null;

	if ( 'wpadintgr-new' == $plugin_page ) {
		$post = WPAdIntgr_Form::get_template( array( 'locale' => isset( $_GET['locale'] ) ? $_GET['locale'] : null ) );
	} elseif ( ! empty( $_GET['post'] ) ) {
		$post = WPAdIntgr_Form::get_instance( $_GET['post'] );
	}

	if ( ! class_exists( 'WPAdIntgr_Form_List_Table' ) ) {
		require_once WPADINTGR_PLUGIN_DIR . '/admin/class-forms-list-table.php';
	}
	
	$current_screen = get_current_screen();
    add_filter( 'manage_' . $current_screen->id . '_columns', array( 'WPAdIntgr_Form_List_Table', 'define_columns' ) );

    add_screen_option( 'per_page', array(
        'default' => 20,
        'option' => 'wpadintgr_forms_per_page',
    ));
}

add_action( 'admin_enqueue_scripts', 'wpadintgr_admin_enqueue_scripts' );
function wpadintgr_admin_enqueue_scripts( $hook_suffix ) {
	if ( false === strpos( $hook_suffix, 'wpadintgr' ) ) {
		return;
	}

	wp_enqueue_style( 'adintgr-form-admin', wpadintgr_plugin_url( 'admin/css/style.css' ), array(), WPADINTGR_VERSION, 'all' );

	if ( wpadintgr_is_rtl() ) {
		wp_enqueue_style( 'adintgr-form-admin-rtl', wpadintgr_plugin_url( 'admin/css/styles-rtl.css' ), array(), WPADINTGR_VERSION, 'all' );
	}

	wp_enqueue_script( 'wpadintgr-admin-blockui', wpadintgr_plugin_url( 'admin/js/jquery.blockUI.js' ), array( 'jquery' ), WPADINTGR_VERSION, true );
	wp_enqueue_script( 'wpadintgr-admin-tiptip', wpadintgr_plugin_url( 'admin/js/jquery.tipTip.js' ), array( 'jquery' ), WPADINTGR_VERSION, true );
	wp_enqueue_script( 'wpadintgr-admin-meta-box', wpadintgr_plugin_url( 'admin/js/meta-box.js' ), array( 'jquery' ), WPADINTGR_VERSION, true );
	wp_enqueue_script( 'wpadintgr-admin-selector', wpadintgr_plugin_url( 'admin/js/box-selector.js' ), array( 'jquery' ), WPADINTGR_VERSION, true );

	$params = array(
		'ajax_url'                            		=> admin_url( 'admin-ajax.php' ),
		'i18n_remove_selector'                      => esc_js( __( 'Are you sure you want to remove this selector?', 'adintgr-form' ) ),
		'i18n_selector_count_single'				=> esc_js( __( '%qty% selector', 'adintgr-form' ) ),
		'i18n_selector_count_plural'				=> esc_js( __( '%qty% selectors', 'adintgr-form' ) ),
		'selectors_per_page'                 		=> absint( apply_filters( 'adintgrform_admin_meta_boxes_selectors_per_page', 5 ) ),
	);

	wp_localize_script( 'wpadintgr-admin-meta-box', 'adintgrform_admin_meta_boxes_selectors', $params );

}

function wpadintgr_admin_management_page() {
	if ( $post = wpadintgr_get_current_form() ) {
		$post_id = $post->initial() ? -1 : $post->id();

		require_once WPADINTGR_PLUGIN_DIR . '/admin/edit-form.php';
		return;
	}

	$list_table = new WPAdIntgr_Form_List_Table();
	$list_table->prepare_items();

?>
<div class="wrap">

<h1 class="wp-heading-inline"><?php
	echo esc_html( __( 'Ad Integration Forms', 'adintgr-form' ) );
?></h1>

<?php
	if ( current_user_can( 'wpadintgr_edit_forms' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
			esc_url( menu_page_url( 'wpadintgr-new', false ) ),
			esc_html( __( 'Add New', 'adintgr-form' ) ) );
	}

	if ( ! empty( $_REQUEST['s'] ) ) {
		echo sprintf( '<span class="subtitle">'
			/* translators: %s: search keywords */
			. __( 'Search results for &#8220;%s&#8221;', 'adintgr-form' ) . '</span>', esc_html( $_REQUEST['s'] ) );
	}
?>

<hr class="wp-header-end">

<form method="get" action="">
	<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
	<?php $list_table->search_box( __( 'Search Forms', 'adintgr-form' ), 'adintgr-form' ); ?>
	<?php $list_table->display(); ?>
</form>

</div>
<?php
}

function wpadintgr_admin_bulk_validate_page() {
	$forms = WPAdIntgr_Form::find();
	$count = WPAdIntgr_Form::count();

	$submit_text = sprintf(
		/* translators: %s: number of Ad Integration Forms */
		_n(
			"%s Form Now",
			"%s Forms Now",
			$count, 'adintgr-form' ),
		number_format_i18n( $count ) );

?>
<div class="wrap">

</div>
<?php
}

function wpadintgr_admin_add_new_page() {
	$post = wpadintgr_get_current_form();

	if ( ! $post ) {
		$post = WPAdIntgr_Form::get_template();
	}

	$post_id = -1;

	require_once WPADINTGR_PLUGIN_DIR . '/admin/edit-form.php';
}