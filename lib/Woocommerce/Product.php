<?php

namespace WpConvertloop\Woocommerce;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Product
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
        add_action( 'woocommerce_before_single_product', array($this, 'wcTrackProductView'), 10 );
    }

    /**
     * Rastrear la vista de un producto con convertloop y
     * enviar el producto visto y los productos relacionados
     */
    public function wcTrackProductView()
    {
        $pid = $_COOKIE['dp_pid'];

        $contact = array('pid' => $pid);
        
        global $product;
        $id = $product->get_id();

        $relatedProducts = wc_get_related_products($id, 3);
        
        $metadata = array( 
                'url_last_seen_product'=>get_permalink( $id ),
                'url_last_seen_product_thumb'=>$this->getThumb( $id ),
                'url_last_seen_related_product1'=> isset($relatedProducts[0]) ? get_permalink( $relatedProducts[0] ) : '',
                'url_last_seen_related_product2'=>isset($relatedProducts[1]) ? get_permalink( $relatedProducts[0] ) : '',
                'url_last_seen_related_product3'=>isset($relatedProducts[2]) ? get_permalink( $relatedProducts[0] ) : '',
                'url_last_seen_related_product_thumb1'=>isset($relatedProducts[0]) ? $this->getThumb( $relatedProducts[0] ) : '',
                'url_last_seen_related_product_thumb2'=>isset($relatedProducts[1]) ? $this->getThumb( $relatedProducts[1] ) : '',
                'url_last_seen_related_product_thumb3'=>isset($relatedProducts[2]) ? $this->getThumb( $relatedProducts[2]) : '',
        );

        $this->convertloop->eventLogs()->send(array('name' => __('Product viewed', 'wp-convertloop'), 'person' => $contact, 'metadata'=>$metadata));
    }

    private function getThumb($id)
    {
        $thumbsArray = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
        return $thumbsArray ? $thumbsArray[0] : '';
    }


}
