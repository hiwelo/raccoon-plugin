<?php
/**
 * WordPress features methods abstract class
 *
 * PHP version 5
 *
 * @category Registration
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
 * @since    1.0.0
 */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\Tools;

/**
 * WordPress features methods abstract class
 *
 * PHP version 5
 *
 * @category Registration
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
 * @since    1.0.0
 */
abstract class Feature
{
    /**
     * Configuration array
     *
     * @var array
     */
    protected $configuration;

    /**
     * WordPress feature constructor
     *
     * @param array $configuration cleanup configuration
     *
     * @see   Register::defaultValues();
     * @see   Register::mergeConfigurationWithDefault();
     * @since 1.2.0
     */
    public function __construct($configuration)
    {
        $this->configuration = $configuration->manifest;
    }

    /**
     * WordPress feature debug helper
     *
     * @return array
     *
     * @see   Register::$configuration
     * @since 1.2.0
     */
    public function __debugInfo()
    {
        return ['configuration' => $this->configuration];
    }

    /**
     * Registration method
     *
     * @return void
     *
     * @see   Register::configuration();
     * @see   Register::registration();
     * @since 1.2.0
     */
    public function register()
    {
        if (empty($this->configuration)) {
            return;
        }

        $this->registration();
    }

    /**
     * WordPress add theme support helper
     *
     * @param string         $feature name for the feature being added
     * @param array|boolean  $args    optional arguments
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://codex.wordpress.org/Function_Reference/add_theme_support
     * @since 1.2.0
     */
    protected function addThemeSupport($feature, $args = true)
    {
        add_theme_support($feature, $args);
    }

    /**
     * WordPress register_nav_menu helper
     *
     * @param string $location    menu location identifier, like a slug
     * @param string $description menu description - for identifying the menu
     *                            in the dashboard
     *
     * @return void
     *
     * @see   https://codex.wordpress.org/Function_Reference/register_nav_menu
     * @since 1.2.0
     */
    protected function registerNavigation($location, $description)
    {
        register_nav_menu($location, $description);
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
     * Default registration method
     *
     * @return void
     *
     * @since 1.2.0
     */
    abstract protected function registration();
}
