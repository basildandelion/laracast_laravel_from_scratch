<?php

use App\Models\Idea;
use App\Models\Step;
use App\Models\User;

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
