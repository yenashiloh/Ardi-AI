<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
class AuditLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'audit_log';
    protected $fillable = [
        'user_id',
        'action',
        'created_at',
    ];
    public $timestamps = false;
    
    // This relationship should match how the _id is stored
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
}