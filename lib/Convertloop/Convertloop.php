<?php namespace WpConvertloop\Convertloop;

class Convertloop{


    static function instance() 
    {

        static $convertloop;

        if (!isset($convertloop)) {
            $convertloop = new \ConvertLoop\ConvertLoop("931b8ad2", "8562jCFAtkHVD77B14iJMX3K", "v1");
        }

        return $convertloop;
    }

    private function __construct() 
    {

    }

    public function start()
    {
        
    }

}

