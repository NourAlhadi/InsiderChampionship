<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller{

    public function index(){

        $teams = Team::all();

        $week = 0;
        $selected = Team::where('selected',1)->get();
        if (count($selected) > 0 ){
            $selected = Team::where('selected',1)->first();
            $week = $selected->played;
        }

        return view('teams.index',[
            'teams' => $teams,
            'week' => $week
        ]);
    }

    public function update(Request $request){
        $nStren = $request->get('nstren');
        if (is_null($nStren)) $nStren = 0;

        $nSelec = $request->get('nselec');
        $t_id = $request->get('team');
        $team = Team::find($t_id);

        if (is_null($nSelec) || is_null($team)) {
            return response()->json([
                'result' => 'error',
                'message' => 'An error occurred please try again later',
                'stren' => $nStren,
                'selec' => $nSelec,
                'team' => $team
            ]);
        }

        $prev_select = Team::where('selected',1)->get();
        if (count($prev_select) >= 4 && $nSelec && !$team->selected){
            return response()->json([
                'result' => 'error',
                'message' => 'Team selection is limited to 4 teams'
            ]);
        }

        $team->selected = $nSelec;
        $team->strength = $nStren;
        $team->save();

        return response()->json([
            'result' => 'success',
            'message' => 'Team info updated',
        ]);
    }

}
