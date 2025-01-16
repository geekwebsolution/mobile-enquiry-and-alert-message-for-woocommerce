<?php
/**
 * Block Name: WhatsApp block 
 */

function whatsapp_inquiry_dynamic_render_callback( $block_attributes, $content ) {

    $general_settings_options = get_option('mmwea_general_settings_options');
    $whatsapp_number = isset($general_settings_options['whatsapp_number']) ? $general_settings_options['whatsapp_number'] : '';
    $message_body= "";
    $button_text= "Inquiry via WhatsApp";
    if(is_cart()){
        $cart_page_options = get_option('mmwea_product_cart_page_options');

        $button_text = isset($cart_page_options['enquiry_btn_text']) ? $cart_page_options['enquiry_btn_text'] : 'Inquiry via WhatsApp';
        $message_body = isset($cart_page_options['body_header']) ? $cart_page_options['body_header'] : 'Hello there, I visited your store. I like some products and want to buy.';    
    }
    if(is_checkout()){
        $checkout_page_options = get_option('mmwea_product_checkout_page_options');

        $button_text = isset($checkout_page_options['enquiry_btn_text']) ? $checkout_page_options['enquiry_btn_text'] : 'Inquiry via WhatsApp';
        $message_body = isset($checkout_page_options['body_header']) ? $checkout_page_options['body_header'] : 'Hello there, I place this order but before buying need some information.';    
    }

    $button_url = "https://wa.me/{$whatsapp_number}?text=" . urlencode($message_body);
    $wh_image_url    = MMWEA_PLUGIN_URL . '/assets/image/whatsapp_phone_icon.svg';
    return sprintf(
        '<div class="whatsapp-inquiry-button-wrapper mmwea-button-box">
            <a href="%s" class="whatsapp-inquiry-button mmwea-wa-button button btn" target="_blank" rel="noopener noreferrer">
                <img src="%s" width="50" height="50" >
                <span>%s</span>
            </a>
        </div>',
        esc_url( $button_url ),
        $wh_image_url,
        $button_text
    );
}

function whatsapp_inquiry_register_block() {

    register_block_type( __DIR__ , array(
        'render_callback' => 'whatsapp_inquiry_dynamic_render_callback'
    ) );

}
add_action( 'init', 'whatsapp_inquiry_register_block' );


function get_cart_data_ajax() {
    if ( WC()->cart ) {
        $cart_data = array();
        $cart_items = WC()->cart->get_cart();

        foreach ( $cart_items as $item_key => $item ) {
            $product = $item['data'];
            $product_data = array(
                'title' => $product->get_title(),
                'url'   => get_permalink( $product->get_id() ),
            );

            // Apply a filter to allow customization of product data
            $cart_data[] = apply_filters( 'mmwea_customize_cart_item_data', $product_data, $item);
        }

        wp_send_json_success( array( 'cartItems' => $cart_data ) );
    } else {
        wp_send_json_error( array( 'message' => 'Cart is empty.' ) );
    }

    wp_die();
}
add_action( 'wp_ajax_get_cart_data', 'get_cart_data_ajax' );
add_action( 'wp_ajax_nopriv_get_cart_data', 'get_cart_data_ajax' );
