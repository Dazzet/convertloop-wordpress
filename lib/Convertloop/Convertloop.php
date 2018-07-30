<?php namespace WpConvertloop\Convertloop;

class Convertloop{


    static function instance()
    {

        static $convertloop;

        if (!isset($convertloop)) {
            $id = get_option('convertloop_app_id');
            $key = get_option('convertloop_api_key');
            $ver = get_option('convertloop_api_version', 'v1');
            $convertloop = new \ConvertLoop\ConvertLoop($id, $key, $ver);
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

