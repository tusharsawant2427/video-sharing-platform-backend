<?php

namespace App\Helpers\Traits;

trait Uuid {
    protected static function boot(): void {
        parent::boot();
        static::creating(function ($model) {
            $model->identifier = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });
        static::saving(function ($model) {
            $originalUUID = $model->getOriginal('identifier');
            if ($originalUUID !== $model->identifier) {
                $model->identifier = $originalUUID;
            }
        });
    }
}
