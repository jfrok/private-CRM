<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/belverzoeken', [\App\Http\Controllers\CallController::class, 'callApi'])->name('callApi');

// Keysoftware api routes
Route::post('/keysoftware-api-calls-update/{token}', [\App\Http\Controllers\KeysoftwareApiCallsController::class, 'updateApiCalls'])->name('keysoftwareUpdateApiCalls');
Route::get('/keysoftware-planviewer-producten/{token}', [\App\Http\Controllers\KeysoftwareApiCallsController::class, 'planviewerProducts'])->name('keysoftwarePlanviewerPrices');
Route::get('/keysfotware-planviewer-specifiek-product/{token}/{slug}', [\App\Http\Controllers\KeysoftwareApiCallsController::class, 'planviewerProduct'])->name('keysoftwarePlanviewerProduct');
Route::get('/site-crm-fetch/{token}',[\App\Http\Controllers\SiteController::class,'fetchProjects']);
Route::get('/site-crm-fetch-content/{token}/{siteN}',[\App\Http\Controllers\SiteController::class,'fetchProjectsContent']);
Route::get('/site-crm-fetch-all-contents/{token}',[\App\Http\Controllers\SiteController::class,'fetchLastContent']);
Route::get('/site-crm-websites/{token}',[\App\Http\Controllers\SiteController::class,'fetchWebsites']);
Route::get('/site-crm-webwinkel/{token}',[\App\Http\Controllers\SiteController::class,'fetchWebwinkels']);
Route::get('/site-crm-software/{token}',[\App\Http\Controllers\SiteController::class,'fetchSoftwares']);

