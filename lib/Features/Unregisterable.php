<?php
/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 21/02/2016
 * Time: 17:26
 */

namespace Hiwelo\Raccoon\Features;


use Hiwelo\Raccoon\Manifest;

trait Unregisterable
{
    protected $toRemove;

    public function Unregister(Manifest $manifest)
    {
        $this->toRemove = $manifest->getArrayValue('remove');
        if(!empty($this->toRemove)) {
            $this->disable();
        }
    }

    abstract protected function disable();
}