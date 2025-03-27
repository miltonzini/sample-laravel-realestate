<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateInitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Milton',
            'surname' => 'Zini',
            'email' => 'miltonzini@gmail.com',
            'password' => '$2y$12$73.PHkeJ1IIR72qOdecym.muO8s6FzN16CptI2Q3RPfbKOkQkW1Dq',
        ]);
        
        User::create([
            'name' => 'Mariano',
            'surname' => 'Menendez',
            'email' => 'menendezarena@gmail.com',
            'password' => '$2y$12$73.PHkeJ1IIR72qOdecym.muO8s6FzN16CptI2Q3RPfbKOkQkW1Dq',
        ]);
    }
}
