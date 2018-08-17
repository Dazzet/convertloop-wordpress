<?php

namespace WpConvertloop\Woocommerce;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * TODO: rastrear carritos abandonado y enviar metadata a convertloop
 * 
 * Agregar a la persona al a un segmento de agrego al carrito y sacarlo cada vez que una orden sea completada
 * 
 * Enviar orreo automatico pasadas 3 horas de agregado al segmento
 * 
 * 
 * No funciono el enfoque de sacar a la persona del segmento de carrito abandonado porque igual le envia el correo
 * 
 * Hay que buscar una forma de que se detecte el carrito abandonado desde woocommerce y es en ese momento que
 * se incluye al cliente en un segmento de carrito abandonado.
 * 
 */
class Cart
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
        add_action( 'woocommerce_add_to_cart', array($this, 'wcTrackAddToCart'), 10 );
        add_action( 'woocommerce_new_order', array($this, 'wcNewOrderAndRemoveAddToCart'), 10, 1);
    }

    public function wcTrackAddToCart()
    {
        $pid = $_COOKIE['dp_pid'];

        $contact = array('pid' => $pid,'add_to_segments' => array(__('addToCart', 'wp-convertloop')));
        

        foreach( WC()->cart->cart_contents as $prod_in_cart ) {
            // Get the Variation or Product ID and leave the last product id added to cart
            $prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? 
                        $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];
        }

        $metadata = array( 
            'url_last_product_added_to_cart'=>get_permalink( $prod_id ),
            'url_last_product_added_to_cart_thumb'=>$this->getThumb( $prod_id ),
            'test_html'=>'<h1>hola mundo</h1><p>aqui viene un link <a href="https://google.com/">Sigueme</a></p>'
    );

        $this->convertloop->eventLogs()->send(
            array(
                'name' => __('Product added to cart', 'wp-convertloop'),
                'person' => $contact, 
                'metadata'=>$metadata
            )
        );
    }

    public function wcNewOrderAndRemoveAddToCart($order_id)
    {
        $pid = $_COOKIE['dp_pid'];

        $contact = array('pid' => $pid,'remove_from_segments' => array(__('addToCart', 'wp-convertloop')));

        $this->convertloop->people()->createOrUpdate($contact);
    }

    private function getThumb($id)
    {
        $thumbsArray = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
        return $thumbsArray ? $thumbsArray[0] : '';
    }


}
