<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Register all your version 1 accessible routes here. [$rachow]
|
*/

// grab all exchanges supported.
Route::get('/exchange/all', 'ExchangeController@getExchanges');

// grab all exchanges with expanded details.
Route::get('/exchange/all/details', 'ExchangeController@getExchangeDetails');

// grab the supported currencies by the exchange.
Route::get('/exchange/{id}/currencies', 'ExchangeController@getExchangeCurrencies');

