<?php
 /**
  * WordPress contact methods methods
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

/**
 * WordPress contact methods methods
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
class ContactMethods implements RegisterableInterface, UnregistrableInterface
{
    use Registerable, Unregisterable;

    /**
     * {@inheritdoc}
     */
    public function enable()
    {
        foreach ($this->toAdd as $id => $name) {
            add_filter('user_contactmethods', function ($contactMethods) use ($id, $name) {
                $contactMethods[$id] = __($name, THEME_NAMESPACE);
                return $contactMethods;
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function disable()
    {
        foreach ($this->toRemove as $id) {
            add_filter('user_contactmethods', function ($contactMethods) use ($id) {
                unset($contactMethods[$id]);
                return $contactMethods;
            });
        }
    }
}
