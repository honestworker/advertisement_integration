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
    
    public static function get_instance( $post ) {
        $form = wpadintgr_form( $post );
        if ( $form ) {
            $post = get_post( $form->page_id );
            
            if ( ! $post || self::post_type != get_post_type( $post ) ) {
                return false;
            }
            
            return self::$current = new self( $post );
		} else {
		    return false;
		}
    }

	private function __construct( $post = null ) {
	    $post = get_post( $post );
	    if ( $post && self::post_type == get_post_type( $post ) ) {
	        $this->id = $post->ID;
		    $form_id = str_replace( "adintgr_", "", $post->post_name );
            $this->form = wpadintgr_form( $form_id );
            $this->form_id = $form_id;
		}
	}
	
    public function page_html( $args = '' ) {
        $adintgr_page_html = "";
        if ( isset( $_POST['selector'] ) && isset( $_POST['zipcode'] ) ) {
            $selectors = $this->form->prop( 'selectors' );
            foreach ( $selectors as $selector ) {
                if ( $selector['selector_slug'] == $_POST['selector'] ) {
                    if ( $selector['selector_type'] == 'mediaalpha' ) {
                        if ( $selector['media_type_unit'] == 'on' ) {
                            $unit_page_html = "<script type=\"text/javascript\">\n";
                            $unit_page_html .= "/* " . $selector['media_comment'] . " */\n";
                            $unit_page_html .= "var MediaAlphaExchange = {\n";
                            $unit_page_html .= "\"type\": \"ad_unit\",\n";
                            $unit_page_html .= "\"placement_id\": \"" . $selector['media_placeid'] . "\",\n";
                            $unit_page_html .= "\"version\": \"17\",\n";
                            $unit_page_html .= "\"sub_1\": \"" . $selector['media_sub1'] . "\",\n";
                            $unit_page_html .= "\"sub_2\": \"" . $selector['media_sub2'] . "\",\n";
                            $unit_page_html .= "\"sub_3\": \"" . $selector['media_sub3'] . "\",\n";
                            $unit_page_html .= "\"data\": {\n"; 
                            $unit_page_html .= "\"zip\": \"" . $_POST['zipcode'] . "\",\n";
                            $unit_page_html .= "}\n";
                            $unit_page_html .= "};\n";
                            $unit_page_html .= "</script>\n";
                            $unit_page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                            $adintgr_page_html = $unit_page_html;
                        }
                        
                        if ( $selector['media_type_form'] == 'on' ) {
                            if ( $selector['media_type_unit'] == 'on' ) {
                                add_action( 'wp_footer', array( $this, 'include_popup_page_script' ) );
                            } else {
                                $form_page_html = "<script type=\"text/javascript\">\n";
                                $form_page_html .= "/* " . $selector['media_comment'] . " */\n";
                                $form_page_html .= "var MediaAlphaExchange = {\n";
                                $form_page_html .= "\"type\": \"form\",\n";
                                $form_page_html .= "\"placement_id\": \"" . $selector['media_placeid'] . "\",\n";
                                $form_page_html .= "\"version\": \"17\",\n";
                                $form_page_html .= "\"sub_1\": \"" . $selector['media_sub1'] . "\",\n";
                                $form_page_html .= "\"sub_2\": \"" . $selector['media_sub2'] . "\",\n";
                                $form_page_html .= "\"sub_3\": \"" . $selector['media_sub3'] . "\",\n";
                                $form_page_html .= "\"data\": {\n"; 
                                $form_page_html .= "\"zip\": \"" . $_POST['zipcode'] . "\",\n";
                                $form_page_html .= "}\n";
                                $form_page_html .= "};\n";
                                $form_page_html .= "</script>\n";
                                $form_page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                                $adintgr_page_html = $form_page_html;
                            }
                        }
                    }
                }
            }
        }
        if ( isset( $_GET['selector'] ) && isset( $_GET['zipcode'] ) ) {
            $adintgr_page_html = "";
            $selectors = $this->form->prop( 'selectors' );
            foreach ( $selectors as $selector ) {
                if ( $selector['selector_slug'] == $_GET['selector'] ) {
                    if ( $selector['selector_type'] == 'mediaalpha' ) {
                        if ( $selector['media_type_form'] == 'on' ) {
                            $adintgr_page_html = "<script type=\"text/javascript\">\n";
                            $adintgr_page_html .= "/* " . $selector['media_comment'] . " */\n";
                            $adintgr_page_html .= "var MediaAlphaExchange = {\n";
                            $adintgr_page_html .= "\"type\": \"form\",\n";
                            $adintgr_page_html .= "\"placement_id\": \"" . $selector['media_placeid'] . "\",\n";
                            $adintgr_page_html .= "\"version\": \"17\",\n";
                            $adintgr_page_html .= "\"sub_1\": \"" . $selector['media_sub1'] . "\",\n";
                            $adintgr_page_html .= "\"sub_2\": \"" . $selector['media_sub2'] . "\",\n";
                            $adintgr_page_html .= "\"sub_3\": \"" . $selector['media_sub3'] . "\",\n";
                            $adintgr_page_html .= "\"data\": {\n";
                            $adintgr_page_html .= "\"zip\": \"" . $_GET['zipcode'] . "\",\n";
                            $adintgr_page_html .= "}\n";
                            $adintgr_page_html .= "};\n";
                            $adintgr_page_html .= "</script>\n";
                            $adintgr_page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                        }
                    }
                }
            }
        }
        return $adintgr_page_html;
    }

    public function include_popup_page_script() {
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery(function($) {
            window.open('https://discount-auto-rates.com/adintgr_<?php echo $this->form_id;?>?selector=<?php echo $_POST['selector'];?>&zipcode=<?php echo $_POST['zipcode'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
        });
        </script>
        <?php
    }
}