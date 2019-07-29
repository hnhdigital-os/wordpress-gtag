<?php
/**
 * @package HnhDigitalGtag
 */

namespace HnhDigitalGtag;

defined('ABSPATH') or die('Direct access not allowed');

/**
 * Helper functions
 */
class HnhDigitalGtagUtils
{
  /**
   * Returns true if id is a positive non-zero integer
   *
   * @access public
   *
   * @param string $tag_id
   *
   * @return bool
   */
  public static function isValidTag($tag_id)
  {
    return isset($tag_id) && !empty($tag_id) && $tag_id !== '0';
  }

  /**
   * Whether current user is Administrator.
   */
  public static function isAdmin()
  {
    return current_user_can('install_plugins');
  }
}
