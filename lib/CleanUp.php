<?php
/**
 * WordPress theme mess cleanup methods
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
namespace Hiwelo\Raccoon;

use Hiwelo\Raccoon\CleanUp\Admin;
use Hiwelo\Raccoon\CleanUp\Head;
use Hiwelo\Raccoon\CleanUp\Security;

/**
 * WordPress theme mess cleanup methods
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
class CleanUp
{
    /**
      * Clean up class constructor, check for configuration or informations
      * in the manifest
      *
      * @param array $configuration cleanUp configuration
      * @return void
      *
      * @link  https://codex.wordpress.org/Function_Reference/get_template_directory
      * @since 1.0.0
      * @uses  CleanUp::adminCleanUp()
      * @uses  CleanUp::defaultThemesCleanUp()
      * @uses  CleanUp::securityCleanUp()
      * @uses  CleanUp::wpheadCleanUp()
      * @uses  Tools::parseBooleans()
      */
    public function __construct($configuration = [])
    {
        $configuration = Manifest::load()
            ->getChildrenOf('theme-features')
            ->getChildrenMergedWithDefaultValueOf('cleanup', [
                'admin' => true,
                'wp_head' => true,
                'security' => true,
            ]);

        (new Admin())->clean($configuration);
        (new Head())->clean($configuration);
        (new Security())->clean($configuration);

        // we call default theme clean up parts, if asked in the manifest
        if (array_key_exists('themes', $this->cleanUp) && $this->cleanUp['themes']) {
            $this->defaultThemesCleanUp();
        }
    }

    /**
     * Merge manifest configuration with default configuration if necessary
     *
     * @param array $configuration manifest configuration
     * @param array $default       default configuration
     *
     * @return array merged configuration
     *
     * @see   Tools::parseBooleans();
     * @since 1.2.0
     */
    public function mergeConfigurationWithDefault($configuration, $default)
    {
        if (is_array($configuration)) {
            return array_merge($default, $configuration);
        } elseif (Tools::parseBooleans($configuration)) {
            return $default;
        }
    }

    /**
      * Remove default WordPress theme from admin panel lists
      *
      * @global array $wp_theme_directories List all themes directories
      *
      * @return void
      *
      * @link  https://developer.wordpress.org/reference/classes/wp_theme
      * @link  https://developer.wordpress.org/reference/functions/add_action
      * @link  https://developer.wordpress.org/reference/functions/wp_get_themes
      * @since 1.0.0
      */
    public function defaultThemesCleanUp()
    {
        // if WordPress have multiple theme directories and one looks like the
        // Bedrock theme directory, we unset all different directories
        global $wp_theme_directories;

        if (count($wp_theme_directories) > 1) {
            // we check if we have an app/ dir
            $path_end_part = substr(ABSPATH, -4, 4);

            if ($path_end_part === '/wp/') {
                $bedrock_theme_dir = substr(ABSPATH, 0, -4) . '/app/themes';
            }

            if (in_array($bedrock_theme_dir, $wp_theme_directories)) {
                foreach ($wp_theme_directories as $key => $theme_dir) {
                    if ($theme_dir !== $bedrock_theme_dir) {
                        unset($wp_theme_directories[$key]);
                    }
                }
            }
        }

        $themes = wp_get_themes();

        foreach ($themes as $slug => $theme) {
            $author = $theme->get('Author');

            if ($author === 'the WordPress team') {
                unset($themes[$slug]);
            }
        }

        // remove element from the dashboard activity widget
        add_action('admin_footer-index.php', function () {
            echo "
                <script>
                    jQuery(document).ready(function () {
                        jQuery('p.hide-if-no-customize').remove();
                    });
                </script>
            ";
        });
    }
}
