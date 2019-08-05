<?php

namespace Backpack\BlockManager\app\Http\Controllers\Admin;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\BlockManager\app\Http\Requests\BlockRequest as StoreRequest;
use Backpack\BlockManager\app\Http\Requests\BlockRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

class BlocksCrudController extends CrudController
{

    public function setup()
    {
        $modelClass = config('backpack.blockmanager.block_model_class', 'Backpack\BlockManager\app\Models\Block');
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel($modelClass);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blocks');

        $this->crud->setEntityNameStrings('block', 'blocks');
        $this->crud->addClause('where', 'page_id', '=', $this->crud->request->page_id);
        $this->crud->setHeading('Blocks of page "' . $this->crud->getModel()->getPageTitle($this->crud->request->page_id) . '"', 'index');
        $this->crud->enableReorder('title', 1);
        $this->crud->allowAccess('reorder');

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->setColumns(['title']);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | BUTTONS
        |--------------------------------------------------------------------------
        */

       $this->crud->removeButton('create');
       $this->crud->removeButton('reorder');
       $this->crud->addButtonFromModelFunction('top', 'reorder', 'addReorderButton', 'beginning');
       $this->crud->addButtonFromModelFunction('top', 'create', 'addCreateButton', 'beginning');
    }

    // -----------------------------------------------
    // Overwrites of CrudController
    // -----------------------------------------------

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    // Overwrites the CrudController add() method to add template usage.
    public function create()
    {
        $this->crud->setSubheading('Add block for page ' . $this->crud->getModel()->getPageTitle($this->crud->request->page_id));
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blocks?page_id=' . $this->crud->request->page_id);
        $this->crud->addField(['name' => 'page_id', 'type' => 'hidden', 'value' => $this->crud->request->page_id]);

        // if the template in the GET parameter is missing, figure it out from the db
//        if ($this->crud->request->page_id == false) {
//            $model = $this->crud->model;
//            $this->data['page_id'] = $this->crud->request->page_id;
//            //$template = $this->data['entry']->template;
//        }

        //$this->addDefaultPageFields($template);
        //$this->useTemplate($template);

        return parent::create();
    }

    // Overwrites the CrudController edit() method to add template usage.
    public function edit($id, $template = false)
    {
        $data = $this->crud->getUpdateFields($this->crud->request->id);
        $this->crud->setSubheading('Edit block for page ' . $this->crud->getModel()->getPageTitle($data['page_id']['value']));
        $this->crud->addField(['name' => 'page_id', 'type' => 'hidden']);
        //$this->crud->setRoute(config('backpack.base.route_prefix') . '/blocks?page_id=' . $data['page_id']['value'], 'edit');
        //$template = request('template');

        // if the template in the GET parameter is missing, figure it out from the db
//        if ($template == false) {
//            $model = $this->crud->model;
//            $this->data['entry'] = $model::findOrFail($id);
//            $template = $this->data['entry']->template;
//        }

        //$this->addDefaultPageFields($template);
        //$this->useTemplate($template);

        return parent::edit($id);
    }

    public function reorder() {
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blocks?page_id=' . $this->crud->request->page_id);
        return parent::reorder();
    }
}
