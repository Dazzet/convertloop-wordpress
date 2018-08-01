<?php namespace WpConvertloop\Wordpress;

/**
 * Crea la página Settings > Convertloop para guardar la llave y el ID de app
 */
class Settings
{
    public static function instance()
    {
        static $obj;

        if (!isset($obj)) {
            $obj = new self;
        }

        return $obj;
    }

    private function __construct()
    {

    }

    /**
     * Registra las acciones y filtros de Wordpress
     */
    public function start()
    {
        add_action('admin_menu', array($this, 'addMenuItem'),  11);

        add_action('admin_init', array($this, 'createSections'));

        add_action('admin_init', array($this, 'createFields'));

        register_setting('wp-convertloop', 'convertloop_api_key');
        register_setting('wp-convertloop', 'convertloop_app_id');
        register_setting('wp-convertloop', 'convertloop_api_version', array('default', 'v1'));
        register_setting('wp-convertloop', 'convertloop_add_snippet');
        register_setting('wp-convertloop', 'convertloop_add_woo_checkout');
    }

    /**
     * Crea la entrada de menu
     */
    public function addMenuItem()
    {
        add_options_page(
            __('Convertloop Settings', 'wp-convertloop'),
            __('Convertloop', 'wp-convertloop'),
            'manage_options',
            'wp-convertloop',
            array($this, 'createPage')
        );

        return $this;
    }


    /**
     * Crea el HTML de la página
     */
    public function createPage()
   {
?>
    <h1><?php _e('Convertloop Settings') ?></h1>
        <form action="options.php" method="post">
            <?php settings_fields('wp-convertloop'); ?>
            <?php do_settings_sections('wp-convertloop') ?>
            <?php submit_button(); ?>
        </form>

        <?php

        return $this;
    }

    /**
     * Crea la única sección de la página
     */
    public function createSections()
    {
        add_settings_section(
            'section-1',
            __('Api key And ID', 'wp-convertloop'),
            array($this, 'section1'),
            'wp-convertloop'
        );

        add_settings_section(
            'section-2',
            __('Tracking Code', 'wp-convertloop'),
            array($this, 'section2'),
            'wp-convertloop'
        );

        add_settings_section(
            'section-3',
            __('Woocommerce', 'wp-convertloop'),
            array($this, 'section3'),
            'wp-convertloop'
        );
    }

    /**
     * Llamados a add_settings_fiel para todos los campos
     */
    public function createFields()
    {
        add_settings_field(
            'convertloop_app_id',
            __('App ID', 'wp-convertloop'),
            array($this, 'createAppIdField'),
            'wp-convertloop',
            'section-1'
        );

        add_settings_field(
            'convertloop_api_key',
            __('Api Key', 'wp-convertloop'),
            array($this, 'createApiKeyField'),
            'wp-convertloop',
            'section-1'
        );

        add_settings_field(
            'convertloop_api_version',
            __('Api Version', 'wp-convertloop'),
            array($this, 'createApiVersionField'),
            'wp-convertloop',
            'section-1'
        );

        add_settings_field(
            'convertloop_add_snippet',
            __('Include the ConvertLoop JS code in the head?', 'wp-convertloop'),
            array($this, 'createAddSnippetField'),
            'wp-convertloop',
            'section-2'
        );

        add_settings_field(
            'convertloop_add_woo_checkout',
            __('Add "subscribe to newsletter" checkbox on checkout?', 'wp-convertloop'),
            array($this, 'createAddWoocommerceField'),
            'wp-convertloop',
            'section-3'
        );
    }

    /**
     * Ayuda de la única sección de la página
     */
    public function section1()
    {
        printf(__('You have to provide at least the App ID if you just want to use tracking. You can find this data  <a href="%s" target="_blank">here</a>', 'wp-convertloop'), 'https://convertloop.co/account');
    }


    /**
     * Campo para registrar el App ID
     */
    public function createAppIdField()
    {
        $val = get_option('convertloop_app_id');
        echo '<input type="text" name="convertloop_app_id" value="'.$val.'" required="required">';
    }

    /**
     * Campo para registrar el Api Key
     */
    public function createApiKeyField()
    {
        $val = get_option('convertloop_api_key');
        echo '<input type="password" name="convertloop_api_key" value="'.$val.'">';
        echo '<br /><small>';
        _e('Only if you want to send data to ConvertLoop in forms', 'wp-convertloop');
        echo '</small>';
    }

    /**
     * Campo para registrar la version del api a usar
     */
    public function createApiVersionField()
    {
        $val = get_option('convertloop_api_version', 'v1');
        echo '<input type="text" name="convertloop_api_version" value="'.$val.'">';
    }

    public function section2(){
        _e('Configure the ConvertLoop tracking code for event tracking', 'wp-convertloop');
    }

    public function createAddSnippetField()
    {
        $val = get_option('convertloop_add_snippet', true);
        $checked = $val ? 'checked="checked"': '';
        echo '<input type="checkbox" value="1" name="convertloop_add_snippet" '.$checked.'>';
    }

    public function section3(){
        _e('Will work only if you have Woocommerce installed', 'wp-convertloop');
    }

    public function createAddWoocommerceField()
    {
        $val = get_option('convertloop_add_woo_checkout', true);
        $checked = $val ? 'checked="checked"': '';
        echo '<input type="checkbox" value="1" name="convertloop_add_woo_checkout" '.$checked.'>';
    }
}
