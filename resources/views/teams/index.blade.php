@extends('base')

@section('teamActive','active')

@section('css')
    <style>
        .excheckbox {
            width: 220px;
            height: 30px;
            background: #f0f0f0;
            margin: 50px auto;
            border-radius: 15px;
            box-sizing: border-box;
            padding: 0 20px;
            position: relative;
            overflow: hidden;
        }
        .excheckbox label[id*=label-] {
            display: block;
            font-size: 12px;
            line-height: 30px;
        }
        .excheckbox #label-1 {
            float: right;
        }
        .excheckbox #label-2 {
            float: left;
        }
        .excheckbox #roll {
            position: absolute;
            top: 0;
            margin: auto;
            width: 52%;
            height: 100%;
            border-radius: 15px;
            background: #5bc0de;
            box-shadow: inset 0 0 10px rgba(0,0,0,.2), 0 0 10px rgba(0,0,0,.5);
        }
        .excheckbox input[type=checkbox] {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            right: 0;
            z-index: 2;
            opacity: 0;
        }
        .excheckbox input[type=checkbox]:checked + #roll {
            left: 50%;
        }
        .excheckbox input[type=checkbox]:not(:checked) + #roll {
            left: 0;
        }

        .mytoast{
            height: 75px;
            width: 250px;
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
    </style>
@endsection

@section('content')
    @if($week > 0)
        <div class="container">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="alert alert-danger">
                        Please note that you're league has started so any update operation will cause your progress to reset
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Team</th>
                        <th scope="col">Strength</th>
                        <th scope="col">Is Selected?</th>
                        <th scope="col">Update</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                            <tr id="team-row-{{ $team->id }}">
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->strength }}</td>
                                <td>{{ $team->selected ? "Yes" : "No" }}</td>
                                <td> <button class="btn btn-outline-success"  data-toggle="modal" data-target="#team-update-{{ $team->id }}">Update</button> </td>
                            </tr>


                            <!-- The Modal -->
                            <div class="modal" id="team-update-{{ $team->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Updating {{ $team->name }}</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <form class="form-group">
                                                <div class="row">
                                                    <div class="col-2">Strength:</div>
                                                    <div class="col-10">
                                                        <input autocomplete="off" type="text" id="strength{{ $team->id }}" class="form-control" value="{{ $team->strength }}">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="excheckbox">
                                                            <label id="label-1">Not Selected</label>
                                                            <label id="label-2">Selected</label>
                                                            <input id="selected{{ $team->id }}" autocomplete="off" type="checkbox" {{ $team->selected ? 'checked' : '' }}>
                                                            <div id="roll"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-success" onclick="update({{ $team->id }});">Update</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script>

        function update(id){
            let nStren = $("#strength"+id).val();
            let nSelec = $('#selected' + id).is(":checked");
            if (nSelec == true) nSelec = 1;
            else nSelec = 0;

            let token = '<?php echo csrf_token(); ?>';

            let url = '{{ route('teams.update') }}';

            $.ajax({
                type:'POST',
                url: url,
                data:'_token=' + token + '&nstren=' + nStren + '&nselec=' + nSelec + '&team=' + id,
                success:function(data) {
                    if (data.result === "success"){
                        let tstOpen = "<div class='mytoast alert alert-success'>";
                        let tstClose = "</div>";
                        let elem = $(tstOpen + data.message + tstClose);
                        $("body").append(elem);
                        setTimeout(function(){
                            elem.hide(1000);
                        },1000);

                        let cols = $("#team-row-"+id).children();
                        cols[1].innerText = nStren;
                        cols[2].innerText =(nSelec?"Yes":"No");
                    }else{
                        let checkBoxes = $('#selected' + id);
                        checkBoxes.prop("checked", !checkBoxes.prop("checked"));

                        let tstOpen = "<div class='mytoast alert alert-danger'>";
                        let tstClose = "</div>";
                        let elem = $(tstOpen + data.message + tstClose);
                        $("body").append(elem);
                        setTimeout(function(){
                            elem.hide(1000);
                        },1000);
                    }
                }
            });

            $("#team-update-"+id).modal('toggle');
        }

    </script>
@endsection