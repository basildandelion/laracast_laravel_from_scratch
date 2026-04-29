<?php

use App\Enums\IdeaStatus;
use App\Models\Idea;
use App\Models\Step;
use App\Models\User;
use Illuminate\Support\Facades\DB;

test('it belongs to a user', function () {
    $idea = Idea::factory()->create();

    expect($idea->user)->toBeInstanceOf(User::class);
});

test('it can have steps', function () {
    $idea = Idea::factory()->create();

    expect($idea->steps)->toBeEmpty();
    $steps = Step::factory()->count(3)->create(['idea_id' => $idea->id]);
    $idea->refresh();

    expect($idea->steps)->toHaveCount(3);
});

test('it counts ideas by status in a single query', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Idea::factory()->for($user)->create(['status' => IdeaStatus::PENDING]);
    Idea::factory()->for($user)->create(['status' => IdeaStatus::PENDING]);
    Idea::factory()->for($user)->create(['status' => IdeaStatus::IN_PROGRESS]);
    Idea::factory()->for($otherUser)->create(['status' => IdeaStatus::COMPLETED]);

    DB::flushQueryLog();
    DB::enableQueryLog();

    $counts = Idea::countByStatus($user);

    DB::disableQueryLog();

    expect($counts)->toBe([
        'all' => [
            'name' => 'All',
            'value' => 3,
        ],
        'pending' => [
            'name' => 'Pending',
            'value' => 2,
        ],
        'in_progress' => [
            'name' => 'In Progress',
            'value' => 1,
        ],
        'completed' => [
            'name' => 'Completed',
            'value' => 0,
        ],
    ])->and(DB::getQueryLog())->toHaveCount(1);
});
