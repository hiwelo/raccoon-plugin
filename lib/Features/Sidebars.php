<?php
 /**
  * WordPress sidebars methods
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
 * WordPress sidebars methods
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
class Sidebars implements RegisterableInterface
{
    use Registerable;
    /**
     * Registration method
     *
     * @return void
     *
     * @see   Feature::$configuration
     * @see   https://developer.wordpress.org/reference/functions/__
     * @see   https://developer.wordpress.org/reference/functions/register_sidebar
     * @since 1.2.0
     */
    protected function enable()
    {
        foreach ($this->toAdd as $args) {
            // parsing arguments to add translation for some keys
            foreach ($args as $key => $value) {
                $i18nKeys = ['name', 'description'];
                if (in_array($key, $i18nKeys)) {
                    $args[$key] = __($value, THEME_NAMESPACE);
                }
            }
            // sidebar registration
            WPUtils::registerSidebar($args);
        }
    }

}
