<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('test-mongodb', function () {
    try {
        DB::connection('mongodb')->command(['ping' => 1]);
        return response()->json(['msg' => 'MongoDB is accessible!']);
    } catch (\Exception $e) {
        return response()->json(['msg' => 'MongoDB is not accessible', 'error' => $e->getMessage()], 500);
    }
});