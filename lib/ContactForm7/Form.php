<?php

namespace WpConvertloop\ContactForm7;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

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
        $event = '';
        $segment = '';

        $props = $form->prop('convertloop_map');
        if (empty($props)) return;

        // Traemos los datos
        $submission = \WPCF7_Submission::get_instance();
        if ( $submission ) {
            $posted_data = $submission->get_posted_data();
        }

        // Lenamos el objeto a enviar a convertloop
        foreach ($props as $key => $val) {

            if ($key == '_cl_segment' ) {
               $segment = $val;
            } else if ($key == '_cl_event' ) {
                $event = $val;
            } else {
                $cl_key = $props[$key];
                if (!empty($cl_key)) $person[$cl_key] = $posted_data[$key];
            }
        }

        // Si no hay que enviar nada, entonces se sale
        if (empty($person)) return $form;

        // Si hay segmento de CL entonces lo agregamos
        if (!empty($segment)) {
            $person['add_to_segments'] = $segment;
        }

        if (!empty($_COOKIE['dp_pid'])) {
            $person['pid'] = $_COOKIE['dp_pid'];
        }

        $this->convertloop->people()->createOrUpdate($person);

        // Si este formulario genera un evento, lo registramos
        if (!empty($event)) {
            $this->convertloop->eventLogs()->send(array(
                'name' => $event,
                'person' => $person,
                'ocurred_at' => time(),
                'metadata' => $posted_data
            ));
        }

        return $form;
    }
}