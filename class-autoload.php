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
spl_autoload_register(function ($className) {
    $path = __FILE__;
    $path = explode('/', $path);
    unset($path[count($path) - 1]);
    $path = implode('/', $path);

    $namespace = explode('\\', $className);
    $className = $namespace[count($namespace) - 1];
    unset($namespace[count($namespace) - 1]);
    $namespace = implode('\\', $namespace);

    switch ($namespace) {
        case 'Hiwelo\Raccoon':
            $file = $path . '/lib/' . $className . '.php';
            break;

        case 'Hiwelo\Raccoon\CleanUp':
            $file = $path . '/lib/CleanUp/' . $className . '.php';
            break;

        case 'Hiwelo\Raccoon\Features':
            $file = $path . '/lib/Features/' . $className . '.php';
            break;

        case 'Symfony\Component\Yaml':
            $file = $path . '/vendor/symfony/yaml/' . $className . '.php';
            break;

        case 'Symfony\Component\Yaml\Exception':
            $file = $path . '/vendor/symfony/yaml/Exception/' . $className . '.php';
            break;
    }

    if (file_exists($file)) {
        require_once $file;
    }
}, false, true);
