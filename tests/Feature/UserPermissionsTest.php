<?php

namespace Tests\Feature;

use App\Enums\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPermissionsTest extends TestCase
{
    public function test_a_basic_request(): void
    {
        $response = $this->get('/api/user-permissions');

        $response->assertStatus(200)
            ->assertExactJson(UserPermission::toArray());
    }
}
