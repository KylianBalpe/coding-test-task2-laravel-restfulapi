<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $incrementing = true;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
