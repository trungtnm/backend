<?php
Route::group(array('before' => 'hasAccess' ),function(){
    // Menu
    $module = "Language";
    $prefixSlug = str_slug($module);
    Route::group(array('prefix' => $prefixSlug ), function() use ($module, $prefixSlug){
        //--index
        Route::get(
            '/',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.read',
                'as'  =>  $module.'Index','uses' => $module.'Controller@indexAction'
            )
        );
        Route::get(
            'adapter',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.read',
                'as'  =>  $module.'Adapter',
                'uses'=>  $module.'Controller@adapterAction'
            )
        );

        //--Create
        Route::get(
            'create',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.create',
                'as' => $module.'Create',
                'uses' => $module.'Controller@editAction'
            )
        );
        Route::post(
            'create',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.create',
                'as' => $module.'Create',
                'uses' => $module.'Controller@editAction'
            )
        );

        //--Update
        Route::get(
            'update/{id}',
            array(
                'before' =>   'hasAccess:'.$prefixSlug.'.edit',
                'as'    =>  $module.'Update',
                'uses' => $module.'Controller@editAction'
            )
        );
        Route::post(
            'update/{id}',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'Update',
                'uses' => $module.'Controller@editAction'
            )
        );
        //--Update Status
        Route::post(
            'toggle-boolean',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.publish',
                'as' => $module.'ToogleBoolean',
                'uses' => $module.'Controller@toggleAction'
            )
        );
        //--Delete
        Route::post(
            'delete',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.delete',
                'as' => $module.'Delete',
                'uses' => $module.'Controller@deleteAction'
            )
        );
        // xeditable routes
        Route::get(
            'get-source/{id}/{field}',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'GetSource',
                'uses' =>  $module.'Controller@getSourceAction'
            )
        );
        Route::post(
            'get-source/{id}/{field}',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'PostSource',
                'uses' => $module.'Controller@saveSourceAction'
            )
        );

    });

});
