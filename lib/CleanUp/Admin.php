<?php
namespace Hiwelo\Raccoon\CleanUp;

/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 20/02/2016
 * Time: 00:03
 */
class Admin extends Cleaner
{

    protected function defaultValues()
    {
        return  ["metaboxes" => [
            "dashboard_incoming_links",
            "dashboard_quick_press",
            "dashboard_plugins",
            "dashboard_recent_drafts",
            "dashboard_recent_comments",
            "dashboard_primary",
            "dashboard_secondary",
            "dashboard_activity",
        ]];
    }

    public function __construct(array $configuration)
    {
        $this->configuration = $this->mergeConfigurationWithDefault($configuration, $this->defaultValues());
    }

    protected function cleaning()
    {
        if (array_key_exists('metaboxes', $this->configuration)
            && is_array($this->configuration['metaboxes'])
            && count($this->configuration['metaboxes'])
        ) {
            foreach ($this->configuration['metaboxes'] as $metabox) {
                add_action('admin_menu', function () use ($metabox) {
                    // remove comment status
                    remove_meta_box($metabox, 'dashboard', 'core');
                });
            }
        }
    }

}