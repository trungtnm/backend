<?php
Route::group(array('middleware' => 'hasAccess' ),function(){
    // Menu
    $module = "Setting";
    $prefixSlug = str_slug($module);
    Route::group(array('prefix' => $prefixSlug ),function() use ($module, $prefixSlug){
        //--index
        Route::get(
            '/',
            array(
                'middleware' => 'hasAccess:'.$prefixSlug.'.read',
                'as'  =>  $module.'Index','uses' => $module.'Controller@indexAction'
            )
        );
        Route::post(
            '/',
            array(
                'middleware' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'Update',
                'uses' => $module.'Controller@editAction'
            )
        );
    });

});
