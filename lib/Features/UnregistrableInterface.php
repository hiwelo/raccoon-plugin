<?php
/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 21/02/2016
 * Time: 17:26
 */

namespace Hiwelo\Raccoon\Features;


use Hiwelo\Raccoon\Manifest;

interface UnregistrableInterface
{
    public function unregister(Manifest $manifest);
}