<?php
namespace Hiwelo\Raccoon\CleanUp;

/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 20/02/2016
 * Time: 00:03
 */
class Security extends Cleaner
{

    protected function defaultValues()
    {
        return [
            "wlwmanifest_link",
            "rsd_link",
            "index_rel_link",
            "parent_post_rel_link",
            "start_post_rel_link",
            "adjacent_posts_rel_link",
            "feed_links_extra",
            "adjacent_posts_rel_link_wp_head",
            "wp_generator",
            "wp_shortlink_wp_head",
            "no-ftp",
            "login-error"
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
                case 'no-ftp':
                    $constants = get_defined_constants();
                    if (!array_key_exists('FS_METHOD', $constants)) {
                        define('FS_METHOD', 'direct');
                    }
                    break;

                case 'login-error':
                    add_filter('login_errors', function ($defaults) {
                        return null;
                    });
                    break;

                default:
                    remove_action('wp_head', $action);
                    break;
            }
        }
    }
}