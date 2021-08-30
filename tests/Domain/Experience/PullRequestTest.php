<?php

use App\Domain\Experience\Commands\RegisterPullRequest;
use App\Domain\Experience\Enums\ExperienceType;
use App\Domain\Experience\Events\PullRequestMerged;
use App\Domain\Experience\Projections\UserAchievementProjection;
use App\Models\User;
use App\Support\Uuid\Uuid;
use Database\Seeders\AchievementSeeder;
use Spatie\EventSourcing\Commands\CommandBus;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Tests\TestCase;

uses(TestCase::class);

test('experience is earned with every pull request', function () {
    /** @var \App\Models\User $user */
    $user = User::factory()->create();

    command(RegisterPullRequest::forUser($user, 'pr'));

    expect($user->experience->amount)->toEqual(ExperienceType::PullRequest()->getAmount());
});

test('100 pull requests achievement', function () {
    (new AchievementSeeder())->run();

    $uuid = Uuid::new();

    $bus = app(CommandBus::class);

    foreach (range(1, 100) as $i) {
        $bus->dispatch(new RegisterPullRequest(
            $uuid,
            1,
            Uuid::new(),
        ));
    }

    $this->assertDatabaseHas(UserAchievementProjection::class, [
        'user_id' => 1,
        'slug' => '10-pull-requests',
    ]);

    $this->assertDatabaseHas(UserAchievementProjection::class, [
        'user_id' => 1,
        'slug' => '50-pull-requests',
    ]);

    $this->assertDatabaseHas(UserAchievementProjection::class, [
        'user_id' => 1,
        'slug' => '100-pull-requests',
    ]);
});

test('same pr cant be registered twice', function () {
    $bus = app(CommandBus::class);

    foreach (range(1, 2) as $i) {
        $bus->dispatch(new RegisterPullRequest(
            'same-uuid',
            1,
            'TEST',
        ));
    }

    expect(EloquentStoredEvent::query()->whereEvent(PullRequestMerged::class)->count())->toEqual(1);
});
