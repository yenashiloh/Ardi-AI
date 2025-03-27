<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Response extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'responses';

    protected $fillable = ['question', 'response'];
}
