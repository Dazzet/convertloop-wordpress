<?php

namespace WpConvertloop\ContactForm7;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Crea un nuevo tab y opciones de configuracion en el Dashboard
 */
class Dashboard
{

    private $convertloop;

    /**
     * @param string Object Objeto tipo \ConvertLoop\ConvertLoop
     */
    static function instance($convertloop = null)
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
     * Registro de las acciones y filtros de contact-form-7
     */
    public function start()
    {
        add_filter('wpcf7_contact_form_properties', array($this, 'formProperties'), 10, 2);

        add_action('wpcf7_editor_panels', array($this, 'addTab'), 1, 1);
        add_action('wpcf7_save_contact_form', array($this, 'save'), 10, 1);
    }

    /**
     * Callback - Especifica que campos adicionales se van a agregar
     */
    public function formProperties($props, $form)
    {
        $props['convertloop_map'] = isset($props['convertloop_map']) ? $props['convertloop_map']: array();

        return $props;
    }

    /**
     * Callback - Crear una nueva cejilla en la creación de formulario
     */
    public function addTab($panels)
    {
        $panels['convertloop'] = array(
            'title' => __('Convertloop', 'wp-convertloop'),
            'callback' => array($this, 'tabFields')
        );
        return $panels;
    }

    /**
     * Creacion de los campos de formulario a agergar en la nueva cejilla
     */
    public function tabFields($post)
    {
        $scanned = $post->scan_form_tags();
        $map = $post->prop('convertloop_map');

        if (empty($scanned)) return _e('Save the form first', 'wp-convertloop');

        echo '<h2>'.__('Map fields', 'wp-convertloop').'</h2>';
        _e('Here you can configure which form fields are going to be sent to ConvertLoop. Leave empty the fields that are not going to be sent', 'wp-convertloop');
        echo '<fieldset>';
        echo '<table class="form-table">';
        echo '<tr><th>'.__('Form field', 'wp-convertloop').'</th><th>'.__('ConvertLoop name', 'wp-convertloop').'</th></tr>';

        $this->addRow( __('ConvertLooop Segment *', 'wp-convertloop'), '_cl_segment', @$map['_cl_segment']);
        $this->addRow( __('ConvertLooop Event **', 'wp-convertloop'), '_cl_event', @$map['_cl_event']);

        foreach ($scanned as  $field) {
            if ($field->type == 'submit') continue;
            $this->addRow($field->name, $field->name, @$map[$field->name]);
        }

        echo '</table>';
        echo '<p>';
        printf(__('* Review segment documentation <a href="%s" target="_blank">here</a>', 'wp-convertloop'), 'https://convertloop.co/docs/developers/adding-people-to-a-segment');
        echo '</p>';
        echo '<p>';
        printf(__('** Review events documentation <a href="%s" target="_blank">here</a>', 'wp-convertloop'), 'https://convertloop.co/docs/developers/tracking-events');
        echo '</p>';
        echo '</fieldset>';
    }

    private function addRow($label, $fieldName, $value)
    {
        echo '<tr>';
        echo '<th scope="row"><label for="">'. $label .'</label></th>';
        echo '<td><input type="text" name="wpcf7-convertloop-map['.$fieldName.']" class="large-text code" size="70" value="'.$value.'" /></td>';
        echo '</tr>';

    }

    /**
     * Guardar los nombres que se van a mapear
     * Esto no es guardar datos de formularios sino que datos se van a enviar a ConvertLoop
     */
    public function save($form)
    {
        $props = $form->get_properties();
        $props['convertloop_map'] = isset($_POST['wpcf7-convertloop-map']) ? $_POST['wpcf7-convertloop-map']: array();
        $form->set_properties($props);
    }
}
