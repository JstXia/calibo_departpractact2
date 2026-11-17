<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    protected $fillable = [
        'equipment_name', 'category', 'unit_price', 'description', 'availability_status',
    ];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function categories(): array
    {
        return ['Paddles', 'Balls', 'Nets', 'Accessories'];
    }

    public static function statuses(): array
    {
        return ['available', 'unavailable'];
    }
}
