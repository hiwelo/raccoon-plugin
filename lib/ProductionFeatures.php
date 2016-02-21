<?php
/**
 * WordPress production features management methods
 *
 * PHP version 5
 *
 * @category ProductionFeatures
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */
namespace Hiwelo\Raccoon;

/**
 * WordPress production features management methods
 *
 * PHP version 5
 *
 * @category ProductionFeatures
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */
class ProductionFeatures
{
    /**
      * Features configuration from the manifest
      *
      * @var array
      */
    private $features = [];

    /**
     * Features default configuration
     *
     * @var array
     */
    public $default = [
        "admin-menu" => [],
    ];

    /**
      * WordPress production features management class constructor,
      * check for configuration or informations in the manifest
      *
      * @param array $configuration features configuration
      * @return void
      *
      * @link  https://codex.wordpress.org/Function_Reference/get_template_directory
      * @since 1.0.0
      * @uses  ProductionFeatures::addIntoAdminMenu()
      * @uses  ProductionFeatures::removeFromAdminMenu()
      * @uses  Tools::parseBooleans()
      */
    public function __construct($configuration = [])
    {
        // load manifest with an empty configuration
        if (count($configuration) === 0) {
            $file = get_template_directory() . '/' . $file;

            // verify if file exists
            if (!file_exists($file)) {
                return false;
            }

            $file = file_get_contents($file);
            $manifest = json_decode($file, true);

            if (array_key_exists('production', $manifest)) {
                $configuration = $manifest['production'];
            }
        }

        if (is_array($configuration)) {
            $this->features = array_merge($this->default, $configuration);
        } else {
            Tools::parseBooleans($configuration);
            if ($configuration) {
                $this->features = $this->default;
            }
        }

        // add elements into admin menu
        // $this->addIntoAdminMenu();

        // remove elements from admin menu
        $this->removeFromAdminMenu();
    }

    /**
     * Remove elements from admin menu bar
     *
     * @return void
     *
     * @link  https://developer.wordpress.org/reference/functions/add_action
     * @link  https://developer.wordpress.org/reference/functions/remove_menu_page
     * @link  https://developer.wordpress.org/reference/functions/remove_submenu_page
     * @since 1.0.0
     * @uses  ProductionFeatures::$features
     */
    private function removeFromAdminMenu()
    {
        if (array_key_exists('admin-menu', $this->features)
            && array_key_exists('remove', $this->features['admin-menu'])
        ) {
            $items = $this->features['admin-menu']['remove'];

            foreach ($items as $item) {
                if (is_string($item)) {
                    add_action('admin_menu', function () use ($item) {
                        remove_menu_page($item);
                    });
                } elseif (is_array($item)) {
                    $menu = array_keys($item)[0];
                    foreach ($item as $submenus) {
                        foreach ($submenus as $submenu) {
                            add_action('admin_menu', function () use ($menu, $submenu) {
                                remove_submenu_page($menu, $submenu);
                            });
                        }
                    }
                }
            }
        }
    }
}
