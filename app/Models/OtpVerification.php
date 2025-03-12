<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'otp_verifications';
    
    protected $fillable = [
        'user_id',
        'email',
        'otp',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}