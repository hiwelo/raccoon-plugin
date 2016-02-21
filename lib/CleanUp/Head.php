<?php
/**
 * WordPress wp_head mess cleanup methods
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

/**
 * WordPress wp_head mess cleanup methods
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
class Head extends Cleaner
{
    /**
     * WP_head cleanup default values
     *
     * @return array default configuration
     *
     * @since 1.2.0
     */
    protected function defaultValues()
    {
        return [
            "remove-adminbar-css",
            "emoji-css",
        ];
    }

    /**
     * WordPress wp_head mess CleanUp constructor
     *
     * @param array $configuration cleanup configuration
     *
     * @see   Admin::mergeConfigurationWithDefault();
     * @since 1.2.0
     */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

    /**
     * Cleaning method
     *
     * @return void
     *
     * @see   Head::configuration();
     * @see   https://developer.wordpress.org/reference/functions/add_theme_support
     * @see   https://developer.wordpress.org/reference/functions/remove_action
     * @see   https://developer.wordpress.org/reference/functions/remove_filter
     * @since 1.2.0
     */
    protected function cleaning()
    {
        foreach ($this->configuration as $action) {
            switch ($action) {
                case 'remove-adminbar-css':
                    add_theme_support('admin-bar', ['callback' => '__return_false']);
                    break;

                case 'emoji-css':
                    remove_action('admin_print_styles', 'print_emoji_styles');
                    remove_action('wp_head', 'print_emoji_detection_script', 7);
                    remove_action('admin_print_scripts', 'print_emoji_detection_script');
                    remove_action('wp_print_styles', 'print_emoji_styles');
                    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
                    remove_filter('the_content_feed', 'wp_staticize_emoji');
                    remove_filter('comment_text_rss', 'wp_staticize_emoji');
                    break;
            }
        }
    }
}
