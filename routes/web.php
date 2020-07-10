<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;


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
// /app/ 으로 시작하는 주소들

// Route::post('app/create_user', 'AdminController@addUser');
Route::prefix('app')->middleware([AdminCheck::class])->group(function () {
  //Tag CRUD
  Route::post('/create_tag', 'AdminController@addTag');
  Route::get('/get_tags', 'AdminController@getTag');
  Route::post('/edit_tags', 'AdminController@editTag');
  Route::post('/delete_tags', 'AdminController@deleteTag');

  Route::post('/upload', 'AdminController@upload');
  Route::post('/delete_image', 'AdminController@deleteImage');

  //Category CRUD
  Route::post('/create_category', 'AdminController@addCategory');
  Route::get('/get_category', 'AdminController@getCategory');
  Route::post('/edit_category', 'AdminController@editCategory');
  Route::post('/delete_category', 'AdminController@deleteCategory');

  Route::post('/create_user', 'AdminController@addUser');
  Route::get('/get_users', 'AdminController@getUser');
  Route::post('/edit_user', 'AdminController@editUser');

  Route::post('/admin_login', 'AdminController@adminLogin');

  //Role CRUD
  Route::post('/create_role', 'AdminController@addRole');
  Route::post('/edit_roles', 'AdminController@editRole');
  Route::post('/delete_roles', 'AdminController@deleteRole');
  Route::get('/get_roles', 'AdminController@getRole');
});



Route::get('/logout', 'AdminController@logout');
Route::get('/', 'AdminController@index');
Route::get('{slug}', 'AdminController@index');

// Route::get('/', function () {
//   return view('welcome');
// });

// Auth::routes();

// Route::any('{slug}', function () {
//   return view('welcome');
// });
