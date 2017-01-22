<?php
namespace Hiwelo\Raccoon;

class WPUtils
{
    /**
     * WordPress add_image_size() helper
     *
     * @param  string        $name   Image size identifier
     * @param  integer       $width  Image width in pixels
     * @param  integer       $height Image height in pixels
     * @param  boolean|array $crop   Whether to crop images to specified width and height or resize
     * @return void
     * @see    https://developer.wordpress.org/reference/functions/add_image_size/
     * @since  1.2.0
     */
    public static function addImageSize($name, $width, $height, $crop = false)
    {
        add_image_size($name, $width, $height, $crop);
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
    public static function addThemeSupport($feature, $args = null)
    {
        if ($args === null) {
            add_theme_support($feature);
        } else {
            add_theme_support($feature, $args);
        }
    }

    /**
     * WordPress stylesheets registration helper
     *
     * @param string       $name Stylesheet name
     * @param string|array $args Stylesheet arguments
     *
     * @return void
     *
     * @see   https://developer.wordpress.org/reference/functions/wp_enqueue_style
     * @since 1.4.0
     */
    public static function enqueueStyle($name, $args)
    {
        if (is_string($args)) {
            add_action('wp_enqueue_scripts', function () use ($name, $args) {
                wp_enqueue_style($name, $args);
            });
        } else {
            if (empty($args['src'])) {
                return;
            }

            if (empty($args['deps'])) {
                $args['deps'] = [];
            }

            if (empty($args['ver'])) {
                $args['ver'] = false;
            }

            if (empty($args['media'])) {
                $args['media'] = 'all';
            }

            add_action('wp_enqueue_scripts', function () use ($name, $args) {
                wp_enqueue_style($name, $args['src'], $args['deps'], $args['ver'], $args['media']);
            });
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
    public static function registerPostType($postType, $args)
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
    public static function registerNavigation($location, $description)
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
    public static function registerPostStatus($postStatus, $args)
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
    public static function registerSidebar($args)
    {
        return register_sidebar($args);
    }

    /**
     * WordPress register_taxonomy helper
     *
     * @param string       $taxonomy name of the taxonomy, max length 20 chars
     * @param string|array $object   targeted object for this taxonomy
     * @param array        $args     an array of arguments for this taxonomy
     *
     * @return string taxonomy id
     *
     * @see   https://codex.wordpress.org/Function_Reference/register_taxonomy
     * @since 1.2.0
     */
    public static function registerTaxonomy($taxonomy, $object, $args = [])
    {
        return register_taxonomy($taxonomy, $object, $args);
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
    public static function registerWidget($widgetClass)
    {
        return register_widget($widgetClass);
    }
}
