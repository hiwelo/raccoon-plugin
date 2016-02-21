<?php
/**
 * Autoload plugin file
 *
 * PHP version 5
 *
 * @category Core
 * @package  Raccoon-plugin
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 */
/**
 * Plugin class autoloading function
 *
 * @param  string $className class name
 * @return void
 */
function __autoload($className)
{
    $path = __FILE__;
    $path = explode('/', $path);
    unset($path[count($path) - 1]);
    $path = implode('/', $path);

    $className = explode('\\', $className);
    $className = $className[count($className) - 1];

    $file = $path . '/lib/' . $className . '.php';
    $file2 = $path . '/vendor/symfony/yaml/' . $className . '.php';
    $file3 = $path . '/vendor/symfony/yaml/Exception/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    } elseif (file_exists($file2)) {
        require_once $file2;
    } elseif (file_exists($file3)) {
        require_once $file3;
    }
}
