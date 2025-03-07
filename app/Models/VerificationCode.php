<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    /** @use HasFactory<\Database\Factories\VerificationCodeFactory> */
    use HasFactory;

    protected $fillable = [
        'email', 'code', 'expires_at',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
}
