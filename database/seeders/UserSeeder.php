<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Umar Irale',
            'email' => 'irale.olamide@gmail.com',
            'password' => Hash::make('Umard3v_')
        ]);

        // Create 20 posts for this specific user.
        Post::factory()->count(20)->create([
            'user_id' => $user->id,
        ]);
    }
}
