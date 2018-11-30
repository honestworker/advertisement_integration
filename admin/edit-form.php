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
		
	$button = sprintf( '<input type="submit" class="button-primary" id="wpadintgr-save" name="wpadintgr-save" value="%1$s" onclick="%2$s" />', esc_attr( __( 'Save', 'adintgr-form' ) ), $onclick );

	echo $button;
}

?><div class="wrap">

<h1 class="wp-heading-inline"><?php
	if ( $post->initial() ) {
		echo esc_html( __( 'Add New Ad Integration Form', 'adintgr-form' ) );
	} else {
		echo esc_html( __( 'Edit Ad Integration Form', 'adintgr-form' ) );
	}
?></h1>

<?php
	if ( ! $post->initial() && current_user_can( 'wpadintgr_edit_forms' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>', esc_url( menu_page_url( 'wpadintgr-new', false ) ), esc_html( __( 'Add New', 'adintgr-form' ) ) );
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
	$custom_code = $props['custom_code'];
	$custom_color = $props['custom_color'];
	$selectors = $props['selectors'];
	$selectors_count = count($selectors);
	$selectors_total_pages = ceil($selectors_count / 15);
	$insurance_states = ['AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME', 'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VA', 'VI', 'VT', 'WA', 'WI', 'WV', 'WY'];
?>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'wpadintgr', false ) ) ); ?>" id="wpadintgr-admin-form-element">
	<div id="adintgr_selector_options" class="panel adintgr-metaboxes-wrapper">
		<input type="hidden" id="ajax_url" name="ajax_url" value="<?php echo admin_url( 'admin-ajax.php' ); ?>" />
		<div id="adintgr_selector_options_inner">
			<div id="titlediv">
				<div id="titlewrap">
					<label class="screen-reader-text" id="title-prompt-text" for="title"><?php echo esc_html( __( 'Enter title here', 'adintgr-form' ) ); ?></label>
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
					<label for="wpadintgr-shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", 'adintgr-form' ) ); ?></label>
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
						<span class="displaying-num"><?php echo esc_html( sprintf( _n( '%s item', '%s items', count($selectors), 'adintgr-form' ), $selectors_count) ); ?></span>
						<span class="expand-close">
							(<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'adintgr-form' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'adintgr-form' ); ?></a>)
						</span>
						<span class="pagination-links">
							<a class="first-page disabled" title="<?php esc_attr_e( 'Go to the first page', 'adintgr-form' ); ?>" href="#">&laquo;</a>
							<a class="prev-page disabled" title="<?php esc_attr_e( 'Go to the previous page', 'adintgr-form' ); ?>" href="#">&lsaquo;</a>
							<span class="paging-select">
								<label for="current-page-selector-1" class="screen-reader-text"><?php esc_html_e( 'Select Page', 'adintgr-form' ); ?></label>
								<select class="page-selector" id="current-page-selector-1" title="<?php esc_attr_e( 'Current page', 'adintgr-form' ); ?>">
									<?php for ( $i = 1; $i <= $selectors_total_pages; $i++ ) : ?>
										<option value="<?php echo $i; // WPCS: XSS ok. ?>"><?php echo $i; // WPCS: XSS ok. ?></option>
									<?php endfor; ?>
								</select>
								<?php echo esc_html_x( 'of', 'number of pages', 'adintgr-form' ); ?> <span class="total-pages"><?php echo esc_html( $selectors_total_pages ); ?></span>
							</span>
							<a class="next-page" title="<?php esc_attr_e( 'Go to the next page', 'adintgr-form' ); ?>" href="#">&rsaquo;</a>
							<a class="last-page" title="<?php esc_attr_e( 'Go to the last page', 'adintgr-form' ); ?>" href="#">&raquo;</a>
						</span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="adintgr_selectors adintgr-metaboxes" data-attributes="<?php echo $attributes_data; // WPCS: XSS ok. ?>" data-total="<?php echo esc_attr( $selectors_count); ?>" data-total_pages="<?php echo esc_attr( $selectors_total_pages ); ?>" data-page="1" data-edited="false">
				<?php
					$display_none_html = "style = \"display:none;\"";
					$checked_html = "checked = \"checked\"";
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
							<div class="adintgr_selector_attributes adintgr-metabox-content" style="display: none;">
								<div class="data">
									<p class="form-selector">
										<label for="selector_name">Name</label>
										<input type="text" class="short selector_name" style="" name="selector_name[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['selector_name']; ?>" placeholder="">
									</p>
									<p class="form-selector">
										<label for="selector_exit_check">Exit Intent Popup Page</label>
										<input type="checkbox" class="checkbox selector_exit_check" name="selector_exit_check[<?php echo $i; ?>]" <?php if ($selectors[$i]['selector_exit_check'] == 'on') { echo $checked_html; }?>/>
									</p>
									<div class="main-exit adintgr-metabox-sub-content adintgr-metabox-sub-content2" <?php if ($selectors[$i]['selector_exit_check'] != 'on') { echo $display_none_html; }?>>
										<p class="form-selector">
											<label for="main_exit_period">Show Period</label>
											<select name="main_exit_period[<?php echo $i; ?>]" class="short main_exit_period">
												<option value="minute" <?php if ($selectors[$i]['main_exit_period'] == "minute") { echo "selected";} ?>>1 Minute</option>
												<option value="minute10" <?php if ($selectors[$i]['main_exit_period'] == "minute10") { echo "selected";} ?>>10 Minutes</option>
												<option value="minute30" <?php if ($selectors[$i]['main_exit_period'] == "minute30") { echo "selected";} ?>>30 Minutes</option>
												<option value="hour" <?php if ($selectors[$i]['main_exit_period'] == "hour") { echo "selected";} ?>>1 Hour</option>
												<option value="day" <?php if ($selectors[$i]['main_exit_period'] == "day" || $selectors[$i]['main_exit_period'] == "") { echo "selected";} ?>>1 Day</option>
												<option value="week" <?php if ($selectors[$i]['main_exit_period'] == "week") { echo "selected";} ?>>1 Week</option>
											</select>
										</p>
										<p class="form-selector">
											<label for="main_exit_type">Integration Type</label>
											<select name="main_exit_type[<?php echo $i; ?>]" class="short main_exit_type">
												<option value="">None</option>
												<option value="mediaalpha" <?php if ($selectors[$i]['main_exit_type'] == "mediaalpha") { echo "selected";} ?>>MediaAlpha</option>
												<option value="insuranceclicks" <?php if ($selectors[$i]['main_exit_type'] == "insuranceclicks") { echo "selected";} ?>>InsuranceClicks</option>
											</select>
										</p>
										<div class="main-exit-integration-none adintgr-metabox-sub-content1" <?php if ($selectors[$i]['main_exit_type'] != "") { echo $display_none_html;} ?>>
											<p class="form-selector">
												<label for="main_exit_url">URL</label>
												<input type="text" class="short main_exit_url" style="" name="main_exit_url[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_url']; ?>" placeholder="">
											</p>
										</div>
										<div class="main-exit-integration adintgr-metabox-sub-content1">
											<div class="main-exit-integration-common" <?php if ($selectors[$i]['main_exit_type'] == "") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="main_exit_header">Page Header</label>
													<input type="text" class="short main_exit_header" style="" name="main_exit_header[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_header']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="main_exit_code">Custom Code</label>
													<textarea type="text" class="short main_exit_code" rows="10" name="main_exit_code[<?php echo $i; ?>]"><?php echo $selectors[$i]['main_exit_code']; ?></textarea>
												</p>
											</div>
											<div class="main-exit-integration-mediaalpha adintgr-metabox-sub-content1" <?php if ($selectors[$i]['main_exit_type'] != "mediaalpha") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="main_exit_media_comment">MediaAlpha Comment</label>
													<input type="text" class="short main_exit_media_comment" style="" name="main_exit_media_comment[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_media_comment']; ?>" placeholder="Niche Seekers, Inc. / Auto - Email - Short Form">
												</p>
												<p class="form-selector">
													<label for="main_exit_media_type">MediaAlpha Type</label>
													<select name="main_exit_media_type[<?php echo $i; ?>]" class="short main_exit_media_type">
														<option value="ad_unit"<?php if ($selectors[$i]['main_exit_media_type'] == "ad_unit") { echo "selected";} ?>>Ad Unit(default)</option>
														<option value="form" <?php if ($selectors[$i]['main_exit_media_type'] == "form") { echo "selected";} ?>>Form</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="main_exit_media_placeid">MediaAlpha Placement ID</label>
													<input type="text" class="short main_exit_media_placeid" style="" name="main_exit_media_placeid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_media_placeid']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="main_exit_media_uaclass">MediaAlpha UA Class</label>
													<select name="main_exit_media_uaclass[<?php echo $i; ?>]" class="short main_exit_media_uaclass">
														<option value="web" <?php if ($selectors[$i]['main_exit_media_uaclass'] == "web") { echo "selected";} ?>>Web(default)</option>
														<option value="mobile" <?php if ($selectors[$i]['main_exit_media_uaclass'] == "mobile") { echo "selected";} ?>>Mobile</option>
														<option value="auto" <?php if ($selectors[$i]['main_exit_media_uaclass'] == "auto") { echo "selected";} ?>>Auto</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="main_exit_media_sub1">MediaAlpha Sub_1</label>
													<input type="text" class="short main_exit_media_sub1" style="" name="main_exit_media_sub1[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_media_sub1']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="main_exit_media_sub2">MediaAlpha Sub_2</label>
													<input type="text" class="short main_exit_media_sub2" style="" name="main_exit_media_sub2[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_media_sub2']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="main_exit_media_sub3">MediaAlpha Sub_3</label>
													<input type="text" class="short main_exit_media_sub3" style="" name="main_exit_media_sub3[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_media_sub3']; ?>" placeholder="">
												</p>
											</div>
											<div class="main-exit-integration-insuranceclicks adintgr-metabox-sub-content1" <?php if ($selectors[$i]['main_exit_type'] != "insuranceclicks") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="main_exit_insurance_token">Token</label>
													<input type="text" class="short main_exit_insurance_token" style="" name="main_exit_insurance_token[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_insurance_token']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="main_exit_insurance_userid">User ID</label>
													<input type="text" class="short main_exit_insurance_userid" style="" name="main_exit_insurance_userid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['main_exit_insurance_userid']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="main_exit_insurance_type">Insurance Type</label>
													<select name="main_exit_insurance_type[<?php echo $i; ?>]" class="short main_exit_insurance_type">
														<option value="auto" <?php if ($selectors[$i]['main_exit_insurance_type'] == "auto") { echo "selected";} ?>>Auto</option>
														<option value="health" <?php if ($selectors[$i]['main_exit_insurance_type'] == "health") { echo "selected";} ?>>Health</option>
														<option value="home" <?php if ($selectors[$i]['main_exit_insurance_type'] == "home") { echo "selected";} ?>>Home</option>
														<option value="life" <?php if ($selectors[$i]['main_exit_insurance_type'] == "life") { echo "selected";} ?>>Life</option>
														<option value="medicare" <?php if ($selectors[$i]['main_exit_insurance_type'] == "medicare") { echo "selected";} ?>>Medicare</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="main_exit_insurance_state">State</label>
													<select name="main_exit_insurance_state[<?php echo $i; ?>]" class="short main_exit_insurance_state">
													<?php foreach ( $insurance_states as $insurance_state ) {
													?>
														<option value="<?php echo $insurance_state; ?>" <?php if ($selectors[$i]['main_exit_insurance_state'] == $insurance_state) { echo "selected";} ?>><?php echo $insurance_state; ?></option>
													<?php
													}
													?>
													</select>
												</p>
											</div>
										</div>
									</div>
									<p class="form-selector">
										<label for="selector_type">Page Type</label>
										<select name="selector_type[<?php echo $i; ?>]" class="short selector_type">
											<option value="" <?php if ($selectors[$i]['selector_type'] == "") { echo "selected";} ?>>None</option>
											<option value="leave" <?php if ($selectors[$i]['selector_type'] == "leave") { echo "selected";} ?>>Leave</option>
											<option value="popup" <?php if ($selectors[$i]['selector_type'] == "popup") { echo "selected";} ?>>Leave and Popup</option>
										</select>
									</p>
									<div class="adintgr_leave adintgr-metabox-sub-content" <?php if ($selectors[$i]['selector_type'] == "") { echo $display_none_html;} ?>>
										<div class="adintgr_leave_label" <?php if ($selectors[$i]['selector_type'] != "popup") { echo $display_none_html;} ?>>
											<p class="form-selector">Leave Page</p>
										</div>
										<p class="form-selector">
											<label for="leave_type">Integration Type</label>
											<select name="leave_type[<?php echo $i; ?>]" class="short leave_type">
												<option value="">None</option>
												<option value="mediaalpha" <?php if ($selectors[$i]['leave_type'] == "mediaalpha") { echo "selected";} ?>>MediaAlpha</option>
												<option value="insuranceclicks" <?php if ($selectors[$i]['leave_type'] == "insuranceclicks") { echo "selected";} ?>>InsuranceClicks</option>
											</select>
										</p>
										<div class="leave-integration-none adintgr-metabox-sub-content1" <?php if ($selectors[$i]['leave_type'] != "") { echo $display_none_html;} ?>>
											<p class="form-selector">
												<label for="leave_url">URL</label>
												<input type="text" class="short leave_url" style="" name="leave_url[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_url']; ?>" placeholder="">
											</p>
										</div>
										<div class="leave-integration adintgr-metabox-sub-content1">
											<div class="leave-integration-common"<?php if ($selectors[$i]['leave_type'] == "") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="leave_header">Page Header</label>
													<input type="text" class="short leave_header" style="" name="leave_header[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_header']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="leave_code">Custom Code</label>
													<textarea type="text" class="short leave_code" rows="10" name="leave_code[<?php echo $i; ?>]"><?php echo $selectors[$i]['leave_code']; ?></textarea>
												</p>
											</div>
											<div class="leave-integration-mediaalpha adintgr-metabox-sub-content1" <?php if ($selectors[$i]['leave_type'] != "mediaalpha") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="leave_media_comment">MediaAlpha Comment</label>
													<input type="text" class="short leave_media_comment" style="" name="leave_media_comment[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_media_comment']; ?>" placeholder="Niche Seekers, Inc. / Auto - Email - Short Form">
												</p>
												<p class="form-selector">
													<label for="leave_media_type">MediaAlpha Type</label>
													<select name="leave_media_type[<?php echo $i; ?>]" class="short leave_media_type">
														<option value="ad_unit"<?php if ($selectors[$i]['leave_media_type'] == "ad_unit") { echo "selected";} ?>>Ad Unit(default)</option>
														<option value="form" <?php if ($selectors[$i]['leave_media_type'] == "form") { echo "selected";} ?>>Form</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="leave_media_placeid">MediaAlpha Placement ID</label>
													<input type="text" class="short leave_media_placeid" style="" name="leave_media_placeid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_media_placeid']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="leave_media_uaclass">MediaAlpha UA Class</label>
													<select name="leave_media_uaclass[<?php echo $i; ?>]" class="short leave_media_uaclass">
														<option value="web" <?php if ($selectors[$i]['leave_media_uaclass'] == "web") { echo "selected";} ?>>Web(default)</option>
														<option value="mobile" <?php if ($selectors[$i]['leave_media_uaclass'] == "mobile") { echo "selected";} ?>>Mobile</option>
														<option value="auto" <?php if ($selectors[$i]['leave_media_uaclass'] == "auto") { echo "selected";} ?>>Auto</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="leave_media_sub1">MediaAlpha Sub_1</label>
													<input type="text" class="short leave_media_sub1" style="" name="leave_media_sub1[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_media_sub1']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="leave_media_sub2">MediaAlpha Sub_2</label>
													<input type="text" class="short leave_media_sub2" style="" name="leave_media_sub2[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_media_sub2']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="leave_media_sub3">MediaAlpha Sub_3</label>
													<input type="text" class="short leave_media_sub3" style="" name="leave_media_sub3[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_media_sub3']; ?>" placeholder="">
												</p>
											</div>
											<div class="leave-integration-insuranceclicks adintgr-metabox-sub-content1" <?php if ($selectors[$i]['leave_type'] != "insuranceclicks") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="leave_insurance_token">Token</label>
													<input type="text" class="short leave_insurance_token" style="" name="leave_insurance_token[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_insurance_token']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="leave_insurance_userid">User ID</label>
													<input type="text" class="short leave_insurance_userid" style="" name="leave_insurance_userid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_insurance_userid']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="leave_insurance_type">Insurance Type</label>
													<select name="leave_insurance_type[<?php echo $i; ?>]" class="short leave_insurance_type">
														<option value="auto" <?php if ($selectors[$i]['leave_insurance_type'] == "auto") { echo "selected";} ?>>Auto</option>
														<option value="health" <?php if ($selectors[$i]['leave_insurance_type'] == "health") { echo "selected";} ?>>Health</option>
														<option value="home" <?php if ($selectors[$i]['leave_insurance_type'] == "home") { echo "selected";} ?>>Home</option>
														<option value="life" <?php if ($selectors[$i]['leave_insurance_type'] == "life") { echo "selected";} ?>>Life</option>
														<option value="medicare" <?php if ($selectors[$i]['leave_insurance_type'] == "medicare") { echo "selected";} ?>>Medicare</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="leave_insurance_state">State</label>
													<select name="leave_insurance_state[<?php echo $i; ?>]" class="short leave_insurance_state">
													<?php foreach ( $insurance_states as $insurance_state ) {
													?>
														<option value="<?php echo $insurance_state; ?>" <?php if ($selectors[$i]['leave_insurance_state'] == $insurance_state) { echo "selected";} ?>><?php echo $insurance_state; ?></option>
													<?php
													}
													?>
													</select>
												</p>
											</div>
										</div>
										<div class="leave-exit" <?php if ($selectors[$i]['leave_type'] == "") { echo $display_none_html;} ?>>
											<p class="form-selector">
												<label for="leave_exit_check">Exit Intent Popup Page</label>
												<input type="checkbox" class="checkbox leave_exit_check" name="leave_exit_check[<?php echo $i; ?>]" <?php if ($selectors[$i]['leave_exit_check'] == 'on') { echo $checked_html; }?>/>
											</p>
											<div class="adintgr_leave_exit adintgr-metabox-sub-content" <?php if ($selectors[$i]['leave_exit_check'] != 'on') { echo $display_none_html; }?>>												
												<p class="form-selector">
													<label for="leave_exit_period">Show Period</label>
													<select name="leave_exit_period[<?php echo $i; ?>]" class="short leave_exit_period">
														<option value="minute" <?php if ($selectors[$i]['leave_exit_period'] == "minute") { echo "selected";} ?>>1 Minute</option>
														<option value="minute10" <?php if ($selectors[$i]['leave_exit_period'] == "minute10") { echo "selected";} ?>>10 Minutes</option>
														<option value="minute30" <?php if ($selectors[$i]['leave_exit_period'] == "minute30") { echo "selected";} ?>>30 Minutes</option>
														<option value="hour" <?php if ($selectors[$i]['leave_exit_period'] == "hour") { echo "selected";} ?>>1 Hour</option>
														<option value="day" <?php if ($selectors[$i]['leave_exit_period'] == "day" || $selectors[$i]['leave_exit_period'] == "") { echo "selected";} ?>>1 Day</option>
														<option value="week" <?php if ($selectors[$i]['leave_exit_period'] == "week") { echo "selected";} ?>>1 Week</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="leave_exit_type">Integration Type</label>
													<select name="leave_exit_type[<?php echo $i; ?>]" class="short leave_exit_type">
														<option value="">None</option>
														<option value="mediaalpha" <?php if ($selectors[$i]['leave_exit_type'] == "mediaalpha") { echo "selected";} ?>>MediaAlpha</option>
														<option value="insuranceclicks" <?php if ($selectors[$i]['leave_exit_type'] == "insuranceclicks") { echo "selected";} ?>>InsuranceClicks</option>
													</select>
												</p>
												<div class="leave-exit-integration-none adintgr-metabox-sub-content1" <?php if ($selectors[$i]['leave_exit_type'] != "") { echo $display_none_html;} ?>>
													<p class="form-selector">
														<label for="leave_exit_url">URL</label>
														<input type="text" class="short leave_exit_url" style="" name="leave_exit_url[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_url']; ?>" placeholder="">
													</p>
												</div>
												<div class="leave-exit-integration adintgr-metabox-sub-content1">
													<div class="leave-exit-integration-common"<?php if ($selectors[$i]['leave_exit_type'] == "") { echo $display_none_html;} ?>>
														<p class="form-selector">
															<label for="leave_exit_header">Page Header</label>
															<input type="text" class="short leave_exit_header" style="" name="leave_exit_header[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_header']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="leave_exit_code">Custom Code</label>
															<textarea type="text" class="short leave_exit_code" rows="10" name="leave_exit_code[<?php echo $i; ?>]"><?php echo $selectors[$i]['leave_exit_code']; ?></textarea>
														</p>
													</div>
													<div class="leave-exit-integration-mediaalpha adintgr-metabox-sub-content1" <?php if ($selectors[$i]['leave_exit_type'] != "mediaalpha") { echo $display_none_html;} ?>>
														<p class="form-selector">
															<label for="leave_exit_media_comment">MediaAlpha Comment</label>
															<input type="text" class="short leave_exit_media_comment" style="" name="leave_exit_media_comment[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_media_comment']; ?>" placeholder="Niche Seekers, Inc. / Auto - Email - Short Form">
														</p>
														<p class="form-selector">
															<label for="leave_exit_media_type">MediaAlpha Type</label>
															<select name="leave_exit_media_type[<?php echo $i; ?>]" class="short leave_exit_media_type">
																<option value="ad_unit"<?php if ($selectors[$i]['leave_exit_media_type'] == "ad_unit") { echo "selected";} ?>>Ad Unit(default)</option>
																<option value="form" <?php if ($selectors[$i]['leave_exit_media_type'] == "form") { echo "selected";} ?>>Form</option>
															</select>
														</p>
														<p class="form-selector">
															<label for="leave_exit_media_placeid">MediaAlpha Placement ID</label>
															<input type="text" class="short leave_exit_media_placeid" style="" name="leave_exit_media_placeid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_media_placeid']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="leave_exit_media_uaclass">MediaAlpha UA Class</label>
															<select name="leave_exit_media_uaclass[<?php echo $i; ?>]" class="short leave_exit_media_uaclass">
																<option value="web" <?php if ($selectors[$i]['leave_exit_media_uaclass'] == "web") { echo "selected";} ?>>Web(default)</option>
																<option value="mobile" <?php if ($selectors[$i]['leave_exit_media_uaclass'] == "mobile") { echo "selected";} ?>>Mobile</option>
																<option value="auto" <?php if ($selectors[$i]['leave_exit_media_uaclass'] == "auto") { echo "selected";} ?>>Auto</option>
															</select>
														</p>
														<p class="form-selector">
															<label for="leave_exit_media_sub1">MediaAlpha Sub_1</label>
															<input type="text" class="short leave_exit_media_sub1" style="" name="leave_exit_media_sub1[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_media_sub1']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="leave_exit_media_sub2">MediaAlpha Sub_2</label>
															<input type="text" class="short leave_exit_media_sub2" style="" name="leave_exit_media_sub2[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_media_sub2']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="leave_exit_media_sub3">MediaAlpha Sub_3</label>
															<input type="text" class="short leave_exit_media_sub3" style="" name="leave_exit_media_sub3[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_media_sub3']; ?>" placeholder="">
														</p>
													</div>
													<div class="leave-exit-integration-insuranceclicks adintgr-metabox-sub-content1" <?php if ($selectors[$i]['leave_exit_type'] != "insuranceclicks") { echo $display_none_html;} ?>>
														<p class="form-selector">
															<label for="leave_exit_insurance_token">Token</label>
															<input type="text" class="short leave_exit_insurance_token" style="" name="leave_exit_insurance_token[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_insurance_token']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="leave_exit_insurance_userid">User ID</label>
															<input type="text" class="short leave_exit_insurance_userid" style="" name="leave_exit_insurance_userid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['leave_exit_insurance_userid']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="leave_exit_insurance_type">Insurance Type</label>
															<select name="leave_exit_insurance_type[<?php echo $i; ?>]" class="short leave_exit_insurance_type">
																<option value="auto" <?php if ($selectors[$i]['leave_exit_insurance_type'] == "auto") { echo "selected";} ?>>Auto</option>
																<option value="health" <?php if ($selectors[$i]['leave_exit_insurance_type'] == "health") { echo "selected";} ?>>Health</option>
																<option value="home" <?php if ($selectors[$i]['leave_exit_insurance_type'] == "home") { echo "selected";} ?>>Home</option>
																<option value="life" <?php if ($selectors[$i]['leave_exit_insurance_type'] == "life") { echo "selected";} ?>>Life</option>
																<option value="medicare" <?php if ($selectors[$i]['leave_exit_insurance_type'] == "medicare") { echo "selected";} ?>>Medicare</option>
															</select>
														</p>
														<p class="form-selector">
															<label for="leave_exit_insurance_state">State</label>
															<select name="leave_exit_insurance_state[<?php echo $i; ?>]" class="short leave_exit_insurance_state">
															<?php foreach ( $insurance_states as $insurance_state ) {
															?>
																<option value="<?php echo $insurance_state; ?>" <?php if ($selectors[$i]['leave_exit_insurance_state'] == $insurance_state) { echo "selected";} ?>><?php echo $insurance_state; ?></option>
															<?php
															}
															?>
															</select>
														</p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="adintgr_popup adintgr-metabox-sub-content" <?php if ($selectors[$i]['selector_type'] != "popup") { echo $display_none_html;} ?>>
										<div class="adintgr_popup_label">
											<p class="form-selector">Popup Page</p>
										</div>
										<p class="form-selector">
											<label for="popup_type">Integration Type</label>
											<select name="popup_type[<?php echo $i; ?>]" class="short popup_type">
												<option value="">None</option>
												<option value="mediaalpha" <?php if ($selectors[$i]['popup_type'] == "mediaalpha") { echo "selected";} ?>>MediaAlpha</option>
												<option value="insuranceclicks" <?php if ($selectors[$i]['popup_type'] == "insuranceclicks") { echo "selected";} ?>>InsuranceClicks</option>
											</select>
										</p>
										<div class="popup-integration-none adintgr-metabox-sub-content1" <?php if ($selectors[$i]['popup_type'] != "") { echo $display_none_html;} ?>>
											<p class="form-selector">
												<label for="popup_url">URL</label>
												<input type="text" class="short popup_url" style="" name="popup_url[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_url']; ?>" placeholder="">
											</p>
										</div>
										<div class="popup-integration adintgr-metabox-sub-content1">
											<div class="popup-integration-common"<?php if ($selectors[$i]['popup_type'] == "") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="popup_header">Page Header</label>
													<input type="text" class="short popup_header" style="" name="popup_header[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_header']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="popup_code">Custom Code</label>
													<textarea type="text" class="short popup_code" rows="10" name="popup_code[<?php echo $i; ?>]"><?php echo $selectors[$i]['popup_code']; ?></textarea>
												</p>
											</div>
											<div class="popup-integration-mediaalpha" <?php if ($selectors[$i]['popup_type'] != "mediaalpha") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="popup_media_comment">MediaAlpha Comment</label>
													<input type="text" class="short popup_media_comment" style="" name="popup_media_comment[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_media_comment']; ?>" placeholder="Niche Seekers, Inc. / Auto - Email - Short Form">
												</p>
												<p class="form-selector">
													<label for="popup_media_type">MediaAlpha Type</label>
													<select name="popup_media_type[<?php echo $i; ?>]" class="short popup_media_type">
														<option value="ad_unit"<?php if ($selectors[$i]['popup_media_type'] == "ad_unit") { echo "selected";} ?>>Ad Unit(default)</option>
														<option value="form" <?php if ($selectors[$i]['popup_media_type'] == "form") { echo "selected";} ?>>Form</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="popup_media_placeid">MediaAlpha Placement ID</label>
													<input type="text" class="short popup_media_placeid" style="" name="popup_media_placeid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_media_placeid']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="popup_media_uaclass">MediaAlpha UA Class</label>
													<select name="popup_media_uaclass[<?php echo $i; ?>]" class="short popup_media_uaclass">
														<option value="web" <?php if ($selectors[$i]['popup_media_uaclass'] == "web") { echo "selected";} ?>>Web(default)</option>
														<option value="mobile" <?php if ($selectors[$i]['popup_media_uaclass'] == "mobile") { echo "selected";} ?>>Mobile</option>
														<option value="auto" <?php if ($selectors[$i]['popup_media_uaclass'] == "auto") { echo "selected";} ?>>Auto</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="popup_media_sub1">MediaAlpha Sub_1</label>
													<input type="text" class="short popup_media_sub1" style="" name="popup_media_sub1[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_media_sub1']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="popup_media_sub2">MediaAlpha Sub_2</label>
													<input type="text" class="short popup_media_sub2" style="" name="popup_media_sub2[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_media_sub2']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="popup_media_sub3">MediaAlpha Sub_3</label>
													<input type="text" class="short popup_media_sub3" style="" name="popup_media_sub3[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_media_sub3']; ?>" placeholder="">
												</p>
											</div>
											<div class="popup-integration-insuranceclicks adintgr-metabox-sub-content1" <?php if ($selectors[$i]['popup_type'] != "insuranceclicks") { echo $display_none_html;} ?>>
												<p class="form-selector">
													<label for="popup_insurance_token">Token</label>
													<input type="text" class="short popup_insurance_token" style="" name="popup_insurance_token[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_insurance_token']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="popup_insurance_userid">User ID</label>
													<input type="text" class="short popup_insurance_userid" style="" name="popup_insurance_userid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_insurance_userid']; ?>" placeholder="">
												</p>
												<p class="form-selector">
													<label for="popup_insurance_type">Insurance Type</label>
													<select name="popup_insurance_type[<?php echo $i; ?>]" class="short popup_insurance_type">
														<option value="auto" <?php if ($selectors[$i]['popup_insurance_type'] == "auto") { echo "selected";} ?>>Auto</option>
														<option value="health" <?php if ($selectors[$i]['popup_insurance_type'] == "health") { echo "selected";} ?>>Health</option>
														<option value="home" <?php if ($selectors[$i]['popup_insurance_type'] == "home") { echo "selected";} ?>>Home</option>
														<option value="life" <?php if ($selectors[$i]['popup_insurance_type'] == "life") { echo "selected";} ?>>Life</option>
														<option value="medicare" <?php if ($selectors[$i]['popup_insurance_type'] == "medicare") { echo "selected";} ?>>Medicare</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="popup_insurance_state">State</label>
													<select name="popup_insurance_state[<?php echo $i; ?>]" class="short popup_insurance_state">
													<?php foreach ( $insurance_states as $insurance_state ) {
													?>
														<option value="<?php echo $insurance_state; ?>" <?php if ($selectors[$i]['popup_insurance_state'] == $insurance_state) { echo "selected";} ?>><?php echo $insurance_state; ?></option>
													<?php
													}
													?>
													</select>
												</p>
											</div>
										</div>
										<div class="popup-exit" <?php if ($selectors[$i]['popup_type'] == "") { echo $display_none_html;} ?>>
											<p class="form-selector">
												<label for="popup_exit_check">Exit Intent Popup Page</label>
												<input type="checkbox" class="checkbox popup_exit_check" name="popup_exit_check[<?php echo $i; ?>]" <?php if ($selectors[$i]['popup_exit_check'] == 'on') { echo $checked_html; }?>/>
											</p>
											<div class="adintgr_popup_exit" <?php if ($selectors[$i]['popup_exit_check'] != 'on') { echo $display_none_html; }?>>
												<p class="form-selector">
													<label for="popup_exit_period">Show Period</label>
													<select name="popup_exit_period[<?php echo $i; ?>]" class="short popup_exit_period">
														<option value="minute" <?php if ($selectors[$i]['popup_exit_period'] == "minute") { echo "selected";} ?>>1 Minute</option>
														<option value="minute10" <?php if ($selectors[$i]['popup_exit_period'] == "minute10") { echo "selected";} ?>>10 Minutes</option>
														<option value="minute30" <?php if ($selectors[$i]['popup_exit_period'] == "minute30") { echo "selected";} ?>>30 Minutes</option>
														<option value="hour" <?php if ($selectors[$i]['popup_exit_period'] == "hour") { echo "selected";} ?>>1 Hour</option>
														<option value="day" <?php if ($selectors[$i]['popup_exit_period'] == "day" || $selectors[$i]['popup_exit_period'] == "") { echo "selected";} ?>>1 Day</option>
														<option value="week" <?php if ($selectors[$i]['popup_exit_period'] == "week") { echo "selected";} ?>>1 Week</option>
													</select>
												</p>
												<p class="form-selector">
													<label for="popup_exit_type">Integration Type</label>
													<select name="popup_exit_type[<?php echo $i; ?>]" class="short popup_exit_type">
														<option value="">None</option>
														<option value="mediaalpha" <?php if ($selectors[$i]['popup_exit_type'] == "mediaalpha") { echo "selected";} ?>>MediaAlpha</option>
														<option value="insuranceclicks" <?php if ($selectors[$i]['popup_exit_type'] == "insuranceclicks") { echo "selected";} ?>>InsuranceClicks</option>
													</select>
												</p>
												<div class="popup-exit-integration-none adintgr-metabox-sub-content1" <?php if ($selectors[$i]['popup_exit_type'] != "") { echo $display_none_html;} ?>>
													<p class="form-selector">
														<label for="popup_exit_url">URL</label>
														<input type="text" class="short popup_exit_url" style="" name="popup_exit_url[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_url']; ?>" placeholder="">
													</p>
												</div>
												<div class="popup-exit-integration adintgr-metabox-sub-content1">
													<div class="popup-exit-integration-common" <?php if ($selectors[$i]['popup_exit_type'] == "") { echo $display_none_html;} ?>>
														<p class="form-selector">
															<label for="popup_exit_header">Page Header</label>
															<input type="text" class="short popup_exit_header" style="" name="popup_exit_header[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_header']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="popup_exit_code">Custom Code</label>
															<textarea type="text" class="short popup_exit_code" rows="10" name="popup_exit_code[<?php echo $i; ?>]"><?php echo $selectors[$i]['popup_exit_code']; ?></textarea>
														</p>
													</div>
													<div class="popup-exit-integration-mediaalpha adintgr-metabox-sub-content1" <?php if ($selectors[$i]['popup_exit_type'] != "mediaalpha") { echo $display_none_html;} ?>>
														<p class="form-selector">
															<label for="popup_exit_media_comment">MediaAlpha Comment</label>
															<input type="text" class="short popup_exit_media_comment" style="" name="popup_exit_media_comment[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_media_comment']; ?>" placeholder="Niche Seekers, Inc. / Auto - Email - Short Form">
														</p>
														<p class="form-selector">
															<label for="popup_exit_media_type">MediaAlpha Type</label>
															<select name="popup_exit_media_type[<?php echo $i; ?>]" class="short popup_exit_media_type">
																<option value="ad_unit"<?php if ($selectors[$i]['popup_exit_media_type'] == "ad_unit") { echo "selected";} ?>>Ad Unit(default)</option>
																<option value="form" <?php if ($selectors[$i]['popup_exit_media_type'] == "form") { echo "selected";} ?>>Form</option>
															</select>
														</p>
														<p class="form-selector">
															<label for="popup_exit_media_placeid">MediaAlpha Placement ID</label>
															<input type="text" class="short popup_exit_media_placeid" style="" name="popup_exit_media_placeid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_media_placeid']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="popup_exit_media_uaclass">MediaAlpha UA Class</label>
															<select name="popup_exit_media_uaclass[<?php echo $i; ?>]" class="short popup_exit_media_uaclass">
																<option value="web" <?php if ($selectors[$i]['popup_exit_media_uaclass'] == "web") { echo "selected";} ?>>Web(default)</option>
																<option value="mobile" <?php if ($selectors[$i]['popup_exit_media_uaclass'] == "mobile") { echo "selected";} ?>>Mobile</option>
																<option value="auto" <?php if ($selectors[$i]['popup_exit_media_uaclass'] == "auto") { echo "selected";} ?>>Auto</option>
															</select>
														</p>
														<p class="form-selector">
															<label for="popup_exit_media_sub1">MediaAlpha Sub_1</label>
															<input type="text" class="short popup_exit_media_sub1" style="" name="popup_exit_media_sub1[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_media_sub1']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="popup_exit_media_sub2">MediaAlpha Sub_2</label>
															<input type="text" class="short popup_exit_media_sub2" style="" name="popup_exit_media_sub2[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_media_sub2']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="popup_exit_media_sub3">MediaAlpha Sub_3</label>
															<input type="text" class="short popup_exit_media_sub3" style="" name="popup_exit_media_sub3[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_media_sub3']; ?>" placeholder="">
														</p>
													</div>
													<div class="popup-exit-integration-insuranceclicks adintgr-metabox-sub-content1" <?php if ($selectors[$i]['popup_exit_type'] != "insuranceclicks") { echo $display_none_html;} ?>>
														<p class="form-selector">
															<label for="popup_exit_insurance_token">Token</label>
															<input type="text" class="short popup_exit_insurance_token" style="" name="popup_exit_insurance_token[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_insurance_token']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="popup_exit_insurance_userid">User ID</label>
															<input type="text" class="short popup_exit_insurance_userid" style="" name="popup_exit_insurance_userid[<?php echo $i; ?>]" value="<?php echo $selectors[$i]['popup_exit_insurance_userid']; ?>" placeholder="">
														</p>
														<p class="form-selector">
															<label for="popup_exit_insurance_type">Insurance Type</label>
															<select name="popup_exit_insurance_type[<?php echo $i; ?>]" class="short popup_exit_insurance_type">
																<option value="auto" <?php if ($selectors[$i]['popup_exit_insurance_type'] == "auto") { echo "selected";} ?>>Auto</option>
																<option value="health" <?php if ($selectors[$i]['popup_exit_insurance_type'] == "health") { echo "selected";} ?>>Health</option>
																<option value="home" <?php if ($selectors[$i]['popup_exit_insurance_type'] == "home") { echo "selected";} ?>>Home</option>
																<option value="life" <?php if ($selectors[$i]['popup_exit_insurance_type'] == "life") { echo "selected";} ?>>Life</option>
																<option value="medicare" <?php if ($selectors[$i]['popup_exit_insurance_type'] == "medicare") { echo "selected";} ?>>Medicare</option>
															</select>
														</p>
														<p class="form-selector">
															<label for="popup_exit_insurance_state">State</label>
															<select name="popup_exit_insurance_state[<?php echo $i; ?>]" class="short popup_exit_insurance_state">
															<?php foreach ( $insurance_states as $insurance_state ) {
															?>
																<option value="<?php echo $insurance_state; ?>" <?php if ($selectors[$i]['popup_exit_insurance_state'] == $insurance_state) { echo "selected";} ?>><?php echo $insurance_state; ?></option>
															<?php
															}
															?>
															</select>
														</p>
													</div>
												</div>
											</div>
										</div>
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
						<span class="displaying-num"><?php echo esc_html( sprintf( _n( '%s item', '%s items', count($selectors), 'adintgr-form' ), $selectors_count) ); ?></span>
						<span class="expand-close">
							(<a href="#" class="expand_all"><?php esc_html_e( 'Expand', 'adintgr-form' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'adintgr-form' ); ?></a>)
						</span>
						<span class="pagination-links">
							<a class="first-page disabled" title="<?php esc_attr_e( 'Go to the first page', 'adintgr-form' ); ?>" href="#">&laquo;</a>
							<a class="prev-page disabled" title="<?php esc_attr_e( 'Go to the previous page', 'adintgr-form' ); ?>" href="#">&lsaquo;</a>
							<span class="paging-select">
								<label for="current-page-selector-1" class="screen-reader-text"><?php esc_html_e( 'Select Page', 'adintgr-form' ); ?></label>
								<select class="page-selector" id="current-page-selector-1" title="<?php esc_attr_e( 'Current page', 'adintgr-form' ); ?>">
									<?php for ( $i = 1; $i <= $selectors_total_pages; $i++ ) : ?>
										<option value="<?php echo $i; // WPCS: XSS ok. ?>"><?php echo $i; // WPCS: XSS ok. ?></option>
									<?php endfor; ?>
								</select>
								<?php echo esc_html_x( 'of', 'number of pages', 'adintgr-form' ); ?> <span class="total-pages"><?php echo esc_html( $selectors_total_pages ); ?></span>
							</span>
							<a class="next-page" title="<?php esc_attr_e( 'Go to the next page', 'adintgr-form' ); ?>" href="#">&rsaquo;</a>
							<a class="last-page" title="<?php esc_attr_e( 'Go to the last page', 'adintgr-form' ); ?>" href="#">&raquo;</a>
						</span>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="custom_field">
				<p class="form-selector">
					<label for="custom_code">Custom Code</label>
					<textarea type="text" class="short custom_code" rows="10" name="custom_code"><?php echo $custom_code; ?></textarea>
				</p>
				<p class="form-selector">
					<label for="custom_color">Custom Color</label>
					<input type="text" class="short custom_color" style="" name="custom_color" value="<?php echo $custom_color; ?>" placeholder="#fff">
				</p>
			</div>
		</div>
	</div>
	
	<input type="submit" class="button-primary" id="wpadintgr_save" name="wpadintgr-save" value="<?php echo esc_attr( __( 'Save', 'adintgr-form' ) ); ?>" />
	<?php
		if ( ! $post->initial() ) :
			$copy_nonce = wp_create_nonce( 'wpadintgr-copy-form_' . $post_id );
	?>
	<input type="submit" class="button-primary" id="wpadintgr-copy" name="wpadintgr_copy" value="<?php echo esc_attr( __( 'Duplicate', 'adintgr-form' ) ); ?>" <?php echo "onclick=\"this.form._wpnonce.value = '$copy_nonce'; this.form.action.value = 'copy'; return true;\""; ?> />
<?php endif; ?>
</form>

<?php endif; ?>

</div><!-- .wrap -->

<?php
	do_action( 'wpadintgr_admin_footer', $post );