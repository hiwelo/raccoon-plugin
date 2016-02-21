<?php
/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 21/02/2016
 * Time: 16:43
 */

namespace Hiwelo\Raccoon;


class WPUtils
{

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
    public static function addThemeSupport($feature, $args)
    {
        add_theme_support($feature, $args);
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