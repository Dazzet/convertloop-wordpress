<?php namespace WpConvertloop\ContactForm;

/**
 * Envia los datos a ConvertLoop cuando se gaurda la forma
 */
class Form
{

    private $convertloop;

    static function instance($convertloop)
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

    /**
     * Inicializacion de acciones y filtros de Wordpress
     */
    public function start()
    {
        add_action('wpcf7_before_send_mail',array($this, 'sendData'));
    }

    /**
     * Toma los datos del formulario y verifica si hay que enviar algo a
     * ConvertLoop
     */
    public function sendData($form)
    {
        $person = array();

        $props = $form->prop('convertloop_map');
        if (empty($props)) return;

        $submission = \WPCF7_Submission::get_instance();
        if ( $submission ) {
            $posted_data = $submission->get_posted_data();
        }

        foreach ($props as $key => $val) {
            if (empty($posted_data[$key])) continue;

            $cl_key = $props[$key];
            $person[$cl_key] = $posted_data[$key];
        }

        if (empty($person)) return $form;


        $this->convertloop->people()->createOrUpdate($person);

        return $form;
    }
}