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

$convertloop = new \ConvertLoop\ConvertLoop(
    get_option('convertloop_app_id'),
    get_option('convertloop_api_key'),
    get_option('convertloop_api_version')
);

Woocommerce\Checkout::instance($convertloop)->start();
ContactForm\Form::instance()->start();
Wordpress\Settings::instance()->start();
