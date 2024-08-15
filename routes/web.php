<?php

use Illuminate\Support\Facades\Route;

Route::get('/{vue_capture?}', function () {
   return view('vue.initial');
})->where('vue_capture', '[\/\w\.-]*');
