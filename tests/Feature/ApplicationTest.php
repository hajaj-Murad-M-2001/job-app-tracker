<?php

use App\Models\JobApplication;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

it('lists only my applications', function () {
    JobApplication::factory()->count(2)->create(['user_id' => $this->user->id]);
    JobApplication::factory()->count(3)->create();

    $this->getJson('/api/applications')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('creates an application', function () {
    $this->postJson('/api/applications', [
        'company_name' => 'Acme',
        'position' => 'Laravel Developer',
        'status' => 'applied',
    ])->assertCreated()->assertJsonPath('data.company_name', 'Acme');

    $this->assertDatabaseHas('job_applications', [
        'company_name' => 'Acme',
        'user_id' => $this->user->id,
    ]);
});

it('validates required fields', function () {
    $this->postJson('/api/applications', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['company_name', 'position']);
});

it('rejects an invalid status', function () {
    $this->postJson('/api/applications', [
        'company_name' => 'Acme',
        'position' => 'Dev',
        'status' => 'not-a-status',
    ])->assertUnprocessable()->assertJsonValidationErrors(['status']);
});

it('filters by status', function () {
    JobApplication::factory()->create(['user_id' => $this->user->id, 'status' => 'applied']);
    JobApplication::factory()->create(['user_id' => $this->user->id, 'status' => 'offer']);

    $this->getJson('/api/applications?status=offer')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('forbids viewing another user application', function () {
    $other = JobApplication::factory()->create();

    $this->getJson("/api/applications/{$other->id}")->assertForbidden();
});

it('updates my application', function () {
    $application = JobApplication::factory()->create(['user_id' => $this->user->id]);

    $this->patchJson("/api/applications/{$application->id}", ['status' => 'interview'])
        ->assertOk()
        ->assertJsonPath('data.status', 'interview');
});

it('deletes my application', function () {
    $application = JobApplication::factory()->create(['user_id' => $this->user->id]);

    $this->deleteJson("/api/applications/{$application->id}")->assertNoContent();
    $this->assertDatabaseMissing('job_applications', ['id' => $application->id]);
});

it('forbids deleting another user application', function () {
    $other = JobApplication::factory()->create();

    $this->deleteJson("/api/applications/{$other->id}")->assertForbidden();
});

it('requires authentication', function () {
    $this->app['auth']->forgetGuards();

    $this->getJson('/api/applications')->assertUnauthorized();
});