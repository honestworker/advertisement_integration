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
		echo esc_html( __( 'Add New URL Link Form', 'urllink-form' ) );
	} else {
		echo esc_html( __( 'Edit URL Link Form', 'urllink-form' ) );
	}
?></h1>

<?php
	if ( ! $post->initial() && current_user_can( 'wpadintgr_edit_forms' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>', esc_url( menu_page_url( 'wpadintgr-new', false ) ), esc_html( __( 'Add New', 'urllink-form' ) ) );
	}
?>

<hr class="wp-header-end">

<?php do_action( 'wpadintgr_admin_warnings' ); ?>
<?php do_action( 'wpadintgr_admin_notices' ); ?>

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
	$companies = $props['company'];
	$fields_count = count($companies);
	$fields_total_pages = ceil($fields_count / 15);
?>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'wpadintgr', false ) ) ); ?>" id="wpadintgr-admin-form-element">
	<div id="company_field_options" class="panel ulf-metaboxes-wrapper">
		<input type="hidden" id="ajax_url" name="ajax_url" value="<?php echo admin_url( 'admin-ajax.php' ); ?>" />
		<div id="company_field_options_inner">
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
						'class' => 'ulf-post_title',
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
			
			<?php do_action( 'urllinkform_company_field_before_fields' ); ?>
			
			<div id="ulflink-field-data" class="ulf-postbox" style="zoom: 1;">
				<div class="toolbar toolbar-top">
					<input type="button" class="button bulk_edit do_field_action button-primary" name="wpadintgr-add-new" value="Add New"/>
					<div class="fields-pagenav">
						<?php /* translators: fields count */ ?>
						<span class="displaying-num"><?php echo esc_html( sprintf( _n( '%s item', '%s items', count($companies), 'urllink-form' ), $fields_count) ); ?></span>
						<span class="expand-close">
							(<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'urllink-form' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'urllink-form' ); ?></a>)
						</span>
						<span class="pagination-links">
							<a class="first-page disabled" title="<?php esc_attr_e( 'Go to the first page', 'urllink-form' ); ?>" href="#">&laquo;</a>
							<a class="prev-page disabled" title="<?php esc_attr_e( 'Go to the previous page', 'urllink-form' ); ?>" href="#">&lsaquo;</a>
							<span class="paging-select">
								<label for="current-page-selector-1" class="screen-reader-text"><?php esc_html_e( 'Select Page', 'urllink-form' ); ?></label>
								<select class="page-selector" id="current-page-selector-1" title="<?php esc_attr_e( 'Current page', 'urllink-form' ); ?>">
									<?php for ( $i = 1; $i <= $fields_total_pages; $i++ ) : ?>
										<option value="<?php echo $i; // WPCS: XSS ok. ?>"><?php echo $i; // WPCS: XSS ok. ?></option>
									<?php endfor; ?>
								</select>
								<?php echo esc_html_x( 'of', 'number of pages', 'urllink-form' ); ?> <span class="total-pages"><?php echo esc_html( $fields_total_pages ); ?></span>
							</span>
							<a class="next-page" title="<?php esc_attr_e( 'Go to the next page', 'urllink-form' ); ?>" href="#">&rsaquo;</a>
							<a class="last-page" title="<?php esc_attr_e( 'Go to the last page', 'urllink-form' ); ?>" href="#">&raquo;</a>
						</span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="urllinkform_fields ulf-metaboxes" data-attributes="<?php echo $attributes_data; // WPCS: XSS ok. ?>" data-total="<?php echo esc_attr( $fields_count); ?>" data-total_pages="<?php echo esc_attr( $fields_total_pages ); ?>" data-page="1" data-edited="false">
				<?php
					for ( $i = 0; $i < $fields_count; $i++ ) {
						?>
						<div class="urllinkform_field ulf-metabox closed field-needs-update">
							<h3>
								<?php if ($companies[$i]['check'] == "on") { ?>
									<input type="checkbox" class="checkbox company_check" name="company_check[<?php echo $i; ?>]" checked="checked"/>
								<?php } else { ?>
									<input type="checkbox" class="checkbox company_check" name="company_check[<?php echo $i; ?>]"/>
								<?php } ?>
								<a href="#" class="remove_field delete">Remove</a>
								<div class="handlediv" aria-label="Click to toggle"></div>
								<div class="tips sort" data-tip="Drag and drop to set field order"></div>
								<strong><?php echo $companies[$i]['name']; ?></strong>
							</h3>
							<div class="ulflinkform_company_attributes ulf-metabox-content" style="display: none;">
								<div class="data">
									<p class="form-field">
										<label for="company_name">Name</label>
										<input type="text" class="short company-name" style="" name="company_name[<?php echo $i; ?>]" value="<?php echo $companies[$i]['name']; ?>" placeholder="">
									</p>
									<p class="form-field">
										<label for="company_slug">Slug</label>
										<input type="text" class="short company-slug" style="" name="company_slug[<?php echo $i; ?>]" value="<?php echo $companies[$i]['slug']; ?>" placeholder="">
									</p>
									<p class="form-field">
										<label for="company_url">URL</label>
										<input type="text" class="short company-url" style="" name="company_url[<?php echo $i; ?>]" value="<?php echo $companies[$i]['url']; ?>" placeholder="">
									</p>
									<p class="form-field">
										<label for="company_integration">Integration Type</label>
										<select name="company_integration[<?php echo $i; ?>]" class="short company_integration">
											<option value="">None</option>
											<option value="mediaalpha" <?php if ($companies[$i]['integration'] == "mediaalpha") { echo "selected";} ?>>MediaAlpha</option>
										</select>
									</p>
									<div class="integration-mediaalpha" <?php if ($companies[$i]['integration'] != "mediaalpha") { echo "style=\"display: none;\"";} ?>>
										<p class="form-field">
											<label for="company_media_comment">MediaAlpha Comment</label>
											<input type="text" class="short company-media-comment" style="" name="company_media_comment[<?php echo $i; ?>]" value="<?php echo $companies[$i]['media_comment']; ?>" placeholder="Niche Seekers, Inc. / Auto - Email - Short Form">
										</p>
										<p class="form-field">
											<label for="company_media_type">MediaAlpha Type</label>
											<select name="company_media_type[<?php echo $i; ?>]" class="short company-media-type">
												<option value="ad_unit" <?php if ($companies[$i]['media_type'] == "ad_unit") { echo "selected";} ?>>Ad Unit(default)</option>
												<option value="form" <?php if ($companies[$i]['media_type'] == "form") { echo "selected";} ?>>Form</option>
											</select>
										</p>
										<p class="form-field">
											<label for="company_media_placeid">MediaAlpha Placement ID</label>
											<input type="text" class="short company-media-placeid" style="" name="company_media_placeid[<?php echo $i; ?>]" value="<?php echo $companies[$i]['media_placeid']; ?>" placeholder="">
										</p>
										<p class="form-field">
											<label for="company_media_uaclass">MediaAlpha UA Class</label>
											<select name="company_media_uaclass[<?php echo $i; ?>]" class="short company-media-uaclass">
												<option value="web" <?php if ($companies[$i]['media_uaclass'] == "web") { echo "selected";} ?>>Web(default)</option>
												<option value="mobile" <?php if ($companies[$i]['media_uaclass'] == "mobile") { echo "selected";} ?>>Mobile</option>
												<option value="auto" <?php if ($companies[$i]['media_uaclass'] == "auto") { echo "selected";} ?>>Auto</option>
											</select>
										</p>
										<p class="form-field">
											<label for="company_media_sub1">MediaAlpha Sub_1</label>
											<input type="text" class="short company-media-sub1" style="" name="company_media_sub1[<?php echo $i; ?>]" value="<?php echo $companies[$i]['media_sub1']; ?>" placeholder="">
										</p>
										<p class="form-field">
											<label for="company_media_sub2">MediaAlpha Sub_2</label>
											<input type="text" class="short company-media-sub2" style="" name="company_media_sub2[<?php echo $i; ?>]" value="<?php echo $companies[$i]['media_sub2']; ?>" placeholder="">
										</p>
										<p class="form-field">
											<label for="company_media_sub3">MediaAlpha Sub_3</label>
											<input type="text" class="short company-media-sub3" style="" name="company_media_sub3[<?php echo $i; ?>]" value="<?php echo $companies[$i]['media_sub3']; ?>" placeholder="">
										</p>
										<p class="form-field">
											<label for="company_media_target">MediaAlpha Target ID</label>
											<input type="text" class="short company-media-target" style="" name="company_media_target[<?php echo $i; ?>]" value="<?php echo $companies[$i]['media_target']; ?>" placeholder="">
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
					<div class="fields-pagenav">
						<?php /* translators: fields count*/ ?>
						<span class="displaying-num"><?php echo esc_html( sprintf( _n( '%s item', '%s items', count($companies), 'urllink-form' ), $fields_count) ); ?></span>
						<span class="expand-close">
							(<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'urllink-form' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'urllink-form' ); ?></a>)
						</span>
						<span class="pagination-links">
							<a class="first-page disabled" title="<?php esc_attr_e( 'Go to the first page', 'urllink-form' ); ?>" href="#">&laquo;</a>
							<a class="prev-page disabled" title="<?php esc_attr_e( 'Go to the previous page', 'urllink-form' ); ?>" href="#">&lsaquo;</a>
							<span class="paging-select">
								<label for="current-page-selector-1" class="screen-reader-text"><?php esc_html_e( 'Select Page', 'urllink-form' ); ?></label>
								<select class="page-selector" id="current-page-selector-1" title="<?php esc_attr_e( 'Current page', 'urllink-form' ); ?>">
									<?php for ( $i = 1; $i <= $fields_total_pages; $i++ ) : ?>
										<option value="<?php echo $i; // WPCS: XSS ok. ?>"><?php echo $i; // WPCS: XSS ok. ?></option>
									<?php endfor; ?>
								</select>
								<?php echo esc_html_x( 'of', 'number of pages', 'urllink-form' ); ?> <span class="total-pages"><?php echo esc_html( $fields_total_pages ); ?></span>
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