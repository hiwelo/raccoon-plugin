<?php
 /**
  * WordPress taxonomies methods
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
class Taxonomies implements RegisterableInterface
{
    use Registerable;
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
        foreach ($this->toAdd as $taxonomy => $args) {
            // parsing labels value
            if (array_key_exists('labels', $args)) {
                $labels = $args['labels'];

                // keys which required a gettext with translation
                $contextKeys = [
                    'name' => 'taxonomy general name',
                    'singular_name' => 'taxonomy singular name',
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

                $args['labels'] = $labels;
            }

            // parsing label value
            if (array_key_exists('label', $args)) {
                $args['label'] = __($args['label'], THEME_NAMESPACE);
            }

            // replace "true" string value by a real boolean
            $stringBooleans = array_keys($args, "true");
            if ($stringBooleans) {
                foreach ($stringBooleans as $key) {
                    $args[$key] = true;
                }
            }

            // get target object in a specific variable
            if (!isset($args['object'])) {
                return;
            }
            $objects = $args['object'];
            unset($args['object']);

            // custom post type registration
            WPUtils::registerTaxonomy($taxonomy, $objects, $args);
        }
    }
}
