<?php
Route::group(array('before' => 'hasAccess' ),function(){
    // Menu
    $module = "User";
    $prefixSlug = str_slug($module);
    Route::group(array('prefix' => $prefixSlug ),function() use ($module, $prefixSlug){
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

        //--Update Nestable
        Route::get(
            'nestable',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'Nestable',
                'uses' =>  $module.'Controller@nestableAction'
            )
        );
        Route::post(
            'nestable',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'PostNestable',
                'uses' => $module.'Controller@saveNestableAction'
            )
        );

        //-- Change password
        Route::get(
            'change-password',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'ChangePassword',
                'uses' =>  $module.'Controller@changePasswordAction'
            )
        );
        Route::post(
            'change-password',
            array(
                'before' => 'hasAccess:'.$prefixSlug.'.edit',
                'as' => $module.'PostChangePassword',
                'uses' => $module.'Controller@saveChangePasswordAction'
            )
        );

    });

});
