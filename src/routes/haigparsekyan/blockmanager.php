<?php

/*
|--------------------------------------------------------------------------
| BlockManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled by the BlockManager package.
|
*/

Route::group([
        'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
        'prefix' => config('backpack.base.route_prefix', 'admin'),
    ], function () {
        $controller = config('backpack.pagemanager.admin_controller_blocks_class', 'Backpack\BlockManager\app\Http\Controllers\Admin\BlocksCrudController');

        Route::get('blocks/create', $controller.'@create');
        Route::get('blocks/{id}/edit', $controller.'@edit');

        Route::post('blocks/search', [
            'as' => 'crud.blocks.search',
            'uses' => $controller.'@search',
        ]);

        Route::resource('blocks', $controller);
    });
