<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use App\Match;
use App\Team;
use Illuminate\Http\Request;

class MatchController extends Controller{

    public function index(Helper $helper){

        $teams = Team::where('selected',1)->orderBy('points','desc')->orderBy('goals','desc')->get();
        if ( count($teams) > 0 ) {
            $week = Team::where('selected', 1)->first()->played;
        }else{
            $week = 0;
        }

        $matches = Match::where('week',$week)->get();

        $myTeams = [];
        foreach ($teams as $team){
            array_push($myTeams,$team);
        }

        /*
        //////////// TODO: working on prediction
        $predections = [];
        foreach ($teams as $team){
            $helper->initPredict($myTeams[0],$myTeams[1],$myTeams[2],$myTeams[3],$team);
            $helper->predict($week);
            $res = $helper->getResult();
            $my = $res['my'];
            $all = $res['all'];
            $predict = ($my * 100) / $all;
            array_push($predections,$predict);
        }

        dd($predections);
        */
        return view('matches.index',[
            'teams' => $teams,
            'week' => $week,
            'matches' => $matches
        ]);
    }


    public function next(Helper $helper){

        $teams = Team::where('selected',1)->orderBy('points','desc')->orderBy('goals','desc')->get();

        if ( count($teams) > 0 ) {
            $week = Team::where('selected', 1)->first()->played + 1;
        }else{
            $week = 1;
        }

        if ($week > 6){
            return back()->withErrors('League finished!! Hooray!!!');
        }

        $matching = $helper->getTeams($week);
        $team1 = $matching['team1'];
        $team2 = $matching['team2'];
        $team3 = $matching['team3'];
        $team4 = $matching['team4'];


        $helper->playMatch($team1,$team2,$week);
        $helper->playMatch($team3,$team4,$week);

        return redirect()->route('matches.index');
    }


    public function all(Helper $helper){
        $teams = Team::where('selected',1)->orderBy('points','desc')->orderBy('goals','desc')->get();

        if ( count($teams) > 0 ) {
            $week = Team::where('selected', 1)->first()->played + 1;
        }else{
            $week = 1;
        }

        if ($week > 6){
            return back()->withErrors('League finished!! Hooray!!!');
        }

        while ($week < 7) {
            $matching = $helper->getTeams($week);
            $team1 = $matching['team1'];
            $team2 = $matching['team2'];
            $team3 = $matching['team3'];
            $team4 = $matching['team4'];


            $helper->playMatch($team1, $team2, $week);
            $helper->playMatch($team3, $team4, $week);

            $week++;
        }
        return redirect()->route('matches.index');
    }
}
