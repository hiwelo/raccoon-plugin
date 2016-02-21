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
 * Version:         1.1.2
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

// require class autoloading
require_once 'class-autoload.php';

// we call our main plugin class
add_action('after_setup_theme', function () {
    $raccoon = new Raccoon();
});
