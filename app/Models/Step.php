<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

#[Fillable([
    'description',
    'completed',
])]
class Step extends Model
{
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
        ];
    }

    protected $attributes = [
        'completed' => false,
    ];

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }
}
