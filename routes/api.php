<?php

use Illuminate\Http\Request;

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
/*
|--------------------------------------------------------------------------
| Laravel 框架自带的 API 资源类(Resources)
|--------------------------------------------------------------------------
|
| 通过隐式路由模型绑定来改写的路由
*/
//Route::group(['middleware' => 'auth:api'], function() {
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{article}', 'ArticleController@show');
    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{article}', 'ArticleController@update');
    Route::delete('articles/{article}', 'ArticleController@delete');
//});

/*
 * 上述通过隐式路由模型绑定的路由，与下面的普通路由一样
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
/*
|--------------------------------------------------------------------------
| Dingo/Api Routes
*/
$api = app(\Dingo\Api\Routing\Router::class);
$api->version('v1', function ($api) {
    $api->get('task/{id}', function ($id) {
        return \App\Task::findOrFail($id);
    });
});

$api->version('v2', function ($api) {
    $api->get('task/{id}', function ($id) {
        return \App\Task::findOrFail(22);
    });
});
$api->version('v3',[
    'namespace' => 'App\Http\Controllers\Api',
], function ($api) {
    $api->get('task/{id}', 'TaskController@show')->name('task.show');
    // 资源集合响应(引入关联模型)
    // 添加 meta 元数据、响应状态码、cookie、额外的响应头、
    $api->get('task', 'TaskController@index')->name('task.list');
});

/**
|--------------------------------------------------------------------------
| 以单个资源为例，在 Laravel 中基于 Fractal 定义一个 API 接口
|--------------------------------------------------------------------------
|
| Fractal 默认支持 ArraySerializer、DataArraySerializer (默认)、JsonApiSerializer 三种序列化器
*/
Route::get('/fractal/resource/data', 'TaskController@data');    // DataArraySerializer (默认)
Route::get('/fractal/resource/array', 'TaskController@array');  // ArraySerializer
Route::get('/fractal/resource/json', 'TaskController@json');    // JsonApiSerializer
// 使用独立的转化器 transformer 类，以提高代码的可复用性
Route::get('/fractal/resource/task/{id}', 'TaskController@show')->name('tasks.show');
Route::get('/fractal/resource/task', 'TaskController@index')->name('tasks.list');
/**
 * Fractal 提供了两种解决方案来支持分页数据结果，分别是分页器和游标
 *
 * 1.分页器可以提供丰富的分页结果信息，包括项目总数、上一页/下一页链接等，但相应的代价是可能会带来额外的性能开销
 * 比如每次调用都要统计项目总数，如果对性能要求比较苛刻，可以考虑使用游标来获取分页结果。
 *
 * 2.通过游标获取分页结果类似限定查询，不会统计项目总数，在性能要优于分页器
 */
Route::get('fractal/paginator', 'TaskController@paginator');
Route::get('fractal/cursor', 'TaskController@cursor');
