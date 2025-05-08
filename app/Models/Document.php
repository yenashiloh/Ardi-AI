<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Document extends Model
{
    protected $collection = 'documents';
    protected $connection = 'mongodb'; 

    protected $fillable = [
        'document_type',
        'notes',
        'file_path',
        'original_file_name'
    ];
}
