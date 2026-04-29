<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\IdeaStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable([
    'title',
    'description',
    'user_id',
    'links',
    'status',
    'image_path',
])]
class Idea extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'links' => AsArrayObject::class,
            'status' => IdeaStatus::class,
        ];
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    protected $attributes = [
        'status' => IdeaStatus::PENDING->value,
    ];

    public static function countByStatus(User $user): array
    {
        $statusCounts = $user->ideas()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $counts['all'] = [
            'name' => 'All',
            'value' => (int) $statusCounts->sum(),
        ];

        foreach (IdeaStatus::cases() as $status) {
            $counts[$status->value] = [
                'name' => $status->label(),
                'value' => (int) ($statusCounts[$status->value] ?? 0),
            ];
        }

        return $counts;
    }
}
