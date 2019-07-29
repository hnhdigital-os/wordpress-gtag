<?php
/**
 * @package HnhDigitalGtag
 */

namespace HnhDigitalGtag;

defined('ABSPATH') or die('Direct access not allowed');

class HnhDigitalGtagConfig
{
    const PLUGIN_VERSION = '1.0.0';
    const SOURCE = 'wordpress';
    const TEXT_DOMAIN = 'hnhdigital-gtag';

    const ADMIN_CAPABILITY = 'manage_options';
    const ADMIN_DISMISS_TAG_ID_NOTICE = 'dismiss_tag_id_notice';
    const ADMIN_IGNORE_TAG_ID_NOTICE = 'ignore_tag_id_notice';
    const ADMIN_MENU_SLUG = 'hnhdigital_gtag_options';
    const ADMIN_MENU_TITLE = 'Google Tag Manager';
    const ADMIN_OPTION_GROUP = 'hnhdigital_gtag_option_group';
    const ADMIN_PAGE_TITLE = 'Google Tag Manager Settings';
    const ADMIN_SECTION_ID = 'hnhdigital_gtag_settings_section';

    const DEFAULT_TAG_ID = null;
    const TAG_ID_KEY = 'tag_id';
    const SETTINGS_KEY = 'hnhdigital_gtag_config';

    const IS_PIXEL_RENDERED = 'is_tag_rendered';
    const IS_NOSCRIPT_RENDERED = 'is_noscript_rendered';
}