<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
$api = app('Dingo\Api\Routing\Router');

// v1 version API
// add in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => [
        'cors',
        'serializer',
         //'serializer:array', // if you want to remove data wrap
        'api.throttle',
    ],
    // each route have a limit of 20 of 1 minutes
    'limit' => 20, 'expires' => 1,
], function ($api) {
    // Auth
    // login
    $api->post('login', [
        'as' => 'authorizations.login',
        'uses' => 'AuthController@login',
    ]);

    // User
    $api->post('users', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
    ]);
    // user list
    $api->get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
    ]);
    // user detail
    $api->get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
    ]);

    // CHECKLIST
    // checklist list
    $api->get('checklists', [
        'as' => 'checklists.index',
        'uses' => 'ChecklistController@index',
    ]);
    // checklist detail
    $api->get('checklists/{id}', [
        'as' => 'checklists.show',
        'uses' => 'ChecklistController@show',
    ]);

    // checklist COMMENT
    // checklist item list
    $api->get('checklists/{checklistId}/items', [
        'as' => 'checklists.items.index',
        'uses' => 'ItemController@index',
    ]);

    $api->put('authorizations/current', [
        'as' => 'authorizations.update',
        'uses' => 'AuthController@update',
    ]);

    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {

        // LOGOUT, DESTROY TOKEN
        $api->delete('logout', [
            'as' => 'authorizations.destroy',
            'uses' => 'AuthController@destroy',
        ]);

        // USER
        // my detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);

        // update part of me
        $api->patch('user', [
            'as' => 'user.update',
            'uses' => 'UserController@patch',
        ]);
        // update my password
        $api->put('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);

        // checklist
        // user's checklists index
        $api->get('user/checklists', [
            'as' => 'user.checklists.index',
            'uses' => 'ChecklistController@userIndex',
        ]);
        // create a checklist
        $api->post('checklists', [
            'as' => 'checklists.store',
            'uses' => 'ChecklistController@store',
        ]);
        // update a checklist
        $api->put('checklists/{id}', [
            'as' => 'checklists.update',
            'uses' => 'ChecklistController@update',
        ]);
        // update part of a checklist
        $api->patch('checklists/{id}', [
            'as' => 'checklists.patch',
            'uses' => 'ChecklistController@patch',
        ]);
        // delete a checklist
        $api->delete('checklists/{id}', [
            'as' => 'checklists.destroy',
            'uses' => 'ChecklistController@destroy',
        ]);

        // CHECKLISTS ITEMS
        // get a item
        $api->get('checklists/{checklistId}/items/{id}', [
            'as' => 'checklists.items.show',
            'uses' => 'ItemController@show',
        ]);
        // create a items
        $api->post('checklists/{checklistId}/items', [
            'as' => 'checklists.items.store',
            'uses' => 'ItemController@store',
        ]);
        $api->put('checklists/{checklistId}/items/{id}', [
            'as' => 'checklists.items.update',
            'uses' => 'ItemController@update',
        ]);
        // delete a items
        $api->delete('checklists/{checklistId}/items/{id}', [
            'as' => 'checklists.items.destroy',
            'uses' => 'ItemController@destroy',
        ]);
    });
});
