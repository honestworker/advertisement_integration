<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function wpadintgr_admin_save_button( $post_id ) {
	static $button = '';

	if ( ! empty( $button ) ) {
		echo $button;
		return;
	}

	$nonce = wp_create_nonce( 'wpadintgr-save-form_' . $post_id );

	$onclick = sprintf( "this.form._wpnonce.value = '%s';" . " this.form.action.value = 'save';" . " return true;", $nonce );
		
	$button = sprintf( '<input type="submit" class="button-primary" name="wpadintgr-save" value="%1$s" onclick="%2$s" />', esc_attr( __( 'Save', 'urllink-form' ) ), $onclick );

	echo $button;
}

?><div class="wrap">

<h1 class="wp-heading-inline"><?php
	if ( $post->initial() ) {
		echo esc_html( __( 'Add New Ad Integration Form', 'urllink-form' ) );
	} else {
		echo esc_html( __( 'Edit Ad Integration Form', 'urllink-form' ) );
	}
?></h1>

<?php
	if ( ! $post->initial() && current_user_can( 'wpadintgr_edit_forms' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>', esc_url( menu_page_url( 'wpadintgr-new', false ) ), esc_html( __( 'Add New', 'urllink-form' ) ) );
	}
?>

<hr class="wp-header-end">

<?php
if ( $post ) :

	if ( current_user_can( 'wpadintgr_edit_form', $post_id ) ) {
		$disabled = '';
	} else {
		$disabled = ' disabled="disabled"';
	}
?>
<?php
	$props = $post->get_properties();
	$selectors = $props['selectors'];
	$selectors_count = count($selectors);
	$selectors_total_pages = ceil($selectors_count / 15);
?>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'wpadintgr', false ) ) ); ?>" id="wpadintgr-admin-form-element">
	<div id="adintgr_selector_options" class="panel adintgr-metaboxes-wrapper">
		<input type="hidden" id="ajax_url" name="ajax_url" value="<?php echo admin_url( 'admin-ajax.php' ); ?>" />
		<div id="adintgr_selector_options_inner">
			<div id="titlediv">
				<div id="titlewrap">
					<label class="screen-reader-text" id="title-prompt-text" for="title"><?php echo esc_html( __( 'Enter title here', 'urllink-form' ) ); ?></label>
				<?php
					$posttitle_atts = array(
						'type' => 'text',
						'name' => 'post_title',
						'size' => 30,
						'value' => $post->initial() ? '' : $post->title(),
						'id' => 'title',
						'class' => 'adintgr-post_title',
						'spellcheck' => 'true',
						'autocomplete' => 'off',
						'disabled' =>
							current_user_can( 'wpadintgr_edit_form', $post_id ) ? '' : 'disabled',
					);

					echo sprintf( '<input %s />', wpadintgr_format_atts( $posttitle_atts ) );
				?>
				</div><!-- #titlewrap -->
				<div class="inside">
				<?php
					if ( ! $post->initial() ) :
				?>
					<p class="description">
					<label for="wpadintgr-shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", 'urllink-form' ) ); ?></label>
					<span class="shortcode wp-ui-highlight"><input type="text" id="wpadintgr-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $post->shortcode() ); ?>" /></span>
					</p>
				<?php
					endif;
				?>
				</div>
			</div>
			<?php
			if ( current_user_can( 'wpadintgr_edit_form', $post_id ) ) {
					wp_nonce_field( 'wpadintgr-save-form_' . $post_id );
				}
			?>
			<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
			<input type="hidden" id="wpadintgr-locale" name="wpadintgr-locale" value="<?php echo esc_attr( $post->locale() ); ?>" />
			<input type="hidden" id="hiddenaction" name="action" value="save" />
			
			<?php do_action( 'adintgr_selector_before_selectors' ); ?>
			
			<div id="adintgr-selector-data" class="adintgr-postbox" style="zoom: 1;">
				<div class="toolbar toolbar-top">
					<input type="button" class="button bulk_edit do_selector_action button-primary" name="wpadintgr-add-new" value="Add New"/>
					<div class="selectors-pagenav">
						<?php /* translators: selectors count */ ?>
						<span class="displaying-num"><?php echo esc_html( sprintf( _n( '%s item', '%s items', count($selectors), 'urllink-form' ), $selectors_count) ); ?></span>
						<span class="expand-close">
							(<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'urllink-form' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'urllink-form' ); ?></a>)
						</span>
						<span class="pagination-links">
							<a class="first-page disabled" title="<?php esc_attr_e( 'Go to the first page', 'urllink-form' ); ?>" href="#">&laquo;</a>
							<a class="prev-page disabled" title="<?php esc_attr_e( 'Go to the previous page', 'urllink-form' ); ?>" href="#">&lsaquo;</a>
							<span class="paging-select">
								<label for="current-page-selector-1" class="screen-reader-text"><?php esc_html_e( 'Select Page', 'urllink-form' ); ?></label>
								<select class="page-selector" id="current-page-selector-1" title="<?php esc_attr_e( 'Current page', 'urllink-form' ); ?>">
									<?php for ( $i = 1; $i <= $selectors_total_pages; $i++ ) : ?>
										<option value="<?php echo $i; // WPCS: XSS ok. ?>"><?php echo $i; // WPCS: XSS ok. ?></option>
									<?php endfor; ?>
								</select>
								<?php echo esc_html_x( 'of', 'number of pages', 'urllink-form' ); ?> <span class="total-pages"><?php echo esc_html( $selectors_total_pages ); ?></span>
							</span>
							<a class="next-page" title="<?php esc_attr_e( 'Go to the next page', 'urllink-form' ); ?>" href="#">&rsaquo;</a>
							<a class="last-page" title="<?php esc_attr_e( 'Go to the last page', 'urllink-form' ); ?>" href="#">&raquo;</a>
						</span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="adintgr_selectors adintgr-metaboxes" data-attributes="<?php echo $attributes_data; // WPCS: XSS ok. ?>" data-total="<?php echo esc_attr( $selectors_count); ?>" data-total_pages="<?php echo esc_attr( $selectors_total_pages ); ?>" data-page="1" data-edited="false">
				<?php
					for ( $i = 0; $i < $selectors_count; $i++ ) {
						?>
						<div class="adintgr_selector adintgr-metabox closed">
							<h3>
								<?php if ($selectors[$i]['selector_check'] == "on") { ?>
									<input type="checkbox" class="checkbox selector_check" name="selector_check[<?php echo $i; ?>]" checked="checked"/>
								<?php } else { ?>
									<input type="checkbox" class="checkbox selector_check" name="selector_check[<?php echo $i; ?>]"/>
								<?php } ?>
								<?php echo $selectors[$i]['selector_name']; ?>
								<a href="#" class="remove_selector delete">Remove</a>
								<div class="handlediv" aria-label="Click to toggle"></div>
								<div class="tips sort" data-tip="Drag and drop to set section order"></div>
								<strong><?php echo $selectors[$i]['name']; ?></strong>
							</h3>
							<div class="adintgrform_adintgr_attributes adintgr-metabox-content" style="display: none;">
								<div class="data">
									<p class="form-selector">
										<label for="selector_name">Name</label>
										<input type="text" class="short selector_name" style="" name="selector_name[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['selector_name']; ?>" placeholder="">
									</p>
									<p class="form-selector">
										<label for="selector_slug">Slug</label>
										<input type="text" class="short selector_slug" style="" name="selector_slug[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['selector_slug']; ?>" placeholder="">
									</p>
									<p class="form-selector">
										<label for="selector_url">URL</label>
										<input type="text" class="short selector_url" style="" name="selector_url[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['selector_url']; ?>" placeholder="">
									</p>
									<p class="form-selector">
										<label for="selector_type">Integration Type</label>
										<select name="selector_type[<?php echo $i; ?>]" class="short selector_type">
											<option value="">None</option>
											<option value="mediaalpha" <?php if ($selectors[$i]['selector_type'] == "mediaalpha") { echo "selected";} ?>>MediaAlpha</option>
										</select>
									</p>
									<div class="integration-mediaalpha" <?php if ($selectors[$i]['selector_type'] != "mediaalpha") { echo "style=\"display: none;\"";} ?>>
										<p class="form-selector">
											<label for="media_comment">MediaAlpha Comment</label>
											<input type="text" class="short media_comment" style="" name="media_comment[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['media_comment']; ?>" placeholder="Niche Seekers, Inc. / Auto - Email - Short Form">
										</p>
										<p class="form-selector">
											<label for="media_type">MediaAlpha Type</label>
											<input type="checkbox" class="checkbox media_type_unit" name="media_type_unit[<?php echo $i; ?>]" <?php if ($selectors[$i]['media_type_unit'] == "on") { echo "checked=\"checked\"";} ?>/><span style="margin-right: 10px;">Ad Unit(default)</span>
											<input type="checkbox" class="checkbox media_type_form" name="media_type_form[<?php echo $i; ?>]" <?php if ($selectors[$i]['media_type_form'] == "on") { echo "checked=\"checked\"";} ?>/><span>Form</span>
										</p>
										<p class="form-selector">
											<label for="media_placeid">MediaAlpha Placement ID</label>
											<input type="text" class="short media_placeid" style="" name="media_placeid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['media_placeid']; ?>" placeholder="">
										</p>
										<p class="form-selector">
											<label for="media_uaclass">MediaAlpha UA Class</label>
											<select name="media_uaclass[<?php echo $i; ?>]" class="short media_uaclass">
												<option value="web" <?php if ($selectors[$i]['media_uaclass'] == "web") { echo "selected";} ?>>Web(default)</option>
												<option value="mobile" <?php if ($selectors[$i]['media_uaclass'] == "mobile") { echo "selected";} ?>>Mobile</option>
												<option value="auto" <?php if ($selectors[$i]['media_uaclass'] == "auto") { echo "selected";} ?>>Auto</option>
											</select>
										</p>
										<p class="form-selector">
											<label for="media_sub1">MediaAlpha Sub_1</label>
											<input type="text" class="short media_sub1" style="" name="media_sub1[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['media_sub1']; ?>" placeholder="">
										</p>
										<p class="form-selector">
											<label for="media_sub2">MediaAlpha Sub_2</label>
											<input type="text" class="short media_sub2" style="" name="media_sub2[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['media_sub2']; ?>" placeholder="">
										</p>
										<p class="form-selector">
											<label for="media_sub3">MediaAlpha Sub_3</label>
											<input type="text" class="short media_sub3" style="" name="media_sub3[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['media_sub3']; ?>" placeholder="">
										</p>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				?>
				</div>
				
				<div class="toolbar toolbar-bottom">
					<div class="selectors-pagenav">
						<?php /* translators: selectors count*/ ?>
						<span class="displaying-num"><?php echo esc_html( sprintf( _n( '%s item', '%s items', count($selectors), 'urllink-form' ), $selectors_count) ); ?></span>
						<span class="expand-close">
							(<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'urllink-form' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'urllink-form' ); ?></a>)
						</span>
						<span class="pagination-links">
							<a class="first-page disabled" title="<?php esc_attr_e( 'Go to the first page', 'urllink-form' ); ?>" href="#">&laquo;</a>
							<a class="prev-page disabled" title="<?php esc_attr_e( 'Go to the previous page', 'urllink-form' ); ?>" href="#">&lsaquo;</a>
							<span class="paging-select">
								<label for="current-page-selector-1" class="screen-reader-text"><?php esc_html_e( 'Select Page', 'urllink-form' ); ?></label>
								<select class="page-selector" id="current-page-selector-1" title="<?php esc_attr_e( 'Current page', 'urllink-form' ); ?>">
									<?php for ( $i = 1; $i <= $selectors_total_pages; $i++ ) : ?>
										<option value="<?php echo $i; // WPCS: XSS ok. ?>"><?php echo $i; // WPCS: XSS ok. ?></option>
									<?php endfor; ?>
								</select>
								<?php echo esc_html_x( 'of', 'number of pages', 'urllink-form' ); ?> <span class="total-pages"><?php echo esc_html( $selectors_total_pages ); ?></span>
							</span>
							<a class="next-page" title="<?php esc_attr_e( 'Go to the next page', 'urllink-form' ); ?>" href="#">&rsaquo;</a>
							<a class="last-page" title="<?php esc_attr_e( 'Go to the last page', 'urllink-form' ); ?>" href="#">&raquo;</a>
						</span>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<input type="submit" class="button-primary" name="wpadintgr-save" value="<?php echo esc_attr( __( 'Save', 'urllink-form' ) ); ?>" />
	<?php
		if ( ! $post->initial() ) :
			$copy_nonce = wp_create_nonce( 'wpadintgr-copy-form_' . $post_id );
	?>
	<input type="submit" class="button-primary"  name="wpadintgr-copy" value="<?php echo esc_attr( __( 'Duplicate', 'urllink-form' ) ); ?>" <?php echo "onclick=\"this.form._wpnonce.value = '$copy_nonce'; this.form.action.value = 'copy'; return true;\""; ?> />
<?php endif; ?>
</form>

<?php endif; ?>

</div><!-- .wrap -->

<?php
	do_action( 'wpadintgr_admin_footer', $post );