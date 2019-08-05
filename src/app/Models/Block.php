<?php

namespace Backpack\BlockManager\app\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'blocks';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['title', 'page_id'];
    // protected $hidden = [];
    // protected $dates = [];
    //protected $fakeColumns = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getBlocksLink()
    {
        return url(config('backpack.base.route_prefix') . '/blocks/create?page_id=' . $_GET['page_id']);
    }

    public function addCreateButton()
    {
        return '<a class="btn btn-primary ladda-button" href="' . $this->getBlocksLink() . '">' .
            '<i class="fa fa-plus"></i> ' . trans('backpack::blockmanager.add') . '</a>';
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
