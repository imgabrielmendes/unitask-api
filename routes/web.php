<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return response()->json([
		'status' => 'ok',
		'message' => 'UniTask API online',
		'base' => '/api',
	]);
});
