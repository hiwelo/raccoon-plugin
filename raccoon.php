<?php
/**
 * Raccoon plugin main file
 *
 * PHP version 5
 *
 * @category Core
 * @package  Raccoon-plugin
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 *
 * @wordpress-plugin
 * Plugin Name:     Raccoon WordPress Plugin
 * Plugin URI:      https://github.com/hiwelo/raccoon-plugin
 * Description:     Raccoon is a WordPress environment used to easily create new projects with a json configuration file
 * Version:         0.2.3
 * Author:          Damien Senger
 * Author URI:      https://github.com/hiwelo
 * License:         GPL-3.0
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     raccoon
 * Domain Path:     /languages
 */

namespace Hiwelo\Raccoon;

// if this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// autoload function
function __autoload($className)
{
    $file = './lib/' . $className . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
}

// we call our main plugin class
$raccoon = new Raccoon();
