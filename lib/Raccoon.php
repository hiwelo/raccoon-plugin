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
 */
namespace Hiwelo\Raccoon;

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
 */
class Raccoon
{
    /**
     * Theme namespace, used mainly for translation methods (_e, __, _n, _x)
     *
     * @var string
     */
    public $namespace = 'raccoon';

    /**
     * Environment status (development, staging, production)
     *
     * @var string
     */
    public $environment = 'production';

    /**
     * Manifest informations for this theme, contain all theme configuration
     *
     * @var array
     */
    public $manifest = [];

    /**
     * Setup this theme with all informations available in the manifest, a JSON
     * configuration file
     *
     * @param string $file manifest filename
     *
     * @return void
     */
    public function __construct($file = '')
    {
        // load theme manifest and store it
        if (!$this->loadManifest($file)) {
            return;
        }

        // load environment status
        $this->loadEnvironmentStatus();
        // load namespace if a specific one is specified
        $this->loadNamespace();
        // load internationalization if exists
        $this->i18nReady();
        // declare all theme features
        $this->loadThemeSupports();
        // if asked, cleanup methods loading
        $this->loadCleanUp();
        // declare all navigations
        $this->loadNavigations();
        // declare all post status
        $this->loadPostStatus();
        // declare all custom post status
        $this->loadCustomPostTypes();
        $this->removePostTypes();
        // declare all sidebars
        $this->loadSidebars();
        // declare all widgets
        $this->loadWidgets();
        // declare custom contact methods
        $this->loadContactMethods();
        // remove comments feature
        $this->removeCommentsFeature();
        // remove widgets feature
        $this->removeWidgetsFeature();
        // remove some admin features in prod
        $this->productionFeatures();
    }

    /**
     * Returned array used for object debug informations
     *
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'manifest' => $this->manifest,
        ];
    }

    /**
     * Load manifest.json content and store it
     *
     * @param string $file filename, must be at theme root folder
     *
     * @return boolean true if the file exists, false otherwise
     *
     * @link https://developer.wordpress.org/reference/functions/locate_template/
     * @uses Raccoon::$manifest
     */
    private function loadManifest($file = '')
    {
        if (empty($file)) {
            $file = 'manifest.json';
        }

        $file = get_template_directory() . '/' . $file;

        // verify if file exists
        if (!file_exists($file)) {
            return false;
        }

        $file = file_get_contents($file);

        // verify if file isn't empty
        if (empty($file)) {
            return false;
        }

        // parsing json file
        $this->manifest = json_decode($file, true);

        return true;
    }

    /**
     * Load the namespace specific information from the manifest
     *
     * @global string  $namespace namespace for this WordPress template
     * @return boolean            true if a namespace is specified, false otherwise
     *
     * @uses Raccoon::$manifest
     * @uses Raccoon::$namespace
     */
    private function loadNamespace()
    {
        if (array_key_exists('namespace', $this->manifest)) {
            if (empty($this->manifest['namespace'])) {
                return false;
            }

            // set namespace into this object
            $this->namespace = $this->manifest['namespace'];

            // set a global var for this namespace
            global $namespace;
            $namespace = $this->namespace;

            return true;
        }
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
     * @uses Raccoon::$environment
     * @uses Raccoon::$manifest
     * @uses Raccoon::loadDebugMethod()
     */
    private function loadEnvironmentStatus()
    {
        // we load a specific environment status from the environment or the manifest
        if (array_key_exists('WP_ENV', $_ENV)) {
            $this->environment = $_ENV['WP_ENV'];
        } elseif (array_key_exists('environment-status', $this->manifest)) {
            $this->environment = $this->manifest['environment-status'];
        } elseif (array_key_exists('env-status', $this->manifest)) {
            $this->environment = $this->manifest['env-status'];
        }

        switch ($this->environment) {
            case "development":
                // add here some development features
                break;
        }
    }

    /**
     * Load WordPress mess cleanup class if asked in the manifest
     *
     * @return void
     *
     * @uses Hiwelo\Raccoon\CleanUp
     * @uses Raccoon::$manifest
     */
    private function loadCleanUp()
    {
        if (array_key_exists('theme-features', $this->manifest)
            && array_key_exists('cleanup', $this->manifest['theme-features'])
        ) {
            $clean = new CleanUp($this->manifest['theme-features']['cleanup']);
        }
    }

    /**
     * Load WordPress production features management class if asked in the manifest
     *
     * @return void
     *
     * @uses Hiwelo\Raccoon\ProductionFeatures
     * @uses Raccoon::$environment
     * @uses Raccoon::$manifest
     */
    private function productionFeatures()
    {
        if ($this->environment === 'production'
            && array_key_exists('production', $this->manifest)
        ) {
            $production = new ProductionFeatures($this->manifest['production']);
        }
    }

    /**
     * Theme translation activation (with .po & .mo files)
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/load_theme_textdomain
     * @uses Raccoon::$manifest
     * @uses Raccoon::$namespace
     */
    private function i18nReady()
    {
        if (array_key_exists('languages-directory', $this->manifest)) {
            $i18nDirectory = get_template_directory() . $this->manifest['languages-directory'];
        } else {
            $i18nDirectory = get_template_directory() . '/languages';
        }

        load_theme_textdomain($this->namespace, $i18nDirectory);
    }

    /**
     * Declare all features asked in the manifest
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support
     * @uses Raccoon::$manifest
     */
    private function loadThemeSupports()
    {
        if (array_key_exists('theme-support', $this->manifest)) {
            $supports = $this->manifest['theme-support'];

            foreach ($supports as $key => $value) {
                Tools::parseBooleans($value);

                switch (gettype($value)) {
                    case "boolean":
                        if ($value === true) {
                            add_theme_support($key);
                        }
                        break;

                    default:
                        add_theme_support($key, $value);
                        break;
                }
            }
        }
    }

    /**
     * Register all navigations trom the manifest
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menu/
     * @uses Raccoon::$manifest
     * @uses Raccoon::$namespace
     */
    private function loadNavigations()
    {
        if (array_key_exists('navigations', $this->manifest)) {
            $navigations = $this->manifest['navigations'];

            foreach ($navigations as $location => $description) {
                register_nav_menu($location, __($description, $this->namespace));
            }
        }
    }

    /**
     * Register custom post status
     *
     * @global object $post data from the current post in the WordPress loop
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/__/
     * @link https://developer.wordpress.org/reference/functions/_n_noop/
     * @link https://developer.wordpress.org/reference/functions/add_action/
     * @link https://developer.wordpress.org/reference/functions/get_query_var/
     * @link https://developer.wordpress.org/reference/functions/is_admin/
     * @link https://developer.wordpress.org/reference/functions/register_post_status/
     * @uses Raccoon::$manifest
     * @uses Raccoon::$namespace
     */
    private function loadPostStatus()
    {
        if (array_key_exists('post-status', $this->manifest)) {
            // we parse namespace for further uses in anonymous functions
            $namespace = $this->namespace;

            // getting all custom post status in the manifest
            $customPostStatus = $this->manifest['post-status'];

            // getting all informations about the current post
            global $post;

            // if exists, remove post status asked to unregistration
            if (array_key_exists('remove', $customPostStatus)) {
                unset($customPostStatus['remove']);
            }

            foreach ($customPostStatus as $postStatus => $args) {
                // parsing labels value
                if (array_key_exists('label', $args)) {
                    $args['label'] = _x($args['label'], 'post', $namespace);
                }

                // parsing label-count values
                if (array_key_exists('label_count', $args)) {
                    $args['label_count'] = _n_noop(
                        $args['label_count'][0],
                        $args['label_count'][1],
                        $namespace
                    );
                }

                // post status registration
                register_post_status($postStatus, $args);

                // if we're in an admin panel, we do some actions after theme setup
                if (is_admin()) {
                    // add label status to a post in the admin panel list if this
                    // status is in the custom status list
                    add_action(
                        'display_post_states',
                        function ($statuses) use ($namespace, $postStatus, $args) {
                            global $post;

                            if (get_query_var('post_status' !== $postStatus)
                                && $post->post_Status === $postStatus
                            ) {
                                return [__($args['label'], $namespace)];
                            }
                        }
                    );

                    // add custom post status to quick edit select box
                    add_action(
                        'admin_footer-edit.php',
                        function () use ($namespace, $postStatus, $args) {
                            echo "
                                <script>
                                    jQuery(document).ready(function () {
                                        jQuery('select[name=\"_status\"]').append('<option value=\"" .
                                        $postStatus .
                                        "\">" .
                                        __($args['label'], $namespace) .
                                        "</option>');
                                    });
                                </script>
                            ";
                        }
                    );

                    // add custom post status to edit page select box
                    add_action(
                        'admin_footer-post.php',
                        function () use ($namespace, $postStatus, $args) {
                            global $post;

                            $complete = '';
                            $label = '';

                            if ($post->post_status === $postStatus) {
                                $complete = ' selected="selected"';
                                $label = '<span id="post-status-display"> ' .
                                         __($args['label'], $namespace) .
                                         '</span>';
                            }

                            echo "
                                <script>
                                    jQuery(document).ready(function () {
                                        jQuery('select#post_status').append('<option value\"" .
                                            $postStatus .
                                            "\" " .
                                            $complete .
                                            ">" .
                                            __($args['label'], $namespace) .
                                            "</option>');
                                        jQuery('.misc-pub-section label').append('" . $label . "');
                                    });
                                </script>
                            ";
                        }
                    );

                    // add custom post status to new page select box
                    add_action(
                        'admin_footer-post-new.php',
                        function () use ($namespace, $postStatus, $args) {
                            global $post;

                            $complete = '';
                            $label = '';

                            if ($post->post_status === $postStatus) {
                                $complete = ' selected="selected"';
                                $label = '<span id="post-status-display"> ' .
                                         __($args['label'], $namespace) .
                                         '</span>';
                            }

                            echo "
                                <script>
                                    jQuery(document).ready(function () {
                                        jQuery('select#post_status').append('<option value=\"" .
                                            $postStatus .
                                            "\" " .
                                            $complete .
                                            ">" .
                                            __($args['label'], $namespace) .
                                            "</option>');
                                        jQuery('.misc-pub-section label').append('" . $label . "');
                                    });
                                </script>
                            ";
                        }
                    );
                }
            }
        }
    }

    /**
     * Register all custom post types from the manifest
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/__/
     * @link https://developer.wordpress.org/reference/functions/_x/
     * @link https://developer.wordpress.org/reference/functions/add_action/
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     * @uses Raccoon::$manifest
     * @uses Raccoon::$namespace
     */
    private function loadCustomPostTypes()
    {
        if (array_key_exists('post-types', $this->manifest)) {
            $customPostTypes = $this->manifest['post-types'];

            // if exists, remove post type asked to unregistration
            unset($customPostTypes['remove']);

            foreach ($customPostTypes as $postType => $args) {
                // parsing labels value
                if (array_key_exists('labels', $args)) {
                    $labels = $args['labels'];

                    // keys which required a gettext with translation
                    $contextKeys = [
                        'name' => 'post type general name',
                        'singular_name' => 'post type singular name',
                        'menu_name' => 'admin menu',
                        'name_admin_bar' => 'add new on admin bar',
                    ];
                    $contextKeysList = array_keys($contextKeys);

                    foreach ($labels as $key => $value) {
                        if (in_array($key, $contextKeysList)) {
                            $labels[$key] = _x(
                                $value,
                                $contextKeys[$key],
                                $this->namespace
                            );
                        } else {
                            $labels[$key] = __($value, $this->namespace);
                        }
                    }
                }
                // parsing label value
                if (array_key_exists('label', $args)) {
                    $args['label'] = __($args['label'], $this->namespace);
                }
                // parsing description value
                if (array_key_exists('description', $args)) {
                    $args['description'] = __($args['description'], $this->namespace);
                }
                // replace "true" string value by a real boolean
                $stringBooleans = array_keys($args, "true");
                if ($stringBooleans) {
                    foreach ($stringBooleans as $key) {
                        $args[$key] = true;
                    }
                }
                // custom post type registration
                register_post_type($postType, $args);
            }
        }
    }

    /**
     * Remove post types asked for unregistration in the manifest file
     *
     * @global array $wp_post_types registered post types informations
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/add_action/
     * @link https://developer.wordpress.org/reference/functions/remove_menu_page/
     * @uses Raccoon::$manifest
     */
    private function removePostTypes()
    {
        // get all register post types
        global $wp_post_types;

        if (array_key_exists('post-types', $this->manifest)
            && array_key_exists('remove', $this->manifest['post-types'])
        ) {
            $postTypes = $this->manifest['post-types']['remove'];

            foreach ($postTypes as $postType) {
                // get post type name to remove from admin menu bar
                $itemName = $wp_post_types[$postType]->name;
                // unregister asked post type
                unset($wp_post_types[$postType]);
                // remove asked post type from admin menu bar
                if ($postType === 'post') {
                    $itemURL = 'edit.php';
                } else {
                    $itemURL = 'edit.php?post_type=' . $itemName;
                }
                // register item menu to remove
                add_action(
                    'admin_menu',
                    function () use ($itemURL) {
                        remove_menu_page($itemURL);
                    }
                );
                // remote post type count from dashboard activity widget
                add_action('admin_footer-index.php', function () use ($postType) {
                    echo "
                        <script>
                            jQuery(document).ready(function () {
                                jQuery('#dashboard_right_now ." . $postType . "-count').remove();
                            });
                        </script>
                    ";
                });
                // remove elements in the welcome panel column
                add_action('admin_footer-index.php', function () use ($postType) {
                    if ($postType === 'post') {
                        $class = '.welcome-write-blog';
                    } elseif ($postType === 'page') {
                        $class = '.welcome-add-page';
                    }
                    echo "
                        <script>
                            jQuery(document).ready(function () {
                                jQuery('" . $class . "').parent().remove();
                            });
                        </script>
                    ";
                });
                // remove elements from admin bar
                add_action('admin_footer', function () use ($postType) {
                    echo "
                        <script>
                            jQuery(document).ready(function () {
                                jQuery('#wp-admin-bar-new-" . $postType . "').remove();
                            });
                        </script>
                    ";
                });
            }
        }
    }

    /**
     * Register all sidebars from the manifest
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/__/
     * @link https://developer.wordpress.org/reference/functions/register_sidebar/
     * @uses Raccoon::$manifest
     * @uses Raccoon::$namespace
     */
    private function loadSidebars()
    {
        if (array_key_exists('sidebars', $this->manifest)) {
            $sidebars = $this->manifest['sidebars'];

            foreach ($sidebars as $args) {
                // parsing arguments to add translation for some keys
                foreach ($args as $key => $value) {
                    $i18nKeys = ['name', 'description'];
                    if (in_array($key, $i18nKeys)) {
                        $args[$key] = __($value, $this->namespace);
                    }
                }
                // sidebar registration
                register_sidebar($args);
            }
        }
    }

    /**
     * Register all widgets from the manifest.
     * Each declared widget must have a specific Widget class
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/register_widget/
     * @uses Raccoon::$manifest
     */
    private function loadWidgets()
    {
        if (array_key_exists('widgets', $this->manifest)) {
            $widgets = $this->manifest['widgets'];
            foreach ($widgets as $widget) {
                add_action('after_theme_setup', function () use ($widget) {
                    register_widget($widget);
                });
            }
        }
    }

    /**
     * Register and unregister custom contact methods
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/add_filter/
     * @uses Raccoon::$manifest
     */
    private function loadContactMethods()
    {
        if (array_key_exists('contact-methods', $this->manifest)) {
            if (array_key_exists('remove', $this->manifest['contact-methods'])) {
                $methodsToRemove = $this->manifest['contact-methods']['remove'];
                $methodsToAdd = $this->manifest['contact-methods'];
                unset($methodsToAdd['remove']);
            } else {
                $methodsToRemove = [];
                $methodsToAdd = $this->manifest['contact-methods'];
            }

            add_filter(
                'user_contactmethods',
                function ($contactMethods) use ($methodsToAdd, $methodsToRemove) {
                    if (count($methodsToAdd)) {
                        foreach ($methodsToAdd as $id => $method) {
                            $contactMethods[$id] = $method;
                        }
                    }

                    if (count($methodsToRemove)) {
                        foreach ($methodsToRemove as $method) {
                            unset($contactMethods[$method]);
                        }
                    }

                    return $contactMethods;
                }
            );
        }
    }

    /**
     * Remove globally comments & discussion feature
     *
     * @return void
     *
     * @link https://developer.wordpress.org/reference/functions/add_action/
     * @link https://developer.wordpress.org/reference/functions/get_post_types/
     * @link https://developer.wordpress.org/reference/functions/remove_menu_page/
     * @link https://developer.wordpress.org/reference/functions/remove_meta_box/
     * @link https://developer.wordpress.org/reference/functions/remove_post_type_support/
     * @link https://developer.wordpress.org/reference/functions/remove_submenu_page/
     * @link https://developer.wordpress.org/reference/functions/update_option/
     * @uses Raccoon::$manifest
     * @uses Tools::parseBooleans()
     */
    private function removeCommentsFeature()
    {
        if (array_key_exists('theme-features', $this->manifest)
            && array_key_exists('comments', $this->manifest['theme-features'])
        ) {
            $commentsFeature = $this->manifest['theme-features']['comments'];
            Tools::parseBooleans($commentsFeature);

            if ($commentsFeature === false) {
                // count options to reset at 0
                $options = ['comments_notify', 'default_pingback_flag'];
                foreach ($options as $option) {
                    update_option($option, 0);
                }

                // remove post type comment support
                $postTypes = get_post_types();
                foreach ($postTypes as $postType) {
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
            }
        }
    }

    private function removeWidgetsFeature()
    {
        if (array_key_exists('theme-features', $this->manifest)
            && array_key_exists('widget', $this->manifest['theme-features'])
        ) {
            $widgetFeature = $this->manifest['theme-features']['widget'];
            Tools::parseBooleans($widgetFeature);

            if ($widgetFeature === false) {
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
                    var_dump($id);
                    add_action('widgets_init', function () use ($id) {
                        var_dump($id);
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
    }
}
