<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'user_details';

    const Gender = [
        'Male' => 1,
        'Female' => 2
    ];

    protected $fillable = [
        'user_id',
        'gender',
        'image_path'
    ];

    public function getGenderLabelAttribute(): int|string
    {
        return array_flip(self::Gender)[$this->attributes['gender']];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
