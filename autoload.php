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
    $file = './lib/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
