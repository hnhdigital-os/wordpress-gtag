<?php
/**
 * @package HnhDigitalGtag
 */

namespace HnhDigitalGtag;

defined('ABSPATH') or die('Direct access not allowed');

class HnhDigitalGtagSettingsPage
{
    private $optionsPage = '';

    public function __construct($plugin_name)
    {
        add_action('admin_menu', [$this, 'addMenu']);
        add_action('admin_init', [$this, 'registerSettingsPage']);
        add_action('admin_init', [$this, 'dismissNotices']);
        add_action('admin_enqueue_scripts', [$this, 'registerPluginStyles']);
        add_action('current_screen', [$this, 'registerNotices']);
        add_filter('plugin_action_links_'.$plugin_name, [$this, 'addSettingsLink']);
    }

    public function addMenu()
    {
        $this->optionsPage = add_options_page(
            HnhDigitalGtagConfig::ADMIN_PAGE_TITLE,
            HnhDigitalGtagConfig::ADMIN_MENU_TITLE,
            HnhDigitalGtagConfig::ADMIN_CAPABILITY,
            HnhDigitalGtagConfig::ADMIN_MENU_SLUG,
            [$this, 'createMenuPage']
        );
    }

    public function createMenuPage()
    {
        if (!current_user_can(HnhDigitalGtagConfig::ADMIN_CAPABILITY)) {
            wp_die(__(
                'You do not have sufficient permissions to access this page',
                HnhDigitalGtagConfig::TEXT_DOMAIN)
            );
        }

        printf(
            '
            <div class="wrap">
            <h2>%s</h2>
            <form action="options.php" method="POST">
            ',
            HnhDigitalGtagConfig::ADMIN_PAGE_TITLE);
            settings_fields(HnhDigitalGtagConfig::ADMIN_OPTION_GROUP);
            do_settings_sections(HnhDigitalGtagConfig::ADMIN_MENU_SLUG);
            submit_button();
            printf(
            '
            </form>
            </div>
        ');
    }

    public function registerSettingsPage()
    {
        register_setting(
            HnhDigitalGtagConfig::ADMIN_OPTION_GROUP,
            HnhDigitalGtagConfig::SETTINGS_KEY,
            array($this, 'sanitizeInput')
        );

        add_settings_section(
            HnhDigitalGtagConfig::ADMIN_SECTION_ID,
            null,
            array($this, 'sectionSubTitle'),
            HnhDigitalGtagConfig::ADMIN_MENU_SLUG
        );

        add_settings_field(
            HnhDigitalGtagConfig::TAG_ID_KEY,
            'Tag ID',
            array($this, 'tagIdFormField'),
            HnhDigitalGtagConfig::ADMIN_MENU_SLUG,
            HnhDigitalGtagConfig::ADMIN_SECTION_ID
        );
    }

    public function sanitizeInput($input)
    {
        return $input;
    }

    public function tagIdFormField()
    {
        $description = esc_html__(
        'The unique identifier for your Google Tag .',
        HnhDigitalGtagConfig::TEXT_DOMAIN);

        $tag_id = HnhDigitalGtagOptions::getTagId();
        printf(
        '
        <input name="%s" id="%s" value="%s" />
        <p class="description">%s</p>
        ',
        HnhDigitalGtagConfig::SETTINGS_KEY . '[' . HnhDigitalGtagConfig::TAG_ID_KEY . ']',
        HnhDigitalGtagConfig::TAG_ID_KEY,
        isset($tag_id)
        ? esc_attr($tag_id)
        : '',
        $description);
    }


    public function sectionSubTitle() {
        printf(
            esc_html__('', HnhDigitalGtagConfig::TEXT_DOMAIN)
        );
    }

    public function registerNotices() 
    {
        // Update class field
        $tag_id = HnhDigitalGtagOptions::getTagId();
        $current_screen_id = get_current_screen()->id;

        if (!HnhDigitalGtagUtils::isValidTag($tag_id)
            && current_user_can(HnhDigitalGtagConfig::ADMIN_CAPABILITY)
            && in_array($current_screen_id, array('dashboard', 'plugins', $this->optionsPage), true)
            && !get_user_meta(get_current_user_id(),HnhDigitalGtagConfig::ADMIN_IGNORE_TAG_ID_NOTICE, true)
        ) {
            add_action('admin_notices', array($this, 'tagIdNotSetNotice'));
        }
    }

    public function registerPluginStyles()
    {
        wp_register_style(
        HnhDigitalGtagConfig::TEXT_DOMAIN,
        plugins_url('../css/admin.css', __FILE__));
        wp_enqueue_style(HnhDigitalGtagConfig::TEXT_DOMAIN);
    }

    public function tagIdNotSetNotice()
    {
        $url = admin_url('options-general.php?page='.HnhDigitalGtagConfig::ADMIN_MENU_SLUG);
        $link = sprintf(
            wp_kses(
            __(
                'The Google Tag Manager plugin requires a Tag ID. Click <a href="%s">here</a> to configure the plugin.',
                HnhDigitalGtagConfig::TEXT_DOMAIN),
                array('a' => array('href' => array()))
            ),
            esc_url($url)
        );

        printf(
            '
            <div class="notice notice-warning is-dismissible hide-last-button">
            <p>%s</p>
            <button
            type="button"
            class="notice-dismiss"
            onClick="location.href=\'%s\'">
            <span class="screen-reader-text">%s</span>
            </button>
            </div>
            ',
            $link,
            esc_url(add_query_arg(HnhDigitalGtagConfig::ADMIN_DISMISS_TAG_ID_NOTICE, '')),
            esc_html__(
            'Dismiss this notice.',
            HnhDigitalGtagConfig::TEXT_DOMAIN)
        );
    }

    public function dismissNotices()
    {
        $user_id = get_current_user_id();

        if (isset($_GET[HnhDigitalGtagConfig::ADMIN_DISMISS_TAG_ID_NOTICE])) {
            update_user_meta($user_id, HnhDigitalGtagConfig::ADMIN_IGNORE_TAG_ID_NOTICE, true);
        }
    }

    public function addSettingsLink($links)
    {
        $settings = array(
            'settings' => sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page='.HnhDigitalGtagConfig::ADMIN_MENU_SLUG),
            'Settings')
        );

        return array_merge($settings, $links);
    }
}
