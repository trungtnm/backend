<?php
namespace Trungtnm\Backend\Model;

use Trungtnm\Backend\Core\AbstractModel;

class Menu extends AbstractModel
{
    protected $table = 'backend_menus';

    public $showFields = [
        'name'         =>  [
            'label'         =>  'Name',
            'type'          =>  'text'
        ],
        'slug'         =>  [
            'label'         =>  'Slug',
            'type'          =>  'text'
        ],
        'parent_id'         =>  [
            'label'         =>  'Parent Menu',
            'type'          =>  'text',
            'alias'         =>  'parent.name'
        ],
        'icon'         =>  [
            'label'         =>  'Icon',
            'type'          =>  'text'
        ],
        'status'         =>  [
            'label'         =>  'Status',
            'type'          =>  'boolean'
        ],
        'created_at'    =>  [
            'label'         =>  'Created at',
            'type'          =>  'text'
        ]
    ];

    public $dataFields = [
        'name' => [
            'label' =>  'Name',
            'type'  =>  'text'
        ],
        'status' => [
            'label'     => 'Status',
            'type'      =>  'select',
            'defaultOption' => [ '1' => "Active", '0' => "Inactive"]
        ],
        'module_id' => [
            'label'     => 'Module',
            'type'      =>  'select',
            'data'      =>  'modules',
        ],
        'parent_id' => [
            'label'     => 'Parent menu',
            'type'      =>  'select',
            'data'      =>  'parent',
        ],
        'slug' => [
            'label'     => 'Menu slug',
            'type'      =>  'text'
        ],
    ];

    public $searchFields = [
        'name'  =>  'Menu name'
    ];
    public $updateRules = [
        'name' 		=> 'required'
    ];

    /**
     * @return \Trungtnm\Backend\Model\Module
     */
	public function module()
    {
		return $this->belongsTo('\Trungtnm\Backend\Model\Module');
	}

    /**
     * @return \Trungtnm\Backend\Model\Menu
     */
    public function parent()
    {
        return $this->belongsTo('\Trungtnm\Backend\Model\Menu', 'parent_id');
    }

    /**
     * @param $query
     * @return mixed
     */
	public function scopeGetList($query)
    {
		return $query->where('status', 1)
            ->where('module_id', '>', 0)
            ->orderBy('parent_id', 'asc')
            ->orderBy('order', 'asc')
            ->get();
	}
}
