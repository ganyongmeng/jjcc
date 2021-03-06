<?php

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




//web官网主页
Route::group(["prefix"=>"/",'namespace'=>'Web'],function (){
    Route::any('/','Index@index');
    Route::any('/index','Index@index');
    Route::any('/home','Index@home');
    Route::any('/test','Index@test');
});

Route::any('/common/file/upload', 'File@upload');
Route::any('/common/file/uploadByEditor', 'File@uploadByEditor');
Route::any('/common/file/delByEditor', 'File@delByEditor');

Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){


    Route::any('/login', 'Login@index');
    Route::any('/login/captcha', 'Login@getcaptcha');
    Route::any('/logout', 'Login@out');
    Route::post('/dologin', 'Login@doLogin');

//登录检查
Route::middleware('checkLogin')->group(function(){

    Route::any('/admin', 'Index@index');
    Route::any('/','Index@index');
    Route::any('index','Index@index');


    //系统管理
    Route::group(['prefix'=>'system','namespace'=>'System'],function(){

        Route::group(["prefix" => "user"], function () {
            Route::any('index', 'User@index');
            Route::any('list', 'User@list');
            Route::any('save', 'User@save');
            Route::any('remove', 'User@remove');
            Route::any('/passwd/change', 'User@changepwd');
            Route::any('/passwd/reset', 'User@resetpwd');
        });
        Route::group(["prefix" => "menu"], function () {
            Route::any('option', 'Menu@option');
            Route::any('p/option', 'Menu@getParentMenuOption');
            Route::any('index', 'Menu@index');
            Route::any('list', 'Menu@list');
            Route::any('save', 'Menu@save');
            Route::any('remove', 'Menu@remove');
        });
        Route::group(["prefix" => "role"], function () {
            Route::any('menu', 'Role@menu');
            Route::any('option', 'Role@option');
            Route::any('index', 'Role@index');
            Route::any('list', 'Role@list');
            Route::any('save', 'Role@save');
            Route::any('remove', 'Role@remove');
            Route::any('set/auth', 'Role@setAuth');
        });
    });

        //内容管理
        Route::group(['prefix'=>'contents','namespace'=>'Contents'],function(){
            Route::any('banner','Banner@index');
            Route::any('banner/edit','Banner@banneredit');
            Route::any('banner/del','Banner@delbanner');
            Route::group(["prefix" => "news"], function () {
                Route::any('index', 'News@index');
                Route::any('add', 'News@add');
                Route::any('lists', 'News@lists');
                Route::any('save', 'News@save');
                Route::any('remove', 'News@remove');
                Route::any('batchRemove', 'News@batchRemove');
                Route::any('publish', 'News@publish');
                Route::any('batchPublish', 'News@batchPublish');
                Route::any('batchOrder', 'News@batchOrder');
            });
        });

    });


});