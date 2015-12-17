<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'provider'];
}
