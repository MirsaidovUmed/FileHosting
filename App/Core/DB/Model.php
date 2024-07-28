<?php

namespace App\Core\DB;

abstract class Model
{
    abstract public static function getTableName(): string;
}
