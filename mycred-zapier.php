<?php
/**
 * Plugin Name: myCred Zapier
 * Plugin URI: https://mycred.me
 * Description:Integrates mycred with Zapier, allowing points log to be automatically sent to your configured Zaps.
 * Version: 1.0.4
 * Tags: mycred, zapier, automation, integration, points system
 * Requires at least: WP 5.8
 * Tested up to: WP 6.6.1
 * Author: myCred
 * Text Domain : mycred-zapier
 */
if (!defined('ABSPATH'))
    exit;

if (!defined('myCRED_ZAPIER'))
    define( 'myCRED_ZAPIER', __FILE__ );

if (!defined('MYCRED_ZAP_DIR'))
    define('MYCRED_ZAP_DIR', plugin_dir_path(__FILE__));

if (!defined('MYCRED_ZAP_URL'))
    define('MYCRED_ZAP_URL', plugin_dir_url(__FILE__));

if (!defined('myCRED_ZAPIER_VERSION'))
    define('myCRED_ZAPIER_VERSION', '1.0.4');

if (!defined('myCRED_ZAPIER_SLUG'))
    define( 'myCRED_ZAPIER_SLUG', 'mycred-zapier' );

if (!defined('MYCRED_ZAPIER_DB_VERSION'))
    define( 'MYCRED_ZAPIER_DB_VERSION', '1.0.4' );

require_once MYCRED_ZAP_DIR . 'includes/mycred-zapier-functions.php';

if ( ! class_exists( 'MyCred_Zapier') ):
    class Mycred_Zapier {

        public function __construct() {

            load_plugin_textdomain('mycred-zapier', false, MYCRED_ZAP_DIR . 'languages');
            add_action('plugins_loaded', array($this, 'mycred_zapier_init'));
            register_activation_hook( myCRED_ZAPIER, 'create_mycred_zapier_table' );
        
        }


        public function mycred_zapier_init() {
            if (!class_exists('myCRED_Addons_Module')) {
                add_action('admin_notices', array($this, 'require_mycred_to_be_installed'));
                return;
            }
            require_once MYCRED_ZAP_DIR . 'includes/class-mycred-zapier.php';
            require_once MYCRED_ZAP_DIR . 'includes/mycred-zapier-api.php';
        }

        public function require_mycred_to_be_installed() {
            $class = 'notice notice-error';
            $message = __('mycred need to be active and installed to use mycred-zapier addon', 'mycred-zapier');

            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }

    }
    
    new Mycred_Zapier;
endif;