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
  * @link     https://github.com/hiwelo/raccoon-plugin
  * @since    1.2.0
  */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\Tools;
use Hiwelo\Raccoon\WPUtils;

/**
 * WordPress post types methods
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
class PostTypes implements RegisterableInterface, UnregistrableInterface
{
    use Registerable, Unregisterable;
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
    protected function enable()
    {
        foreach ($this->toAdd as $postType => $args) {
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
            WPUtils::registerPostType($postType, $args);

            // register a new nav panel for this custom post type

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
    protected function disable()
    {
        // get all register post types
        global $wp_post_types;

        foreach ($this->toRemove as $postType) {
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
