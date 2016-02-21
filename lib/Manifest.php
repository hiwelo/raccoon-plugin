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
 * @link     ./docs/api/classes/Hiwelo.Raccoon.Core.html
 * @since    1.0.0
 */
namespace Hiwelo\Raccoon;

use SplFileInfo;
use Symfony\Component\Yaml\Parser as Parser;

/**
 * Raccoon plugin core methods
 *
 * PHP version 5
 *
 * @category Core
 * @package  Raccoon-plugin
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     ./docs/api/classes/Hiwelo.Raccoon.Raccoon.html
 * @since    1.0.0
 */
class Manifest
{
    /**
     * Load a manifest and create a Manifest object
     *
     * @return Manifest
     * @throws LogicException
     *
     * @see    Manifest
     * @see    Manifest::fileContent();
     * @see    Manifest::filePath();
     * @see    Manifest::getValueOrDefault();
     * @see    Manifest::parseManifest();
     * @see    https://secure.php.net/manual/fr/splfileinfo.getextension.php
     * @since  1.2.0
     * @static
     */
    public static function load()
    {
        $file = self::filePath(
            self::getValueOrDefault(
                get_defined_constants(),
                'RACCOON_MANIFEST_FILE',
                'manifest.json'
            )
        );

        // get manifest file mime type
        $fileinfo = new SplFileInfo($file);
        // get manifest content
        $manifest = self::fileContent($file);
        $manifest = self::parseManifest($manifest, $fileinfo);

        if (empty($manifest)) {
            throw \LogicException('manifest file doesn\'t exists or is empty !');
        }
        return new self($manifest);
    }

    /**
     * Get file path for a manifest
     *
     * @param string $fileName manifest filename
     *
     * @return string manifest file path
     *
     * @see    https://developer.wordpress.org/reference/functions/get_template_directory
     * @since  1.2.0
     * @static
     */
    public static function filePath($fileName)
    {
        return get_template_directory() . '/' . $fileName;
    }

    /**
     * Get file contents helper
     *
     * @param string $filePath filepath
     *
     * @return string file content
     *
     * @since  1.2.0
     * @static
     */
    public static function fileContent($filePath)
    {
        if (!file_exists($filePath)) {
            return '';
        }
        return file_get_contents($filePath);
    }

    /**
     * Parse manifest data with the correct parser
     *
     * @param string      $manifest manifest file content
     * @param SplFileInfo $fileinfo SplFileInfo object with all file informations
     *
     * @return array Parsed manifest
     *
     * @see    Symfony\Component\Yaml\Parser::parse();
     * @see    https://php.net/manual/splfileinfo.getextension.php
     * @since  1.2.0
     * @static
     */
    public static function parseManifest($manifest, $fileinfo)
    {
        switch ($fileinfo->getExtension()) {
            case 'json':
                return json_decode($manifest, true);
                break;

            case 'yaml':
                $yaml = new Parser();
                return $yaml->parse($manifest);
                break;
        }
    }

    /**
     * Manifest class constructor
     *
     * @param array $manifest manifest informations
     *
     * @return void
     *
     * @see   Manifest::$manifest
     * @since 1.2.0
     */
    public function __construct($manifest)
    {
        $this->manifest = $manifest;
    }

    /**
     * Get a manifest value for a specific key
     *
     * @param string $key     searched key
     * @param string $default default value if key doesn't exists
     *
     * @return string|array searched value
     *
     * @see   Manifest::getValueOrDefault();
     * @since 1.2.0
     */
    public function getValue($key, $default = null)
    {
        return self::getValueOrDefault($this->manifest, $key, $default);
    }

    /**
     * Get Value or a false boolean
     *
     * @param string $key searched value
     *
     * @return string|array|boolean value or boolean
     *
     * @see   Manifest::getValue();
     * @since 1.2.0
     */
    public function getValueOrFalse($key)
    {
        return $this->getValue($key, false);
    }

    /**
     * Get a children information from the manifest
     *
     * @param string $key manifest searched key
     *
     * @return Manifest
     *
     * @see   Manifest
     * @see   Manifest::getArrayValue();
     * @since 1.2.0
     */
    public function getChildrenOf($key)
    {
        return new self($this->getArrayValue($key));
    }

    /**
     * Get an array value from the manifest without default value
     *
     * @param string $key manifest searched data
     *
     * @return array searched array
     *
     * @see   Manifest::getValue();
     * @since 1.2.0
     */
    public function getArrayValue($key)
    {
        return $this->getValue($key, []);
    }

    /**
     * Get an array value from the manifest without 'remove' key
     *
     * @param  array $key manifest's array
     *
     * @return array manifest's array without 'remove' key
     *
     * @see   Manifest::getArrayValue();
     * @since 1.2.0
     */
    public function getArrayValueWithoutRemove($key)
    {
        $a = $this->getArrayValue($key);
        unset($a['remove']);
        return $a;
    }

    /**
     * Get an array of all root object items without 'remove' key
     *
     * @return array manifest's array without 'remove' key
     *
     * @see   Manifest::$manifest
     * @since 1.2.0
     */
    public function getRootItemsWithoutRemove()
    {
        $rootItems = $this->manifest;
        unset($rootItems['remove']);
        return $rootItems;
    }

    /**
     * Check if a key exist in the manifest
     *
     * @param string $key searched key
     *
     * @return boolean
     *
     * @see   Manifest::$manifest
     * @since 1.2.0
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->manifest);
    }

    /**
     * Check if a key exist in the manifest and if it's not empty
     *
     * @param string $key manifest key
     *
     * @return boolean
     *
     * @see   Manifest::$manifest
     * @since 1.2.0
     */
    public function existsAndNotEmpty($key)
    {
        return array_key_exists($key, $this->manifest) && !empty($this->manifest[$key]);
    }

    /**
     * Get value from an array or use default information
     *
     * @param  array        $array   array where to pick information
     * @param  string|array $key     searched information
     * @param  string|array $default default information
     *
     * @return string|array searched information
     *
     * @since  1.2.0
     * @static
     */
    protected static function getValueOrDefault($array, $key, $default = null)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $default;
    }
}
