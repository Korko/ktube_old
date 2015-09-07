<?php

namespace Korko\kTube;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['name', 'provider'];
}