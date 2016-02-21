<?php
 /**
  * WordPress widgets methods
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
 * WordPress widgets methods
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
class Widgets implements RegisterableInterface
{
    use Registerable;
    /**
     * Registration method
     *
     * @return void
     *
     * @see   Feature::$configuration
     * @see   Feature::registerWidget();
     * @since 1.2.0
     */
    protected function enable()
    {
        foreach ($this->toAdd as $widget) {
            WPUtils::registerWidget($widget);
        }
    }
}
