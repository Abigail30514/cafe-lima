<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'nombre',
        'estado',
        'destacado',
        'observacion'
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'integer',
            'destacado' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function histories()
    {
        return $this->hasMany(ProductStatusHistory::class);
    }

    public function consumptions()
    {
        return $this->hasMany(ProductConsumption::class);
    }
}