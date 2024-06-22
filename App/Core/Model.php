<?php

namespace App\Core;

abstract class Model
{
    abstract public static function getTableName(): string;
}
