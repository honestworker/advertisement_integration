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
        if ( isset( $_GET['action'] ) && isset( $_GET['selector'] ) ) {
            if ( $_GET['selector'] != '' ) {
                $selector = $selectors[$_GET['selector']];
                if ( $_GET['action'] == 'exit' ) {
                    if ( $selector['main_exit_type'] == 'mediaalpha' ) {
                        $page_html .= "<h2>{$selector['main_exit_header']}</h2>\n";
                        $page_html .= "<script type=\"text/javascript\">\n";
                        $page_html .= "/* " . $selector['main_exit_media_comment'] . " */\n";
                        $page_html .= "var MediaAlphaExchange = {\n";
                        $page_html .= "\"type\": \"" . $selector['main_exit_media_type'] . "\",\n";
                        $page_html .= "\"placement_id\": \"" . $selector['main_exit_media_placeid'] . "\",\n";
                        $page_html .= "\"version\": \"17\",\n";
                        if ( $selector['main_exit_media_uaclass'] )
                            $page_html .= "\"ua_class\": \"" . $selector['main_exit_media_uaclass'] . "\",\n";
                        if ( $selector['main_exit_media_sub1'] )
                            $page_html .= "\"sub_1\": \"" . $selector['main_exit_media_sub1'] . "\",\n";
                        if ( $selector['main_exit_media_sub2'] )
                            $page_html .= "\"sub_2\": \"" . $selector['main_exit_media_sub2'] . "\",\n";
                        if ( $selector['main_exit_media_sub3'] )
                            $page_html .= "\"sub_3\": \"" . $selector['main_exit_media_sub3'] . "\",\n";
                        $page_html .= "\"data\": {\n"; 
                        $page_html .= "\"zip\": 90210,\n";
                        $page_html .= "}\n";
                        $page_html .= "};\n";
                        $page_html .= "</script>\n";
                        $page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                    } else if ( $selector['main_exit_type'] == 'insuranceclicks' ) {
                        $page_html .= "<h2>{$selector['main_exit_header']}</h2>\n";
                        $page_html .= "<script>\n";
                        $page_html .= "cInsstate = \"" . $selector['main_exit_insurance_state'] . "\";\n";
                        $zipcode = "35004";
                        if ( isset($_GET['zipcode']) ) {
                            if ( $_GET['zipcode'] != '' ) {
                                $zipcode = $_GET['zipcode'];
                            }
                        }
                        $page_html .= "cInszip = \"" . $zipcode . "\";\n";
                        $page_html .= "cInsmaxads = \"4\";\n";
                        $page_html .= "cInspublisher_id = \"" . $selector['main_exit_insurance_userid'] . "\";\n";
                        $page_html .= "cInsinsurancetype = \"" . $selector['main_exit_insurance_type'] . "\";\n";
                        $page_html .= "!function (e, f, u) {\n";
                        $page_html .= "e.async = 1;\n";
                        $page_html .= "e.src = u;\n";
                        $page_html .= "document.write('<div id=\"iClickContainer\"></div>');\n";
                        $page_html .= "f.parentNode.insertBefore(e, f);\n";
                        $page_html .= "}(document.createElement('script'),\n";
                        $page_html .= "document.getElementsByTagName('script')[0],\n";
                        $page_html .= "'https://insuranceclicks.com/iClickListing/RenderListing.js');\n";
                        $page_html .= "</script>\n";
                    } else {
                        add_action( 'wp_footer', array( $this, 'include_exit_redirect_code' ) );
                    }
                    if ( $selector['main_exit_type'] != '' ) {
                        if ( $selector['main_exit_code'] ) {
                            add_action( 'wp_footer', array( $this, 'include_exit_custom_code' ) );
                        }
                    }
                } else if ( $_GET['action'] == 'leave' ) {
                    if ( $selector['selector_type'] != '' ) {
                        if ( $selector['leave_exit_check'] == 'on' ) {
                            if ( $selector['leave_exit_type'] == 'mediaalpha' ) {
                                $page_html .= "<h2>{$selector['leave_exit_header']}</h2>\n";
                                $page_html .= "<script type=\"text/javascript\">\n";
                                $page_html .= "/* " . $selector['leave_exit_media_comment'] . " */\n";
                                $page_html .= "var MediaAlphaExchange = {\n";
                                $page_html .= "\"type\": \"" . $selector['leave_exit_media_type'] . "\",\n";
                                $page_html .= "\"placement_id\": \"" . $selector['leave_exit_media_placeid'] . "\",\n";
                                $page_html .= "\"version\": \"17\",\n";
                                if ( $selector['leave_exit_media_uaclass'] )
                                    $page_html .= "\"ua_class\": \"" . $selector['leave_exit_media_uaclass'] . "\",\n";
                                if ( $selector['leave_exit_media_sub1'] )
                                    $page_html .= "\"sub_1\": \"" . $selector['leave_exit_media_sub1'] . "\",\n";
                                if ( $selector['leave_exit_media_sub2'] )
                                    $page_html .= "\"sub_2\": \"" . $selector['leave_exit_media_sub2'] . "\",\n";
                                if ( $selector['leave_exit_media_sub3'] )
                                    $page_html .= "\"sub_3\": \"" . $selector['leave_exit_media_sub3'] . "\",\n";
                                $page_html .= "\"data\": {\n"; 
                                $page_html .= "\"zip\": 90210,\n";
                                $page_html .= "}\n";
                                $page_html .= "};\n";
                                $page_html .= "</script>\n";
                                $page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                            } else if ( $selector['leave_exit_type'] == 'insuranceclicks' ) {
                                $page_html .= "<h2>{$selector['leave_exit_header']}</h2>\n";
                                $page_html .= "<script>\n";
                                $page_html .= "cInsstate = \"" . $selector['leave_exit_insurance_state'] . "\";\n";
                                $zipcode = "35004";
                                if ( isset($_GET['zipcode']) ) {
                                    if ( $_GET['zipcode'] != '' ) {
                                        $zipcode = $_GET['zipcode'];
                                    }
                                }
                                $page_html .= "cInszip = \"" . $zipcode . "\";\n";
                                $page_html .= "cInsmaxads = \"4\";\n";
                                $page_html .= "cInspublisher_id = \"" . $selector['leave_exit_insurance_userid'] . "\";\n";
                                $page_html .= "cInsinsurancetype = \"" . $selector['leave_exit_insurance_type'] . "\";\n";
                                $page_html .= "!function (e, f, u) {\n";
                                $page_html .= "e.async = 1;\n";
                                $page_html .= "e.src = u;\n";
                                $page_html .= "document.write('<div id=\"iClickContainer\"></div>');\n";
                                $page_html .= "f.parentNode.insertBefore(e, f);\n";
                                $page_html .= "}(document.createElement('script'),\n";
                                $page_html .= "document.getElementsByTagName('script')[0],\n";
                                $page_html .= "'https://insuranceclicks.com/iClickListing/RenderListing.js');\n";
                                $page_html .= "</script>\n";
                            } else {
                                add_action( 'wp_footer', array( $this, 'include_exit_leave_redirect_code' ) );
                            }
                            if ( $selector['leave_exit_type'] != '' ) {
                                if ( $selector['leave_exit_code'] ) {
                                    add_action( 'wp_footer', array( $this, 'include_exit_leave_custom_code' ) );
                                }
                            }
                        }
                    }
                } else if ( $_GET['action'] == 'popup' ) {
                    if ( $selector['selector_type'] == 'popup' ) {
                        if ( $selector['popup_exit_check'] == 'on' ) {
                            if ( $selector['popup_exit_type'] == 'mediaalpha' ) {
                                $page_html .= "<h2>{$selector['popup_exit_header']}</h2>\n";
                                $page_html .= "<script type=\"text/javascript\">\n";
                                $page_html .= "/* " . $selector['popup_exit_media_comment'] . " */\n";
                                $page_html .= "var MediaAlphaExchange = {\n";
                                $page_html .= "\"type\": \"" . $selector['popup_exit_media_type'] . "\",\n";
                                $page_html .= "\"placement_id\": \"" . $selector['popup_exit_media_placeid'] . "\",\n";
                                $page_html .= "\"version\": \"17\",\n";
                                if ( $selector['popup_exit_media_uaclass'] )
                                    $page_html .= "\"ua_class\": \"" . $selector['popup_exit_media_uaclass'] . "\",\n";
                                if ( $selector['popup_exit_media_sub1'] )
                                    $page_html .= "\"sub_1\": \"" . $selector['popup_exit_media_sub1'] . "\",\n";
                                if ( $selector['popup_exit_media_sub2'] )
                                    $page_html .= "\"sub_2\": \"" . $selector['popup_exit_media_sub2'] . "\",\n";
                                if ( $selector['popup_exit_media_sub3'] )
                                    $page_html .= "\"sub_3\": \"" . $selector['popup_exit_media_sub3'] . "\",\n";
                                $page_html .= "\"data\": {\n"; 
                                $page_html .= "\"zip\": 90210,\n";
                                $page_html .= "}\n";
                                $page_html .= "};\n";
                                $page_html .= "</script>\n";
                                $page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                            } else if ( $selector['popup_exit_type'] == 'insuranceclicks' ) {
                                $page_html .= "<h2>{$selector['popup_exit_header']}</h2>\n";
                                $page_html .= "<script>\n";
                                $page_html .= "cInsstate = \"" . $selector['popup_exit_insurance_state'] . "\";\n";
                                $zipcode = "35004";
                                if ( isset($_GET['zipcode']) ) {
                                    if ( $_GET['zipcode'] != '' ) {
                                        $zipcode = $_GET['zipcode'];
                                    }
                                }
                                $page_html .= "cInszip = \"" . $zipcode . "\";\n";
                                $page_html .= "cInsmaxads = \"4\";\n";
                                $page_html .= "cInspublisher_id = \"" . $selector['popup_exit_insurance_userid'] . "\";\n";
                                $page_html .= "cInsinsurancetype = \"" . $selector['popup_exit_insurance_type'] . "\";\n";
                                $page_html .= "!function (e, f, u) {\n";
                                $page_html .= "e.async = 1;\n";
                                $page_html .= "e.src = u;\n";
                                $page_html .= "document.write('<div id=\"iClickContainer\"></div>');\n";
                                $page_html .= "f.parentNode.insertBefore(e, f);\n";
                                $page_html .= "}(document.createElement('script'),\n";
                                $page_html .= "document.getElementsByTagName('script')[0],\n";
                                $page_html .= "'https://insuranceclicks.com/iClickListing/RenderListing.js');\n";
                                $page_html .= "</script>\n";
                            } else {
                                add_action( 'wp_footer', array( $this, 'include_exit_popup_redirect_code' ) );
                            }
                            if ( $selector['popup_exit_type'] != '' ) {
                                if ( $selector['popup_exit_code'] ) {
                                    add_action( 'wp_footer', array( $this, 'include_exit_popup_custom_code' ) );
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if ( isset( $_POST['adintgr-selector'] ) ) {
                if ( isset( $_POST['zipcode'] ) ) {
                    if ( $_POST['adintgr-selector'] != '' ) {
                        $selector = $selectors[$_POST['adintgr-selector']];
                        if ( $selector['selector_type'] == 'popup' ) {
                            if ( $selector['popup_type'] == '' ) {
                                add_action( 'wp_footer', array( $this, 'include_popup_redirect_script' ) );
                            } else {
                                add_action( 'wp_footer', array( $this, 'include_popup_script' ) );
                            }
                        }
                        if ( $selector['leave_type'] == 'mediaalpha' ) {
                            $page_html .= "<h2>{$selector['leave_header']}</h2>\n";
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
                        } else if ( $selector['leave_type'] == 'insuranceclicks' ) {
                            $page_html .= "<h2>{$selector['leave_header']}</h2>\n";
                            $page_html .= "<script>\n";
                            $page_html .= "cInsstate = \"" . $selector['leave_insurance_state'] . "\";\n";
                            $zipcode = "35004";
                            if ( isset($_POST['zipcode']) ) {
                                if ( $_POST['zipcode'] != '' ) {
                                    $zipcode = $_POST['zipcode'];
                                }
                            }
                            $page_html .= "cInszip = \"" . $_POST['zipcode'] . "\";\n";
                            $page_html .= "cInsmaxads = \"4\";\n";
                            $page_html .= "cInspublisher_id = \"" . $selector['leave_insurance_userid'] . "\";\n";
                            $page_html .= "cInsinsurancetype = \"" . $selector['leave_insurance_type'] . "\";\n";
                            $page_html .= "!function (e, f, u) {\n";
                            $page_html .= "e.async = 1;\n";
                            $page_html .= "e.src = u;\n";
                            $page_html .= "document.write('<div id=\"iClickContainer\"></div>');\n";
                            $page_html .= "f.parentNode.insertBefore(e, f);\n";
                            $page_html .= "}(document.createElement('script'),\n";
                            $page_html .= "document.getElementsByTagName('script')[0],\n";
                            $page_html .= "'https://insuranceclicks.com/iClickListing/RenderListing.js');\n";
                            $page_html .= "</script>\n";
                        } else {
                            add_action( 'wp_footer', array( $this, 'include_leave_redirect_code' ) );
                        }
                        if ( $selector['leave_type'] != '' ) {
                            if ( $selector['leave_code'] ) {
                                add_action( 'wp_footer', array( $this, 'include_leave_custom_code' ) );
                            }
                        }
                        if ( $selector['leave_exit_check'] == 'on' ) {
                            add_action( 'wp_footer', array( $this, 'include_leave_exit_code' ) );
                        }
                    }
                }
            }
            
            if ( isset( $_GET['selector'] ) ) {
                if ( isset( $_GET['zipcode'] ) ) {
                    if ( $_GET['selector'] != '' ) {
                        $selector = $selectors[$_GET['selector']];
                        if ( $selector['popup_type'] == 'mediaalpha' ) {
                            $page_html .= "<h2>{$selector['popup_header']}</h2>\n";
                            $page_html .= "<script type=\"text/javascript\">\n";
                            $page_html .= "/* " . $selector['popup_media_comment'] . " */\n";
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
                            $page_html .= "\"zip\": \"" . $_GET['zipcode'] . "\",\n";
                            $page_html .= "}\n";
                            $page_html .= "};\n";
                            $page_html .= "</script>\n";
                            $page_html .= "<script src=\"//insurance.mediaalpha.com/js/serve.js\"></script>\n";
                        } else if ( $selector['popup_type'] == 'insuranceclicks' ) {
                            $page_html .= "<h2>{$selector['popup_header']}</h2>\n";
                            $page_html .= "<script>\n";
                            $page_html .= "cInsstate = \"" . $selector['popup_insurance_state'] . "\";\n";
                            $zipcode = "35004";
                            if ( isset($_GET['zipcode']) ) {
                                if ( $_GET['zipcode'] != '' ) {
                                    $zipcode = $_GET['zipcode'];
                                }
                            }
                            $page_html .= "cInszip = \"" . $zipcode . "\";\n";
                            $page_html .= "cInsmaxads = \"4\";\n";
                            $page_html .= "cInspublisher_id = \"" . $selector['popup_insurance_userid'] . "\";\n";
                            $page_html .= "cInsinsurancetype = \"" . $selector['popup_insurance_type'] . "\";\n";
                            $page_html .= "!function (e, f, u) {\n";
                            $page_html .= "e.async = 1;\n";
                            $page_html .= "e.src = u;\n";
                            $page_html .= "document.write('<div id=\"iClickContainer\"></div>');\n";
                            $page_html .= "f.parentNode.insertBefore(e, f);\n";
                            $page_html .= "}(document.createElement('script'),\n";
                            $page_html .= "document.getElementsByTagName('script')[0],\n";
                            $page_html .= "'https://insuranceclicks.com/iClickListing/RenderListing.js');\n";
                        } else {
                            add_action( 'wp_footer', array( $this, 'include_popup_redirect_code' ) );
                        }
                        if ( $selector['popup_type'] != '' ) {                            
                            if ( $selector['popup_code'] ) {
                                add_action( 'wp_footer', array( $this, 'include_popup_custom_code' ) );
                            }
                        }
                        if ( $selector['popup_exit_check'] == 'on' ) {
                            add_action( 'wp_footer', array( $this, 'include_popup_exit_code' ) );
                        }
                    }
                }
            }
        }
        return $page_html;
    }

    public function include_exit_custom_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
            echo $selector['main_exit_code'];
        }
    }

    public function include_exit_redirect_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
            ?>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script type="text/javascript">
            jQuery(function($) {
                window.location.replace('<?php echo $selector['main_exit_url'];?>');
            });
            </script>
        <?php
        }
    }

    public function include_popup_script() {
        $selectors = $this->form->prop( 'selectors' );
        $selector = $selectors[$_POST['adintgr-selector']];
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery(function($) {
            window.open('<?php echo get_permalink($this->pages_id[$_POST['adintgr-selector']]);?>?zipcode=<?php echo $_POST['zipcode'];?>&selector=<?php echo $_POST['adintgr-selector'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
        });
        </script>
        <?php
    }

    public function include_popup_redirect_script() {
        if ( isset($_POST['adintgr-selector']) ) {
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
    }

    public function include_leave_custom_code() {
        if ( isset($_POST['adintgr-selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_POST['adintgr-selector']];
            echo $selector['leave_code'];
        }
    }

    public function include_leave_redirect_code() {
        if ( isset($_POST['adintgr-selector']) ) {
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
    }

    public function include_leave_exit_code() {
        if ( isset($_POST['adintgr-selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_POST['adintgr-selector']];
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery( function( $ ) {
            document.body.addEventListener('mouseleave', function(e) {
            if (e.pageY - document.body.scrollTop < 0) {
				if ( typeof localStorage.getItem("leave_exitpopup_time") != 'undefined' ) {
					var nowDate = new Date();
                    var nowTime = nowDate.getTime();
        <?php
                    $exit_period = 1000 * 60;
					if ( $selector['leave_exit_period'] == 'minute' ) {
						$exit_period = $exit_period;
					} else if ( $selector['leave_exit_period'] == 'minute10' ) {
						$exit_period = $exit_period * 10;
					} else if ( $selector['leave_exit_period'] == 'minute30' ) {
						$exit_period = $exit_period * 30;
					} else if ( $selector['leave_exit_period'] == 'hour' ) {
						$exit_period = $exit_period * 60;
					} else if ( $selector['leave_exit_period'] == 'day' ) {
						$exit_period = $exit_period * 60 * 24;
					} else if ( $selector['leave_exit_period'] == 'week' ) {
						$exit_period = $exit_period * 60 * 24 * 7;
					} else {
						$exit_period = $exit_period * 60 * 24;
                    }
		?>
					if ( nowTime - localStorage.getItem("leave_exitpopup_time") < <?php echo $exit_period;?> ) {
						return;
					} else {
						localStorage.setItem("leave_exitpopup_time" + check_index, nowTime);
					}
				} else {
					localStorage.setItem("leave_exitpopup_time" + check_index, nowTime);
				}
		<?php
				if ( $selector['leave_exit_type'] == '' ) {
		?>
					window.open('<?php echo $selector['leave_exit_url'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
		<?php
				} else if ($selector['leave_exit_type'] == 'mediaalpha') {
		?>
					window.open('<?php echo get_permalink($this->pages_id[$_POST['adintgr-selector']]);?>?action=leave&selector=<?php echo $_POST['adintgr-selector'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
		<?php
                }
		?>  }
		});
		});
		</script>
        <?php
        }
    }

    public function include_popup_custom_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
            echo $selector['popup_code'];
        }
    }
    
    public function include_popup_exit_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        jQuery( function( $ ) {
            document.body.addEventListener('mouseleave', function(e) {
            if (e.pageY - document.body.scrollTop < 0) {
				if ( typeof localStorage.getItem("popup_exitpopup_time") != 'undefined' ) {
					var nowDate = new Date();
					var nowTime = nowDate.getTime();
        <?php
                    $exit_period = 1000 * 60;
					if ( $selector['popup_exit_period'] == 'minute' ) {
						$exit_period = $exit_period;
					} else if ( $selector['popup_exit_period'] == 'minute10' ) {
						$exit_period = $exit_period * 10;
					} else if ( $selector['popup_exit_period'] == 'minute30' ) {
						$exit_period = $exit_period * 30;
					} else if ( $selector['popup_exit_period'] == 'hour' ) {
						$exit_period = $exit_period * 60;
					} else if ( $selector['popup_exit_period'] == 'day' ) {
						$exit_period = $exit_period * 60 * 24;
					} else if ( $selector['popup_exit_period'] == 'week' ) {
						$exit_period = $exit_period * 60 * 24 * 7;
					} else {
						$exit_period = $exit_period * 60 * 24;
                    }
		?>
					if ( nowTime - localStorage.getItem("popup_exitpopup_time") < <?php echo $exit_period;?> ) {
						return;
					} else {
						localStorage.setItem("popup_exitpopup_time" + check_index, nowTime);
					}
				} else {
					localStorage.setItem("leave_exitpopup_time" + check_index, nowTime);
				}
		<?php
				if ( $selector['popup_exit_type'] == '' ) {
		?>
					window.open('<?php echo $selector['popup_exit_url'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
		<?php
				} else if ($selector['popup_exit_type'] == 'mediaalpha') {
		?>
					window.open('<?php echo get_permalink($this->pages_id[$_GET['selector']]);?>?action=popup&selector=<?php echo $_GET['selector'];?>', '_blank', 'width=' + screen.width + ',height=' + screen.height + ',');
		<?php
                }
		?>  }
		});
		});
		</script>
        <?php
        }
    }

    public function include_exit_leave_custom_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
            echo $selector['leave_exit_code'];
        }
    }

    public function include_exit_leave_redirect_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
            ?>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script type="text/javascript">
            jQuery(function($) {
                window.location.replace('<?php echo $selector['leave_exit_url'];?>');
            });
            </script>
        <?php
        }
    }

    public function include_exit_popup_custom_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
            echo $selector['popup_exit_code'];
        }
    }

    public function include_exit_popup_redirect_code() {
        if ( isset($_GET['selector']) ) {
            $selectors = $this->form->prop( 'selectors' );
            $selector = $selectors[$_GET['selector']];
            ?>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script type="text/javascript">
            jQuery(function($) {
                window.location.replace('<?php echo $selector['popup_exit_url'];?>');
            });
            </script>
        <?php
        }
    }
}