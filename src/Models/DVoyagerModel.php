<?php

namespace Desoft\DVoyager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use TCG\Voyager\Traits\Translatable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class DVoyagerModel extends Model {

    use Translatable;
    use HasSlug;

    protected $slugFrom = 'title';
    protected $translatable = [];

    /*
        TODO Revisar si esta es la mejor forma
        Información auxiliar de cada campo para mostrar en el formulario para cada lenguaje
        Formato
        {
            'es: {
                'title': 'Información',
                'from': 'Otra información'
            },
            'en': {
                'title': 'Information',
                'from': 'Another information'
            }
        }
        json
    */
    protected $fieldsInfo = '';
    protected $searchable = [];

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
                          ->generateSlugsFrom($this->slugFrom)
                          ->saveSlugsTo('slug')
                          ;
    }

    public function getFieldInfo($field)
    {
        $currentLocale = App::getLocale();
        $decoded = json_decode($this->fieldsInfo);
        if(isset($decoded->$currentLocale))
        {
            if(isset($decoded->$currentLocale->$field))
            {
                return $decoded->$currentLocale->$field;
            }
        }

        return '';
    }
}