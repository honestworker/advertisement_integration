<?php

class WPAdIntgr_Form {
    
    const post_type = 'adintgr-form';
    
	private static $found_items = 0;
    private static $current = null;
    
	private $id;
	private $name;
	private $title;
	private $locale;
	private $page_id;
	private $unit_tag;
    private $properties = array();
    
	public static function count() {
		return self::$found_items;
    }
    
	public static function get_current() {
		return self::$current;
	}
    
	public static function register_post_type() {
		register_post_type( self::post_type, array(
			'labels' => array(
				'name' => __( 'Ad Integration Forms', 'adintgr-form' ),
				'singular_name' => __( 'Ad Integration Form', 'adintgr-form' ),
			),
			'rewrite' => false,
			'query_var' => false,
		) );
	}

	public static function find( $args = '' ) {
		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC',
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$args['post_type'] = self::post_type;
		
		$q = new WP_Query();
		$posts = $q->query( $args );
		
		self::$found_items = $q->found_posts;
		
		$objs = array();
		
		foreach ( (array) $posts as $post ) {
			$objs[] = new self( $post );
		}
		
		return $objs;
	}

	public static function get_template( $args = '' ) {
		global $l10n;
		
		$defaults = array( 'locale' => null, 'title' => '' );
		$args = wp_parse_args( $args, $defaults );
		
		$locale = $args['locale'];
		$title = $args['title'];
		
		if ( $locale ) {
			$mo_orig = $l10n['adintgr-form'];
			wpadintgr_load_textdomain( $locale );
		}
		
		self::$current = $form = new self;
		$form->title = ( $title ? $title : __( 'Untitled', 'adintgr-form' ) );
		$form->locale = ( $locale ? $locale : get_locale() );
		
		$properties = $form->get_properties();
		$form->properties = $properties;
		
		$form = apply_filters( 'wpadintgr_form_default_pack', $form, $args );
			
		if ( isset( $mo_orig ) ) {
			$l10n['adintgr-form'] = $mo_orig;
		}
		
		return $form;
	}
    
	public static function get_instance( $post ) {
		$post = get_post( $post );
		
		if ( ! $post || self::post_type != get_post_type( $post ) ) {
			return false;
		}
		
		return self::$current = new self( $post );
    }
    
	private static function get_unit_tag( $id = 0 ) {
		static $global_count = 0;
		
		$global_count += 1;
		
		if ( in_the_loop() ) {
			$unit_tag = sprintf( 'wpadintgr-f%1$d-p%2$d-o%3$d', absint( $id ), get_the_ID(), $global_count );
		} else {
			$unit_tag = sprintf( 'wpadintgr-f%1$d-o%2$d', absint( $id ), $global_count );
		}
		
		return $unit_tag;
	}

	private function __construct( $post = null ) {
		$post = get_post( $post );
		
		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id = $post->ID;
			$this->name = $post->post_name;
			$this->title = $post->post_title;
			$this->locale = get_post_meta( $post->ID, '_locale', true );
			$this->page_id = get_post_meta( $post->ID, '_page_id', true );
			
			$properties = $this->get_properties();
			
			foreach ( $properties as $key => $value ) {
				if ( metadata_exists( 'post', $post->ID, '_' . $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, '_' . $key, true );
				} elseif ( metadata_exists( 'post', $post->ID, $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, $key, true );
				}
			}
			
			$this->properties = $properties;
		}
		
		do_action( 'adintgr-form', $this );
    }

	public function __get( $name ) {
		$message = __( '<code>%1$s</code> property of a <code>WPAdIntgr_Form</code> object is <strong>no longer accessible</strong>. Use <code>%2$s</code> method instead.', 'adintgr-form' );

		if ( 'id' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'id', 'id()' ) );
			}			
			return $this->id;
		} elseif ( 'title' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'title', 'title()' ) );
			}			
			return $this->title;
		} elseif ( $prop = $this->prop( $name ) ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, $name, 'prop(\'' . $name . '\')' ) );
			}			
			return $prop;
		}
    }
    
	public function initial() {
		return empty( $this->id );
	}

	public function prop( $name ) {
		$props = $this->get_properties();
		return isset( $props[$name] ) ? $props[$name] : null;
    }
    
	public function get_properties() {
		$properties = (array) $this->properties;

		$properties = wp_parse_args( $properties, array(
			'selectors' => array(),
		));
		
		$properties = (array) apply_filters( 'wpadintgr_form_properties', $properties, $this );
		
		return $properties;
    }
    
	public function set_properties( $properties ) {
		$defaults = $this->get_properties();
		
		$properties = wp_parse_args( $properties, $defaults );
		$properties = array_intersect_key( $properties, $defaults );
		
		$this->properties = $properties;
    }
    
	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function set_title( $title ) {
		$title = trim( $title );
		
		if ( '' === $title ) {
			$title = __( 'Untitled', 'adintgr-form' );
		}
		
		$this->title = $title;
	}

	public function page_id() {
		return $this->page_id;
	}

	public function set_page_id($page_id) {
		$this->page_id = $page_id;
	}

	public function locale() {
		if ( wpadintgr_is_valid_locale( $this->locale ) ) {
			return $this->locale;
		} else {
			return '';
		}
	}

	public function set_locale( $locale ) {
		$locale = trim( $locale );
		
		if ( wpadintgr_is_valid_locale( $locale ) ) {
			$this->locale = $locale;
		} else {
			$this->locale = 'en_US';
		}
    }

    // Return true if this form is the same one as currently POSTed.
	public function is_posted() {
		if ( ! WPAdIntgr_Submission::get_instance() ) {
			return false;
		}
    }
    
	/* Generating Form HTML */
	public function form_html( $args = '' ) {
		$args = wp_parse_args( $args, array(
			'html_id' => '',
			'html_name' => '',
			'html_class' => '',
		) );
		
		if ( ! current_user_can( 'wpadintgr_submit', $this->id() ) ) {
			$notice = __("This form is available only for logged in users.", "adintgr-form" );
			$notice = sprintf('<p class="wpadintgr-subscribers-only">%s</p>', esc_html( $notice ) );
			
			return apply_filters( 'wpadintgr_subscribers_only_notice', $notice, $this );
		}
		
		$this->unit_tag = self::get_unit_tag( $this->id );
		
		$lang_tag = str_replace( '_', '-', $this->locale );
		if ( preg_match( '/^([a-z]+-[a-z]+)-/i', $lang_tag, $matches ) ) {
			$lang_tag = $matches[1];
		}
		        
		$html = sprintf( '<div %s>', wpadintgr_format_atts( array('role' => 'form', 'class' => 'wpadintgr',	'id' => $this->unit_tag, ( get_option( 'html_type' ) == 'text/html' ) ? 'lang' : 'xml:lang' => $lang_tag, 'dir' => wpadintgr_is_rtl( $this->locale ) ? 'rtl' : 'ltr', ) ));
        
        $url = wpadintgr_get_request_uri();
		if ( $frag = strstr( $url, '#' ) ) {
			$url = substr( $url, 0, -strlen( $frag ) );
		}		
		$url = apply_filters( 'wpadintgr_form_action_url', $url );
		
		$id_attr = apply_filters( 'wpadintgr_form_id_attr', preg_replace( '/[^A-Za-z0-9:._-]/', '', $args['html_id'] ) );
		$name_attr = apply_filters( 'wpadintgr_form_name_attr', preg_replace( '/[^A-Za-z0-9:._-]/', '', $args['html_name'] ) );
		
		$class = 'adintgr-form';
		if ( $args['html_class'] ) {
			$class .= ' ' . $args['html_class'];
		}
		
		$class = explode( ' ', $class );
		$class = array_map( 'sanitize_html_class', $class );
		$class = array_filter( $class );
		$class = array_unique( $class );
		$class = implode( ' ', $class );
		$class = apply_filters( 'wpadintgr_form_class_attr', $class );
		
		$enctype = apply_filters( 'wpadintgr_form_enctype', '' );
		$autocomplete = apply_filters( 'wpadintgr_form_autocomplete', '' );
		
		$atts = array(
			'action' => esc_url( $url ),
			'method' => 'post',
			'class' => $class,
			'enctype' => wpadintgr_enctype_value( $enctype ),
			'autocomplete' => $autocomplete,
		);
		
		if ( '' !== $id_attr ) {
			$atts['id'] = $id_attr;
		}
		
		if ( '' !== $name_attr ) {
			$atts['name'] = $name_attr;
		}
		
		$atts = wpadintgr_format_atts( $atts );		
		$html .= sprintf( '<form %s>', $atts ) . "\n";
		$html .= $this->form_hidden_fields();
		//$html .= $this->form_elements();		
		$html .= '</form>';
		$html .= '</div>';
		
		return $html;
	}

	private function form_hidden_fields() {
		$hidden_fields = array(
			'_wpadintgr' => $this->id(),
			'_wpadintgr_version' => WPADINTGR_VERSION,
			'_wpadintgr_locale' => $this->locale(),
			'_wpadintgr_page_id' => $this->page_id(),
			'_wpadintgr_unit_tag' => $this->unit_tag,
			'_wpadintgr_container_post' => 0,
		);

		if ( in_the_loop() ) {
			$hidden_fields['_wpadintgr_container_post'] = (int) get_the_ID();
		}

		if ( $this->nonce_is_active() ) {
			$hidden_fields['_wpnonce'] = wpadintgr_create_nonce();
		}

		$hidden_fields += (array) apply_filters(
			'wpadintgr_form_hidden_fields', array() );

		$content = '';

		foreach ( $hidden_fields as $name => $value ) {
			$content .= sprintf( '<input type="hidden" name="%1$s" value="%2$s" />', esc_attr( $name ), esc_attr( $value ) ) . "\n";
		}

		return '<div style="display: none;">' . "\n" . $content . '</div>' . "\n";
    }
    
	public function nonce_is_active() {
		$is_active = WPADINTGR_VERIFY_NONCE;

		$is_active = true;

		return (bool) apply_filters( 'wpadintgr_verify_nonce', $is_active, $this );
	}

	/* Save */
	public function save() {
		$props = $this->get_properties();
		$post_content = implode( "\n", wpadintgr_array_flatten( $props ) );

		if ( $this->initial() ) {
			$post_id = wp_insert_post( array(
				'post_type' => self::post_type,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => trim( $post_content ),
			));
			$this->page_id = wp_insert_post( array(
				'post_type' => 'page',
				'post_name' => 'adintgr_' . $post_id,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => '',
			));
		} else {
			$post_id = wp_update_post( array(
				'ID' => (int) $this->id,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => trim( $post_content ),
            ));
            $page = get_post( $this->page_id );
			$page_post_id = wp_update_post( array(
				'post_type' => 'page',
				'post_name' => 'adintgr_' . $post_id,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => '',
			));
		}

		if ( $post_id ) {
			foreach ( $props as $prop => $value ) {
                update_post_meta( $post_id, '_' . $prop, wpadintgr_normalize_newline_deep( $value ) );
			}

			if ( wpadintgr_is_valid_locale( $this->locale ) ) {
				update_post_meta( $post_id, '_locale', $this->locale );
			}

			update_post_meta( $post_id, '_page_id', $this->page_id );

			if ( $this->initial() ) {
				$this->id = $post_id;
				do_action( 'wpadintgr_after_create', $this );
			} else {
				do_action( 'wpadintgr_after_update', $this );
			}

			do_action( 'wpadintgr_after_save', $this );
		}

		return $post_id;
	}

	/* Copy */
	public function copy() {
		$new = new self;
		$new->title = $this->title . '_copy';
		$new->locale = $this->locale;
		$new->properties = $this->properties;

		return apply_filters( 'wpadintgr_copy', $new, $this );
	}

	/* Delete */
	public function delete() {
		if ( $this->initial() ) {
			return;
		}

		if ( wp_delete_post( $this->id, true ) ) {
			$this->id = 0;
			return true;
		}

		return false;
	}

	/* Shortcode */
	public function shortcode( $args = '' ) {
		$title = str_replace( array( '"', '[', ']' ), '', $this->title );
        $shortcode = sprintf( '[adintgr-form id="%1$d" title="%2$s"]', $this->id, $title );
        
		return apply_filters( 'wpadintgr_form_shortcode', $shortcode, $args, $this );
	}

}