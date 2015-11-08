<?php

namespace Trungtnm\Backend\Model;


use Trungtnm\Backend\Core\ModelTrait;

class Role extends \Cartalyst\Sentinel\Roles\EloquentRole
{
    use ModelTrait;

    const ROOT = 1;
    const ADMIN = 2;
    const USER = 3;

    public $table = "roles";

    public $showFields = [
        'name'         =>  [
            'label'         =>  'Module name',
            'type'          =>  'text'
        ],
        'slug'         =>  [
            'label'         =>  'Module slug',
            'type'          =>  'text'
        ],
        'created_at'    =>  [
            'label'         =>  'Created at',
            'type'          =>  'text'
        ]
    ];

    public $dataFields = [
        'name'         =>  [
            'label'         =>  'Module name',
            'type'          =>  'text'
        ],
        'slug'         =>  [
            'label'         =>  'Module slug',
            'type'          =>  'text'
        ]
    ];

    public $searchFields = [
        'name'  =>  'Role name',
        'slug'  =>  'Role slug'
    ];

    public $updateRules = [
        "name"			=>	"required",
        "slug"			=>	"required",
    ];

    public $updateLangs = [];

}