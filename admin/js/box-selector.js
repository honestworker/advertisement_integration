jQuery( function( $ ) {
    'use strict';
	
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
			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.exit_type', this.change_exit_type );
			
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
			$( '.adintgr_selectors .tips, .adintgr_selectors .help_tip, .adintgr_selectors .adintgrform-help-tip', wrapper ).tipTip({
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
				$(this).parent().parent().find( '.adintgrform_leave' ).hide();
				$(this).parent().parent().find( '.adintgrform_popup' ).hide();
			} else if ( $( this ).val() == 'leave' ) {
				$(this).parent().parent().find( '.adintgrform_popup' ).hide();
				$(this).parent().parent().find( '.adintgrform_leave' ).show();
				$(this).parent().parent().find( '.adintgrform_leave_label' ).hide();
			} else if ( $( this ).val() == 'popup' ) {
				$(this).parent().parent().find( '.adintgrform_leave' ).show();
				$(this).parent().parent().find( '.adintgrform_popup' ).show();
				$(this).parent().parent().find( '.adintgrform_leave_label' ).show();
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
				$(this).parent().parent().find( '.adintgrform_selector_exit' ).show();
			} else {
				$(this).parent().parent().find( '.adintgrform_selector_exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_exit_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.exit-integration-mediaalpha' ).show();
			} else {
				$(this).parent().parent().parent().find( '.exit-integration-none' ).show();
				$(this).parent().parent().parent().find( '.exit-integration-mediaalpha' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_leave_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.leave-integration-mediaalpha' ).show();
				$(this).parent().parent().parent().find( '.leave-integration-none' ).hide();
			} else {
				$(this).parent().parent().parent().find( '.leave-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.leave-integration-none' ).show();
			}
		},

		/**
		 * Actions
		 */
		change_leave_exit_checked: function() {
			if ( $( this ).prop('checked') ) {
				$(this).parent().parent().find( '.adintgrform_leave_exit' ).show();
			} else {
				$(this).parent().parent().find( '.adintgrform_leave_exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_leave_exit_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.leave-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.leave-exit-integration-mediaalpha' ).show();
			} else {
				$(this).parent().parent().parent().find( '.leave-exit-integration-none' ).show();
				$(this).parent().parent().parent().find( '.leave-exit-integration-mediaalpha' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_popup_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.popup-integration-mediaalpha' ).show();
				$(this).parent().parent().parent().find( '.popup-integration-none' ).hide();
			} else {
				$(this).parent().parent().parent().find( '.popup-integration-mediaalpha' ).hide();
				$(this).parent().parent().parent().find( '.popup-integration-none' ).show();
			}
		},

		/**
		 * Actions
		 */
		change_popup_exit_checked: function() {
			if ( $( this ).prop('checked') ) {
				$(this).parent().parent().find( '.adintgrform_popup_exit' ).show();
			} else {
				$(this).parent().parent().find( '.adintgrform_popup_exit' ).hide();
			}
		},

		/**
		 * Actions
		 */
		change_popup_exit_type: function() {
			if ( $( this ).val() == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.popup-exit-integration-none' ).hide();
				$(this).parent().parent().parent().find( '.popup-exit-integration-mediaalpha' ).show();
			} else {
				$(this).parent().parent().parent().find( '.popup-exit-integration-none' ).show();
				$(this).parent().parent().parent().find( '.popup-exit-integration-mediaalpha' ).hide();
			}
		},

		/**
		 * Actions
		 */
		save_selectors: function(e) {
			e.preventDefault();
			$( '.adintgr_selectors .adintgr_selector' ).each( function ( index, el ) {
				if ($( el ).find( '.selector_slug' ).val() == '') {
					alert("No");
					return;
				}
			});
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
				offset       = parseInt( ( current_page - 1 ) * adintgrform_admin_meta_boxes_selectors.selectors_per_page, 10 );
				
			$( '.adintgr_selectors .adintgr_selector' ).each( function ( index, el ) {
				$( el ).find( '.selector_check' )[0].name = "selector_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.selector_name' )[0].name = "selector_name[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.selector_slug' )[0].name = "selector_slug[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.selector_title' )[0].name = "selector_title[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				// selector exit
				$( el ).find( '.selector_exit_check' )[0].name = "selector_exit_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_type' )[0].name = "exit_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_url' )[0].name = "exit_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_header' )[0].name = "exit_media_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_comment' )[0].name = "exit_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_type' )[0].name = "exit_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_placeid' )[0].name = "exit_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_uaclass' )[0].name = "exit_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_sub1' )[0].name = "exit_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_sub2' )[0].name = "exit_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_sub3' )[0].name = "exit_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.exit_media_code' )[0].name = "exit_media_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.selector_type' )[0].name = "selector_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";

				$( el ).find( '.leave_type' )[0].name = "leave_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_url' )[0].name = "leave_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_header' )[0].name = "leave_media_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_comment' )[0].name = "leave_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_type' )[0].name = "leave_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_placeid' )[0].name = "leave_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_uaclass' )[0].name = "leave_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_sub1' )[0].name = "leave_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_sub2' )[0].name = "leave_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_sub3' )[0].name = "leave_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_media_code' )[0].name = "leave_media_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				// leave exit
				$( el ).find( '.leave_exit_check' )[0].name = "leave_exit_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_type' )[0].name = "leave_exit_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_url' )[0].name = "leave_exit_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_header' )[0].name = "leave_exit_media_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_comment' )[0].name = "leave_exit_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_type' )[0].name = "leave_exit_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_placeid' )[0].name = "leave_exit_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_uaclass' )[0].name = "leave_exit_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_sub1' )[0].name = "leave_exit_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_sub2' )[0].name = "leave_exit_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_sub3' )[0].name = "leave_exit_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.leave_exit_media_code' )[0].name = "leave_exit_media_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				$( el ).find( '.popup_type' )[0].name = "popup_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_url' )[0].name = "popup_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_header' )[0].name = "popup_media_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_comment' )[0].name = "popup_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_type' )[0].name = "popup_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_placeid' )[0].name = "popup_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_uaclass' )[0].name = "popup_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_sub1' )[0].name = "popup_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_sub2' )[0].name = "popup_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_sub3' )[0].name = "popup_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_media_code' )[0].name = "popup_media_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				
				// popup exit
				$( el ).find( '.popup_exit_check' )[0].name = "popup_exit_check[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_type' )[0].name = "popup_exit_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_url' )[0].name = "popup_exit_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_header' )[0].name = "popup_exit_media_header[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_comment' )[0].name = "popup_exit_media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_type' )[0].name = "popup_exit_media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_placeid' )[0].name = "popup_exit_media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_uaclass' )[0].name = "popup_exit_media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_sub1' )[0].name = "popup_exit_media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_sub2' )[0].name = "popup_exit_media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_sub3' )[0].name = "popup_exit_media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.popup_exit_media_code' )[0].name = "popup_exit_media_code[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
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
					if ( window.confirm( adintgrform_admin_meta_boxes_selectors.i18n_remove_selector ) ) {
						var wrapper      = $( '#adintgr_selector_options' ).find( '.adintgr_selectors' ),
							current_page = parseInt( wrapper.attr( 'data-page' ), 10 ),
							total_pages  = Math.ceil( ( parseInt( wrapper.attr( 'data-total' ), 10 ) - 1 ) / adintgrform_admin_meta_boxes_selectors.selectors_per_page ),
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
			var checked_html = "checked=\"checked\"";
			if (count > 0) {
				checked_html = "";
			}
			var selector_html = "<div class=\"adintgr_selector adintgr-metabox closed selector-needs-update\">" +
									"<h3>" +
									"<input type=\"checkbox\" class=\"checkbox selector_check\" name=\"selector_check[" + count + "]\" " + checked_html + "/>" +
									"<a href=\"#\" class=\"remove_selector delete\">Remove</a>" +
									"<div class=\"handlediv\" aria-label=\"Click to toggle\"></div>" +
									"<div class=\"tips sort\" data-tip=\"Drag and drop to set selector order\"></div>" +
									"<strong></strong>" +
									"</h3>" +
									"<div class=\"adintgrform_adintgr_attributes adintgr-metabox-content\" style=\"display: none;\">" +
									"<div class=\"data\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"selector_name\">Name</label>" +
									"<input type=\"text\" class=\"short selector_name\" style=\"\" name=\"selector_name[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"selector_slug\">Slug</label>" +
									"<input type=\"text\" class=\"short selector_slug\" style=\"\" name=\"selector_slug[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"selector_title\">Page Title</label>" +
									"<input type=\"text\" class=\"short selector_title\" style=\"\" name=\"selector_title[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"selector_exit_check\">Exit Intent Popup Page</label>" +
									"<input type=\"checkbox\" class=\"checkbox selector_exit_check\" name=\"selector_exit_check[" + count + "]\"/>" +
									"</p>" +
									"<div class=\"adintgrform_selector_exit adintgr-metabox-sub-content adintgr-metabox-sub-content2\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_type\">Integration Type</label>" +
									"<select name=\"exit_type[" + count + "]\" class=\"short exit_type\">" +
									"<option value=\"\">None</option>" +
									"<option value=\"mediaalpha\">MediaAlpha</option>" +
									"</select>" +
									"<div class=\"exit-integration-none\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_url\">URL</label>" +
									"<input type=\"text\" class=\"short exit_url\" style=\"\" name=\"exit_url[" + count + "]\" value=\"\">" +
									"</p>" +
									"</div>" +
									"<div class=\"exit-integration-mediaalpha\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_header\">Page Header</label>" +
									"<input type=\"text\" class=\"short exit_media_header\" style=\"\" name=\"exit_media_header[" + count + "]\" checked=\"checked\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_comment\">MediaAlpha Comment</label>" +
									"<input type=\"text\" class=\"short exit_media_comment\" style=\"\" name=\"exit_media_comment[" + count + "]\" value=\"\" placeholder=\"Niche Seekers, Inc. / Auto - Email - Short Form\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_type\">MediaAlpha Type</label>" +
									"<select name=\"exit_media_type[" + count + "]\" class=\"short exit_media_type\">" +
									"<option value=\"ad_unit\">Ad Unit(default)</option>" +
									"<option value=\"form\">Form</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_placeid\">MediaAlpha Placement ID</label>" +
									"<input type=\"text\" class=\"short exit_media_placeid\" style=\"\" name=\"exit_media_placeid[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_uaclass\">MediaAlpha UA Class</label>" +
									"<select name=\"exit_media_uaclass[" + count + "]\" class=\"short exit_media_uaclass\">" +
									"<option value=\"web\">Web(default)</option>" +
									"<option value=\"mobile\">Mobile</option>" +
									"<option value=\"auto\">Auto</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_sub1\">MediaAlpha Sub_1</label>" +
									"<input type=\"text\" class=\"short exit_media_sub1\" style=\"\" name=\"exit_media_sub1[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_sub2\">MediaAlpha Sub_2</label>" +
									"<input type=\"text\" class=\"short exit_media_sub2\" style=\"\" name=\"exit_media_sub2[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_sub3\">MediaAlpha Sub_3</label>" +
									"<input type=\"text\" class=\"short exit_media_sub3\" style=\"\" name=\"exit_media_sub3[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"exit_media_code\">Custom Code</label>" +
									"<textarea type=\"text\" class=\"short exit_media_code\" rows=\"10\" name=\"exit_media_code[" + count + "]\" value=\"\"></textarea>" +
									"</p>" +
									"</div>" +
									"</div>" +
									"<p class=\"form-selector\">" +
									"<label for=\"selector_type\">Page Type</label>" +
									"<select name=\"selector_type[" + count + "]\" class=\"short selector_type\">" +
									"<option value=\"\">None</option>" +
									"<option value=\"leave\">Leave</option>" +
									"<option value=\"popup\">Leave and Popup</option>" +
									"</select>" +
									"</p>" +
									"<div class=\"adintgrform_leave adintgr-metabox-sub-content\">" +
									"<div class=\"adintgrform_leave_label\" style=\"display: none;\">" +
									"<p class=\"form-selector\">Leave Page</p>" +
									"</div>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_type\">Integration Type</label>" +
									"<select name=\"leave_type[" + count + "]\" class=\"short leave_type\">" +
									"<option value=\"\">None</option>" +
									"<option value=\"mediaalpha\">MediaAlpha</option>" +
									"</select>" +
									"</p>" +
									"<div class=\"leave-integration-none\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_url\">URL</label>" +
									"<input type=\"text\" class=\"short leave_url\" style=\"\" name=\"leave_url[" + count + "]\" value=\"\">" +
									"</p>" +
									"</div>" +
									"<div class=\"leave-integration-mediaalpha\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_header\">Page Header</label>" +
									"<input type=\"text\" class=\"short leave_media_header\" style=\"\" name=\"leave_media_header[" + count + "]\" checked=\"checked\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_comment\">MediaAlpha Comment</label>" +
									"<input type=\"text\" class=\"short leave_media_comment\" style=\"\" name=\"leave_media_comment[" + count + "]\" value=\"\" placeholder=\"Niche Seekers, Inc. / Auto - Email - Short Form\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_type\">MediaAlpha Type</label>" +
									"<select name=\"leave_media_type[" + count + "]\" class=\"short leave_media_type\">" +
									"<option value=\"ad_unit\">Ad Unit(default)</option>" +
									"<option value=\"form\">Form</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_placeid\">MediaAlpha Placement ID</label>" +
									"<input type=\"text\" class=\"short leave_media_placeid\" style=\"\" name=\"leave_media_placeid[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_uaclass\">MediaAlpha UA Class</label>" +
									"<select name=\"leave_media_uaclass[" + count + "]\" class=\"short leave_media_uaclass\">" +
									"<option value=\"web\">Web(default)</option>" +
									"<option value=\"mobile\">Mobile</option>" +
									"<option value=\"auto\">Auto</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_sub1\">MediaAlpha Sub_1</label>" +
									"<input type=\"text\" class=\"short leave_media_sub1\" style=\"\" name=\"leave_media_sub1[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_sub2\">MediaAlpha Sub_2</label>" +
									"<input type=\"text\" class=\"short leave_media_sub2\" style=\"\" name=\"leave_media_sub2[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_sub3\">MediaAlpha Sub_3</label>" +
									"<input type=\"text\" class=\"short leave_media_sub3\" style=\"\" name=\"leave_media_sub3[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_media_code\">Custom Code</label>" +
									"<textarea type=\"text\" class=\"short leave_media_code\" rows=\"10\" name=\"leave_media_code[" + count + "]\" value=\"\"></textarea>" +
									"</p>" +
									"</div>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_check\">Exit Intent Popup Page</label>" +
									"<input type=\"checkbox\" class=\"checkbox popup_exit_check\" name=\"popup_exit_check[" + count + "]\"/>" +
									"</p>" +
									"<div class=\"adintgrform_leave_exit adintgr-metabox-sub-content\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_type\">Integration Type</label>" +
									"<select name=\"leave_exit_type[" + count + "]\" class=\"short leave_exit_type\">" +
									"<option value=\"\">None</option>" +
									"<option value=\"mediaalpha\">MediaAlpha</option>" +
									"</select>" +
									"<div class=\"leave-exit-integration-none\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_url\">URL</label>" +
									"<input type=\"text\" class=\"short leave_exit_url\" style=\"\" name=\"leave_exit_url[" + count + "]\" value=\"\">" +
									"</p>" +
									"</div>" +
									"<div class=\"leave-exit-integration-mediaalpha\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_header\">Page Header</label>" +
									"<input type=\"text\" class=\"short leave_exit_media_header\" style=\"\" name=\"leave_exit_media_header[" + count + "]\" checked=\"checked\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_comment\">MediaAlpha Comment</label>" +
									"<input type=\"text\" class=\"short leave_exit_media_comment\" style=\"\" name=\"leave_exit_media_comment[" + count + "]\" value=\"\" placeholder=\"Niche Seekers, Inc. / Auto - Email - Short Form\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_type\">MediaAlpha Type</label>" +
									"<select name=\"leave_exit_media_type[" + count + "]\" class=\"short leave_exit_media_type\">" +
									"<option value=\"ad_unit\">Ad Unit(default)</option>" +
									"<option value=\"form\">Form</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_placeid\">MediaAlpha Placement ID</label>" +
									"<input type=\"text\" class=\"short leave_exit_media_placeid\" style=\"\" name=\"leave_exit_media_placeid[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_uaclass\">MediaAlpha UA Class</label>" +
									"<select name=\"leave_exit_media_uaclass[" + count + "]\" class=\"short leave_exit_media_uaclass\">" +
									"<option value=\"web\">Web(default)</option>" +
									"<option value=\"mobile\">Mobile</option>" +
									"<option value=\"auto\">Auto</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_sub1\">MediaAlpha Sub_1</label>" +
									"<input type=\"text\" class=\"short leave_exit_media_sub1\" style=\"\" name=\"leave_exit_media_sub1[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_sub2\">MediaAlpha Sub_2</label>" +
									"<input type=\"text\" class=\"short leave_exit_media_sub2\" style=\"\" name=\"leave_exit_media_sub2[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_sub3\">MediaAlpha Sub_3</label>" +
									"<input type=\"text\" class=\"short leave_exit_media_sub3\" style=\"\" name=\"leave_exit_media_sub3[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"leave_exit_media_code\">Custom Code</label>" +
									"<textarea type=\"text\" class=\"short leave_exit_media_code\" rows=\"10\" name=\"leave_exit_media_code[" + count + "]\" value=\"\"></textarea>" +
									"</p>" +
									"</div>" +
									"</div>" +
									"</div>" +
									"<div class=\"adintgrform_popup adintgr-metabox-sub-content\" style=\"display: none;\">" +
									"<p class=\"form-selector\">Popup Page</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_type\">Integration Type</label>" +
									"<select name=\"popup_type[" + count + "]\" class=\"short popup_type\">" +
									"<option value=\"\">None</option>" +
									"<option value=\"mediaalpha\">MediaAlpha</option>" +
									"</select>" +
									"</p>" +
									"<div class=\"popup-integration-none\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_url\">URL</label>" +
									"<input type=\"text\" class=\"short popup_url\" style=\"\" name=\"popup_url[" + count + "]\" value=\"\">" +
									"</p>" +
									"</div>" +
									"<div class=\"popup-integration-mediaalpha\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_header\">Page Header</label>" +
									"<input type=\"text\" class=\"short popup_media_header\" style=\"\" name=\"popup_media_header[" + count + "]\" checked=\"checked\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_comment\">MediaAlpha Comment</label>" +
									"<input type=\"text\" class=\"short popup_media_comment\" style=\"\" name=\"popup_media_comment[" + count + "]\" value=\"\" placeholder=\"Niche Seekers, Inc. / Auto - Email - Short Form\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_type\">MediaAlpha Type</label>" +
									"<select name=\"popup_media_type[" + count + "]\" class=\"short popup_media_type\">" +
									"<option value=\"ad_unit\">Ad Unit(default)</option>" +
									"<option value=\"form\">Form</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_placeid\">MediaAlpha Placement ID</label>" +
									"<input type=\"text\" class=\"short popup_media_placeid\" style=\"\" name=\"popup_media_placeid[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_uaclass\">MediaAlpha UA Class</label>" +
									"<select name=\"popup_media_uaclass[" + count + "]\" class=\"short popup_media_uaclass\">" +
									"<option value=\"web\">Web(default)</option>" +
									"<option value=\"mobile\">Mobile</option>" +
									"<option value=\"auto\">Auto</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_sub1\">MediaAlpha Sub_1</label>" +
									"<input type=\"text\" class=\"short popup_media_sub1\" style=\"\" name=\"popup_media_sub1[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_sub2\">MediaAlpha Sub_2</label>" +
									"<input type=\"text\" class=\"short popup_media_sub2\" style=\"\" name=\"popup_media_sub2[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_sub3\">MediaAlpha Sub_3</label>" +
									"<input type=\"text\" class=\"short popup_media_sub3\" style=\"\" name=\"popup_media_sub3[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_media_code\">Custom Code</label>" +
									"<textarea type=\"text\" class=\"short popup_media_code\" rows=\"10\" name=\"popup_media_code[" + count + "]\" value=\"\"></textarea>" +
									"</p>" +
									"</div>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_check\">Exit Intent Popup Page</label>" +
									"<input type=\"checkbox\" class=\"checkbox popup_exit_check\" name=\"popup_exit_check[" + count + "]\"/>" +
									"</p>" +
									"<div class=\"adintgrform_popup_exit adintgr-metabox-sub-content\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_type\">Integration Type</label>" +
									"<select name=\"popup_exit_type[" + count + "]\" class=\"short popup_exit_type\">" +
									"<option value=\"\">None</option>" +
									"<option value=\"mediaalpha\">MediaAlpha</option>" +
									"</select>" +
									"<div class=\"popup-exit-integration-none\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_url\">URL</label>" +
									"<input type=\"text\" class=\"short popup_exit_url\" style=\"\" name=\"popup_exit_url[" + count + "]\" value=\"\">" +
									"</p>" +
									"</div>" +
									"<div class=\"popup-exit-integration-mediaalpha\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_header\">Page Header</label>" +
									"<input type=\"text\" class=\"short popup_exit_media_header\" style=\"\" name=\"popup_exit_media_header[" + count + "]\" checked=\"checked\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_comment\">MediaAlpha Comment</label>" +
									"<input type=\"text\" class=\"short popup_exit_media_comment\" style=\"\" name=\"popup_exit_media_comment[" + count + "]\" value=\"\" placeholder=\"Niche Seekers, Inc. / Auto - Email - Short Form\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_type\">MediaAlpha Type</label>" +
									"<select name=\"popup_exit_media_type[" + count + "]\" class=\"short popup_exit_media_type\">" +
									"<option value=\"ad_unit\">Ad Unit(default)</option>" +
									"<option value=\"form\">Form</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_placeid\">MediaAlpha Placement ID</label>" +
									"<input type=\"text\" class=\"short popup_exit_media_placeid\" style=\"\" name=\"popup_exit_media_placeid[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_uaclass\">MediaAlpha UA Class</label>" +
									"<select name=\"popup_exit_media_uaclass[" + count + "]\" class=\"short popup_exit_media_uaclass\">" +
									"<option value=\"web\">Web(default)</option>" +
									"<option value=\"mobile\">Mobile</option>" +
									"<option value=\"auto\">Auto</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_sub1\">MediaAlpha Sub_1</label>" +
									"<input type=\"text\" class=\"short popup_exit_media_sub1\" style=\"\" name=\"popup_exit_media_sub1[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_sub2\">MediaAlpha Sub_2</label>" +
									"<input type=\"text\" class=\"short popup_exit_media_sub2\" style=\"\" name=\"popup_exit_media_sub2[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_sub3\">MediaAlpha Sub_3</label>" +
									"<input type=\"text\" class=\"short popup_exit_media_sub3\" style=\"\" name=\"popup_exit_media_sub3[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"popup_exit_media_code\">Custom Code</label>" +
									"<textarea type=\"text\" class=\"short popup_exit_media_code\" rows=\"10\" name=\"popup_exit_media_code[" + count + "]\" value=\"\"></textarea>" +
									"</p>" +
									"</div>" +
									"</div>" +
									"</div>" +
									"</div>" +
									"</div>" +
									"</div>" +
									"</div>";
									
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
				displaying_num.text( adintgrform_admin_meta_boxes_selectors.i18n_selector_count_single.replace( '%qty%', total ) );
			} else {
				displaying_num.text( adintgrform_admin_meta_boxes_selectors.i18n_selector_count_plural.replace( '%qty%', total ) );
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
				total_pages			= Math.ceil( new_qty / adintgrform_admin_meta_boxes_selectors.selectors_per_page ),
				options				= '';
				
			// Set the new total of pages
			wrapper.attr( 'data-total_pages', total_pages );
			
			$( '.total-pages', page_nav ).text( total_pages );
			
			// Set the new pagenav options
			for ( var i = 1; i <= total_pages; i++ ) {
				options += '<option value="' + i + '">' + i + '</option>';
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
