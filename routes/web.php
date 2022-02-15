<?php

use App\Notifications\NewSale;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Notifications\CustomerOrder;

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

Route::get('/welcome', function () {

    $user=new User(['email' => 'kicportharcourt@kingsword.org','name'=>'name','password'=>'pass']);

    $user->notify(new CustomerOrder());

    return "sent";


});
