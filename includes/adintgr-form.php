<?php

class WPAdIntgr_Form {
    
    const post_type = 'adintgr-form';
    
	private static $found_items = 0;
    private static $current = null;
    
	private $id;
	private $name;
	private $title;
	private $locale;
	private $pages_id = [];
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

	private static function get_unit_page( $id = 0 ) {
		static $global_page_count = 0;
		
		$global_page_count += 1;
		
		if ( in_the_loop() ) {
			$unit_page = sprintf( 'wpadintgr_f%1$d_p%2$d_o%3$d', absint( $id ), get_the_ID(), $global_page_count );
		} else {
			$unit_page = sprintf( 'wpadintgr_f%1$d_o%2$d', absint( $id ), $global_page_count );
		}
		
		return $unit_page;
	}

	private function __construct( $post = null ) {
		$post = get_post( $post );
		
		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id = $post->ID;
			$this->name = $post->post_name;
			$this->title = $post->post_title;
			$this->locale = get_post_meta( $post->ID, '_locale', true );
			$this->pages_id = get_post_meta( $post->ID, '_pages_id', true );
			
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

	public function pages_id() {
		return $this->pages_id;
	}

	public function set_pages_id($pages_id) {
		$this->pages_id = $pages_id;
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
    
	public function form_elements() {
		$elements = "<p>";
		$elements .= "<span id=\"adintgr-checked\" class=\"wpadintgr-form-control-wrap checkboxes\">";
		$elements .= "<span class=\"wpadintgr-form-control wpadintgr-radio checkboxes\">";
		$selectors = $this->prop( 'selectors' );
		$selector_count = count($selectors);
		for ($i = 0; $i < $selector_count; $i++) {
		    $elements .= "<span class=\"wpadintgr-list-item ";
		    if ($i == 0) {
		        $elements .= "first";
		    } else if ($i == $selector_count - 1) {
		        $elements .= "last";
		    }
		    $elements .= "\">";
		    $elements .= "<input type=\"radio\" class=\"adintgr_selector\" name=\"selector\" value=\"" . $selectors[$i]['selector_slug']  . "\"";
		    if ($selectors[$i]['selector_check'] == "on") {
		        $elements .= " checked=\"checked\"";
		    }
		    $elements .= " page_url=\"" . get_permalink( $this->pages_id[$i] ) . "\"/>";
		    $elements .= "<span class=\"wpadintgr-list-item-label\">" . $selectors[$i]['selector_name']  . "</span>";
		    $elements .= "</span>";
		}
		
		$elements .= "</span>";
		$elements .= "</span>";
		
		$elements .= "<span class=\"wpadintgr-form-control-wrap zipcode\">";
		$elements .= "<input id=\"adintgr-zipcode\" type=\"text\" name=\"zipcode\" value=\"\" size=\"40\" class=\"wpadintgr-form-control wpadintgr-text wpadintgr-validates-as-required\" aria-required=\"true\" aria-invalid=\"false\" placeholder=\"Type Zipcode\">";
		$elements .= "</span>";
		$elements .= "<input type=\"submit\" value=\"GO>\" class=\"wpadintgr-form-control wpadintgr-submit submit\">";
		
		$elements .= "</p>";
		
		return $elements;
	}

	/* Generating Form HTML */
	public function form_html( $args = '' ) {
		$args = wp_parse_args( $args, array(
			'html_id' => '',
			'html_name' => '',
			'html_class' => '',
		) );
		
		$this->unit_tag = self::get_unit_tag( $this->id );
		
		$lang_tag = str_replace( '_', '-', $this->locale );
		if ( preg_match( '/^([a-z]+-[a-z]+)-/i', $lang_tag, $matches ) ) {
			$lang_tag = $matches[1];
		}
		        
		$html = sprintf( '<div %s>', wpadintgr_format_atts( array('role' => 'form', 'class' => 'wpadintgr wpadintgr-wrapper',	'id' => $this->unit_tag, ( get_option( 'html_type' ) == 'text/html' ) ? 'lang' : 'xml:lang' => $lang_tag, 'dir' => wpadintgr_is_rtl( $this->locale ) ? 'rtl' : 'ltr', ) ));
        
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
		
		$selectors = $this->prop( 'selectors' );
		$action_url = "";
		for ( $index = 0; $index < $this->pages_id; $index++ ) {
			if ( $selectors[$index]['selector_check'] == 'on' ) {
				$action_url = get_permalink( $this->pages_id[ $index ] );
				break;
			}
		}
		
		$atts = array(
			'action' => $action_url,
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
		$html .= $this->form_elements();		
		$html .= '</form>';
		$html .= '</div>';
		
		return $html;
	}

	private function form_hidden_fields() {
		$hidden_fields = array(
			'_wpadintgr' => $this->id(),
			'_wpadintgr_version' => WPADINTGR_VERSION,
			'_wpadintgr_locale' => $this->locale(),
			'_wpadintgr_unit_tag' => $this->unit_tag,
			'_wpadintgr_container_post' => 0,
		);

		if ( in_the_loop() ) {
			$hidden_fields['_wpadintgr_container_post'] = (int) get_the_ID();
		}
		
		if ( $this->nonce_is_active() ) {
			$hidden_fields['_wpnonce'] = wpadintgr_create_nonce();
		}
		
		$hidden_fields += (array) apply_filters( 'wpadintgr_form_hidden_fields', array() );
		
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
				'post_content' => '',
			));
			if ( isset( $props['selectors'] ) ) {
				if ( is_array( $props['selectors'] ) ) {
					for ( $index = 0; $indx < count( $props['selectors'] ); $indx++ ) {
						$this->pages_id[] = wp_insert_post( array(
							'post_type' => 'page',
							'post_name' => $this->get_unit_page(),
							'post_status' => 'publish',
							'post_title' =>  $props['selectors'][$index]['selector_title'],
							'post_content' => "[adintgr-page id=\"" . $post_id . "\" title=\"" . $props['selectors'][$index]['selector_title'] . "\" index=\"". $index ."\"]",
						));
					}
				}
			}
		} else {
			$post_id = wp_update_post( array(
				'ID' => (int) $this->id,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => '',
			));
			if ( isset( $props['selectors'] ) ) {
				if ( is_array( $props['selectors'] ) ) {
					for ( $index = 0; $index < count( $props['selectors'] ); $index++ ) {
						if ( $index + 1 > count( $this->pages_id ) ) {
							$this->pages_id[] = wp_insert_post( array(
								'post_type' => 'page',
								'post_name' => $this->get_unit_page(),
								'post_status' => 'publish',
								'post_title' =>  $props['selectors'][$index]['selector_title'],
								'post_content' => "[adintgr-page id=\"" . $post_id . "\" title=\"" . $props['selectors'][$index]['selector_title'] . "\" index=\"". $index ."\"]",
							));
						} else {
							wp_update_post( array(
								'ID' => (int) $this->pages_id[$index],
								'post_type' => 'page',
								'post_status' => 'publish',
								'post_title' =>  $props['selectors'][$index]['selector_title'],
								'post_content' => "[adintgr-page id=\"" . $post_id . "\" title=\"" . $props['selectors'][$index]['selector_title'] . "\" index=\"". $index ."\"]",
							));
						}
					}
				}
			}
		}
		
		if ( $post_id ) {
			foreach ( $props as $prop => $value ) {
                update_post_meta( $post_id, '_' . $prop, wpadintgr_normalize_newline_deep( $value ) );
			}
			
			if ( wpadintgr_is_valid_locale( $this->locale ) ) {
				update_post_meta( $post_id, '_locale', $this->locale );
			}
			
			update_post_meta( $post_id, '_pages_id', $this->pages_id );
			
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