<?php 

// app/Models/Certificate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = ['user_id', 'course_id', 'uuid', 'issued_at'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn ($cert) => $cert->uuid ??= Str::uuid());
    }
}