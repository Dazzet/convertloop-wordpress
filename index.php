<?php

namespace WpConvertloop;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
Plugin Name: Convertloop Wordpress
Plugin URI:  https://dazzet.co/
Description: Convertloop for Wordpress
Author:      Mario Yepes, Sebastian Usuga, Eduardo Diaz
Author URI:  https://dazzet.co
Version:     0.0.1
License:     MIT
License URI: license.txt
Text Domain: wp-convertloop
Domain Path: /languages
 */


require_once __DIR__ . '/vendor/autoload.php';

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), function($links) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=wp-convertloop') ) .'">'.__('Settings').'</a>';
   return $links;
});

load_plugin_textdomain('wp-convertloop', false, basename( dirname( __FILE__ ) ) . '/languages' );

// La idea es crear aqui los objetos y pasarlos como parámetros
$convertloop = new \ConvertLoop\ConvertLoop(
    get_option('convertloop_app_id'),
    get_option('convertloop_api_key'),
    get_option('convertloop_api_version')
);

ContactForm7\Form::instance($convertloop)->start();
ContactForm7\Dashboard::instance()->start();
Wordpress\Settings::instance()->start();

// Agregar checkbox en el checkout the WooCommerce
if (get_option('convertloop_add_woo_checkout', true)) {
    Woocommerce\Checkout::instance($convertloop)->start();
}

// Insertar código de tracking si el usuario lo quiere
if (get_option('convertloop_add_snippet', false)) {
    Wordpress\TrackingCode::instance( get_option('convertloop_app_id') )->start();
}

Woocommerce\Product::instance($convertloop)->start();
Woocommerce\Cart::instance($convertloop)->start();

