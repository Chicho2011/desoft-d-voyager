<?php

namespace Desoft\DVoyager\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class DVoyagerModel extends Model {

    use Translatable;
    use HasSlug;

    protected $slugFrom = 'title';
    protected $translatable = [];

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
                          ->generateSlugsFrom($this->slugFrom)
                          ->saveSlugsTo('slug')
                          ;
    }
}