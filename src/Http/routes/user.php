<?php
Route::group(array('before' => 'basicAuth', 'prefix'    => Config::get('backend.uri') ),function(){

    Route::group(array('prefix' => 'user' ),function(){
        $prefixName = "UserBackend";
        $prefixSlug = Str::snake(str_replace('Backend', '', $prefixName) ,'-');

        //--Show ListprefixSlug
        Route::get('show-list',     array('before' =>   'hasPermissions:'.$prefixSlug.'-read','as'  =>  $prefixName.'ShowList','uses'   =>  $prefixName.'Controller@showList'));
        Route::post('get-list',     array('before' =>   'hasPermissions:'.$prefixSlug.'-read','as'  =>  $prefixName.'GetList','uses'    =>  $prefixName.'Controller@getList'));
        //--Create
        Route::get('create',        array('before' =>   'hasPermissions:'.$prefixSlug.'-create','as'    =>  $prefixName.'ShowCreate','uses' =>  $prefixName.'Controller@showUpdate'));
        Route::post('create',       array('before' =>   'hasPermissions:'.$prefixSlug.'-create|csrf','as'   =>  $prefixName.'ShowCreate','uses' =>  $prefixName.'Controller@showUpdate'));
        //--Update
        Route::get('update/{id}',   array('before' =>   'hasPermissions:'.$prefixSlug.'-edit','as'    =>  $prefixName.'ShowUpdate','uses' =>  $prefixName.'Controller@showUpdate'));
        Route::post('update/{id}',  array('before' =>   'hasPermissions:'.$prefixSlug.'-edit|csrf','as'   =>  $prefixName.'ShowUpdate','uses' =>  $prefixName.'Controller@showUpdate'));
        //--Update Status
        Route::post('changeBooleanType', array('before' =>   'hasPermissions:'.$prefixSlug.'-publish','as'    =>  $prefixName.'ChangeBooleanType','uses'   =>  $prefixName.'Controller@changeBooleanType'));
        //--Delete
        Route::post('delete',       array('before' =>   'hasPermissions:'.$prefixSlug.'-delete','as'    =>  $prefixName.'Delete','uses' =>  $prefixName.'Controller@delete'));
        //--Permission
        Route::get('permission/{id}',   array('before' =>   'hasPermissions:'.$prefixSlug.'-edit','as'    =>  $prefixName.'ShowPermission','uses' =>  $prefixName.'Controller@showPermission'));
        Route::post('permission/{id}',  array('before' =>   'hasPermissions:'.$prefixSlug.'-edit|csrf','as'   =>  $prefixName.'ShowPermission','uses' =>  $prefixName.'Controller@showPermission'));
        //--Change Password
        Route::get('change-password', array('as'    =>  'changePassword','uses' =>  $prefixName.'Controller@changePassword'));
        Route::post('change-password', array('before'   =>  'csrf','as' =>  'changePassword','uses' =>  $prefixName.'Controller@changePassword'));
    });
});
