<?php
namespace Hiwelo\Raccoon\CleanUp;
use Hiwelo\Raccoon\CleanUp;

/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 20/02/2016
 * Time: 00:03
 */
class Head extends Cleaner
{

    protected function defaultValues()
    {
        return [
            "remove-adminbar-css",
            "emoji-css",
        ];
    }

    public function __construct(array $configuration)
    {
        parent::__construct($configuration);
    }

    protected function cleaning()
    {
        foreach ($this->configuration as $action) {
            switch ($action) {
                case 'remove-adminbar-css':
                    add_theme_support('admin-bar', ['callback' => '__return_false']);
                    break;

                case 'emoji-css':
                    remove_action('admin_print_styles', 'print_emoji_styles');
                    remove_action('wp_head', 'print_emoji_detection_script', 7);
                    remove_action('admin_print_scripts', 'print_emoji_detection_script');
                    remove_action('wp_print_styles', 'print_emoji_styles');
                    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
                    remove_filter('the_content_feed', 'wp_staticize_emoji');
                    remove_filter('comment_text_rss', 'wp_staticize_emoji');
                    break;
            }
        }    }
}