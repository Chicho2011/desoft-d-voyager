<?php

namespace Desoft\DVoyager\Traits;

use Desoft\DVoyager\Models\DVoyagerSearch;
use Desoft\DVoyager\Services\SearchServices;

trait SearchChanges{
    public function search()
    {
        return $this->morphOne(DVoyagerSearch::class, 'searchable');
    }

    public static function bootSearchChanges()
    {
        static::created(function ($model) {
            if(count($model->searchable) > 0)
            {
                $composedText = '';
                foreach ($model->searchable as $value) {
                    $composedText .= $model->{$value}.' ';
                }
                $searchServices = new SearchServices;
                $searchServices->insertSearchableItem($model, $composedText);
            }
        });

        static::deleting(function ($model)
        {
            $searchServices = new SearchServices;
            $searchServices->deleteSearchableItem($model);
        });
    }
}