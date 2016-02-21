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
  * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
  * @since    1.2.0
  */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\Tools;

/**
 * WordPress sidebars methods
 *
 * PHP version 5
 *
 * @category Registration
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
 * @since    1.2.0
 */
class Sidebars extends Feature
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
     * @see   ThemeSupports::mergeConfigurationWithDefault();
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
     * @see   Feature::$configuration
     * @see   https://developer.wordpress.org/reference/functions/__
     * @see   https://developer.wordpress.org/reference/functions/register_sidebar
     * @since 1.2.0
     */
    protected function registration()
    {
        foreach ($this->addItems as $args) {
            // parsing arguments to add translation for some keys
            foreach ($args as $key => $value) {
                $i18nKeys = ['name', 'description'];
                if (in_array($key, $i18nKeys)) {
                    $args[$key] = __($value, THEME_NAMESPACE);
                }
            }
            // sidebar registration
            $this->registerSidebar($args);
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
