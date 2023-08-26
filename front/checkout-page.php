<?php 
add_action('wp','mmwea_checkout_page_front_setting',10);
function mmwea_checkout_page_front_setting(){
    if(is_checkout()){
        $general_settings_options   = get_option('mmwea_general_settings_options');
        $checkout_page_options        = get_option('mmwea_product_checkout_page_options');

        $whatsapp_number = $hide_btn_des = $new_tab = $display_btn = $msg_body = $body_header = $body_footer = $enable_user_role = $user_role_option = $login_user_role = $button_text = $btn_position_hook = "";

        $user_role_wise = 1;
        
        if(isset($general_settings_options) && !empty($general_settings_options)){
    
            if (isset($general_settings_options['whatsapp_number']))		$whatsapp_number    = $general_settings_options['whatsapp_number'];
            if (isset($general_settings_options['hide_wa_btn_desktop']))    $hide_btn_des    	= $general_settings_options['hide_wa_btn_desktop'];
            if (isset($general_settings_options['open_link_new_tab']))		$new_tab    	    = $general_settings_options['open_link_new_tab'];
            if (isset($general_settings_options['enable_user_role']))		$enable_user_role   = $general_settings_options['enable_user_role'];
            if (isset($general_settings_options['user_role_option']))		$user_role_option   = $general_settings_options['user_role_option'];
            if (isset($general_settings_options['login_user_role']))		$login_user_role    = $general_settings_options['login_user_role'];
        }

        if(isset($checkout_page_options) && !empty($checkout_page_options)){
            if (isset($checkout_page_options['display_checkout_page']))		$display_btn    	    = $checkout_page_options['display_checkout_page'];
            if (isset($checkout_page_options['enquiry_btn_text']))		    $button_text    	    = $checkout_page_options['enquiry_btn_text'];
            if (isset($checkout_page_options['btn_position_hook']))		    $btn_position_hook    	= $checkout_page_options['btn_position_hook'];
            if (isset($checkout_page_options['body_header']))		        $body_header    	    = $checkout_page_options['body_header'];
            if (isset($checkout_page_options['message_body']))		        $message_body    	    = $checkout_page_options['message_body'];
            if (isset($checkout_page_options['body_footer']))		        $body_footer    	    = $checkout_page_options['body_footer'];
        }

        if($enable_user_role == "on"){
            if($user_role_option == "logged-in" && is_user_logged_in()){               
                if(isset($login_user_role) && !empty($login_user_role)){
                    $allow_user_role = explode(",",$login_user_role);
                    $user_id = get_current_user_id();
                    $user_meta = get_userdata($user_id);
                    $user_roles = $user_meta->roles; 
    
                    if(!empty($user_roles) && isset($user_roles)){
                        foreach ($user_roles as $key => $role) {                 
                            if(in_array($role, $allow_user_role)){
                                $user_role_wise = 0;
                            }
                        }
                    }
                }else{
                    $user_role_wise = 0;
                }
            }elseif($user_role_option == "non-logged" && !is_user_logged_in()){
                $user_role_wise = 0;
            }
        }else{
            $user_role_wise = 0;
        }
    
        if($display_btn == "on" && $user_role_wise == 0){

            $btn_class  = $hide_btn_des == 'on' ? 'mmwea-for-mob' : '';
            $btn_target = $new_tab == 'on' ? 'target="_blank"' : '';

            $msg_data = array();

            global $woocommerce;
            $items = $woocommerce->cart->get_cart();

            foreach ($items as $item => $values) {
                $_product =  wc_get_product($values['data']->get_id());

                $product_name = $_product->get_title();
                $product_price = get_post_meta($values['product_id'], '_price', true);
                $product_quantity   =  $values['quantity'];
                $product_url = get_the_post_thumbnail_url($values['product_id']);

                $old_val =  array("{{product_name}}", "{{product_price}}", "{{product_quantity}}", "{{product_url}}");
                $new_val =  array($product_name, $product_price, $product_quantity, $product_url);
                $updated_val = str_replace($old_val, $new_val, $message_body);

                $msg_data[] = urlencode($updated_val);
            }

            $msg_data = implode("<br />", $msg_data);

            $body_header = str_replace('<br />', '%0D%0A', nl2br($body_header));
            $body_footer = str_replace('<br />', '%0D%0A', nl2br($body_footer));            
            $msg_body = str_replace('<br />', '%0D%0A--------------%0D%0A', nl2br($msg_data));

            $msg_body = $body_header.'%0D%0A%0D%0A'.$msg_body.'%0D%0A%0D%0A'.$body_footer;

			$button_url = "https://wa.me/".$whatsapp_number."/?text=".$msg_body;

            $button_html = '<div class="mmwea-buuton-box"><a href="{{btn_url}}" id="mmwea-checkout-btn" class="mmwea-wa-button  button btn {{btn_class}}" {{btn_target}} ><img src="'.MMWEA_PLUGIN_URL.'/assets/image/whatsapp_phone_icon.svg" width="50" height="50" ><span>{{btn_text}}</span></a></div>';

            $old_value = array('{{btn_url}}','{{btn_class}}','{{btn_target}}','{{btn_text}}');
            $new_value = array($button_url,$btn_class,$btn_target,$button_text);
            $wa_btn_html = str_replace($old_value,$new_value,$button_html);

            add_action($btn_position_hook, function() use ($wa_btn_html) {
                _e($wa_btn_html,'mobile-enquiry-and-alert-message-for-woocommerce');                
            });
        }
    }
}