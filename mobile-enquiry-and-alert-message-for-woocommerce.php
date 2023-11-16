<?php
/*
Plugin Name: Mobile Enquiry and Alert Message for Woocommerce
Description: Mobile Enquiry and Alert Message for Woocommerce is used to get a enquriy from user directly to your whatsapp for product, cart and order detail etc!
Author: Geek Code Lab
Version: 1.5
WC tested up to: 8.2.2
Author URI: https://geekcodelab.com/
Text Domain : mobile-enquiry-and-alert-message-for-woocommerce
*/
if (!defined('ABSPATH')) exit;

if (!defined("MMWEA_PLUGIN_DIR_PATH"))

	define("MMWEA_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("MMWEA_PLUGIN_URL"))
    
    define("MMWEA_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));
    
define("mmwea_version", '1.5');

register_activation_hook( __FILE__, 'mmwea_plugin_active_woocommerce_shop_page_customizer' );
function mmwea_plugin_active_woocommerce_shop_page_customizer(){
	$error	=	'required <b>woocommerce</b> plugin.';	
	if ( !class_exists( 'WooCommerce' ) ) {
	   die('Plugin NOT activated: ' . $error);
	}
}

require_once( MMWEA_PLUGIN_DIR_PATH .'admin/options.php');

require_once( MMWEA_PLUGIN_DIR_PATH .'front/product-single-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/cart-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/checkout-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/order-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/account-page.php');

require_once( MMWEA_PLUGIN_DIR_PATH .'/customizer/customizer-library/customizer-library.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'/customizer/styles.php');

function mmwea_plugin_add_settings_link($links){

	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __('Support') . '</a>';
	array_unshift($links, $support_link);

	$settings_link = '<a href="admin.php?page=mmwea-option-page">' . __('Settings') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'mmwea_plugin_add_settings_link');

add_action('admin_print_styles', 'mmwea_admin_style');
function mmwea_admin_style(){
	if (is_admin()) {
		wp_enqueue_style('mmwea-admin-style', MMWEA_PLUGIN_URL . '/assets/css/admin-style.css' , '',mmwea_version);
		wp_enqueue_style('mmwea-select2-style', MMWEA_PLUGIN_URL . '/assets/css/select2.min.css' , '',mmwea_version);
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('mmwea-admin-select2-js',MMWEA_PLUGIN_URL.'/assets/js/select2.min.js' ,array('jquery'),mmwea_version);
		wp_enqueue_script('mmwea-admin-js',MMWEA_PLUGIN_URL.'/assets/js/admin-script.js' ,array('jquery'),mmwea_version);
	}
}

add_action('wp_enqueue_scripts', 'mmwea_include_front_script');
function mmwea_include_front_script(){
    wp_enqueue_style("mmwea_front_style", MMWEA_PLUGIN_URL . "/assets/css/front-style.css", '',mmwea_version);
    wp_enqueue_script('mmwea_donation_script', MMWEA_PLUGIN_URL.'/assets/js/front-script.js', array('jquery'), mmwea_version);
}

function mmwea_get_product_category($term_id,$select_category_id){
	$select_category_id = $select_category_id;
	$args = array(
		'parent'         => $term_id,
		'hide_empty' => false,
	); 
	
	$sub_terms = get_terms('product_cat', $args);
	if (isset($sub_terms) && !empty($sub_terms) ) {
		foreach ( $sub_terms as $sub_taxonomy ) {
			if (in_array($sub_taxonomy->term_id, $select_category_id)){	?>
                    <option value="<?php echo esc_attr($sub_taxonomy->term_id); ?>" selected="selected" ><?php esc_html_e($sub_taxonomy->name) ?></option>
                <?php
			}else{ ?>
                    <option value="<?php echo esc_attr($sub_taxonomy->term_id); ?>" ><?php esc_html_e($sub_taxonomy->name) ?></option>
                <?php
			}
			mmwea_get_product_category($sub_taxonomy->term_id,$select_category_id);
		}
	}
}

/**
 * Added HPOS support for woocommerce
 */
add_action( 'before_woocommerce_init', 'mmwea_before_woocommerce_init' );
function mmwea_before_woocommerce_init() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

// add_filter('woocommerce_get_availability', 'availability_filter_func');
// function availability_filter_func($availability){

// 	$availability['availability'] = str_ireplace('Out of stock', 'Coming Soon!', $availability['availability']);
// 	return $availability;

// }