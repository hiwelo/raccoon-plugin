<?php
 /**
  * WordPress admin mess cleanup methods
  *
  * PHP version 5
  *
  * @category CleanUp
  * @package  Raccoon
  * @author   Damien Senger <hi@hiwelo.co>
  * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
  * @link     https://github.com/hiwelo/raccoon-plugin
  * @since    1.2.0
  */

namespace Hiwelo\Raccoon\CleanUp;

/**
 * WordPress admin mess cleanup methods
 *
 * PHP version 5
 *
 * @category CleanUp
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.2.0
 */
class Admin extends Cleaner
{
    /**
     * Admin cleanup default values
     *
     * @return array default configuration
     *
     * @since 1.2.0
     */
    protected function defaultValues()
    {
        return ["metaboxes" => [
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

    /**
     * WordPress admin mess CleanUp constructor
     *
     * @param array $configuration cleanup configuration
     *
     * @see   Admin::mergeConfigurationWithDefault();
     * @since 1.2.0
     */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

    /**
     * Admin mess cleaning method
     *
     * @return void
     *
     * @see   Admin::$configuration
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/remove_meta_box
     * @since 1.2.0
     */
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
