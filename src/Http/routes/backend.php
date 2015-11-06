<?php
Route::group(['before' => 'basicAuth'],function() {
        Route::get('/dashboard', [
            'as' => 'indexDashboard',
            'uses' => 'BackendController@indexAction'
        ]);
        Route::get('dashboard/logout', [
            'as' => 'logoutBackend',
            'uses' => 'BackendController@logoutAction'
        ]);
        Route::get('dashboard/access-denied', [
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


