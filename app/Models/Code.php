<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code_snippet',
        'user_id',
        'guid'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function($code) {
            if(empty($code->guid)) {
                $code->guid = (string) Str::uuid();
            }
        });
    }
}
