<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.ru',
            'email_verified_at' => now(),
            'about' => 'Администратор',
            'photo_path' => 'avatar'.rand(1,4).'.png',
            'group' => 'admin',
            'password' => bcrypt('admin'), // password
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::factory(5)->create();
        $chats = \App\Models\Chat::factory(1)->create();
        $users = User::all();
        /*
        $messages = \App\Models\Message::factory(150)->create();
         \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
         ]);
        foreach($users as $user){
            $zn = 3;
            $chatsId = $chats->random($zn)->pluck('id');
            $user->chats()->attach($chatsId);
        }*/
//        $users = User::all();
//        $chats = Chat::all();
        foreach($users as $user){
            $user->chats()->attach(1);
        }


    }
}
