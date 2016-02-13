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
 * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
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
 * @link     ./docs/api/classes/Hwlo.Raccoon.Core.html
 */
class ProductionFeatures
{
    /**
      * Features configuration from the manifest
      * @var array
      */
    private $features = [];

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
      * @link https://codex.wordpress.org/Function_Reference/get_template_directory
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
    }
}
