<?php
/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 20/02/2016
 * Time: 00:31
 */

namespace Hiwelo\Raccoon\CleanUp;


abstract class Cleaner
{
    protected $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $this->mergeConfigurationWithDefault($configuration, $this->defaultValues());
    }

    public function clean(){
        if (empty($this->configuration)) return;

        $this->cleaning();
    }

    protected function mergeConfigurationWithDefault($configuration, $default)
    {
        if (is_array($configuration)) {
            return  array_merge($default, $configuration);
        } else if (Tools::parseBooleans($configuration)) {
            return $default;
        }
    }

    abstract protected function defaultValues();

    abstract protected function cleaning();
}