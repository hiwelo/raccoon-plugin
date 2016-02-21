<?php
 /**
  * WordPress post types methods
  *
  * PHP version 5
  *
  * @category Registration
  * @package  Raccoon
  * @author   Damien Senger <hi@hiwelo.co>
  * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
  * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
  * @since    1.2.0
  */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\Tools;

/**
 * WordPress post types methods
 *
 * PHP version 5
 *
 * @category Registration
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
 * @since    1.2.0
 */
class PostTypes extends Feature
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
     * @see   Feature::$addItems
     * @see   Feature::registerPostType();
     * @see   https://developer.wordpress.org/reference/functions/__
     * @see   https://developer.wordpress.org/reference/functions/_x
     * @since 1.2.0
     */
    protected function registration()
    {
        foreach ($this->addItems as $postType => $args) {
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
                            THEME_NAMESPACE
                        );
                    } else {
                        $labels[$key] = __($value, THEME_NAMESPACE);
                    }
                }
            }
            // parsing label value
            if (array_key_exists('label', $args)) {
                $args['label'] = __($args['label'], THEME_NAMESPACE);
            }
            // parsing description value
            if (array_key_exists('description', $args)) {
                $args['description'] = __($args['description'], THEME_NAMESPACE);
            }
            // replace "true" string value by a real boolean
            $stringBooleans = array_keys($args, "true");
            if ($stringBooleans) {
                foreach ($stringBooleans as $key) {
                    $args[$key] = true;
                }
            }
            // custom post type registration
            $this->registerPostType($postType, $args);
        }
    }

    /**
     * Unregistration method
     *
     * @global array $wp_post_types registered post types informations
     *
     * @return void
     *
     * @see   Feature::$removeItems
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/remove_menu_page
     * @since 1.2.0
     */
    protected function unregistration()
    {
        // get all register post types
        global $wp_post_types;

        foreach ($this->removeItems as $postType) {
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
