<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MachineController;
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

Route::get('/',[SensorController::class,'show']);
Route::post('weather-add',[SensorController::class,'writeData'])->name('add');
Route::get('weather-save',[SensorController::class,'saveData'])->name('save');
Route::post('/weather-chart',[SensorController::class,'chart']);
Route::post('/weather-chartWeek',[SensorController::class,'chartWeek']);
Route::post('/weather-pm24',[SensorController::class,'pmAvg']);
Route::post('/weather-data24',[SensorController::class,'dayAvg']);
Route::post('/weather-data7',[SensorController::class,'weekAvg']);

Route::get('chartPm',[ChartController::class,'chartData']);
Route::get('chartData',[ChartController::class,'chartSelect']);
Route::get('dataSelect',[ChartController::class,'dataSelect'])->name('see');

Route::get('edit/{id}',[MachineController::class,'showEdit']);
Route::post('addMachine',[MachineController::class,'addMachine'])->name('saveMac');
Route::post('/updateMachine',[MachineController::class,'updateMachine'])->name('editMac');
Route::get('delMachine/{id}',[MachineController::class,'deleteMachine']);

Route::get('getData', function()
{
    return view('getMqtt');
});
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('is_admin');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware('is_admin');
Route::post('/getChart', [HomeController::class, 'chart']);
Route::post('/getChartWeek', [HomeController::class, 'chartWeek']);

\Request::getRequestUri();