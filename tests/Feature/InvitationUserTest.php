<?php

namespace Tests\Feature;

use App\Enums\UserPermission;
use App\Mail\Invitation;
use Faker\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationUserTest extends TestCase
{
    public function test_a_basic_request(): void
    {
        Mail::fake();
        $faker = Factory::create();

        $recipients = [
            [
                'email' => $faker->email,
                'name' => $faker->name,
                'role' => Arr::random(UserPermission::toValues()),
            ],
            [
                'email' => $faker->email,
                'name' => $faker->name,
                'role' => Arr::random(UserPermission::toValues()),
            ],
        ];

        $response = $this->postJson('/api/invite', [
            'recipients' => $recipients,
            'message' => $faker->sentence,
        ]);

        $response->assertStatus(200)
            ->assertJson(['data' => $recipients]);

        Mail::assertQueued(Invitation::class, 2);
    }

    public function test_a_validation_fake_data(): void
    {
        Mail::fake();
        $faker = Factory::create();

        $response = $this->postJson('/api/invite', [
            'recipients' => [
                ['email' => 'fake', 'name' => 'fake', 'role' => 'fake', 'message' => 'fake']
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('recipients.0.role')
            ->assertJsonValidationErrorFor('recipients.0.email');

        Mail::assertNothingQueued();
    }

    public function test_a_validation_nullable_data(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/invite', [
            'recipients' => [
                ['email' => null, 'name' => null, 'role' => null]
            ],
            'message' => null,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('recipients.0.role')
            ->assertJsonValidationErrorFor('recipients.0.name')
            ->assertJsonValidationErrorFor('recipients.0.email');

        Mail::assertNothingQueued();
    }

    public function test_a_validation_over_length_data(): void
    {
        Mail::fake();
        $faker = Factory::create();
        $overText = $faker->text(500);

        $response = $this->postJson('/api/invite', [
            'recipients' => [
                ['email' => $faker->email, 'name' => $overText, 'role' => Arr::random(UserPermission::toValues())]
            ],
            'message' => $overText,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('recipients.0.name')
            ->assertJsonValidationErrorFor('message');

        Mail::assertNothingQueued();
    }

    public function test_a_validation_duplicates_emails(): void
    {
        Mail::fake();
        $faker = Factory::create();
        $duplicateEmail = $faker->email;

        $response = $this->postJson('/api/invite', [
            'recipients' => [
                ['email' => $duplicateEmail, 'name' => $faker->name, 'role' => Arr::random(UserPermission::toValues())],
                ['email' => $duplicateEmail, 'name' => $faker->name, 'role' => Arr::random(UserPermission::toValues())]
            ],
            'message' => $faker->sentence,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('recipients.0.email');

        Mail::assertNothingQueued();
    }
}
