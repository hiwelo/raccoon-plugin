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
 * @link     https://github.com/hiwelo/raccoon-plugin
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
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */
abstract class Feature
{
    /**
     * Configuration array
     *
     * @var array
     */
    protected $configuration = [];

    /**
     * Items to remove
     *
     * @var array
     */
    protected $removeItems = [];

    /**
     * Items to add
     *
     * @var array
     */
    protected $addItems = [];

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
        $this->addItems = $configuration->getRootItemsWithoutRemove();
        $this->removeItems = $configuration->getArrayValue('remove');
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
        if (empty($this->addItems)) {
            return;
        }

        $this->registration();
    }

    /**
     * Unregistration method
     *
     * @return void
     *
     * @see   Register::configuration();
     * @see   Register::registration();
     * @since 1.2.0
     */
    public function unregister()
    {
        if (empty($this->removeItems)) {
            return;
        }

        $this->unregistration();
    }

    /**
     * WordPress add theme support helper
     *
     * @param string        $feature name for the feature being added
     * @param array|boolean $args    optional arguments
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://codex.wordpress.org/Function_Reference/add_theme_support
     * @since 1.2.0
     */
    protected function addThemeSupport($feature, $args = null)
    {
        if (is_null($args)) {
            add_theme_support($feature);
        } else {
            add_theme_support($feature, $args);
        }
    }

    /**
     * WordPress register_post_type helper
     *
     * @param string $postType post type (max 20 chars)
     * @param array  $args     an array of arguments
     *
     * @return WP_Post registered post type object
     *
     * @see   https://codex.wordpress.org/Function_Reference/register_post_type
     * @since 1.2.0
     */
    protected function registerPostType($postType, $args)
    {
        return register_post_type($postType, $args);
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
     * WordPress register_post_status helper
     *
     * @param string $postStatus name of the post status, max length 20 chars
     * @param array  $args       an array of arguments for this post status
     *
     * @return object
     *
     * @see   https://codex.wordpress.org/Function_Reference/register_post_status
     * @since 1.2.0
     */
    protected function registerPostStatus($postStatus, $args)
    {
        return register_post_status($postStatus, $args);
    }

    /**
     * WordPress register_sidebar helper
     *
     * @param array $args builds sidebar based off of "name" and "id" values
     *
     * @return string sidebar id
     *
     * @see   https://codex.wordpress.org/Function_Reference/register_sidebar
     * @since 1.2.0
     */
    protected function registerSidebar($args)
    {
        return register_sidebar($args);
    }

    /**
     * WordPress register_taxonomy_for_object_type helper
     *
     * @param  string  $taxonomy Taxonomy slug
     * @param  string  $postType Post Type slug
     * @return boolean
     *
     * @see   https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
     * @since 1.2.5
     */
    protected function registerTaxonomyForObjectType($taxonomy, $postType)
    {
        return add_action('init', function () use ($taxonomy, $postType) {
            register_taxonomy_for_object_type($taxonomy, $postType);
        });
    }

    /**
     * WordPress register_widget helper
     *
     * @param string $widgetClass the name of the class that extends WP_Widget
     *
     * @return void
     *
     * @see   https://codex.wordpress.org/Function_Reference/register_widget
     * @since 1.2.0
     */
    protected function registerWidget($widgetClass)
    {
        return register_widget($widgetClass);
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

    /**
     * Default unregistration method
     *
     * @return void
     *
     * @since 1.2.0
     */
    abstract protected function unregistration();
}
