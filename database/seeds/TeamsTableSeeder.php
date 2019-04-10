<?php

use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder{

    private $teams = [
        'Liverpool',
        'Manchester City',
        'Chelsea',
        'Tottenham Hotspur',
        'Arsenal',
        'Manchester United',
        'Leicester City',
        'Wolverhampton Wanderers',
        'Everton',
        'Watford',
        'West Ham United',
        'Crystal Palace',
        'Bournemouth',
        'Burnley',
        'Newcastle United',
        'Brighton & Hove Albion',
        'Southampton',
        'Cardiff City',
        'Fulham',
        'Huddersfield Town'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        foreach ($this->teams as $team) {
            DB::table('teams')->insert([
                'name' => $team,
            ]);
        }
    }
}
