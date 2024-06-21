<?php

namespace App\Models;

abstract class Model
{
    abstract public static function getTableName(): string;
}
