<?php

class WPAdIntgr_Page {
    
    const post_type = 'page';
    
    private static $id;
    private static $form;
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
		}
	}
	
    public function page_html( $args = '' ) {
        if ( isset( $_POST['selector'] ) && isset( $_POST['zipcode'] ) ) {
            $selectors = $this->form->prop( 'selectors' );
            foreach ( $selectors as $selector ) {
                if ( $selector['selector_slug'] == $_POST['selector'] ) {
                    if ( $selector['selector_type'] == 'mediaalpha' ) {
                        $footer_html = "<script type=\"text/javascript\">\n";
                        $footer_html .= "/* " . $selector['media_comment'] . " */\n";
                        $footer_html .= "var MediaAlphaExchange = {\n";
                        $footer_html .= "\"type\": \"" . $selector['media_type'] . "\",\n";
                        $footer_html .= "\"placement_id\": \"" . $selector['media_placeid'] . "\",\n";
                        $footer_html .= "\"version\": \"17\",\n";
                        $footer_html .= "\"sub_1\": \"" . $selector['media_sub1'] . "\",\n";
                        $footer_html .= "\"sub_2\": \"" . $selector['media_sub2'] . "\",\n";
                        $footer_html .= "\"sub_3\": \"" . $selector['media_sub3'] . "\",\n";
                        $footer_html .= "\"data\": {\n"; 
                        $footer_html .= "\"zip\": \"" . $_POST['zipcode'] . "\",\n";
                        $footer_html .= "}\n";
                        $footer_html .= "};\n";
                        $footer_html .= "</script>\n";
                        $footer_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                    }
                }
            }
        }
        return $footer_html;
    }
}