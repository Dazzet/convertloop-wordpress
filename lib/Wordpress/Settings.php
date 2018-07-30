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
    }

    /**
     * Ayuda de la única sección de la página
     */
    public function section1()
    {
        _e('Consult your Api Key and App Id in the convertloop dashboard', 'wp-convertloop');
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
    }

    /**
     * Campo para registrar el App ID
     */
    public function createAppIdField()
    {
        $val = get_option('convertloop_app_id');
        echo '<input type="text" name="convertloop_app_id" value="'.$val.'">';
    }

    /**
     * Campo para registrar el Api Key
     */
    public function createApiKeyField()
    {
        $val = get_option('convertloop_api_key');
        echo '<input type="password" name="convertloop_api_key" value="'.$val.'">';
    }

    /**
     * Campo para registrar la version del api a usar
     */
    public function createApiVersionField()
    {
        $val = get_option('convertloop_api_version', 'v1');
        echo '<input type="text" name="convertloop_api_version" value="'.$val.'">';
    }

}
