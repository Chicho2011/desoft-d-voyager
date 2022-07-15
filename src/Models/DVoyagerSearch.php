<?php

namespace Desoft\DVoyager\Models;

use Illuminate\Database\Eloquent\Model;

class DVoyagerSearch extends Model
{
    protected $table = 'searches';
    protected $fillable = ['title', 'content'];

    public function searchable()
    {
        return $this->morphTo();
    }
}