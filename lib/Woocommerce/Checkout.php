<?php namespace WpConvertloop\Woocommerce;

class Checkout
{

    private $convertloop;

    public static function instance($convertloop)
    {
        static $obj;

        if (!isset($obj)) {
            $obj = new self($convertloop);
        }

        return $obj;
    }

    private function __construct($convertloop)
    {
        $this->convertloop = $convertloop;
    }

    public function start()
    {
        add_action('woocommerce_after_order_notes', array($this, 'converloopCheckbox'));
        add_action('woocommerce_new_order', array($this, 'newOrder'));
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

    public function newOrder($order_id)
    {
        if ($_POST['checkbox_subscribe_convertloop'] == true) {
            $order = wc_get_order($order_id);
            $person = array(
                "email"      => $order->get_billing_email(),
                "first_name" => $order->get_billing_first_name(),
                "last_name"  => $order->get_billing_last_name(),
            );
            $this->convertloop->people()->createOrUpdate($person);
        }
    }

}
