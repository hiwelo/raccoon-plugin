<?php
 /**
  * WordPress scripts registration methods
  *
  * PHP version 5
  *
  * @category Registration
  * @package  Raccoon
  * @author   Damien Senger <hi@hiwelo.co>
  * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
  * @link     https://github.com/hiwelo/raccoon-plugin
  * @since    1.4.0
  */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\WPUtils;

/**
 * WordPress scripts registration methods
 *
 * PHP version 5
 *
 * @category Registration
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.4.0
 */
class Scripts implements RegisterableInterface
{
    use Registerable;

    /**
     * Scripts registration method
     *
     * @return void
     *
     * @see   WPUtils::enqueueScript()
     * @see   https://developer.wordpress.org/reference/functions/get_template_directory
     * @since 1.4.0
     */
    protected function enable()
    {
        foreach ($this->toAdd as $name => $args) {
            if (is_string($args)) {
                $args = get_template_directory() . $args;
            } else {
                if (empty($args['src'])) {
                    return;
                }
                $args['src'] = get_template_directory() . $args['src'];
            }

            WPUtils::enqueueScript($name, $args);
        }
    }
}
