<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        JobApplication::factory()
            ->count(15)
            ->create(['user_id' => $user->id]);
    }
}