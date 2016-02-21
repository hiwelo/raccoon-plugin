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
  * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
  * @since    1.2.0
  */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\Tools;

/**
 * WordPress widgets methods
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
class Widgets extends Feature
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
     * @see   Feature::$configuration
     * @see   Feature::registerWidget();
     * @since 1.2.0
     */
    protected function registration()
    {
        foreach ($this->addItems as $widget) {
            $this->registerWidget($widget);
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
