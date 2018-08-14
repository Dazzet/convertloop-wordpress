<?php namespace WpConvertloop\Woocommerce;
/**
 * TODO: rastrear carritos abandonado y enviar metadata a convertloop
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
        
    }

    


}
