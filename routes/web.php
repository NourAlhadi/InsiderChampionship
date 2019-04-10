<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Routes Without Controller Needed
Route::get('/', function () {
    return view('index');
})->name('homepage');

Route::post('/resets', function(\App\Helpers\Helper $helper, \Illuminate\Http\Request $request){
    if(!$request->has('token')){
        return redirect()->route('homepage');
    }
    return $helper->resetLeague();
})->name('reset');


// Teams Routes
Route::prefix('teams')->group(function () {
    Route::get('/','TeamController@index')->name('teams.index');
    Route::post('/update','TeamController@update')->name('teams.update');

});

// Matches Routes
Route::prefix('matches')->group(function () {
    Route::get('/','MatchController@index')->name('matches.index');
    Route::post('/next','MatchController@next')->name('matches.next');
    Route::post('/all','MatchController@all')->name('matches.all');
});