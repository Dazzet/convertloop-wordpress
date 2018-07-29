<?php namespace WpConvertloop\Woocommerce;

class Checkout
{

    public static function instance()
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
        add_action('woocommerce_new_order', array($this, 'action_woocommerce_neworder'));
    }

    public function converloopCheckbox($checkout)
    {
        woocommerce_form_field('checkbox_subscribe_convertloop', array(
            'type' => 'checkbox',
            'class' => array(
                'checkbox-convertloop',
            ),
            'label' => __('Subscribe to our newsletter', 'wp-convertloop'),
            'default' => true,
        ), $checkout->get_value('checkbox_subscribe_convertloop'));
    }

    public function action_woocommerce_neworder($order_id)
    {
        if ($_POST['checkbox_subscribe_convertloop'] == true) {
            $order = wc_get_order($order_id);

            $convertloop = \WpConvertloop\Convertloop\Convertloop::instance();

            $person = array(
                "email" => $order->get_billing_email(),
                "first_name" => $order->get_billing_first_name(),
                "last_name" => $order->get_billing_last_name(),
            );
            $convertloop->people()->createOrUpdate($person);
        }
    }

}
