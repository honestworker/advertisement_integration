jQuery( function( $ ) {
    'use strict';
	
	/**
	 * Selctors actions
	 */
	var adintgr_meta_boxes_make_html = {
		make_header_html: function(count) {
			var checked_html;
			if ( count == 0 ) {
				checked_html = "checked=\"checked\"";
			} 
			var header_html = 	"<h3>" +
								"<input type=\"checkbox\" class=\"checkbox selector_check\" name=\"selector_check[" + count + "]\" " + checked_html + "/>" +
								"<a href=\"#\" class=\"remove_selector delete\">Remove</a>" +
								"<div class=\"handlediv\" aria-label=\"Click to toggle\"></div>" +
								"<div class=\"tips sort ui-sortable-handle\"></div>" +
								"<strong></strong>" +
								"</h3>" +
								"<div class=\"adintgr_selector_attributes adintgr-metabox-content\" style=\"display: none;\">" +
								"<div class=\"data\">" +
								"<p class=\"form-selector\">" +
								"<label for=\"selector_name\">Name</label>" +
								"<input type=\"text\" class=\"short selector_name\" style=\"\" name=\"selector_name[" + count + "]\" value=\"\" placeholder=\"\">" +
								"</p>" +
								"<p class=\"form-selector\">" +
								"<label for=\"selector_exit_check\">Exit Intent Popup Page</label>" +
								"<input type=\"checkbox\" class=\"checkbox selector_exit_check\" name=\"selector_exit_check[" + count + "]\">" +
								"</p>";
			return header_html;
		},

		make_exit_popup_html: function(tag, count) {
			var exit_popup_html = "<div class=\"" + tag + "-exit adintgr-metabox-sub-content adintgr-metabox-sub-content2\" style=\"display: none;\">" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + tag + "_exit_period\">Show Period</label>" +
										"<select name=\"" + tag + "_exit_period[" + count + "]\" class=\"short " + tag + "_exit_period\">" +
										"<option value=\"minute\">1 Minute</option>" +
										"<option value=\"minute10\">10 Minutes</option>" +
										"<option value=\"minute30\">30 Minutes</option>" +
										"<option value=\"hour\">1 Hour</option>" +
										"<option value=\"day\" selected>1 Day</option>" +
										"<option value=\"week\">1 Week</option>" +
										"</select>" +
										"</p>" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + tag + "_exit_type\">Integration Type</label>" +
										"<select name=\"" + tag + "_exit_type[" + count + "]\" class=\"short " + tag + "_exit_type\">" +
										"<option value=\"\">None</option>" +
										"<option value=\"mediaalpha\">MediaAlpha</option>" +
										"<option value=\"insuranceclicks\">InsuranceClicks</option>" +
										"</select>" +
										"</p>" +
										adintgr_meta_boxes_make_html.make_integration_none_html(tag, count, 'exit') +
										adintgr_meta_boxes_make_html.make_integration_html(tag, count, 'exit') +
										"</div>";
			return exit_popup_html;
		},

		make_integration_none_html: function(tag1, count, tag2 = '') {
			var class_tag = tag1 + "-";
			var id_tag = tag1 + "_";
			if ( tag2 != '' ) {
				class_tag = class_tag + tag2 + "-";
				id_tag = id_tag + tag2 + "_";
			}
			var integration_none_html = "<div class=\"" + class_tag + "integration-none adintgr-metabox-sub-content1\">" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + id_tag + "url\">URL</label>" +
										"<input type=\"text\" class=\"short " + id_tag + "url\" style=\"\" name=\"" + id_tag + "url[" + count + "]\" value=\"\" placeholder=\"\">" +
										"</p>" +
										"</div>";
			return integration_none_html;
		},

		make_integration_common_html: function(tag1, count, tag2 = '') {
			var class_tag = tag1 + "-";
			var id_tag = tag1 + "_";
			if ( tag2 != '' ) {
				class_tag = class_tag + tag2 + "-";
				id_tag = id_tag + tag2 + "_";
			}
			var common_html = "<div class=\"" + class_tag + "integration-common\" style=\"display: none;\">" +
								"<p class=\"form-selector\">" +
								"<label for=\"" + id_tag + "header\">Page Header</label>" +
								"<input type=\"text\" class=\"short " + id_tag + "header\" style=\"\" name=\"" + id_tag + "header[" + count + "]\" value=\"\" placeholder=\"\">" +
								"</p>" +
								"<p class=\"form-selector\">" +
								"<label for=\"" + id_tag + "code\">Custom Code</label>" +
								"<textarea type=\"text\" class=\"short " + id_tag + "code\" rows=\"10\" name=\"" + id_tag + "code[" + count + "]\"></textarea>" +
								"</p>" +
								"</div>";
			return common_html;
		},
		
		make_integration_mediaalpha_html: function(tag1, count, tag2 = '') {
			var class_tag = tag1 + "-";
			var id_tag = tag1 + "_";
			if ( tag2 != '' ) {
				class_tag = class_tag + tag2 + "-";
				id_tag = id_tag + tag2 + "_";
			}
			var mediaalpha_html = "<div class=\"" + class_tag + "integration-mediaalpha adintgr-metabox-sub-content1\" style=\"display:none;\">" + 
									"<p class=\"form-selector\">" + 
									"<label for=\"" + id_tag + "media_comment\">MediaAlpha Comment</label>" + 
									"<input type=\"text\" class=\"short " + id_tag + "media_comment\" style=\"\" name=\"" + id_tag + "media_comment[" + count + "]\" value=\"\" placeholder=\"Niche Seekers, Inc. / Auto - Auto - Short Form\">" + 
									"</p>" + 
									"<p class=\"form-selector\">" + 
									"<label for=\"" + id_tag + "media_type\">MediaAlpha Type</label>" + 
									"<select name=\"" + id_tag + "media_type[" + count + "]\" class=\"short " + id_tag + "media_type\">" + 
									"<option value=\"ad_unit\">Ad Unit(default)</option>" + 
									"<option value=\"form\">Form</option>" + 
									"</select>" + 
									"</p>" + 
									"<p class=\"form-selector\">" + 
									"<label for=\"" + id_tag + "media_placeid\">MediaAlpha Placement ID</label>" + 
									"<input type=\"text\" class=\"short " + id_tag + "media_placeid\" style=\"\" name=\"" + id_tag + "media_placeid[" + count + "]\" value=\"\" placeholder=\"\">" + 
									"</p>" + 
									"<p class=\"form-selector\">" + 
									"<label for=\"" + id_tag + "media_uaclass\">MediaAlpha UA Class</label>" + 
									"<select name=\"" + id_tag + "media_uaclass[" + count + "]\" class=\"short " + id_tag + "media_uaclass\">" + 
									"<option value=\"web\">Web(default)</option>" + 
									"<option value=\"mobile\">Mobile</option>" + 
									"<option value=\"auto\">Auto</option>" + 
									"</select>" + 
									"</p>" + 
									"<p class=\"form-selector\">" + 
									"<label for=\"" + id_tag + "media_sub1\">MediaAlpha Sub_1</label>" + 
									"<input type=\"text\" class=\"short " + id_tag + "media_sub1\" style=\"\" name=\"" + id_tag + "media_sub1[" + count + "]\" value=\"\" placeholder=\"\">" + 
									"</p>" + 
									"<p class=\"form-selector\">" + 
									"<label for=\"" + id_tag + "media_sub2\">MediaAlpha Sub_2</label>" + 
									"<input type=\"text\" class=\"short " + id_tag + "media_sub2\" style=\"\" name=\"" + id_tag + "media_sub2[" + count + "]\" value=\"\" placeholder=\"\">" + 
									"</p>" + 
									"<p class=\"form-selector\">" + 
									"<label for=\"" + id_tag + "media_sub3\">MediaAlpha Sub_3</label>" + 
									"<input type=\"text\" class=\"short " + id_tag + "media_sub3\" style=\"\" name=\"" + id_tag + "media_sub3[" + count + "]\" value=\"\" placeholder=\"\">" + 
									"</p>" + 
									"</div>";
			return mediaalpha_html;
		},

		make_insuranceclicks_state_selection_html: function() {
			var insuranceclicks_states_html = "";
			var insuranceclicks_states = ['AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA', 'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME', 'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM', 'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VA', 'VI', 'VT', 'WA', 'WI', 'WV', 'WY'];
			for ( var index = 0; index < insuranceclicks_states.length; index++ ) {
				insuranceclicks_states_html = insuranceclicks_states_html + "<option value=\"" + insuranceclicks_states[index] + "\">" + insuranceclicks_states[index] + "</option>";
			}
			return insuranceclicks_states_html;
		},

		make_integration_insuranceclicks_html: function(tag1, count, tag2 = '') {
			var class_tag = tag1 + "-";
			var id_tag = tag1 + "_";
			if ( tag2 != '' ) {
				class_tag = class_tag + tag2 + "-";
				id_tag = id_tag + tag2 + "_";
			}
			var insuranceclicks_html = "<div class=\"" + class_tag + "integration-insuranceclicks adintgr-metabox-sub-content1\" style=\"display:none;\">" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + id_tag + "insurance_token\">Token</label>" +
										"<input type=\"text\" class=\"short " + id_tag + "insurance_token\" style=\"\" name=\"" + id_tag + "insurance_token[" + count + "]\" value=\"\" placeholder=\"\">" +
										"</p>" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + id_tag + "insurance_userid\">User ID</label>" +
										"<input type=\"text\" class=\"short " + id_tag + "insurance_userid\" style=\"\" name=\"" + id_tag + "insurance_userid[" + count + "]\" value=\"\" placeholder=\"\">" +
										"</p>" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + id_tag + "insurance_type\">Insurance Type</label>" +
										"<select name=\"" + id_tag + "insurance_type[" + count + "]\" class=\"short " + id_tag + "insurance_type\">" +
										"<option value=\"auto\">Auto</option>" +
										"<option value=\"health\">Health</option>" +
										"<option value=\"home\">Home</option>" +
										"<option value=\"life\">Life</option>" +
										"<option value=\"medicare\">Medicare</option>" +
										"</select>" +
										"</p>" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + id_tag + "insurance_state\">State</label>" +
										"<select name=\"" + id_tag + "insurance_state[" + count + "]\" class=\"short " + id_tag + "insurance_state\">" +
										adintgr_meta_boxes_make_html.make_insuranceclicks_state_selection_html() +
										"</select>" +
										"</p>" +
										"</div>";
			return insuranceclicks_html;
		},

		make_integration_html: function(tag1, count, tag2 = '') {
			var class_tag = tag1 + "-";
			var id_tag = tag1 + "_";
			if ( tag2 != '' ) {
				class_tag = class_tag + tag2 + "-";
				id_tag = id_tag + tag2 + "_";
			}
			var integration_html = "<div class=\"" + class_tag + "integration adintgr-metabox-sub-content1\">" +
									adintgr_meta_boxes_make_html.make_integration_common_html(tag1, count, tag2) + 
									adintgr_meta_boxes_make_html.make_integration_mediaalpha_html(tag1, count, tag2) + 
									adintgr_meta_boxes_make_html.make_integration_insuranceclicks_html(tag1, count, tag2) +
									"</div>";
			return integration_html;
		},

		make_exit_popup_page_html: function(tag, count) {
			var exit_popup_html = "<div class=\"" + tag + "-exit\" style=\"display: none;\">" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + tag + "_exit_check\">Exit Intent Popup Page</label>" +
										"<input type=\"checkbox\" class=\"checkbox " + tag + "_exit_check\" name=\"" + tag + "_exit_check[" + count + "]\"/>" +
										"</p>" +
										"<div class=\"adintgr_" + tag + "_exit adintgr-metabox-sub-content\" style=\"display: none;\">" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + tag + "_exit_period\">Show Period</label>" +
										"<select name=\"" + tag + "_exit_period[" + count + "]\" class=\"short " + tag + "_exit_period\">" +
										"<option value=\"minute\">1 Minute</option>" +
										"<option value=\"minute10\">10 Minutes</option>" +
										"<option value=\"minute30\">30 Minutes</option>" +
										"<option value=\"hour\">1 Hour</option>" +
										"<option value=\"day\" selected>1 Day</option>" +
										"<option value=\"week\">1 Week</option>" +
										"</select>" +
										"</p>" +
										"<p class=\"form-selector\">" +
										"<label for=\"" + tag + "_exit_type\">Integration Type</label>" +
										"<select name=\"" + tag + "_exit_type[" + count + "]\" class=\"short " + tag + "_exit_type\">" +
										"<option value=\"\">None</option>" +
										"<option value=\"mediaalpha\">MediaAlpha</option>" +
										"<option value=\"insuranceclicks\">InsuranceClicks</option>" +
										"</select>" +
										"</p>" +
										adintgr_meta_boxes_make_html.make_integration_none_html(tag, count, 'exit') +
										adintgr_meta_boxes_make_html.make_integration_html(tag, count, 'exit') +
										"</div>" +
										"</div>";
			return exit_popup_html;
		},

		make_page_type_html: function(count) {
			var page_type_html = "<p class=\"form-selector\">" + 
									"<label for=\"selector_type\">Page Type</label>" + 
									"<select name=\"selector_type[" + count + "]\" class=\"short selector_type\">" + 
									"<option value=\"\">None</option>" + 
									"<option value=\"leave\">Leave</option>" + 
									"<option value=\"popup\">Leave and Popup</option>" + 
									"</select>" + 
									"</p>";
			return page_type_html;
		},

		make_page_html: function(tag, count, name) {
			var page_html =  "<div class=\"adintgr_" + tag + " adintgr-metabox-sub-content\" style=\"display: none;\">" + 
								"<div class=\"adintgr_" + tag + "_label\" style=\"display: none;\">" + 
								"<p class=\"form-selector\">" + name + " Page</p>" + 
								"</div>" + 
								"<p class=\"form-selector\">" +
								"<label for=\"" + tag + "_type\">Integration Type</label>" +
								"<select name=\"" + tag + "_type[" + count + "]\" class=\"short " + tag + "_type\">" +
								"<option value=\"\">None</option>" +
								"<option value=\"mediaalpha\">MediaAlpha</option>" +
								"<option value=\"insuranceclicks\">InsuranceClicks</option>" +
								"</select>" +
								"</p>" +
								adintgr_meta_boxes_make_html.make_integration_none_html(tag, count) +
								adintgr_meta_boxes_make_html.make_integration_html(tag, count) +
								adintgr_meta_boxes_make_html.make_exit_popup_page_html(tag, count) +
								"</div>";
			return page_html;
		},		
	};

	/**
	 * Selctors actions
	 */
	var adintgr_meta_boxes_selectors_actions = {
		/**
		 * Initialize selectors actions
		 */
		init: function() {
			$( '#adintgr_selector_options' )
				.on( 'click', 'h3 .sort', this.set_menu_order )
				.on( 'click', '.remove_selector', this.remove_selector )
				.on( 'reload', this.reload );
			
			$( document.body ).on( 'adintgr_selectors_added', this.selector_added );
			
			$( '.adintgr-metaboxes-wrapper' ).on( 'click', 'input.do_selector_action', this.do_selector_action );
			$( '.adintgr-metaboxes-wrapper' ).on( 'click', '.selector_check', this.change_selector_checked );

			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.selector_type', this.change_selector_type );

			$( '.adintgr-metaboxes-wrapper' ).on( 'click', '.selector_exit_check', this.change_exit_checked );
			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.main_exit_type', this.change_main_exit_type );
			
			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.leave_type', this.change_leave_type );
			$( '.adintgr-metaboxes-wrapper' ).on( 'click', '.leave_exit_check', this.change_leave_exit_checked );
			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.leave_exit_type', this.change_leave_exit_type );

			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.popup_type', this.change_popup_type );
			$( '.adintgr-metaboxes-wrapper' ).on( 'click', '.popup_exit_check', this.change_popup_exit_checked );
			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.popup_exit_type', this.change_popup_exit_type );
			
			adintgr_meta_boxes_selectors_actions.selectors_loaded( null, true );
			adintgr_meta_boxes_selectors_actions.reload();
		},
		
		/**
		 * Reload UI
		 *
		 * @param {Object} event
		 * @param {Int} qty
		 */
		reload: function() {
			adintgr_meta_boxes_selectors_pagenav.set_paginav( 0 );
		},

		/**
		* Run actions when selectors is loaded
		*
		* @param {Object} event
		* @param {Int} needsUpdate
		*/
		selectors_loaded: function( event, needsUpdate ) {
			needsUpdate = needsUpdate || false;
			
			var wrapper = $( '#adintgr-selector-data' );
			
			// Init TipTip
			$( '#tiptip_holder' ).removeAttr( 'style' );
			$( '#tiptip_arrow' ).removeAttr( 'style' );
			$( '.adintgr_selectors .tips, .adintgr_selectors .help_tip, .adintgr_selectors .adintgr-help-tip', wrapper ).tipTip({
				'attribute': 'data-tip',
				'fadeIn':    50,
				'fadeOut':   50,
				'delay':     200
			});
			
			// Allow sorting
			$( '.adintgr_selectors', wrapper ).sortable({
				items:                '.adintgr_selector',
				cursor:               'move',
				axis:                 'y',
				handle:               '.sort',
				scrollSensitivity:    40,
				forcePlaceholderSize: true,
				helper:               'clone',
				opacity:              0.65,
				stop:                 function() {
					adintgr_meta_boxes_selectors_actions.selector_row_indexes();
				}
			});
			
			$( document.body ).trigger( 'adintgr-enhanced-select-init' );
		},
		
		/**
		 * Actions
		 */
		change_selector_type: function() {
			if ( $( this ).val() == '' ) {
				$(this).parent().parent().find( '.adintgr_leave' ).hide();
				$(this).parent().parent().find( '.adintgr_popup' ).hide();
				$(this).parent().parent().find( '.adintgr_leave_label' ).hide();
				$(this).parent().parent().find( '.adintgr_popup_label' ).hide();
			} else if ( $( this ).val() == 'leave' ) {
				$(this).parent().parent().find( '.adintgr_popup' ).hide();
				$(this).parent().parent().find( '.adintgr_leave' ).show();
				$(this).parent().parent().find( '.adintgr_leave_label' ).hide();
				$(this).parent().parent().find( '.adintgr_popup_label' ).hide();
			} else if ( $( this ).val() == 'popup' ) {
				$(this).parent().parent().find( '.adintgr_leave' ).show();
				$(this).parent().parent().find( '.adintgr_popup' ).show();
				$(this).parent().parent().find( '.adintgr_leave_label' ).show();
				$(this).parent().parent().find( '.adintgr_popup_label' ).show();
			}
		},

		/**
		 * Actions
		 */
		change_selector_checked: function() {
			$( '.selector_check' ).each( function() {
				$( this ).prop('checked', false);
			});
			$( this ).prop('checked', true);
		},

		/**
		 * Actions
		 */
		change_exit_checked: function() {
			if ( $( this ).prop('checked') ) {
				$(this).parent().parent().find( '.main-exit' ).show();
			} else {
				$(this).parent().parent().find( '.main-exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_main_exit_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.main-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.main-exit-integration-mediaalpha' ).show();
				$(this).parent().parent().parent().find( '.main-exit-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.main-exit-integration-common' ).show();
			} else if ( $( this ).val() == 'insuranceclicks' ) {
				$(this).parent().parent().parent().find( '.main-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.main-exit-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.main-exit-integration-insuranceclicks' ).show();
				$(this).parent().parent().parent().find( '.main-exit-integration-common' ).show();
			} else {
				$(this).parent().parent().parent().find( '.main-exit-integration-none' ).show();
				$(this).parent().parent().parent().find( '.main-exit-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.main-exit-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.main-exit-integration-common' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_leave_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.leave-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.leave-integration-mediaalpha' ).show();
				$(this).parent().parent().parent().find( '.leave-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.leave-integration-common' ).show();
				$(this).parent().parent().parent().find( '.leave-exit' ).show();
			} else if ( $( this ).val() == 'insuranceclicks' ) {
				$(this).parent().parent().parent().find( '.leave-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.leave-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.leave-integration-insuranceclicks' ).show();
				$(this).parent().parent().parent().find( '.leave-integration-common' ).show();
				$(this).parent().parent().parent().find( '.leave-exit' ).show();
			} else {
				$(this).parent().parent().parent().find( '.leave-integration-none' ).show();
				$(this).parent().parent().parent().find( '.leave-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.leave-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.leave-integration-common' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_leave_exit_checked: function() {
			if ( $( this ).prop('checked') ) {
				$(this).parent().parent().find( '.adintgr_leave_exit' ).show();
			} else {
				$(this).parent().parent().find( '.adintgr_leave_exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_leave_exit_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.leave-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit-integration-mediaalpha' ).show();
				$(this).parent().parent().parent().find( '.leave-exit-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit-integration-common' ).show();
			} else if ( $( this ).val() == 'insuranceclicks' ) {
				$(this).parent().parent().parent().find( '.leave-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit-integration-insuranceclicks' ).show();
				$(this).parent().parent().parent().find( '.leave-exit-integration-common' ).show();
			} else {
				$(this).parent().parent().parent().find( '.leave-exit-integration-none' ).show();
				$(this).parent().parent().parent().find( '.leave-exit-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit-integration-common' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_popup_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.popup-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.popup-integration-mediaalpha' ).show();
				$(this).parent().parent().parent().find( '.popup-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.popup-integration-common' ).show();
				$(this).parent().parent().parent().find( '.popup-exit' ).show();
			} else if ( $( this ).val() == 'insuranceclicks' ) {
				$(this).parent().parent().parent().find( '.popup-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.popup-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.popup-integration-insuranceclicks' ).show();
				$(this).parent().parent().parent().find( '.popup-integration-common' ).show();
				$(this).parent().parent().parent().find( '.popup-exit' ).show();
			} else {
				$(this).parent().parent().parent().find( '.popup-integration-none' ).show();
				$(this).parent().parent().parent().find( '.popup-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.popup-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.popup-integration-common' ).show();
				$(this).parent().parent().parent().find( '.popup-exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_popup_exit_checked: function() {
			if ( $( this ).prop('checked') ) {
				$(this).parent().parent().find( '.adintgr_popup_exit' ).show();
			} else {
				$(this).parent().parent().find( '.adintgr_popup_exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_popup_exit_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.popup-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.popup-exit-integration-mediaalpha' ).show();
				$(this).parent().parent().parent().find( '.popup-exit-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.popup-exit-integration-common' ).show();
			} else if ( $( this ).val() == 'insuranceclicks' ) {
				$(this).parent().parent().parent().find( '.popup-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.popup-exit-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.popup-exit-integration-insuranceclicks' ).show();
				$(this).parent().parent().parent().find( '.popup-exit-integration-common' ).show();
			} else {
				$(this).parent().parent().parent().find( '.popup-exit-integration-none' ).show();
				$(this).parent().parent().parent().find( '.popup-exit-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.popup-exit-integration-insuranceclicks' ).hide();
				$(this).parent().parent().parent().find( '.popup-exit-integration-common' ).hide();
			}
		},

		/**
		 * Actions
		 */
		save_selectors: function(e) {
			e.preventDefault();
			$( this ).submit();
		},

		/**
		 * Actions
		 */
		save_selectors: function(e) {
			e.preventDefault();
			$( this ).submit();
		},

		/**
		 * Set menu order
		 */
		selector_row_indexes: function() {
			var wrapper      = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ),
				current_page = parseInt( wrapper.attr( 'data-page' ), 10 ),
				offset       = parseInt( ( current_page - 1 ) * adintgr_admin_meta_boxes_selectors.selectors_per_page, 10 );

			$( '.adintgr_selectors .adintgr_selector' ).each( function ( index, el ) {
				$( el ).find( '.selector_check' )[0].name = "selector_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.selector_name' )[0].name = "selector_name[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				// Selector exit
				$( el ).find( '.selector_exit_check' )[0].name = "selector_exit_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_period' )[0].name = "main_exit_period[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_type' )[0].name = "main_exit_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_url' )[0].name = "main_exit_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.main_exit_header' )[0].name = "main_exit_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_code' )[0].name = "main_exit_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";

				/// MediaAlpha
				$( el ).find( '.main_exit_media_comment' )[0].name = "main_exit_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_media_type' )[0].name = "main_exit_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_media_placeid' )[0].name = "main_exit_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_media_uaclass' )[0].name = "main_exit_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_media_sub1' )[0].name = "main_exit_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_media_sub2' )[0].name = "main_exit_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_media_sub3' )[0].name = "main_exit_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";

				/// InsuranceClick
				$( el ).find( '.main_exit_insurance_type' )[0].name = "main_exit_insurance_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_insurance_token' )[0].name = "main_exit_insurance_token[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_insurance_userid' )[0].name = "main_exit_insurance_userid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.main_exit_insurance_state' )[0].name = "main_exit_insurance_state[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				// Selector(continue)
				$( el ).find( '.selector_type' )[0].name = "selector_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";


				// Leave page
				$( el ).find( '.leave_type' )[0].name = "leave_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_url' )[0].name = "leave_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.leave_header' )[0].name = "leave_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_code' )[0].name = "leave_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";

				/// MediaAlpha Leave page
				$( el ).find( '.leave_media_comment' )[0].name = "leave_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_type' )[0].name = "leave_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_placeid' )[0].name = "leave_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_uaclass' )[0].name = "leave_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_sub1' )[0].name = "leave_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_sub2' )[0].name = "leave_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_sub3' )[0].name = "leave_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				/// InsuranceClick Leave page
				$( el ).find( '.leave_insurance_type' )[0].name = "leave_insurance_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_insurance_token' )[0].name = "leave_insurance_token[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_insurance_userid' )[0].name = "leave_insurance_userid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_insurance_state' )[0].name = "leave_insurance_state[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				// Leave Page exit
				$( el ).find( '.leave_exit_check' )[0].name = "leave_exit_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_period' )[0].name = "leave_exit_period[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_type' )[0].name = "leave_exit_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_url' )[0].name = "leave_exit_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.leave_exit_header' )[0].name = "leave_exit_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_code' )[0].name = "leave_exit_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";

				/// MediaAlpha Leave Page exit
				$( el ).find( '.leave_exit_media_comment' )[0].name = "leave_exit_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_type' )[0].name = "leave_exit_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_placeid' )[0].name = "leave_exit_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_uaclass' )[0].name = "leave_exit_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_sub1' )[0].name = "leave_exit_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_sub2' )[0].name = "leave_exit_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_sub3' )[0].name = "leave_exit_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				/// InsuranceClick Leave Page exit
				$( el ).find( '.leave_exit_insurance_type' )[0].name = "leave_exit_insurance_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_insurance_token' )[0].name = "leave_exit_insurance_token[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_insurance_userid' )[0].name = "leave_exit_insurance_userid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_insurance_state' )[0].name = "leave_exit_insurance_state[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				

				// Popup page
				$( el ).find( '.popup_type' )[0].name = "popup_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_url' )[0].name = "popup_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.popup_header' )[0].name = "popup_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_code' )[0].name = "popup_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";

				/// MediaAlpha Popup page
				$( el ).find( '.popup_media_comment' )[0].name = "popup_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_type' )[0].name = "popup_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_placeid' )[0].name = "popup_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_uaclass' )[0].name = "popup_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_sub1' )[0].name = "popup_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_sub2' )[0].name = "popup_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_sub3' )[0].name = "popup_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				/// InsuranceClick Popup page
				$( el ).find( '.popup_insurance_type' )[0].name = "popup_insurance_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_insurance_token' )[0].name = "popup_insurance_token[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_insurance_userid' )[0].name = "popup_insurance_userid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_insurance_state' )[0].name = "popup_insurance_state[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				// Popup exit
				$( el ).find( '.popup_exit_check' )[0].name = "popup_exit_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_period' )[0].name = "popup_exit_period[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_type' )[0].name = "popup_exit_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_url' )[0].name = "popup_exit_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.popup_exit_header' )[0].name = "popup_exit_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_code' )[0].name = "popup_exit_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";

				/// MediaAlpha Popup exit
				$( el ).find( '.popup_exit_media_comment' )[0].name = "popup_exit_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_type' )[0].name = "popup_exit_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_placeid' )[0].name = "popup_exit_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_uaclass' )[0].name = "popup_exit_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_sub1' )[0].name = "popup_exit_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_sub2' )[0].name = "popup_exit_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_sub3' )[0].name = "popup_exit_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				/// InsuranceClick Popup exit
				$( el ).find( '.popup_exit_insurance_type' )[0].name = "popup_exit_insurance_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_insurance_token' )[0].name = "popup_exit_insurance_token[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_insurance_userid' )[0].name = "popup_exit_insurance_userid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_insurance_state' )[0].name = "popup_exit_insurance_state[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
			});
		},

		/**
		 * Remove selector
		 *
		 * @return {Bool}
		 */
		remove_selector: function() {
			if ( $( this ).parent().length > 0 ) {
				if ( $( this ).parent().parent().length > 0 ) {
					if ( window.confirm( adintgr_admin_meta_boxes_selectors.i18n_remove_selector ) ) {
						var wrapper      = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ),
							current_page = parseInt( wrapper.attr( 'data-page' ), 10 ),
							total_pages  = Math.ceil( ( parseInt( wrapper.attr( 'data-total' ), 10 ) - 1 ) / adintgr_admin_meta_boxes_selectors.selectors_per_page ),
							page         = 1;
						
						$( this ).parent().parent().remove();
						
						if ( current_page === total_pages || current_page <= total_pages ) {
							page = current_page;
						} else if ( current_page > total_pages && 0 !== total_pages ) {
							page = total_pages;
						}
						adintgr_meta_boxes_selectors_pagenav.update_selectors_count(-1);
					}
					
					return true;
				}
			}
			return false;
		},

		/**
		 * Actions
		 */
		do_selector_action: function() {
			var count = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ).children().length;
			var selector_html = "<div class=\"adintgr_selector adintgr-metabox closed\">" +
								adintgr_meta_boxes_make_html.make_header_html(count) +
								adintgr_meta_boxes_make_html.make_exit_popup_html('main', count) +
								adintgr_meta_boxes_make_html.make_page_type_html(count) +
								adintgr_meta_boxes_make_html.make_page_html('leave', count, 'Leave') +
								adintgr_meta_boxes_make_html.make_page_html('popup', count, 'Popup') +
								"<div>" +
								"<div>" +
								"<div>";
									
			var selector = $(selector_html);
			$( '#adintgr_selector_options' ).find( '.adintgr_selectors' ).append( selector );
			$( 'button.cancel-selector-changes, button.save-selector-changes' ).removeAttr( 'disabled' );
			$( '#adintgr_selector_options' ).trigger( 'adintgr_selectors_added', 1 );
		},
	};
	
	/**
	 * Selectors pagenav
	 */
	var adintgr_meta_boxes_selectors_pagenav = {

		/**
		 * Initialize selectors meta box
		 */
		init: function() {
			$( document.body )
				.on( 'aadintgr_selectors_added', this.update_single_quantity )
				.on( 'change', '.selectors-pagenav .page-selector', this.page_selector )
				.on( 'click', '.selectors-pagenav .first-page', this.first_page )
				.on( 'click', '.selectors-pagenav .prev-page', this.prev_page )
				.on( 'click', '.selectors-pagenav .next-page', this.next_page )
				.on( 'click', '.selectors-pagenav .last-page', this.last_page );
		},
		
		/**
		 * Set selectors count
		 *
		 * @param {Int} qty
		 *
		 * @return {Int}
		 */
		update_selectors_count: function( qty ) {
			var wrapper        = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ),
				total          = parseInt( wrapper.attr( 'data-total' ), 10 ) + qty,
				displaying_num = $( '.selectors-pagenav .displaying-num' );
				
			// Set the new total of selectors
			wrapper.attr( 'data-total', total );
			
			if ( 1 === total ) {
				displaying_num.text( adintgr_admin_meta_boxes_selectors.i18n_selector_count_single.replace( '%qty%', total ) );
			} else {
				displaying_num.text( adintgr_admin_meta_boxes_selectors.i18n_selector_count_plural.replace( '%qty%', total ) );
			}
			
			adintgr_meta_boxes_selectors_actions.selector_row_indexes();

			return total;
		},
		
		/**
		 * Update selectors quantity when add a new selector
		 *
		 * @param {Object} event
		 * @param {Int} qty
		 */
		update_single_quantity: function( event, qty ) {
			if ( 1 === qty ) {
				var page_nav = $( '.selectors-pagenav' );
				
				adintgr_meta_boxes_selectors_pagenav.update_selectors_count( qty );
				
				if ( page_nav.is( ':hidden' ) ) {
					$( 'option, optgroup', '.selector_actions' ).show();
					$( '.selector_actions' ).val( 'add_selector' );
					$( '#adintgr_selector_options' ).find( '.toolbar' ).show();
					page_nav.show();
					$( '.pagination-links', page_nav ).hide();
				}
			}
		},
		
		/**
		 * Set the pagenav selectors
		 *
		 * @param {Int} qty
		 */
		set_paginav: function( qty ) {
			var wrapper				= $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ),
				new_qty				= adintgr_meta_boxes_selectors_pagenav.update_selectors_count( qty ),
				toolbar				= $( '#adintgr_selector_options' ).find( '.toolbar' ),
				selector_action		= $( '.selector_actions' ),
				page_nav			= $( '.selectors-pagenav' ),
				displaying_links	= $( '.pagination-links', page_nav ),
				total_pages			= Math.ceil( new_qty / adintgr_admin_meta_boxes_selectors.selectors_per_page ),
				options				= '';
				
			// Set the new total of pages
			wrapper.attr( 'data-total_pages', total_pages );
			
			$( '.total-pages', page_nav ).text( total_pages );
			
			// Set the new pagenav options
			for ( var i = 1; i <= total_pages; i++ ) {
				options += '<option value=\"' + i + '">' + i + '</option>';
			}
			
			$( '.page-selector', page_nav ).empty().html( options );
			
			// Show/hide pagenav
			if ( 0 === new_qty ) {
				toolbar.not( '.toolbar-top, .toolbar-buttons' ).hide();
				page_nav.hide();
				$( 'option, optgroup', selector_action ).hide();
				$( '.selector_actions' ).val( 'add_selector' );
				$( 'option[data-global="true"]', selector_action ).show();
				
			} else {
				toolbar.show();
				page_nav.show();
				$( 'option, optgroup', selector_action ).show();
				$( '.selector_actions' ).val( 'add_selector' );
				
				// Show/hide links
				if ( 1 === total_pages ) {
					displaying_links.hide();
				} else {
					displaying_links.show();
				}
			}
		},
		
		/**
		 * Check button if enabled and if don't have changes
		 *
		 * @return {Bool}
		 */
		check_is_enabled: function( current ) {
			return ! $( current ).hasClass( 'disabled' );
		},
		
		/**
		 * Change "disabled" class on pagenav
		 */
		change_classes: function( selected, total ) {
			var first_page = $( '.selectors-pagenav .first-page' ),
				prev_page  = $( '.selectors-pagenav .prev-page' ),
				next_page  = $( '.selectors-pagenav .next-page' ),
				last_page  = $( '.selectors-pagenav .last-page' );
				
			if ( 1 === selected ) {
				first_page.addClass( 'disabled' );
				prev_page.addClass( 'disabled' );
			} else {
				first_page.removeClass( 'disabled' );
				prev_page.removeClass( 'disabled' );
			}
			
			if ( total === selected ) {
				next_page.addClass( 'disabled' );
				last_page.addClass( 'disabled' );
			} else {
				next_page.removeClass( 'disabled' );
				last_page.removeClass( 'disabled' );
			}
		},
		
		/**
		 * Set page
		 */
		set_page: function( page ) {
			$( '.selectors-pagenav .page-selector' ).val( page ).first().change();
		},
		
		/**
		 * Navigate on selectors pages
		 *
		 * @param {Int} page
		 * @param {Int} qty
		 */
		go_to_page: function( page, qty ) {
			page = page || 1;
			qty  = qty || 0;
			
			adintgr_meta_boxes_selectors_pagenav.set_paginav( qty );
			adintgr_meta_boxes_selectors_pagenav.set_page( page );
		},
		
		/**
		 * Paginav pagination selector
		 */
		page_selector: function() {
			var selected = parseInt( $( this ).val(), 10 ),
				wrapper  = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' );
				
			$( '.selectors-pagenav .page-selector' ).val( selected );
			
			adintgr_meta_boxes_selectors_pagenav.change_classes( selected, parseInt( wrapper.attr( 'data-total_pages' ), 10 ) );
		},
		
		/**
		 * Go to first page
		 *
		 * @return {Bool}
		 */
		first_page: function() {
			if ( adintgr_meta_boxes_selectors_pagenav.check_is_enabled( this ) ) {
				adintgr_meta_boxes_selectors_pagenav.set_page( 1 );
			}
			
			return false;
		},
		
		/**
		 * Go to previous page
		 *
		 * @return {Bool}
		 */
		prev_page: function() {
			if ( adintgr_meta_boxes_selectors_pagenav.check_is_enabled( this ) ) {
				var wrapper   = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ),
					prev_page = parseInt( wrapper.attr( 'data-page' ), 10 ) - 1,
					new_page  = ( 0 < prev_page ) ? prev_page : 1;
					
				adintgr_meta_boxes_selectors_pagenav.set_page( new_page );
			}
			
			return false;
		},
		
		/**
		 * Go to next page
		 *
		 * @return {Bool}
		 */
		next_page: function() {
			if ( adintgr_meta_boxes_selectors_pagenav.check_is_enabled( this ) ) {
				var wrapper     = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ),
					total_pages = parseInt( wrapper.attr( 'data-total_pages' ), 10 ),
					next_page   = parseInt( wrapper.attr( 'data-page' ), 10 ) + 1,
					new_page    = ( total_pages >= next_page ) ? next_page : total_pages;
					
				adintgr_meta_boxes_selectors_pagenav.set_page( new_page );
			}
			
			return false;
		},
		
		/**
		 * Go to last page
		 *
		 * @return {Bool}
		 */
		last_page: function() {
			if ( adintgr_meta_boxes_selectors_pagenav.check_is_enabled( this ) ) {
				var last_page = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ).attr( 'data-total_pages' );
				
				adintgr_meta_boxes_selectors_pagenav.set_page( last_page );
			}
			
			return false;
		}
	};

	adintgr_meta_boxes_selectors_actions.init();
	adintgr_meta_boxes_selectors_pagenav.init();
});
