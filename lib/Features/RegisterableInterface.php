<?php
/**
 * Created by PhpStorm.
 * User: alemaire
 * Date: 21/02/2016
 * Time: 17:25
 */

namespace Hiwelo\Raccoon\Features;


use Hiwelo\Raccoon\Manifest;

interface RegisterableInterface
{
    function Register(Manifest $manifest);
}