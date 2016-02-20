<?php
/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 20/02/2016
 * Time: 14:32
 */

namespace Hiwelo\Raccoon;


class Manifest
{

    /**
     * @return Manifest
     * @throws LogicException
     */
    public static function load()
    {
        $file =
            self::fileContent(
                self::filePath(
                    self::getValueOrDefault(get_defined_constants(), 'RACCOON_MANIFEST_FILE', 'manifest.json')
        ));

        if (empty($file)) throw \LogicException('manifest file doesn\'t exists or is empty !');
        return new self($file);
    }

    public static function filePath($fileName)
    {
        return get_template_directory() . '/' . $fileName;
    }

    public static function fileContent($filePath)
    {
        if (!file_exists($filePath)) return '';
        return file_get_contents($filePath);
    }

    public function __construct($manifest)
    {
        $this->manifest = $manifest;
    }

    public function getValue($key, $default = null)
    {
        return self::getValueOrDefault($this->manifest, $key, $default);
    }

    public function getValueOrFalse($key)
    {
        return $this->$this->getValue($key, false);
    }

    /**
     * @param $key
     * @return Manifest
     */
    public function getChildrenOf($key)
    {
        return new self($this->getArrayValue($key));
    }

    public function getArrayValue($key)
    {
        return $this->getValue($key, []);
    }

    public function getArrayValueWithoutRemove($key)
    {
        $a = $this->getArrayValue($key);
        unset($a['remove']);
        return $a;
    }

    public function exists($key)
    {
        return array_key_exists($key, $this->manifest);
    }

    public function existsAndNotEmpty($key)
    {
        return array_key_exists($key, $this->manifest) && !empty($this->manifest[$key]);
    }

    protected static function getValueOrDefault($array, $key, $default = null)
    {
        if(array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $default;
    }
}