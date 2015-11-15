<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Sentinel;
use Trungtnm\Backend\Core\BackendControllerInterface;
use Trungtnm\Backend\Core\CoreBackendController;
use Trungtnm\Backend\Model\Module;
use Trungtnm\Backend\Model\Permission;
use Trungtnm\Backend\Model\Role;

class RoleController extends CoreBackendController implements BackendControllerInterface
{
    protected $module = "Role";

	public function __construct(Role $model) {
        $this->model = $model;
        $this->init();
	}	

    public function processData($id = 0)
    {
        $this->updateData = [
            'id'    =>  $id,
            'name'  => request('name'),
            'slug'  => request('slug'),
            'permissions' => request('permissions', [])
        ];
    }

    public function getEditData()
    {
        $this->getPermissions();
        $this->data['customView'] = view('TrungtnmBackend::role.permission', $this->data)->render();
    }

	function getPermissions()
    {
		// GET ALL PERMISSION
		$permissions = Permission::all()->toArray();
		$permissionMap = array();
		// GET ALL MODULE
		$moduleData = Module::all()->toArray();
		if( !empty($permissions) ){
			foreach( $permissions as $permission ){
				$permissionMap[$permission['module']][] = $permission;
			}
		}
		if( !empty($moduleData) ){
			$moduleData = array_column($moduleData, 'name', 'id');
		}
		// get role permission
		$rolePermissions = $this->data['id'] ? $this->data['item']->permissions : [];
		$this->data['permissionMap'] 	= $permissionMap;
		$this->data['moduleData']		= $moduleData;
		$this->data['rolePermissions']	= $rolePermissions;
        return true;
	}

	function postPermission( $id, &$data ){

		if( $groupData = Sentry::findGroupById($id) ){

			$allData = Input::all();
			if( isset( $allData['_token'] ) ){
				unset( $allData['_token'] );
			}
			$groupData->permissions = $allData;
			
			if($groupData->save()){
				$data['status'] 	= TRUE;
				$data['message'] 	= "Sửa quyền truy cập thành công";
				foreach ($groupData->users as $k => $user) {
					DB::table($user->table)->where('id', $user->id)->update(array('permissions' => json_encode($groupData->permissions)));
				}
			}
		}

	}

	/*----------------------------- END CREATE & UPDATE --------------------------------*/

	/*----------------------------- DELETE --------------------------------*/

	function delete(){
        $id 	= Input::get('id');
        try
        {
            $role = Sentinel::findRoleById($id);
            // Delete the group
            if($role->delete()){
                return "success";
            }
        }
        catch (\Exception $e)
        {
            return "fail";
        }
        return "fail";
	}

	/*----------------------------- END DELETE --------------------------------*/

}
