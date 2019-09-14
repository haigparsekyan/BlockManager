<?php

namespace Backpack\BlockManager\app\Http\Controllers\Admin;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\BlockManager\app\Http\Requests\BlockRequest as StoreRequest;
use Backpack\BlockManager\app\Http\Requests\BlockRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\BlockTemplates;

class BlocksCrudController extends CrudController
{
    use BlockTemplates;

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
        $this->crud->orderBy('lft', 'ASC');

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->setColumns(['title', 'block']);

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
//dd($request);exit;
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

        return parent::create();
    }

    // Overwrites the CrudController edit() method to add template usage.
    public function edit($id, $template = false)
    {
        $data = $this->crud->getUpdateFields($this->crud->request->id);
        $this->crud->setSubheading('Edit block for page ' . $this->crud->getModel()->getPageTitle($data['page_id']['value']));
        $this->crud->addField(['name' => 'page_id', 'type' => 'hidden']);
        $this->crud->addField(['name' => 'block', 'type' => 'hidden']);
        //$this->crud->setEditView('blockmanager::edit');
        $this->getBlockFields($data['block']['value']);

        return parent::edit($id);
    }

    public function reorder() {
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/blocks?page_id=' . $this->crud->request->page_id);
        return parent::reorder();
    }

    public function getBlockFields($block_name = null)
    {
        $blocks = $this->getBlocks();
        $names = \Illuminate\Support\Arr::pluck($blocks, 'name');

        if(!in_array($block_name, $names)) {
            abort(503, trans('backpack::blockmanager.block_not_found'));
        }

        if ($blocks) {
            $this->{$block_name}();
        }
    }

    /**
     * Get all defined blocks.
     */
    public function getBlocks()
    {
        $blocks = [];

        $blocks_trait = new \ReflectionClass('App\BlockTemplates');
        $blocks = $blocks_trait->getMethods();

        if (!count($blocks)) {
            abort(503, trans('backpack::pagemanager.template_not_found'));
        }

        return $blocks;
    }
}
