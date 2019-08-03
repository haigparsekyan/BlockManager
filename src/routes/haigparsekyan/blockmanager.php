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
        $controller = config('backpack.pagemanager.admin_controller_class', 'Backpack\PageManager\app\Http\Controllers\Admin\PageCrudController');
        $block_controller = config('backpack.pagemanager.admin_controller_blocks_class', 'Backpack\PageManager\app\Http\Controllers\Admin\BlocksCrudController');

        // Backpack\PageManager routes
        Route::get('page/create/{template}', $controller.'@create');
        Route::get('page/{id}/edit/{template}', $controller.'@edit');

        // This triggered an error before publishing the PageTemplates trait, when calling Route::controller();
        // CRUD::resource('page', $controller . '');

        // So for PageCrudController all routes are explicitly defined:
        Route::get('page/reorder', $controller.'@reorder');
        Route::get('page/reorder/{lang}', $controller.'@reorder');
        Route::post('page/reorder', $controller.'@saveReorder');
        Route::post('page/reorder/{lang}', $controller.'@saveReorder');
        Route::get('page/{id}/details', $controller.'@showDetailsRow');
        Route::get('page/{id}/translate/{lang}', $controller.'@translateItem');

        Route::post('page/search', [
            'as' => 'crud.page.search',
            'uses' => $controller.'@search',
        ]);

        Route::resource('page', $controller);

        //Blocks
        Route::get('blocks/create', $block_controller.'@create');
        Route::get('blocks/{id}/edit', $block_controller.'@edit');

        Route::post('blocks/search', [
            'as' => 'crud.blocks.search',
            'uses' => $block_controller.'@search',
        ]);

        Route::resource('blocks', $block_controller);
    });
