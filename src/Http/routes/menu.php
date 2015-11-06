<?php
Route::group(array('before' => 'basicAuth' ),function(){
    // Menu
    Route::group(array('prefix' => 'menu' ),function(){
        $module = "Menu";
        $prefixSlug = Str::slug($module);

        //--Show List
        Route::get('/',     array('before' =>   'hasPermissions:'.$prefixSlug.'-read','as'  =>  $module.'Index','uses'   =>  $module.'Controller@indexAction'));
        Route::get('adapter',     array('before' =>   'hasPermissions:'.$prefixSlug.'-read','as'  =>  $module.'Adapter','uses'    =>  $module.'Controller@adapterAction'));
        //--Create
        Route::get('create',        array('before' =>   'hasPermissions:'.$prefixSlug.'-create','as'    =>  $module.'Create','uses' =>  $module.'Controller@editAction'));
        Route::post('create',       array('before' =>   'hasPermissions:'.$prefixSlug.'-create','as'   =>  $module.'Create','uses' =>  $module.'Controller@editAction'));
        //--Update
        Route::get('update/{id}',   array('before' =>   'hasPermissions:'.$prefixSlug.'-edit','as'    =>  $module.'Update','uses' =>  $module.'Controller@editAction'));
        Route::post('update/{id}',  array('before' =>   'hasPermissions:'.$prefixSlug.'-edit','as'   =>  $module.'Update','uses' =>  $module.'Controller@editAction'));
        //--Update Status
        Route::post('toggle-boolean', array('before' =>   'hasPermissions:'.$prefixSlug.'-publish','as'    =>  $module.'ToogleBoolean', 'uses'   =>  $module.'Controller@toggleAction'));
        //--Delete
        Route::post('delete',       array('before' =>   'hasPermissions:'.$prefixSlug.'-delete','as'    =>  $module.'Delete','uses' =>  $module.'Controller@deleteAction'));

        //--Update Nestable
        Route::get('nestable',  array('before' =>   'hasPermissions:'.$prefixSlug.'-edit','as'    =>  $module.'Nestable','uses'   =>  $module.'Controller@nestableAction'));
        Route::post('postNestable',     array('before' =>   'hasPermissions:'.$prefixSlug.'-edit','as'    =>  $module.'PostNestable','uses'   =>  $module.'Controller@saveNestableAction'));


    });

});
