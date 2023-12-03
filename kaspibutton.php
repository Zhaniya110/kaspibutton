<?php 
/*
 * Plugin Name: Kaspi Button for WooCommerce
 * Author: Zhaniya
 * Author URI: zhan883.kz
 * Description: The Kaspi Button for WooCommerce plugin enhances your online store by seamlessly integrating with Kaspi.kz, providing a streamlined experience for your customers. Easily display essential information under the "Add to Cart" button on both single product pages and product archive (loop) pages.
 * Text Domain: kaspibutton
 */


 // Add custom fields to WooCommerce product
function add_custom_fields() {
    woocommerce_wp_text_input(
        array(
            'id'          => 'product_sku',
            'label'       => __('Product SKU', 'woocommerce'),
            'placeholder' => __('Enter SKU', 'woocommerce'),
            'desc_tip'    => true,
        )
    );
}
add_action('woocommerce_product_options_general_product_data', 'add_custom_fields');

// Save custom fields
function save_custom_fields($post_id) {
    $product_sku = isset($_POST['product_sku']) ? sanitize_text_field($_POST['product_sku']) : '';
    update_post_meta($post_id, 'product_sku', $product_sku);
}
add_action('woocommerce_process_product_meta', 'save_custom_fields');


function add_custom_settings() {
    add_settings_section('custom_section', 'Custom Settings', 'custom_section_callback', 'general');
    add_settings_field('merchant_code', 'Data Merchant Code', 'merchant_code_callback', 'general', 'custom_section');
    add_settings_field('data_city', 'Data City', 'data_city_callback', 'general', 'custom_section');

    register_setting('general', 'merchant_code', 'sanitize_text_field');
    register_setting('general', 'data_city', 'sanitize_text_field');
}

function custom_section_callback() {
    echo '<p>Additional product data settings:</p>';
}

function merchant_code_callback() {
    $merchant_code = get_option('merchant_code');
    echo "<input type='text' name='merchant_code' value='$merchant_code' />";
}

function data_city_callback() {
    $data_city = get_option('data_city');
    echo "<input type='text' name='data_city' value='$data_city' />";
}

add_action('admin_init', 'add_custom_settings');


function display_custom_link() {
    global $product;

    $product_sku = get_post_meta($product->get_id(), 'product_sku', true);
    $merchant_code = get_option('merchant_code');
    $data_city = get_option('data_city');

    // Output the custom link
    echo "<div id='dynamic'></div>";

    // Output the script with dynamic values
    echo "<script>
        (function (d, s, id) {
            var js, kjs;
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://kaspi.kz/kaspibutton/widget/ks-wi_ext.js';
            kjs = document.getElementsByTagName(s)[0]
            kjs.parentNode.insertBefore(js, kjs);
        }(document, 'script', 'KS-Widget'));
        
        setTimeout(function () {
            document.getElementById('dynamic').innerHTML = '<div class=\"ks-widget\" data-template=\"button\" data-merchant-sku=\"$product_sku\" data-merchant-code=\"$merchant_code\" data-city=\"$data_city\"></div>';
            ksWidgetInitializer.reinit();
        }, 1000)</script>";
}

add_action('woocommerce_single_product_summary', 'display_custom_link');


function enqueue_custom_scripts() {
    // Enqueue the external script
    wp_enqueue_script('ks-wi-ext-script', 'https://kaspi.kz/kaspibutton/widget/ks-wi_ext.js', array(), '1.0', true);
}

// Hook the function to the appropriate action
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


function display_custom_data_under_add_to_cart_loop() {
    global $product;

    $product_sku = get_post_meta($product->get_id(), 'product_sku', true);
    $merchant_code = get_option('merchant_code');
    $data_city = get_option('data_city');

    // Output the custom link
    echo "<div id='dynamic'></div>";

    // Output the script with dynamic values
    echo "<script>
        (function (d, s, id) {
            var js, kjs;
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://kaspi.kz/kaspibutton/widget/ks-wi_ext.js';
            kjs = document.getElementsByTagName(s)[0]
            kjs.parentNode.insertBefore(js, kjs);
        }(document, 'script', 'KS-Widget'));
        
        setTimeout(function () {
            document.getElementById('dynamic').innerHTML = '<div class=\"ks-widget\" data-template=\"button\" data-merchant-sku=\"$product_sku\" data-merchant-code=\"$merchant_code\" data-city=\"$data_city\"></div>';
            ksWidgetInitializer.reinit();
        }, 1000)</script>";
}

// Hook the function to display the custom data under the Add to Cart button on the archive loop
add_action('woocommerce_after_shop_loop_item', 'display_custom_data_under_add_to_cart_loop', 15); // Adjust the priority to control the position