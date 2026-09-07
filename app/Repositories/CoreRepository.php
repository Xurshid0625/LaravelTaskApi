<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class CoreRepository
{
    /**
     * @var class-string<Model>
     */
    protected static string $model;

    public static function getModel(): Model
    {
        return app(static::$model);
    }
}
