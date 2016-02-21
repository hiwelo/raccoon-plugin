<?php
/**
 * WordPress cleanup methods abstract class
 *
 * PHP version 5
 *
 * @category CleanUp
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */

namespace Hiwelo\Raccoon\CleanUp;

use Hiwelo\Raccoon\Manifest;
use Hiwelo\Raccoon\Tools;

/**
 * WordPress cleanup methods abstract class
 *
 * PHP version 5
 *
 * @category CleanUp
 * @package  Raccoon
 * @author   Damien Senger <hi@hiwelo.co>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3.0
 * @link     https://github.com/hiwelo/raccoon-plugin
 * @since    1.0.0
 */
abstract class Cleaner
{
    /**
     * Cleaning method
     *
     * @return void
     *
     * @see   Cleaner::cleaning();
     * @see   Cleaner::configuration();
     * @since 1.2.0
     */
    public function clean(Manifest $manifest)
    {
        if ($manifest->isEmpty()) {
            return;
        }

        $this->cleaning(
            $manifest->getChildrenMergedWithDefaultValueOf(
                $this->manifestKey(), $this->defaultValues()
            )
        );
    }

    /**
     * Default values method
     *
     * @return void
     *
     * @since 1.2.0
     */
    abstract protected function defaultValues();

    /**
     *
     */
    abstract protected function manifestKey();

    /**
     * Default cleaning method
     *
     * @return void
     *
     * @since 1.2.0
     */
    abstract protected function cleaning(Manifest $manifest);
}
