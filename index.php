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

require_once(__DIR__.'/vendor/autoload.php');

Woocommerce\Checkout::instance()->start();
ContactForm\Form::instance()->start();

