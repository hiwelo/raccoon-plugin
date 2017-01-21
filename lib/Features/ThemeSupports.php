<?php
 /**
  * WordPress theme supports registration methods
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
 * WordPress theme supports registration methods
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
class ThemeSupports implements RegisterableInterface
{
    use Registerable;
    /**
     * Theme supports registration method
     *
     * @return void
     *
     * @see   ThemeSupports::$configuration
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/remove_meta_box
     * @since 1.2.0
     */
    protected function enable()
    {
        foreach ($this->toAdd as $key => $value) {
            if (gettype(Tools::parseBooleans($value)) == 'boolean' && $value === true) {
                WPUtils::addThemeSupport($key);
            } else {
                WPUtils::addThemeSupport($key, $value);
            }
        }
    }
}
