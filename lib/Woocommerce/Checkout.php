<?php

namespace WpConvertloop\Woocommerce;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Inscribir a ConvertLoop en el checkout de WooCommerce
 */
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
        add_action('woocommerce_new_order', array($this, 'newOrder'), 11, 1);
        add_action('woocommerce_thankyou', array($this, 'thankYou'), 11, 1);
    }

    /**
     * Crea el checkbox en el formulario de compra de ConvertLoop
     */
    public function converloopCheckbox($checkout)
    {
        woocommerce_form_field('checkbox_subscribe_convertloop', array(
            'type' => 'checkbox',
            'class' => array(
                'checkbox-convertloop',
            ),
            'label' => __('I want to receive information about deals and discounts', 'wp-convertloop'),
            'default' => true,
        ), $checkout->get_value('checkbox_subscribe_convertloop'));
    }

    /**
     * Procesa los datos de checkout para enviarlos a ConvertLoop
     */
    public function newOrder($order_id)
    {
        if ($_POST['checkbox_subscribe_convertloop'] == true) {
            $pid = $_COOKIE['dp_pid'];
            $order = wc_get_order($order_id);
            $person = array(
                'email'      => $order->get_billing_email(),
                'first_name' => $order->get_billing_first_name(),
                'last_name'  => $order->get_billing_last_name(),
                "pid"        => $pid
            );
            $event = array(
                'name' => __('Start Checkout', 'wp-convertloop'),
                'person' => $person,
                'ocurred_at' => time(),
                'metadata' => array(
                    'order_total' => $order->get_total(),
                    'order_shipping' => $order->calculate_shipping(),
                    'order_tax' => $order->get_cart_tax()
                )
            );
            $this->convertloop->people()->createOrUpdate($person);
            $this->convertloop->eventLogs()->send($event);
        }
    }

    public function thankYou($order_id)
    {
        $order = wc_get_order($order_id);
        $person = array(
            'email'      => $order->get_billing_email()
        );
        $products = array();
        foreach ($order->get_items() as $id => $item) {
            $products[$id] = array(
                'name' => $item['name']
            );
        }
        $event = array(
            'name' => __('End Checkout', 'wp-convertloop'),
            'person' => $person,
            'ocurred_at' => time(),
            'metadata' => array(
                'order_total' => $order->get_total(),
                'order_shipping' => $order->calculate_shipping(),
                'order_tax' => $order->get_cart_tax(),
                'order_transaction_id' => $order->transaction_id,
                'order_products' => $products
            )
        );
        $this->convertloop->eventLogs()->send($event);

    }

}
