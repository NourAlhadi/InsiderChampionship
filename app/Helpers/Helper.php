<?php
namespace App\Helpers;


use App\Match;
use App\Team;

class Helper{

    function resetLeague(){
        $matches = Match::all();
        foreach ($matches as $match){
            $match->delete();
        }
        $teams = Team::where('selected',1)->get();
        foreach ($teams as $team){
            $team->played = 0;
            $team->points = 0;
            $team->win = 0;
            $team->draw = 0;
            $team->lose = 0;
            $team->goals = 0;
            $team->save();
        }
        return redirect()->route('homepage')->with('success','League reset completed');
    }


    function getTeams($week){
        $teams_ids = Team::where('selected',1)->orderBy('id','asc')->get()->pluck('id');

        // Getting next round teams
        $adds = $week;
        if ($adds > 3){
            $adds -= 3;
        }
        $team1 = 1;
        $team2 = 1 + $adds;

        if ($team2 == 2){
            $team3 = 3;
            $team4 = 4;
        }else if ($team2 == 3){
            $team3 = 2;
            $team4 = 4;
        }else{
            $team3 = 2;
            $team4 = 3;
        }

        // Getting Data from DB
        $team1 = Team::find($teams_ids[$team1-1]);
        $team2 = Team::find($teams_ids[$team2-1]);
        $team3 = Team::find($teams_ids[$team3-1]);
        $team4 = Team::find($teams_ids[$team4-1]);
        return [
            'team1' => $team1,
            'team2' => $team2,
            'team3' => $team3,
            'team4' => $team4,
        ];
    }


    function playMatch(Team $team1,Team $team2, $week, $auto_save = true){
        // Random amounts of goals
        $gl1 = rand(1,6);
        $gl2 = rand(1,6);

        // Add played to all teams
        $team1->played++;
        $team2->played++;


        $power1 = $team1->strength;
        $power2 = $team2->strength;

        // First Match
        if ($power1 > $power2){
            if ($gl1 == $gl2) $gl1++;
            if ($gl2 > $gl1) {
                $tmp=$gl1;
                $gl1=$gl2;
                $gl2=$tmp;
            }

            $team1->goals += ($gl1-$gl2);
            $team1->win++;
            $team1->points += 3;

            $team2->goals += ($gl2-$gl1);
            $team2->lose++;

        }else if ($power1 < $power2){
            if ($gl1 == $gl2) $gl1++;
            if ($gl1 > $gl2) {
                $tmp=$gl1;
                $gl1=$gl2;
                $gl2=$tmp;
            }


            $team2->goals += ($gl2-$gl1);
            $team2->win++;
            $team2->points += 3;

            $team1->goals += ($gl1-$gl2);
            $team1->lose++;

        }else{
            $team1->draw++;
            $team1->points++;

            $team2->draw++;
            $team2->points++;
        }


        if ($auto_save) {
            $team1->save();
            $team2->save();
        }

        $match = new Match();
        $match->week = $week;
        $match->team1_id = $team1->id;
        $match->team2_id = $team2->id;
        $match->team1_score = $gl1;
        $match->team2_score = $gl2;

        if ($auto_save) {
            $match->save();
        }

    }


    private $all,$my_team,$my_score;
    private $tm1,$tm2,$tm3,$tm4;

    public function initPredict($tm1,$tm2,$tm3,$tm4,$mine){
        $this->all = 1;
        $this->tm1 = $tm1;
        $this->tm2 = $tm2;
        $this->tm3 = $tm3;
        $this->tm4 = $tm4;
        $this->my_team = $mine;
    }

    public function getResult(){
        return [
            'my' => $this->my_score,
            'all' => $this->all
        ];
    }

    public function predict($curr_week){

        if ($curr_week == 7){

            $final = [$this->tm1,$this->tm2,$this->tm3,$this->tm4];
            $final = $final->sort(function($team1,$team2){
                $pts1 = $team1->points;
                $pts2 = $team2->points;

                $gls1 = $team1->goals;
                $gls2 = $team2->goals;

                if ($pts1 > $pts2) return 1;
                if ($pts2 > $pts1) return -1;
                if ($gls1 > $gls2) return 1;
                if ($gls2 > $gls1) return -1;
                if ($team1 == $this->my_team) return 1;
                if ($team2 == $this->my_team) return -1;
                return 1;
            });

            $this->all++;
            if ($final->first() == $this->my_team) $this->my_score++;
            return;
        }

        $teams_ids = Team::where('selected',1)->orderBy('id','asc')->get()->pluck('id');

        // Getting next round teams
        $adds = $curr_week;
        if ($adds > 3){
            $adds -= 3;
        }
        $team1 = $this->tm1;
        $team2 = 1 + $adds;


        if ($team2 == 2){
            $team2 = $this->tm2;
            $team3 = $this->tm3;
            $team4 = $this->tm4;
        }else if ($team2 == 3){
            $team2 = $this->tm3;
            $team3 = $this->tm2;
            $team4 = $this->tm4;
        }else{
            $team2 = $this->tm4;
            $team3 = $this->tm2;
            $team4 = $this->tm3;
        }


        for ($i=1;$i<=2;$i++){
            for ($j=3;$j<=4;$j++){

            }
        }



    }


}