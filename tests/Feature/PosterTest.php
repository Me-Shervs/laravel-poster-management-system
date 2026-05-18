<?php

use App\Models\User;
use App\Models\Poster;
use App\Models\Schedule;
use App\Models\Category;
use App\Jobs\PublishPosterJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| 1. Default Status
|--------------------------------------------------------------------------
*/

it('creates poster with draft status by default', function () {

    $poster = Poster::factory()->create();

    expect($poster->status->value)->toBe('draft');
});

/*
|--------------------------------------------------------------------------
| 2. Policy Enforcement
|--------------------------------------------------------------------------
*/

it('prevents users from updating posters they do not own', function () {

    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $poster = Poster::factory()->create([
        'user_id' => $owner->id,
    ]);

    Sanctum::actingAs($otherUser);

    $response = $this->putJson("/api/v1/posters/{$poster->id}", [
        'title' => 'Hacked Title',
    ]);

    $response->assertForbidden();

    expect($poster->fresh()->title)->not->toBe('Hacked Title');
});

/*
|--------------------------------------------------------------------------
| 3. Queue Dispatching (Schedule API)
|--------------------------------------------------------------------------
*/

it('dispatches publish poster job when scheduling', function () {

    Queue::fake();

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $poster = Poster::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->postJson("/api/v1/posters/{$poster->id}/schedule", [
        'scheduled_at' => now()->addMinute()->toDateTimeString(),
        'action' => 'publish',
    ])->assertOk();

    Queue::assertPushed(PublishPosterJob::class);
});

/*
|--------------------------------------------------------------------------
| 4. Expiration Command
|--------------------------------------------------------------------------
*/

it('expires published posters via command', function () {

    $poster = Poster::factory()->create([
        'status' => 'published',
        'expires_at' => now()->subMinute(),
    ]);

    Artisan::call('posters:expire');

    expect($poster->fresh()->status->value)->toBe('expired');
});

/*
|--------------------------------------------------------------------------
| 5. Category Scope (includes children)
|--------------------------------------------------------------------------
*/

it('includes posters from child categories in scope', function () {

    $parent = Category::factory()->create();

    $child = Category::factory()->create([
        'parent_id' => $parent->id,
    ]);

    $poster = Poster::factory()->create();

    $poster->categories()->attach($child->id);

    $results = Poster::byCategory($parent->id)->get();

    expect($results->contains($poster))->toBeTrue();
});