<?php namespace WpConvertloop;

/*
Plugin Name: Wordpress Convertloop
Plugin URI:  https://dazzet.co/
Description: Convertloop for wordpress
Author:      Mario Yepez, Sebastian Usuga, Eduardo Diaz
Author URI:  https://dazzet.co
Version:     0.0.1
License:     MIT
License URI: license.txt
Text Domain: wp-convertloop
Domain Path: /languages
 */

require_once __DIR__ . '/vendor/autoload.php';

// La idea es crear aqui los objetos y pasarlos como parámetros
$convertloop = new \ConvertLoop\ConvertLoop(
    get_option('convertloop_app_id'),
    get_option('convertloop_api_key'),
    get_option('convertloop_api_version')
);

ContactForm\Form::instance($convertloop)->start();
ContactForm\Dashboard::instance()->start();
Wordpress\Settings::instance()->start();
Woocommerce\Checkout::instance($convertloop)->start();
