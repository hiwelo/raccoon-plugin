<?php
 /**
  * WordPress post status methods
  *
  * PHP version 5
  *
  * @category Registration
  * @package  Raccoon
  * @author   Damien Senger <hi@hiwelo.co>
  * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
  * @link     https://github.com/hiwelo/raccoon-plugin
  * @since    1.2.0
  */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\Tools;

/**
 * WordPress post status methods
 *
 * PHP version 5
 *
 * @category Registration
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.2.0
 */
class PostStatus extends Feature
{
    /**
     * Default values
     *
     * @return array default configuration
     *
     * @since 1.2.0
     */
    protected function defaultValues()
    {
        return [];
    }

    /**
     * Feature constructor
     *
     * @param array $configuration cleanup configuration
     *
     * @see   ThemeSupports::mergeConfigurationWithDefault();
     * @since 1.2.0
     */
    public function __construct($configuration)
    {
        parent::__construct($configuration);
    }

    /**
     * Registration method
     *
     * @global object $post data from the current post in the WordPress loop
     *
     * @return void
     *
     * @see   Feature::$configuration
     * @see   Feature::registerPostStatus();
     * @see   https://developer.wordpress.org/reference/functions/__
     * @see   https://developer.wordpress.org/reference/functions/_n_noop
     * @see   https://developer.wordpress.org/reference/functions/_x
     * @see   https://developer.wordpress.org/reference/functions/add_action
     * @see   https://developer.wordpress.org/reference/functions/get_query_var
     * @see   https://developer.wordpress.org/reference/functions/is_admin
     * @since 1.2.0
     */
    protected function registration()
    {
        // early termination
        if (!$this->addItems) {
            return;
        }

        foreach ($this->addItems as $postStatus => $args) {
            // parsing labels value
            if (array_key_exists('label', $args)) {
                $args['label'] = _x($args['label'], 'post', THEME_NAMESPACE);
            }

            // parsing label-count values
            if (array_key_exists('label_count', $args)) {
                $args['label_count'] = _n_noop(
                    $args['label_count'][0],
                    $args['label_count'][1],
                    THEME_NAMESPACE
                );
            }

            // post status registration
            $this->registerPostStatus($postStatus, $args);

            // if we're in an admin panel, we do some actions after theme setup
            if (is_admin()) {
                // add label status to a post in the admin panel list if this
                // status is in the custom status list
                add_action(
                    'display_post_states',
                    function ($statuses) use ($postStatus, $args) {
                        global $post;

                        if (get_query_var('post_status' !== $postStatus)
                            && $post->post_Status === $postStatus
                        ) {
                            return [__($args['label'], THEME_NAMESPACE)];
                        }
                    }
                );

                // add custom post status to quick edit select box
                add_action(
                    'admin_footer-edit.php',
                    function () use ($postStatus, $args) {
                        echo "
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery('select[name=\"_status\"]').append('" .
                                        "<option value=\"" .
                                        $postStatus .
                                        "\">" .
                                        __($args['label'], THEME_NAMESPACE) .
                                        "</option>" .
                                    "');
                                });
                            </script>
                        ";
                    }
                );

                // add custom post status to edit page select box
                add_action(
                    'admin_footer-post.php',
                    function () use ($postStatus, $args) {
                        global $post;

                        $complete = '';
                        $label = '';

                        if ($post->post_status === $postStatus) {
                            $complete = ' selected="selected"';
                            $label = '<span id="post-status-display"> ' .
                                     __($args['label'], THEME_NAMESPACE) .
                                     '</span>';
                        }

                        echo "
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery('select#post_status').append('" .
                                        "<option value\"" .
                                        $postStatus .
                                        "\" " .
                                        $complete .
                                        ">" .
                                        __($args['label'], THEME_NAMESPACE) .
                                        "</option>');
                                    jQuery('.misc-pub-section label').append('" .
                                        $label .
                                    "');
                                });
                            </script>
                        ";
                    }
                );

                // add custom post status to new page select box
                add_action(
                    'admin_footer-post-new.php',
                    function () use ($postStatus, $args) {
                        global $post;

                        $complete = '';
                        $label = '';

                        if ($post->post_status === $postStatus) {
                            $complete = ' selected="selected"';
                            $label = '<span id="post-status-display"> ' .
                                     __($args['label'], THEME_NAMESPACE) .
                                     '</span>';
                        }

                        echo "
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery('select#post_status').append('" .
                                        "<option value=\"" .
                                        $postStatus .
                                        "\" " .
                                        $complete .
                                        ">" .
                                        __($args['label'], THEME_NAMESPACE) .
                                        "</option>" .
                                    "');
                                    jQuery('.misc-pub-section label').append('" . $label . "');
                                });
                            </script>
                        ";
                    }
                );
            }
        }
    }

    /**
     * Unregistration method
     *
     * @return void
     *
     * @since 1.2.0
     */
    protected function unregistration()
    {
        return;
    }
}
