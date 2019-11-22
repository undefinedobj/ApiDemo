<?php

use Illuminate\Http\Request;
use App\Article;

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

// 通过隐式路由模型绑定来改写的路由
//Route::group(['middleware' => 'auth:api'], function() {
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{article}', 'ArticleController@show');
    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{article}', 'ArticleController@update');
    Route::delete('articles/{article}', 'ArticleController@delete');
//});

/*
 * 上述通过隐式路由模型绑定的路由，与厦门的普通路由一样
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{id}', 'ArticleController@show');
    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{id}', 'ArticleController@update');
    Route::delete('articles/{id}', 'ArticleController@delete');
*/

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

/**
 * jwt-auth
 */
// 注册
Route::post('auth/register', 'AuthController@register');
// 登录
Route::post('auth/login', 'AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function(){

    // 获取当前登录用户数据
    Route::get('auth/user', 'AuthController@user');
    // 退出
    Route::post('auth/logout', 'AuthController@logout');
});

Route::group(['middleware' => 'jwt.refresh'], function(){

    // 检查当前登录用户 token 是否仍然有效
    Route::get('auth/refresh', 'AuthController@refresh');
});