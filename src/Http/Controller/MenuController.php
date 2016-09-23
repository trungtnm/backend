<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Request;
use Sentinel;
use Trungtnm\Backend\Core\BackendControllerInterface;
use Trungtnm\Backend\Core\CoreBackendController;
use Trungtnm\Backend\Model\Menu;
use Trungtnm\Backend\Model\Module;
use Trungtnm\Backend\Model\Permission;

class MenuController extends CoreBackendController  implements BackendControllerInterface
{
    protected $data = [];

	public function __construct(Menu $model) {
        //TODO: install for 1st times run this package
        $this->model = $model;
        $this->init();
	}

    public function getEditData()
    {
        $this->data['parent']  = array_column(
            $this->model->where('id', '!=', $this->data['id'])
                ->get()
                ->toArray(),
            'name',
            'id'
        );
        $this->data['modules']  = array_column(Menu::all()->toArray(), 'module', 'module');
        $this->data['customView'] = view('TrungtnmBackend::menu.edit', $this->data)->render();
    }

    /**
     * @param int $id
     *
     * @return $this
     */
	public function processData($id = 0){
        $this->updateData = array(
            'id'        =>  $id,
            'status'    =>	(int) request('status'),
            'name'      =>	trim(request('name')),
            'module' =>	trim(strtolower(request('module'))),
            'parent_id' =>	intval(request('parent_id')),
            'slug'      =>	trim(request('slug')),
            'icon'      =>	trim(request('icon')),
        );
        return $this;
	}

    /**
     * @param      $item
     * @param bool $isDelete
     */
    public function afterSave($item, $action = '')
    {
        $adminRoles = [
            Sentinel::findRoleById(1), // role ROOT
            Sentinel::findRoleById(2)  // role Admin
        ];
        if ($action == self::ACTION_CREATE) {
            $this->addPermission($adminRoles, $item->module);
        } elseif ($action == self::ACTION_DELETE) {
            $this->removePermission($adminRoles, $item->module);
        }
    }

    /**
     * @param $roles
     * @param $module
     *
     * @return $this
     */
    private function addPermission($roles, $module)
    {
        $defaultPermissions = Permission::$defaultPermissions;
        $permissionAdded = [];
        foreach ($roles as $role) {
            foreach ($defaultPermissions as $permission) {
                $role = $role->addPermission($module . $permission);
                if (!in_array($module . $permission, $permissionAdded)) {
                    Permission::add($module, $permission);
                    $permissionAdded[] = $module . $permission;
                }
            }
            $role->save();
        }
        return $this;
    }

    /**
     * @param $roles
     * @param $module
     *
     * @return $this
     */
    public function removePermission($roles, $module)
    {
        $defaultPermissions = Permission::$defaultPermissions;
        foreach ($roles as $role) {
            foreach ($defaultPermissions as $permission) {
                $role = $role->removePermission($module . $permission);
            }
            $role->save();
        }
        Permission::removeModule($module);

        return $this;
    }

	public function nestableAction(){
		$this->data['listMenus'] = $this->getMenu();

		return view('TrungtnmBackend::menu.nestable', $this->data);
	}

    public function saveNestableAction(){

        $listMenus = request('menu');
        $order = 0;

        if( !empty($listMenus) ){
            foreach( $listMenus as $id => $parent ){
                $order++;
                $menu = Menu::where('id', $id)->update(array('parent_id' => intval($parent), 'order' => $order));
            }
        }

        return response()->json(TRUE);

    }

	public function deleteAction(){

		if( Request::ajax() ){
			$id 	= request('id');
			$item 	= $this->model->find($id);
			if( $item ){
				$parent_id = $item->parent_id;
				if($item->delete()){
					if( $parent_id == 0 ){
						$this->model->where('parent_id', $id)->update(array(
							'parent_id'	=>	0
						));
					}
                    $this->afterSave($item, self::ACTION_DELETE);
					return response("success");
				}
			}
		}
		return response("fail");

	}

}
