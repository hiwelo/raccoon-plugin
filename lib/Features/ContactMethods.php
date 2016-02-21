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

use Hiwelo\Raccoon\Tools;

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
class ContactMethods extends Feature
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
     * @see   https://codex.wordpress.org/Function_Reference/add_filter
     * @since 1.2.0
     */
    protected function registration()
    {
        foreach ($this->addItems as $id => $name) {
            add_filter('user_contactmethods', function ($contactMethods) use ($id, $name) {
                $contactMethods[$id] = __($name, THEME_NAMESPACE);
                return $contactMethods;
            });
        }
    }

    /**
     * Unregistration method
     *
     * @return void
     *
     * @see   Feature::$removeItems
     * @see   https://codex.wordpress.org/Function_Reference/add_filter
     * @since 1.2.0
     */
    protected function unregistration()
    {
        foreach ($this->removeItems as $id) {
            add_filter('user_contactmethods', function ($contactMethods) use ($id) {
                unset($contactMethods[$id]);
                return $contactMethods;
            });
        }
    }
}
