<?php

namespace Trungtnm\Backend\Model;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Trungtnm\Backend\Core\ModelTrait;

class Role extends EloquentRole
{
    use ModelTrait;

    const ROOT = 1;
    const ADMIN = 2;
    const USER = 3;

    public $table = "roles";

    public $showFields = [
        'name'         =>  [
            'label'         =>  'Role name',
            'type'          =>  'text'
        ],
        'slug'         =>  [
            'label'         =>  'Role slug',
            'type'          =>  'text'
        ],
        'created_at'    =>  [
            'label'         =>  'Created at',
            'type'          =>  'text'
        ]
    ];

    public $dataFields = [
        'name'         =>  [
            'label'         =>  'Role name',
            'type'          =>  'text'
        ],
        'slug'         =>  [
            'label'         =>  'Role slug',
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

    /**
     * @param $permission
     *
     * @return $this
     */
    public function removePermission($permission)
    {
        $permission = strtolower($permission);
        return parent::removePermission($permission);
    }

    /**
     * @param      $permission
     * @param bool $value
     *
     * @return $this
     */
    public function addPermission($permission, $value = true)
    {
        $permission = strtolower($permission);
        return parent::addPermission($permission, $value);
    }

}