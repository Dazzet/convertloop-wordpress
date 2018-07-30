<?php namespace WpConvertloop\ContactForm;

/**
 * Crea un nuevo tab y opciones de configuracion en el Dashboard
 */
class Dashboard
{

    private $convertloop;

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

    public function start()
    {
        add_filter('wpcf7_contact_form_properties', array($this, 'formProperties'), 10, 2);

        add_action('wpcf7_editor_panels', array($this, 'addTab'), 1, 1);
        add_action('wpcf7_save_contact_form', array($this, 'save'), 10, 1);
    }

    public function formProperties($props, $form)
    {
        $props['convertloop_map'] = isset($props['convertloop_map']) ? $props['convertloop_map']: array();

        return $props;
    }

    public function addTab($panels)
    {
        $panels['convertloop'] = array(
            'title' => __('Convertloop', 'wp-convertloop'),
            'callback' => array($this, 'tabFields')
        );
        return $panels;
    }

    public function tabFields($post)
    {
        $scanned = $post->scan_form_tags();
        $map = $post->prop('convertloop_map');

        if (empty($scanned)) return _e('Save the form first', 'wp-convertloop');

        echo '<h2>'.__('Map fields', 'wp-convertloop').'</h2>';
        echo '<fieldset>';
        echo '<table class="form-table">';
        foreach ($scanned as  $field) {
            if ($field->type == 'submit') continue;
            echo '<tr>';
            echo '<th scope="row"><label for="">'. $field->name .'</label></th>';
            echo '<td><input type="text" name="wpcf7-convertloop-map['.$field->name.']" class="large-text code" size="70" value="'.@$map[$field->name].'" /></td>';
            echo '</tr>';

        }
        echo '</table>';
        echo '</fieldset>';

    }

    public function save($form)
    {
        $props = $form->get_properties();
        $props['convertloop_map'] = isset($_POST['wpcf7-convertloop-map']) ? $_POST['wpcf7-convertloop-map']: array();
        //wp_die('<pre>'.print_r($props, true).'</pre>');
        $form->set_properties($props);
    }
}
