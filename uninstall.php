<?php
/**
 * @package HnhDigitalGtag
 */

namespace HnhDigitalGtag;

require_once plugin_dir_path(__FILE__).'vendor/autoload.php';

use HnhDigitalGtag\HnhDigitalGtagConfig;

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
  die;
}

delete_option(HnhDigitalGtagConfig::SETTINGS_KEY);
delete_user_meta(get_current_user_id(), HnhDigitalGtagConfig::ADMIN_IGNORE_PIXEL_ID_NOTICE);
