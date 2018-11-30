<?php

class WPAdIntgr_Page {
    
    const post_type = 'page';
    
    private static $id;
    private static $form;
    private static $form_id;
    private static $current = null;
	
	public static function get_current() {
		return self::$current;
    }
    
    public static function get_instance( $post, $index ) {
        $form = wpadintgr_form( $post );
        if ( $form ) {
            $page = get_post( $form->pages_id[$index] );
            
            if ( ! $page || self::post_type != get_post_type( $page ) ) {
                return false;
            }
            
            return self::$current = new self( $page, $post, $index );
		} else {
		    return false;
		}
    }
    
    private function __construct( $page = null, $post = null, $index = null ) {
        $page = get_post( $page );
        if ( $page && self::post_type == get_post_type( $page ) ) {
            $this->id = $page->ID;
            $this->index = $index;
            $this->form_id = $post;
            $this->form = wpadintgr_form( $this->form_id );
		}
	}
	
    public function page_html( $args = '' ) {
        $page_html = "";
        $selectors = $this->form->prop( 'selectors' );
        $selector = $selectors[$this->index];
        if ( isset( $_POST['zipcode'] ) ) {
            if ( $selector['leave_type'] == 'mediaalpha' ) {
                if ( $selector['selector_type'] != 'popup' ) {
                    if ( !$_POST['zipcode'] ) {
                        add_action( 'wp_footer', array( $this, 'include_redirect_own_script' ) );
                        return;
                    }
                }
            }
            if ( $selector['selector_type'] == 'popup' ) {
                if ( $selector['popup_type'] == 'mediaalpha' ) {
                    add_action( 'wp_footer', array( $this, 'include_popup_page_script' ) );
                } else if ($selector['popup_type'] == '' ) {
                    add_action( 'wp_footer', array( $this, 'include_popup_script' ) );
                }
            }
            if ( $selector['leave_type'] == 'mediaalpha' ) {
                $page_html .= "<h2>{$selector['leave_media_header']}</h2>\n";
                $page_html .= "<script type=\"text/javascript\">\n";
                $page_html .= "/* " . $selector['leave_media_comment'] . " */\n";
                $page_html .= "var MediaAlphaExchange = {\n";
                $page_html .= "\"type\": \"" . $selector['leave_media_type'] . "\",\n";
                $page_html .= "\"placement_id\": \"" . $selector['leave_media_placeid'] . "\",\n";
                $page_html .= "\"version\": \"17\",\n";
                if ( $selector['leave_media_uaclass'] )
                    $page_html .= "\"ua_class\": \"" . $selector['leave_media_uaclass'] . "\",\n";
                if ( $selector['leave_media_sub1'] )
                    $page_html .= "\"sub_1\": \"" . $selector['leave_media_sub1'] . "\",\n";
                if ( $selector['leave_media_sub2'] )
                    $page_html .= "\"sub_2\": \"" . $selector['leave_media_sub2'] . "\",\n";
                if ( $selector['leave_media_sub3'] )
                    $page_html .= "\"sub_3\": \"" . $selector['leave_media_sub3'] . "\",\n";
                $page_html .= "\"data\": {\n"; 
                $page_html .= "\"zip\": \"" . $_POST['zipcode'] . "\",\n";
                $page_html .= "}\n";
                $page_html .= "};\n";
                $page_html .= "</script>\n";
                $page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                add_action( 'wp_footer', array( $this, 'include_custom_code' ) );
            } else if ($selector['leave_type'] == '' ) {
                add_action( 'wp_footer', array( $this, 'include_redirect_code' ) );
            }
            if ( $selector['selector_type'] == 'exit' ) {
                if ( $selector['popup_type'] == 'mediaalpha' ) {
                    add_action( 'wp_footer', array( $this, 'include_exit_code' ) );
                }
            }
        }
        
        if ( isset( $_GET['zipcode'] ) ) {
            if ( $selector['selector_type'] == 'popup' ) {
                if ( $selector['popup_type'] == 'mediaalpha' ) {
                    $page_html = "<h2>{$selector['popup_media_header']}</h2>\n";
                    $page_html .= "<script type=\"text/javascript\">\n";
                    $page_html .= "/* " . $selector['leave_media_comment'] . " */\n";
                    $page_html .= "var MediaAlphaExchange = {\n";
                    $page_html .= "\"type\": \"" . $selector['popup_media_type'] . "\",\n";
                    $page_html .= "\"placement_id\": \"" . $selector['popup_media_placeid'] . "\",\n";
                    $page_html .= "\"version\": \"17\",\n";
                    if ( $selector['popup_media_uaclass'] )
                        $page_html .= "\"ua_class\": \"" . $selector['popup_media_uaclass'] . "\",\n";
                    if ( $selector['popup_media_sub1'] )
                        $page_html .= "\"sub_1\": \"" . $selector['popup_media_sub1'] . "\",\n";
                    if ( $selector['popup_media_sub2'] )
                        $page_html .= "\"sub_2\": \"" . $selector['popup_media_sub2'] . "\",\n";
                    if ( $selector['popup_media_sub3'] )
                        $page_html .= "\"sub_3\": \"" . $selector['popup_media_sub3'] . "\",\n";
                    $page_html .= "\"data\": {\n"; 
                    $page_html .= "\"zip\": \"" . $_POST['zipcode'] . "\",\n";
                    $page_html .= "}\n";
                    $page_html .= "};\n";
                    $page_html .= "</script>\n";
                    $page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                    add_action( 'wp_footer', array( $this, 'include_custom_code' ) );
                }
            }
        }
        return $page_html;
    }

    public function include_custom_code() {
        $selectors = $this->form->prop( 'selectors' );
        $selector = $selectors[$_POST['adintgr-selector']];
        if ( isset( $_GET['zipcode'] ) ) {
            echo $selector['popup_media_code'];
        } else {
            echo $selector['leave_media_code'];
        }
    }
    
    public function include_redirect_own_script() {
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery(function($) {
            window.location.replace('<?php echo get_site_url();?>');
        });
        </script>
        <?php
    }
    
    public function include_redirect_code() {
        $selectors = $this->form->prop( 'selectors' );
        $selector = $selectors[$_POST['adintgr-selector']];
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery(function($) {
            window.location.replace('<?php echo $selector['leave_url'];?>');
        });
        </script>
        <?php
    }

    public function include_popup_script() {
        $selectors = $this->form->prop( 'selectors' );
        $selector = $selectors[$_POST['adintgr-selector']];
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery(function($) {
            window.open('<?php echo $selector['popup_url'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
        });
        </script>
        <?php
    }

    public function include_exit_code() {
        $selectors = $this->form->prop( 'selectors' );
        $selector = $selectors[$_POST['adintgr-selector']];
        ?>
        <script type="text/javascript">
        document.addEventListener('mouseleave', function(e) {
            if (e.pageY - document.body.scrollTop < 0) {
                window.open('<?php echo $selector['exit_url'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
            }
        });
        </script>
        <?php
    }
    
    public function include_popup_page_script() {
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery(function($) {
            window.open('<?php echo get_permalink($this->id);?>?zipcode=<?php echo $_POST['zipcode'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
        });
        </script>
        <?php
    }
}