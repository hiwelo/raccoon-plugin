<?php
/**
 * WordPress security cleanup methods
 *
 * PHP version 5
 *
 * @category CleanUp
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.2.0
 */

namespace Hiwelo\Raccoon\CleanUp;

use Hiwelo\Raccoon\Manifest;

/**
 * WordPress security cleanup methods
 *
 * PHP version 5
 *
 * @category CleanUp
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.2.0
 */
class Security extends Cleaner
{
    /**
     * Security cleanup default values
     *
     * @return array default configuration
     *
     * @since 1.2.0
     */
    protected function defaultValues()
    {
        return [
            "wlwmanifest_link",
            "rsd_link",
            "index_rel_link",
            "parent_post_rel_link",
            "start_post_rel_link",
            "adjacent_posts_rel_link",
            "feed_links_extra",
            "adjacent_posts_rel_link_wp_head",
            "wp_generator",
            "wp_shortlink_wp_head",
            "no-ftp",
            "login-error"
        ];
    }

    /**
     * Cleaning method
     *
     * @return void
     *
     * @see   Head::configuration();
     * @see   https://developer.wordpress.org/reference/functions/add_filter
     * @see   https://developer.wordpress.org/reference/functions/remove_action
     * @since 1.2.0
     */
    protected function cleaning(Manifest $manifest)
    {
        foreach ($manifest->asArray() as $action) {
            switch ($action) {
                case 'no-ftp':
                    $constants = get_defined_constants();
                    if (!array_key_exists('FS_METHOD', $constants)) {
                        define('FS_METHOD', 'direct');
                    }
                    break;

                case 'login-error':
                    add_filter('login_errors', function ($defaults) {
                        return null;
                    });
                    break;

                default:
                    remove_action('wp_head', $action);
                    break;
            }
        }
    }

    /**
     *
     */
    protected function manifestKey()
    {
        return 'security';
    }
}
