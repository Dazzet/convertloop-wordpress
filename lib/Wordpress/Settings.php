<?php

namespace WpConvertloop\Wordpress;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

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
        add_action('admin_init', array($this, 'createSectionsAndFields'));

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
    <div class="wrap">
        <h1><?php _e('Convertloop Settings') ?></h1>
        <form action="options.php" method="post">
            <?php settings_fields('wp-convertloop'); ?>
            <?php do_settings_sections('wp-convertloop') ?>
            <?php submit_button(); ?>
        </form>

    </div>
        <?php

        return $this;
    }


    /**
     * Llamados a add_settings_field para todos los campos
     */
    public function createSectionsAndFields()
    {
        // {{{ Section: Api settings
        add_settings_section(
            'section-api',
            __('Api key And ID', 'wp-convertloop'),
            array($this, 'sectionApi'),
            'wp-convertloop'
        );

        register_setting('wp-convertloop', 'convertloop_app_id');
        add_settings_field(
            'convertloop_app_id',
            __('App ID', 'wp-convertloop'),
            array($this, 'createAppIdField'),
            'wp-convertloop',
            'section-api'
        );

        register_setting('wp-convertloop', 'convertloop_api_key');
        add_settings_field(
            'convertloop_api_key',
            __('Api Key', 'wp-convertloop'),
            array($this, 'createApiKeyField'),
            'wp-convertloop',
            'section-api'
        );

        register_setting('wp-convertloop', 'convertloop_api_version');
        add_settings_field(
            'convertloop_api_version',
            __('Api Version', 'wp-convertloop'),
            array($this, 'createApiVersionField'),
            'wp-convertloop',
            'section-api'
        );
        // }}}

        // {{{ Section : JS Tracking code
        add_settings_section(
            'section-snip',
            __('Tracking Code', 'wp-convertloop'),
            array($this, 'sectionSnip'),
            'wp-convertloop'
        );

        register_setting('wp-convertloop', 'convertloop_add_snippet');
        add_settings_field(
            'convertloop_add_snippet',
            __('Include the ConvertLoop JS code in the head?', 'wp-convertloop'),
            array($this, 'createAddSnippetField'),
            'wp-convertloop',
            'section-snip'
        );
        // }}}

        // {{{ Section: Woocommerce
        add_settings_section(
            'section-woo',
            __('Woocommerce', 'wp-convertloop'),
            array($this, 'sectionWoo'),
            'wp-convertloop'
        );

        register_setting('wp-convertloop', 'convertloop_add_woo_checkout');
        add_settings_field(
            'convertloop_add_woo_checkout',
            __('Add "subscribe to newsletter" checkbox on checkout?', 'wp-convertloop'),
            array($this, 'createAddWoocommerceField'),
            'wp-convertloop',
            'section-woo'
        );

        add_settings_field(
            'convertloop_add_woo_segment',
            __('To which segment should the checkout subscribers need to be added', 'wp-convertloop'),
            array($this, 'createAddWoocommerceSegment'),
            'wp-convertloop',
            'section-woo'
        );
        // }}}

    }

    /**
     * Ayuda de la única sección de la página
     */
    public function sectionApi()
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

    public function sectionSnip(){
    }

    public function createAddSnippetField()
    {
        $val = get_option('convertloop_add_snippet', true);
        $checked = $val ? 'checked="checked"': '';
        echo '<input type="checkbox" value="1" name="convertloop_add_snippet" '.$checked.'>';
        echo '<br /><small>';
        _e('Requires that you provide the App ID', 'wp-convertloop');
        echo '</small>';
    }

    public function sectionWoo(){
        _e('Will work only if you have Woocommerce installed', 'wp-convertloop');
    }

    public function createAddWoocommerceField()
    {
        $val = get_option('convertloop_add_woo_checkout', true);
        $checked = $val ? 'checked="checked"': '';
        echo '<input type="checkbox" value="1" name="convertloop_add_woo_checkout" '.$checked.'>';
    }

    public function createAddWoocommerceSegment()
    {
        $val = get_option('convertloop_add_woo_segment', __('clients', 'wp-convertloop'));
        echo '<input type="text" name="convertloop_add_woo_segment" value="'.$val.'">';
        echo '<br /><small>';
        _e('The subscribers captured on the chackout will be added to this segment', 'wp-convertloop');
        echo '</small>';
    }
}

// vim:
