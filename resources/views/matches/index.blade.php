@extends('base')

@section('matchActive','active')

@section('css')
    <style>
        .title{
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            font-size: 30px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="title m-b-md">
                    League Table
                </div>
            </div>

            <div class="col-1"></div>
            <div class="col-5">
                <div class="title m-b-md">
                    Last Round Matches
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2">Teams</th>
                            <th scope="col">PTS</th>
                            <th scope="col">P</th>
                            <th scope="col">W</th>
                            <th scope="col">D</th>
                            <th scope="col">L</th>
                            <th scope="col">GD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                            <tr>
                                <td colspan="2">{{ $team->name }}</td>
                                <td>{{ $team->points }}</td>
                                <td>{{ $team->played }}</td>
                                <td>{{ $team->win }}</td>
                                <td>{{ $team->draw }}</td>
                                <td>{{ $team->lose }}</td>
                                <td>{{ $team->goals }}</td>
                            </tr>
                        @endforeach
                        @if(count($teams) == 0)
                            <tr class="text-center">
                                <td colspan="8"> No Teams Selected Yet</td>
                            </tr>
                            <tr class="text-center">
                                <td colspan="8"> You can start selecting
                                    <a href="{{ route('teams.index') }}">here</a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-1"></div>

            <div class="col-5">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2">Team 1</th>
                            <th scope="col">Goals</th>
                            <th scope="col"> || </th>
                            <th scope="col">Goals</th>
                            <th scope="col" colspan="2">Team 2</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($matches as $match)
                        <tr>
                            <td colspan="2">{{ $match->team1->name }}</td>
                            <td>{{ $match->team1_score }}</td>
                            <td> || </td>
                            <td>{{ $match->team2_score }}</td>
                            <td colspan="2">{{ $match->team2->name }}</td>
                        </tr>
                    @endforeach

                    @if(count($matches) == 0)
                        <tr class="text-center">
                            <td colspan="7"> No Matches Played Yet</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if($errors->any())
            <div class="row">
                <div class="col-5">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-2">
                <form method="post" action="{{ route('matches.next') }}">
                    @csrf
                    <input class="btn btn-outline-success" type="submit" value="Next Round">
                </form>
            </div>
            <div class="col-1"></div>
            <div class="col-2">
                <form method="post" action="{{ route('matches.all') }}">
                    @csrf
                    <input class="btn btn-outline-success" type="submit" value="Complete The League">
                </form>
            </div>
        </div>
    </div>
@endsection