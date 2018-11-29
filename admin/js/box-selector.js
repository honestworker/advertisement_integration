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
			$( '.adintgr-metaboxes-wrapper' ).on( 'click', '.selector_check', this.change_checked );
			
			$( '.adintgr-metaboxes-wrapper' ).on( 'change', '.selector_type', this.change_type );
			
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
		change_checked: function() {
			$( '.adintgr_check' ).each( function() {
				$( this ).prop('checked', false);
			});
			$( this ).prop('checked', true);
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
				$( el ).find( '.selector_url' )[0].name = "selector_url[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.selector_type' )[0].name = "selector_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.media_comment' )[0].name = "media_comment[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.media_type' )[0].name = "media_type[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.media_placeid' )[0].name = "media_placeid[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.media_uaclass' )[0].name = "media_uaclass[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.media_sub1' )[0].name = "media_sub1[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.media_sub2' )[0].name = "media_sub2[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
				$( el ).find( '.media_sub3' )[0].name = "media_sub3[" + parseInt( $( el ).index( '.adintgr_selectors .adintgr_selector' ) + offset, 10 ) + "]";
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
									"<label for=\"selector_url\">URL</label>" +
									"<input type=\"text\" class=\"short selector_url\" style=\"\" name=\"selector_url[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"selector_type\">Integration Type</label>" +
									"<select name=\"selector_type[" + count + "]\" class=\"short selector_type\">" +
									"<option value=\"\">None</option>" +
									"<option value=\"mediaalpha\">MediaAlpha</option>" +
									"</select>" +
									"</p>" +
									"<div class=\"integration-mediaalpha\" style=\"display: none;\">" +
									"<p class=\"form-selector\">" +
									"<label for=\"media_comment\">MediaAlpha Comment</label>" +
									"<input type=\"text\" class=\"short media_comment\" style=\"\" name=\"media_comment[" + count + "]\" value=\"Niche Seekers, Inc. / Auto - Email - Short Form\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"media_type\">MediaAlpha Type</label>" +
									"<select name=\"media_type[" + count + "]\" class=\"short media_type\">" +
									"<option value=\"ad_unit\">Ad Unit(default)</option>" +
									"<option value=\"form\">Form</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"media_placeid\">MediaAlpha Placement ID</label>" +
									"<input type=\"text\" class=\"short media_placeid\" style=\"\" name=\"media_placeid[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"media_uaclass\">MediaAlpha UA Class</label>" +
									"<select name=\"media_uaclass[" + count + "]\" class=\"short media_uaclass\">" +
									"<option value=\"web\">Web(default)</option>" +
									"<option value=\"mobile\">Mobile</option>" +
									"<option value=\"auto\">Auto</option>" +
									"</select>" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"media_sub1\">MediaAlpha Sub_1</label>" +
									"<input type=\"text\" class=\"short media_sub1\" style=\"\" name=\"media_sub1[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"media_sub2\">MediaAlpha Sub_2</label>" +
									"<input type=\"text\" class=\"short media_sub2\" style=\"\" name=\"media_sub2[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"<p class=\"form-selector\">" +
									"<label for=\"media_sub3\">MediaAlpha Sub_3</label>" +
									"<input type=\"text\" class=\"short media_sub3\" style=\"\" name=\"media_sub3[" + count + "]\" value=\"\" placeholder=\"\">" +
									"</p>" +
									"</div>" +
									"</div>" +
									"</div>" +
									"</div>";
									
			var selector = $(selector_html);
			$( '#adintgr_selector_options' ).find( '.adintgr_selectors' ).append( selector );
			$( 'button.cancel-selector-changes, button.save-selector-changes' ).removeAttr( 'disabled' );
			$( '#adintgr_selector_options' ).trigger( 'adintgr_selectors_added', 1 );
		},
		
		/**
		 * Change Integration Type
		 *
		 * @return {Bool}
		 */
		change_type: function() {
			var integration_type = $( this ).val();
			if ( integration_type == 'mediaalpha' ) {
				$(this).parent().parent().parent().find( '.integration-mediaalpha' ).show();
			} else {
				$(this).parent().parent().parent().find( '.integration-mediaalpha' ).hide();
			}
		}
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
