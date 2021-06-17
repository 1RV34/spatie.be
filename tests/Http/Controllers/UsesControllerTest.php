<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\UsesController;
use App\Models\Enums\TechnologyType;
use App\Models\Technology;
use Tests\TestCase;

class UsesControllerTest extends TestCase
{
    /** @test */
    public function it_can_show_the_uses_page()
    {
        $technologies = [];

        foreach (TechnologyType::toArray() as $type) {
            $technologies[] = Technology::factory()
                ->state(['type' => $type])
                ->create()
                ->getAttributes();
        }

        $this
            ->get(action([UsesController::class, 'index']))
            ->assertOk()
            ->assertSee($technologies[0]['name'])
            ->assertSee($technologies[1]['website_url'])
            ->assertSee($technologies[2]['description']);
    }
}
