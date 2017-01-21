<?php
 /**
  * WordPress thumbnail management methods
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
class Thumbnails implements RegisterableInterface
{
    use Registerable;

    /**
     * Registration method
     *
     * @return void
     *
     * @since 1.3.0
     */
    protected function enable()
    {
        foreach ($this->toAdd as $sizeName => $sizeArgs) {
            WPUtils::addImageSize(
                $sizeName,
                $sizeArgs['width'],
                $sizeArgs['height'],
                $sizeArgs['crop'],
            );
        }
    }
}
