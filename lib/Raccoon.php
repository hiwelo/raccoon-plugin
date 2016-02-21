<?php
/**
 * Raccoon plugin core methods
 *
 * PHP version 5
 *
 * @category Core
 * @package  Raccoon-plugin
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     ./docs/api/classes/Hiwelo.Raccoon.Core.html
 * @since    1.0.0
 */
namespace Hiwelo\Raccoon;

use Hiwelo\Raccoon\Features\ContactMethods;
use Hiwelo\Raccoon\Features\Navigations;
use Hiwelo\Raccoon\Features\PostStatus;
use Hiwelo\Raccoon\Features\PostTypes;
use Hiwelo\Raccoon\Features\Sidebars;
use Hiwelo\Raccoon\Features\ThemeSupports;
use Hiwelo\Raccoon\Features\Widgets;

/**
 * Raccoon plugin core methods
 *
 * PHP version 5
 *
 * @category Core
 * @package  Raccoon-plugin
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     ./docs/api/classes/Hiwelo.Raccoon.Raccoon.html
 * @since    1.0.0
 */
class Raccoon
{
    /**
     * Theme namespace, used mainly for translation methods (_e, __, _n, _x)
     *
     * @var   string
     */
    public $namespace = 'raccoon';

    /**
     * Environment status (development, staging, production)
     *
     * @var   string
     */
    public $environment = 'production';

    /**
     * Manifest informations for this theme, contain all theme configuration
     *
     * @var Manifest
     */
    public $manifest = null;

    /**
     * Setup this theme with all informations available in the manifest, a JSON
     * configuration file
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        // load and parse manifest file
        $this->manifest = Manifest::load();

        // load environment status
        $this->loadEnvironmentStatus();
        // load namespace if a specific one is specified
        $this->loadNamespace();
        // load internationalization if exists
        $this->i18nReady();

        // load all features or tasks asked
        (new ThemeSupports($this->manifest->getChildrenOf('theme-support')))->register();
        (new Navigations($this->manifest->getChildrenOf('navigations')))->register();
        (new Sidebars($this->manifest->getChildrenOf('sidebars')))->register();
        (new Widgets($this->manifest->getChildrenOf('widgets')))->register();
        (new PostStatus($this->manifest->getChildrenOf('post-status')))->register();
        (new PostTypes($this->manifest->getChildrenOf('post-types')))->register();
        (new ContactMethods($this->manifest->getChildrenOf('contact-methods')))->register();

        // remove asked features or items
        (new PostTypes($this->manifest->getChildrenOf('post-types')))->unregister();
        (new ContactMethods($this->manifest->getChildrenOf('contact-methods')))->unregister();

        // if asked, cleanup methods loading
        $this->loadCleanUp();
        // remove comments feature
        $this->removeCommentsFeature();
        // remove widgets feature
        $this->removeWidgetsFeature();
        // add a script for JS detection
        $this->addJSDetectionScript();
        // add a global settings page if asked
        $this->loadAllSettingsPage();
        // add thumbnails in pages or posts lists
        $this->addThumbnailInLists();
        // add svg support in WP medias library
        $this->enableSVG();
    }

    /**
     * Returned array used for object debug informations
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function __debugInfo()
    {
        return [
            'namespace' => $this->namespace,
            'manifest' => $this->manifest,
        ];
    }

    /**
     * Load the namespace specific information from the manifest
     *
     * @global string  $namespace namespace for this WordPress template
     *
     * @return boolean if true a namespace is specified,
     *                 false otherwise
     *
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     * @uses  Raccoon::$namespace
     */
    private function loadNamespace()
    {
        if (!$this->manifest->existsAndNotEmpty('namespace')) {
            return;
        }
        $this->namespace = $this->manifest->getValue('namespace');

        global $namespace;
        $namespace = $this->namespace;

        define('THEME_NAMESPACE', $namespace);
    }

    /**
     * Search environment status if avanlable and apply specific methods
     *   1. first in $_ENV
     *   2. if non available, in manifest.json (environment-status and env-status)
     *   3. if non available, environment status is set at `production`
     *
     * @global array $_ENV Environment variables
     *
     * @return void
     *
     * @since 1.0.0
     * @uses  Raccoon::$environment
     * @uses  Raccoon::$manifest
     */
    private function loadEnvironmentStatus()
    {
        $this->environment = $this->extractEnvironmentStatus();

        switch ($this->environment) {
            case "development":
                // add here some development features
                break;
            case "production":
                new ProductionFeatures($this->manifest['production']);
                break;
        }
    }

    /**
     * Extract environment status from the manifest or from the environment variables
     *
     * @return string environment status
     *
     * @see   Manifest::getValue();
     * @since 1.2.0
     */
    private function extractEnvironmentStatus()
    {
        if (array_key_exists('WP_ENV', $_ENV)) {
            return $_ENV['WP_ENV'];
        }

        return $this->manifest->getValue(
            'environment-status',
            $this->manifest->getValue('env-status')
        );
    }

    /**
     * Load WordPress mess cleanup class if asked in the manifest
     *
     * @return void
     *
     * @since 1.0.0
     * @uses  Hiwelo\Raccoon\CleanUp
     * @uses  Raccoon::$manifest
     */
    private function loadCleanUp()
    {
        if (array_key_exists('cleanup', $this->manifest->getArrayValue('theme-features'))) {
            $themefeatures = $this->manifest->getArrayValue('theme-features');
            new CleanUp($themefeatures['cleanup']);
        }
    }

    /**
     * Theme translation activation (with .po & .mo files)
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/get_template_directory
     * @link  https://developer.wordpress.org/reference/functions/load_theme_textdomain
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     * @uses  Raccoon::$namespace
     */
    private function i18nReady()
    {

        $i18nDirectory = get_template_directory().$this->manifest->getValue('languages-directory', '/languages');
        load_theme_textdomain($this->namespace, $i18nDirectory);
    }

    /**
     * Remove globally comments & discussion feature
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/add_action
     * @link  https://developer.wordpress.org/reference/functions/add_filter
     * @link  https://developer.wordpress.org/reference/functions/get_bloginfo
     * @link  https://developer.wordpress.org/reference/functions/get_post_types
     * @link  https://developer.wordpress.org/reference/functions/remove_menu_page
     * @link  https://developer.wordpress.org/reference/functions/remove_meta_box
     * @link  https://developer.wordpress.org/reference/functions/remove_post_type_support
     * @link  https://developer.wordpress.org/reference/functions/remove_submenu_page
     * @link  https://developer.wordpress.org/reference/functions/update_option
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     * @uses  Tools::parseBooleans()
     */
    private function removeCommentsFeature()
    {
        // early termination
        if (Tools::parseBooleans($this->manifest->getChildrenOf('theme-features')->getValue('comments')) !== false) {
            return;
        }

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
     * @link  https://developer.wordpress.org/reference/functions/add_action
     * @link  https://developer.wordpress.org/reference/functions/remove_submenu_page
     * @link  https://developer.wordpress.org/reference/functions/unregister_sidebar
     * @link  https://developer.wordpress.org/reference/functions/unregister_widget
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     * @uses  Tools::parseBooleans()
     */
    private function removeWidgetsFeature()
    {
        if (Tools::parseBooleans($this->manifest->getChildrenOf('theme-features')->getValueOrFalse('widget'))) {
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
        }

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
     * Add a script in the footer to detect if JS is available or not
     * (by removing .no-js class from html element)
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/add_action
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     * @uses  Tools::parseBooleans()
     */
    private function addJSDetectionScript()
    {
        if (Tools::parseBooleans($this->manifest->getValueOrFalse('theme-feature'))) {
            add_action('wp_footer', function () {
                echo "
                    <script>
                        document.getElementsByTagName('html')[0]
                                .classList
                                .remove('no-js');
                    </script>
                ";
            });
        }
    }

    /**
     * Add a custom menu link for all settings page
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/add_action
     * @link  https://developer.wordpress.org/reference/functions/add_options_page
     * @since 1.0.0
     * @uses  Raccoon::$environment
     * @uses  Raccoon::$manifest
     * @uses  Tools::parseBooleans()
     */
    private function loadAllSettingsPage()
    {
        if (Tools::parseBooleans($this->manifest->getChildrenOf('theme-features')->getValueOrFalse('all-settings'))
            && $this->environment === 'development') {
            add_action('admin_menu', function () {
                add_options_page(
                    __('All Settings'),
                    __('All Settings'),
                    'administrator',
                    'options.php'
                );
            });
        }
    }

    /**
     * Add a thumbnail column in the posts or pages list
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/add_action
     * @link  https://developer.wordpress.org/reference/functions/add_filter
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     * @uses  Tools::parseBooleans()
     */
    private function addThumbnailInLists()
    {
        if (Tools::parseBooleans(
            $this->manifest->getChildrenOf('theme-features')->getValueOrFalse('thumbnail-in-list')
        )) {
            add_filter('manage_posts_columns', [$this, 'addThumbColumn']);
            add_filter('manage_pages_columns', [$this, 'addThumbColumn']);

            add_action('manage_posts_custom_column', [$this, 'addThumbValue'], 10, 2);
            add_action('manage_pages_custom_column', [$this, 'addThumbValue'], 10, 2);
        }
    }

    /**
     * Enable SVG uploads in the media library
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/add_filter
     * @since 1.2.0
     * @uses  Raccoon::$manifest
     */
    private function enableSVG()
    {
        if (Tools::parseBooleans($this->manifest->getChildrenOf('theme-feature')->getValueOrFalse('svg-upload'))) {
            add_filter('upload_mimes', function ($mimes) {
                $mimes['svg'] = 'image/svg+xml';
                return $mimes;
            });
        }
    }

    /**
     * Add a new column in an array for thumnails
     *
     * @param array $cols columns
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function addThumbColumn($cols)
    {
        $cols['Thumbnail'] = __('Thumbnail');
        return $cols;
    }

    /**
     * Add a thumbnail into a list of posts or pages
     *
     * @param string  $column array column name
     * @param integer $post   post id
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/get_children
     * @link  https://developer.wordpress.org/reference/functions/get_post_meta
     * @link  https://developer.wordpress.org/reference/functions/wp_get_attachment_image
     * @since 1.0.0
     */
    public function addThumbValue($column, $post)
    {
        $width = (int) 35;
        $height = (int) 35;

        if ('thumbnail' === $column) {
            $thumbnailID = get_post_meta($post, '_thumbnail_id', true);
            $attachments = get_children([
                'post_parent' => $post,
                'post_type' =>
                'attachment',
                'post_mime_type' => 'image'
            ]);
            if ($thumbnailID) {
                $thumbnail = wp_get_attachment_image(
                    $thumbnailID,
                    [$width, $height],
                    true
                );
            } elseif ($attachments) {
                foreach ($attachments as $attachmentID => $attachment) {
                    $thumnail = wp_get_attachment_image(
                        $attachmentID,
                        [$width, $height],
                        true
                    );
                }
            }
            if (isset($thumbnail) && $thumbnail) {
                echo $thumbnail;
            } else {
                _e('None');
            }
        }
    }
}
