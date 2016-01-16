<?php
Route::group(['before' => 'hasAccess'], function() {
        Route::get('/dashboard', [
            'as' => 'indexDashboard',
            'uses' => 'BackendController@indexAction'
        ]);
        Route::get('logout', [
            'as' => 'logoutBackend',
            'uses' => 'BackendController@logoutAction'
        ]);
        Route::get('access-denied', [
            'as' => 'accessDenied',
            'uses' => 'BackendController@accessDeniedAction'
        ]);

        Route::get('/add-role', [
            'as' => 'addRole',
            'uses' => 'BackendController@addRoleAction'
        ]);
    }
);
Route::group(['before' => 'notAuth'], function() {
    Route::get('/', array('as' => 'loginBackend', 'uses' => 'BackendController@loginAction'));
    Route::post('/', array('as' => 'loginBackend', 'uses' => 'BackendController@loginAction'));
//    TODO: do some databases setup here
//    Route::get('install', array('as' => 'install', 'uses' => 'BackendController@install'));
});

