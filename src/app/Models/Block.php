<?php

namespace Backpack\BlockManager\app\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    protected $fillable = ['title', 'page_id', 'block', 'extras'];
    //protected $hidden = [];
    // protected $dates = [];
    protected $fakeColumns = ['extras'];

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
            '<i class="fa fa-plus"></i>&nbsp; ' . trans('backpack::blockmanager.add') . '</a>';
    }

    public function getReorderLink()
    {
        return url(config('backpack.base.route_prefix') . '/blocks/reorder?page_id=' . $_GET['page_id']);
    }

    public function addReorderButton()
    {
        return '<a class="btn btn-default ladda-button" href="' . $this->getReorderLink() . '">' .
            '<i class="fa fa-arrows-v"></i>&nbsp; ' . trans('backpack::blockmanager.reorder') . '</a>';
    }

    public function getPageTitle($id) {
        $page = DB::table('pages')->where('id', $id)->first();
        if($page) {
            return $page->title;
        }

        return '';
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
