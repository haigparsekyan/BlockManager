<?php

namespace App;

trait BlockTemplates
{
    // function name = block template

    public function about_us()
    {
        $this->crud->addField([
            'name' => 'content',
            'label' => trans('backpack::pagemanager.content'),
            'type' => 'wysiwyg',
            'placeholder' => trans('backpack::pagemanager.content_placeholder'),
        ]);
    }
}
