<?php

namespace Desoft\DVoyager\Models;

use Illuminate\Database\Eloquent\Model;

class DVoyagerTrace extends Model
{
    protected $table = 'traces';
    protected $fillable = ['user', 'action', 'table', 'fields'];
}