<?php
 /**
  * Raccoon specific features methods
  *
  * PHP version 5
  *
  * @category Registration
  * @package  Raccoon
  * @author   Damien Senger <hi@hiwelo.co>
  * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
  * @link     https://github.com/hiwelo/raccoon-plugin
  * @since    1.2.0
  */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\CleanUp;
use Hiwelo\Raccoon\Tools;

/**
 * Raccoon specific features methods
 *
 * PHP version 5
 *
 * @category Registration
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.2.0
 */
class RaccoonFeatures extends Feature
{
    /**
     * Default values
     *
     * @return array default configuration
     *
     * @since 1.2.0
     */
    protected function defaultValues()
    {
        return [];
    }

    /**
     * Feature constructor
     *
     * @param array $configuration cleanup configuration
     *
     * @see   Feature::mergeConfigurationWithDefault();
     * @since 1.2.0
     */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

    /**
     * Registration method
     *
     * @return void
     *
     * @see   Feature::$configuration
     * @see   Feature::enableSVG();
     * @see   Tools::parseBooleans();
     * @see   https://codex.wordpress.org/Function_Reference/add_filter
     * @since 1.2.0
     */
    protected function registration()
    {
        foreach ($this->configuration as $option => $status) {
            Tools::parseBooleans($status);

            switch ($option) {
                case 'svg-upload':
                    if ($status === true) {
                        $this->enableSVG();
                    }
                    break;

                case 'comments':
                    if ($status === false) {
                        $this->removeComments();
                    }
                    break;

                case 'widget':
                case 'widgets':
                    if ($status === false) {
                        $this->removeWidgets();
                    }
                    break;

                case 'cleanup':
                    if ($status === true || is_array($status)) {
                        new CleanUp($status);
                    }
                    break;

                case 'js-detection':
                    if ($status === true) {
                        $this->enableJSDetection();
                    }
                    break;

                case 'all-settings':
                    if ($status === true) {
                        $this->enableAllSettingsPage();
                    }
                    break;
            }
        }
    }

    /**
     * Unregistration method
     *
     * @return void
     *
     * @see   Feature::$removeItems
     * @see   https://codex.wordpress.org/Function_Reference/add_filter
     * @since 1.2.0
     */
    protected function unregistration()
    {
        return;
    }

    /**
     * Enable SVG uploads in the WordPress media library
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/add_filter
     * @since 1.2.0
     */
    protected function enableSVG()
    {
        add_filter('upload_mimes', function ($mimes) {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        });
    }

    /**
     * Remove globally comments & discussion feature
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/add_filter
     * @see   https://developer.wordpress.org/reference/functions/get_bloginfo
     * @see   https://developer.wordpress.org/reference/functions/get_post_types
     * @see   https://developer.wordpress.org/reference/functions/remove_menu_page
     * @see   https://developer.wordpress.org/reference/functions/remove_meta_box
     * @see   https://developer.wordpress.org/reference/functions/remove_post_type_support
     * @see   https://developer.wordpress.org/reference/functions/remove_submenu_page
     * @see   https://developer.wordpress.org/reference/functions/update_option
     * @since 1.2.0
     */
    protected function removeComments()
    {
        // count options to reset at 0
        array_map(function ($item) {
            update_option($item, 0);
        }, ['comments_notify', 'default_pingback_flag']);

        // remove post type comment support
        foreach (get_post_types() as $postType) {
            add_action('admin_menu', function () use ($postType) {
                // remove comment status
                remove_meta_box('commentstatusdiv', $postType, 'normal');

                // remove trackbacks
                remove_meta_box('trackbacksdiv', $postType, 'normal');
            });

            // remove all comments/trackbacks from tables
            remove_post_type_support($postType, 'comments');
            remove_post_type_support($postType, 'trackbacks');
        }

        // remove dashboard meta box for recents comments
        add_action('admin_menu', function () {
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        });

        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
            remove_submenu_page('options-general.php', 'options-discussion.php');
        });

        // remove comments count in activity dashboard
        add_action('admin_footer-index.php', function () {
            echo "
                <script>
                    jQuery(document).ready(function () {
                        jQuery('#dashboard_right_now .comment-count').remove();
                    });
                </script>
            ";
        });

        // remove comments in the welcome panel column
        add_action('admin_footer-index.php', function () {
            echo "
                <script>
                    jQuery(document).ready(function () {
                        jQuery('.welcome-comments').parent().remove();
                    });
                </script>
            ";
        });

        add_action('admin_bar_menu', function ($wp_admin_bar) {
            $wp_admin_bar->remove_node('comments');
        }, 999);

        // remove comments feed
        remove_action('wp_head', 'feed_links', 2);
        add_action('wp_head', function () {
            echo '<link rel="alternate" type="application/rss+xml" ' .
                 'title="' .
                 get_bloginfo('sitename') .
                 ' &raquo; ' .
                 __('RSS Feed') .
                 '" href="' .
                 get_bloginfo('rss2_url') .
                 '">';
        }, 2);

        // remove admin comments column from admin page list
        add_filter('manage_pages_columns', function ($defaults) {
            unset($defaults['comments']);
            return $defaults;
        });
    }

    /**
     * Remove widget feature from all admin panels
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/remove_submenu_page
     * @see   https://developer.wordpress.org/reference/functions/unregister_sidebar
     * @see   https://developer.wordpress.org/reference/functions/unregister_widget
     * @since 1.2.0
     */
    protected function removeWidgets()
    {
        // remove defaults widget
        $defaultWidgets = [
            'WP_Widget_Pages',
            'WP_Widget_Archives',
            'WP_Widget_Meta',
            'WP_Widget_Text',
            'WP_Widget_Recent_Posts',
            'WP_Widget_Recent_Comments',
            'WP_Widget_Calendar',
            'WP_Widget_Links',
            'WP_Widget_Search',
            'WP_Widget_Categories',
            'WP_Widget_RSS',
            'WP_Widget_Tag_Cloud',
            'WP_Nav_Menu_Widget',
            'Twenty_Eleven_Ephemera_Widget',
        ];

        foreach ($defaultWidgets as $widget) {
            add_action('widgets_init', function () use ($widget) {
                unregister_widget($widget);
            });
        }

        // list all custom widgets for unregistration
        global $wp_widget_factory;
        $widgets = $wp_widget_factory->widgets;

        foreach ($widgets as $id => $widget) {
            add_action('widgets_init', function () use ($id) {
                unregister_widget($id);
            });
        }

        // list all sidebars for unregistration
        global $wp_registered_sidebars;
        $sidebars = $wp_registered_sidebars;
        foreach ($sidebars as $id => $sidebar) {
            add_action('widgets_init', function () use ($id) {
                unregister_sidebar($id);
            });
        }

        // remove widget admin menu item
        add_action('admin_menu', function () {
            remove_submenu_page('themes.php', 'widgets.php');
        });

        // remove widget feature in the welcome panel column
        add_action('admin_footer-index.php', function () {
            echo "
                <script>
                    jQuery(document).ready(function () {
                        jQuery('.welcome-widgets-menus').parent().remove();
                    });
                </script>
            ";
        });
    }

    /**
     * Add a custom menu link for all settings page
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/add_options_page
     * @since 1.2.0
     */
    protected function enableAllSettingsPage()
    {
        add_action('admin_menu', function () {
            add_options_page(
                __('All Settings'),
                __('All Settings'),
                'administrator',
                'options.php'
            );
        });
    }

    /**
     * Add a script in the footer to detect if JS is available or not
     * (by removing .no-js class from body element)
     * and add a class into WordPress body_class() function
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/add_filter
     * @since 1.2.0
     */
    protected function enableJSDetection()
    {
        // add a no-js class in body_class();
        add_filter('body_class', function ($classes) {
            $classes[] = 'no-js';
            return $classes;
        });

        // add a JS script to remove this class if JS is enabled
        add_action('wp_footer', function () {
            echo "
                <script>
                    document.getElementsByTagName('body')[0]
                            .classList
                            .remove('no-js');
                </script>
            ";
        });
    }
}
