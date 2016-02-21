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
class ThemeSupports extends Feature
{
    /**
     * Theme supports default values
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
     * Theme supports registration constructor
     *
     * @param array $configuration cleanup configuration
     *
     * @see   ThemeSupports::mergeConfigurationWithDefault();
     * @since 1.2.0
     */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

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
    protected function registration()
    {
        foreach ($this->addItems as $key => $value) {
            switch (gettype(Tools::parseBooleans($value))) {
                case 'boolean':
                    if ($value === true) {
                        $this->addThemeSupport($key);
                    }
                    break;

                default:
                    $this->addThemeSupport($key, $value);
                    break;
            }
        }
    }

    /**
     * Unregistration method
     *
     * @return void
     *
     * @since 1.2.0
     */
    protected function unregistration()
    {
        return;
    }
}
