<?php
/*
Plugin Name: H&H Digital - Google Tag Manager
*/

defined('ABSPATH') or die('Direct access not allowed');

require_once plugin_dir_path(__FILE__).'vendor/autoload.php';

use HnhDigitalGtag\HnhDigitalGtagOptions;
use HnhDigitalGtag\HnhDigitalGtagSettingsPage;

class HnhDigitalGtag
{
    /**
     * Plugin instance
     *
     * @var null|HnhDigitalGtag
     */
    private static $instance = null;

    /**
     * Short code name.
     *
     * @var string
     */
    private $plugin_name = 'hnhdigital-gtag';

    /**
     * Return the plugin instance
     *
     * @return HnhDigitalGtag
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'register'], 100);
        
        HnhDigitalGtagOptions::initialize();

        // initialize admin page config
        $this->registerSettingsPage();
    }

    /**
     * Register scripts needed for the frontend.
     *
     * @return void
     */
    public function register()
    {
        wp_enqueue_script(
            $this->plugin_name.'-resource-js',
            'https://www.googletagmanager.com/gtag/js?id='.HnhDigitalGtagOptions::getTagId(),
            [],
            null,
            true
        );

        wp_enqueue_script(
            $this->plugin_name.'-js',
            plugins_url('js/hnhdigital-gtag.js?id='.HnhDigitalGtagOptions::getTagId(), __FILE__),
            ['jquery'],
            filemtime(__DIR__.'/js/hnhdigital-gtag.js'),
            true
        );
    }

    /**
    * Helper function for registering the settings page.
    */
    public function registerSettingsPage()
    {
        if (is_admin()) {
            $plugin_name = plugin_basename(__FILE__);
            new HnhDigitalGtagSettingsPage($this->plugin_name);
        }
    }
}

// Init the plugin and load the plugin instance for the first time.
add_action('plugins_loaded', ['HnhDigitalGtag', 'getInstance']);
