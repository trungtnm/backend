<?php
namespace Trungtnm\Backend\Model;


use Trungtnm\Backend\Core\AbstractModel;
use Trungtnm\Backend\Core\ModelTrait;

class Permission extends AbstractModel
{
    use ModelTrait;

    public static $defaultPermissions = [
        '.create', '.edit', '.read', '.publish', '.delete'
    ];

    protected $table = "permissions";

    public function scopeAdd($query, $module, $permission)
    {
        $data = [
            'module' => strtolower($module),
            'permission' => $permission
        ];
        return $query->insert($data);
    }

    public function scopeRemove($query, $module, $permission)
    {
        $query->where([
            'module' => strtolower($module),
            'permission' => $permission
        ]);
        return $query->delete();
    }

    public function scopeRemoveModule($query, $module)
    {
        $query->where([
            'module' => strtolower($module)
        ]);
        return $query->delete();
    }
}