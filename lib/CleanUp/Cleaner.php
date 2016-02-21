<?php
/**
 * WordPress cleanup methods abstract class
 *
 * PHP version 5
 *
 * @category CleanUp
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */

namespace Hiwelo\Raccoon\CleanUp;

use Hiwelo\Raccoon\Tools;

/**
 * WordPress cleanup methods abstract class
 *
 * PHP version 5
 *
 * @category CleanUp
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */
abstract class Cleaner
{
    /**
     * Configuration array
     *
     * @var array
     */
    protected $configuration;

    /**
     * WordPress cleanup constructor
     *
     * @param array $configuration cleanup configuration
     *
     * @see   Cleaner::defaultValues();
     * @see   Cleaner::mergeConfigurationWithDefault();
     * @since 1.2.0
     */
    public function __construct($configuration)
    {
        $this->configuration = $this->mergeConfigurationWithDefault($configuration, $this->defaultValues());
    }

    /**
     * Cleaning method
     *
     * @return void
     *
     * @see   Cleaner::cleaning();
     * @see   Cleaner::configuration();
     * @since 1.2.0
     */
    public function clean()
    {
        if (empty($this->configuration)) {
            return;
        }

        $this->cleaning();
    }

    /**
     * Merge asked configuration with default configuration
     *
     * @param array $configuration asked configuration from the manifest
     * @param array $default       default configuration
     *
     * @return array merged configuration
     *
     * @see   Tools::parseBooleans();
     * @since 1.2.0
     */
    protected function mergeConfigurationWithDefault($configuration, $default)
    {
        if (is_array($configuration)) {
            return  array_merge($default, $configuration);
        } elseif (Tools::parseBooleans($configuration)) {
            return $default;
        }
    }

    /**
     * Default values method
     *
     * @return void
     *
     * @since 1.2.0
     */
    abstract protected function defaultValues();

    /**
     * Default cleaning method
     *
     * @return void
     *
     * @since 1.2.0
     */
    abstract protected function cleaning();
}
