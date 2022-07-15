<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'dvoyager.'], function(){

    $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';

    Route::get('dvoyager-assets', ['uses' => $namespacePrefix.'VoyagerController@dAssets', 'as' => 'dvoyager_assets']);
});