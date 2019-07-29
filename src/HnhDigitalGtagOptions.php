<?php
/**
 * @package HnhDigitalGtag
 */

namespace HnhDigitalGtag;

defined('ABSPATH') or die('Direct access not allowed');

class HnhDigitalGtagOptions
{
    private static $options = [];
    private static $userInfo = [];
    private static $versionInfo = [];

    public static function initialize()
    {
        self::setOptions();
        self::setUserInfo();
        self::setVersionInfo();
    }

    public static function getOptions()
    {
        return self::$options;
    }

    private static function setOptions() {
        self::$options = \get_option(
            HnhDigitalGtagConfig::SETTINGS_KEY,
            [
                HnhDigitalGtagConfig::TAG_ID_KEY => is_null(HnhDigitalGtagConfig::DEFAULT_TAG_ID) ? '' : HnhDigitalGtagConfig::DEFAULT_TAG_ID,
            ]
        );

        // we need esc_js because the id is set through the form
        self::$options[HnhDigitalGtagConfig::TAG_ID_KEY] =
        esc_js(self::$options[HnhDigitalGtagConfig::TAG_ID_KEY]);
    }

    public static function getTagId()
    {
        return self::$options[HnhDigitalGtagConfig::TAG_ID_KEY];
    }

    public static function getUserInfo()
    {
        return self::$userInfo;
    }

    public static function setUserInfo()
    {
        add_action('init', array('HnhDigitalGtag\\HnhDigitalGtagOptions', 'registerUserInfo'), 0);
    }

    public static function registerUserInfo()
    {
        $current_user = wp_get_current_user();

        if (0 === $current_user->ID) {
            // User not logged in or admin chose not to send PII.
            self::$userInfo = [];
        } else {
            self::$userInfo = array_filter(
                [
                    'em' => $current_user->user_email,
                    'fn' => $current_user->user_firstname,
                    'ln' => $current_user->user_lastname
                ],
                function ($value) {
                    return $value !== null && $value !== '';
                }
            );
        }
    }

    public static function getVersionInfo()
    {
        return self::$versionInfo;
    }

    public static function setVersionInfo()
    {
        global $wp_version;

        self::$versionInfo = [
            'pluginVersion' => HnhDigitalGtagConfig::PLUGIN_VERSION,
            'source'        => HnhDigitalGtagConfig::SOURCE,
            'version'       => $wp_version
        ];
    }

    public static function getAgentString()
    {
        return sprintf(
            '%s-%s-%s',
            self::$versionInfo['source'],
            self::$versionInfo['version'],
            self::$versionInfo['pluginVersion']
        );
    }
}