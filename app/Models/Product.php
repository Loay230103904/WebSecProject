<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'model',
        'description',
        'price',
        'stock',
        'photo',
        'review',
        'reviewed_by',
        'reviewed_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'reviewed_at' => 'datetime'
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function purchases()
    {
        return $this->belongsToMany(User::class, 'purchases')
            ->withPivot('quantity', 'created_at')
            ->withTimestamps();
    }
} 