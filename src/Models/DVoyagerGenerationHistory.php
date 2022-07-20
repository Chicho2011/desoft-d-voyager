<?php

namespace Desoft\DVoyager\Models;

use Illuminate\Database\Eloquent\Model;

class DVoyagerGenerationHistory extends Model
{
    protected $table = 'dvoyager_generation_history';
    protected $fillable = ['bread', 'model', 'migration', 'table'];
}