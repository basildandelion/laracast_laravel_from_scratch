<?php

use App\Enums\IdeaStatus;
use App\Models\Idea;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('it filters ideas by a valid requested status', function (): void {
    $user = User::factory()->create();
    $pendingIdea = Idea::factory()->for($user)->create([
        'status' => IdeaStatus::PENDING,
    ]);

    Idea::factory()->for($user)->create([
        'status' => IdeaStatus::COMPLETED,
    ]);

    Idea::factory()->create([
        'status' => IdeaStatus::PENDING,
    ]);

    $this->actingAs($user)
        ->get(route('ideas.index', ['status' => IdeaStatus::PENDING->value]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Ideas/Index')
            ->where('requestedStatus', IdeaStatus::PENDING->value)
            ->has('items.data', 1)
            ->where('items.data.0.id', $pendingIdea->id)
            ->where('items.data.0.status', IdeaStatus::PENDING->label())
            ->etc()
        );
});

test('it ignores an invalid requested status', function (): void {
    $user = User::factory()->create();

    Idea::factory()->for($user)->create([
        'status' => IdeaStatus::PENDING,
    ]);
    Idea::factory()->for($user)->create([
        'status' => IdeaStatus::COMPLETED,
    ]);
    Idea::factory()->create([
        'status' => IdeaStatus::PENDING,
    ]);

    $this->actingAs($user)
        ->get(route('ideas.index', ['status' => 'archived']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Ideas/Index')
            ->where('requestedStatus', 'all')
            ->has('items.data', 2)
            ->etc()
        );
});
