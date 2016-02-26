<?php
/**
 * Raccoon plugin core methods
 *
 * PHP version 5
 *
 * @category Core
 * @package  Raccoon-plugin
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */
namespace Hiwelo\Raccoon;

use Hiwelo\Raccoon\Features\ContactMethods;
use Hiwelo\Raccoon\Features\Navigations;
use Hiwelo\Raccoon\Features\PostStatus;
use Hiwelo\Raccoon\Features\PostTypes;
use Hiwelo\Raccoon\Features\RaccoonFeatures;
use Hiwelo\Raccoon\Features\Sidebars;
use Hiwelo\Raccoon\Features\ThemeSupports;
use Hiwelo\Raccoon\Features\Widgets;

/**
 * Raccoon plugin core methods
 *
 * PHP version 5
 *
 * @category Core
 * @package  Raccoon-plugin
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */
class Raccoon
{
    /**
     * Environment status (development, staging, production)
     *
     * @var   string
     */
    public $environment = 'production';

    /**
     * Manifest informations for this theme, contain all theme configuration
     *
     * @var Manifest
     */
    public $manifest = null;

    /**
     * Setup this theme with all informations available in the manifest, a JSON
     * configuration file
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        // load and parse manifest file
        $this->manifest = Manifest::load();

        // early termination
        if (is_null($this->manifest)) {
            return;
        }

        // load environment status
        $this->loadEnvironmentStatus();
        // load namespace if a specific one is specified
        $this->loadNamespace();
        // load internationalization if exists
        $this->i18nReady();

        // load all features or tasks asked
        (new ContactMethods($this->manifest->getChildrenOf('contact-methods')))->register();
        (new Navigations($this->manifest->getChildrenOf('navigations')))->register();
        (new PostStatus($this->manifest->getChildrenOf('post-status')))->register();
        (new PostTypes($this->manifest->getChildrenOf('post-types')))->register();
        (new RaccoonFeatures($this->manifest->getChildrenOf('theme-features')))->register();
        (new Sidebars($this->manifest->getChildrenOf('sidebars')))->register();
        (new ThemeSupports($this->manifest->getChildrenOf('theme-support')))->register();
        (new Widgets($this->manifest->getChildrenOf('widgets')))->register();

        // remove asked features or items
        (new ContactMethods($this->manifest->getChildrenOf('contact-methods')))->unregister();
        (new PostTypes($this->manifest->getChildrenOf('post-types')))->unregister();
    }

    /**
     * Returned array used for object debug informations
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function __debugInfo()
    {
        return [
            'manifest' => $this->manifest,
        ];
    }

    /**
     * Load the namespace specific information from the manifest
     *
     * @global string  $namespace namespace for this WordPress template
     *
     * @return void
     *
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     */
    private function loadNamespace()
    {
        if (!$this->manifest->existsAndNotEmpty('namespace')) {
            $manifestNamespace = 'raccoon';
        } else {
            $manifestNamespace = $this->manifest->getValue('namespace');
        }

        // define a PHP constant for the namespace
        define('THEME_NAMESPACE', $manifestNamespace);

        // define a WordPress global variable for the namespace
        global $namespace;
        $namespace = $manifestNamespace;
    }

    /**
     * Search environment status if avanlable and apply specific methods
     *   1. first in $_ENV
     *   2. if non available, in manifest.json (environment-status and env-status)
     *   3. if non available, environment status is set at `production`
     *
     * @global array $_ENV Environment variables
     *
     * @return void
     *
     * @since 1.0.0
     * @uses  Raccoon::$environment
     * @uses  Raccoon::$manifest
     */
    private function loadEnvironmentStatus()
    {
        $this->environment = $this->extractEnvironmentStatus();

        switch ($this->environment) {
            case "development":
                // add here some development features
                break;
            case "production":
                new ProductionFeatures($this->manifest['production']);
                break;
        }
    }

    /**
     * Extract environment status from the manifest or from the environment variables
     *
     * @return string environment status
     *
     * @see   Manifest::getValue();
     * @since 1.2.0
     */
    private function extractEnvironmentStatus()
    {
        if (array_key_exists('WP_ENV', $_ENV)) {
            return $_ENV['WP_ENV'];
        }

        return $this->manifest->getValue(
            'environment-status',
            $this->manifest->getValue('env-status')
        );
    }

    /**
     * Theme translation activation (with .po & .mo files)
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/get_template_directory
     * @link  https://developer.wordpress.org/reference/functions/load_theme_textdomain
     * @since 1.0.0
     * @uses  Raccoon::$manifest
     * @uses  Raccoon::$namespace
     */
    private function i18nReady()
    {

        $i18nDirectory = get_template_directory().$this->manifest->getValue('languages-directory', '/languages');
        load_theme_textdomain(THEME_NAMESPACE, $i18nDirectory);
    }
}
