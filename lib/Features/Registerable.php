<?php
/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 21/02/2016
 * Time: 17:16
 */

namespace Hiwelo\Raccoon\Features;

use Hiwelo\Raccoon\Manifest;

trait Registerable
{
    protected $toAdd;

    public function register(Manifest $manifest)
    {
        $this->toAdd = $manifest->getRootItemsWithoutRemove();
        if (!empty($this->toAdd)) {
            $this->enable();
        }
    }

    abstract protected function enable();
}
