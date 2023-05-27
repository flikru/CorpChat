<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = \App\Models\User::factory(5)->create();
        $chats = \App\Models\Chat::factory(20)->create();
        $messages = \App\Models\Message::factory(150)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        foreach($users as $user){
            $zn = rand(0,4);
            $chatsId = $chats->random($zn)->pluck('id');
            $user->chats()->attach($chatsId);
        }
    }
}
