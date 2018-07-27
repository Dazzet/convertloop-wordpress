<?php namespace WpConvertloop\Woocommerce;

class Checkout{


    static function instance() 
    {
        static $obj;

        if (!isset($obj)) {
            $obj = new self;
        }

        return $obj;
    }

    private function __construct() 
    {

    }

    public function start()
    {
        add_action('woocommerce_after_order_notes', array($this, 'converloopCheckbox'));
    }

    public function converloopCheckbox($checkout) 
    {
        woocommerce_form_field('customised_fields_name', array(
            'type' => 'checkbox',
            'class' => array(
                'checkbox-convertloop'
            ),
            'label' => __('Subscribe to our newsletter', 'wp-convertloop'),
            'default' => true,
        ), $checkout->get_value('customised_fields_name'));
    }

}