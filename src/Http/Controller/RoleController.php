<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Trungtnm\Backend\Core\BackendControllerInterface;
use Trungtnm\Backend\Core\CoreBackendController;
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
        ];
    }

	function showPermission( $id ){

		$this->data['status'] = (Session::has("status")) ? Session::get("status") : FALSE ;
		$this->data['message'] = (Session::has("message")) ? Session::get("message") : "" ;
		$this->data['id'] = $id;

		
		// GET ALL PERMISSION
		$permissions = Permission::all()->toArray();
		$permissionMap = array();

		// GET ALL MODULE
		$moduleData = Modules::all()->toArray();
		if( !empty($permissions) ){
			foreach( $permissions as $permission ){
				$permissionMap[$permission['module_id']][] = $permission;
			}
		}

		if( !empty($moduleData) ){
			$moduleData = array_column($moduleData, 'name', 'id');
		}

		// GET USER PERMISSION
		$groupPermissions = Sentry::findGroupById($id)->getPermissions();
		$this->data['permissionMap'] 	= $permissionMap;
		$this->data['moduleData']		= $moduleData;
		$this->data['groupPermissions']	= $groupPermissions;

		if (Request::isMethod('post'))
		{
			$this->postPermission($id, $this->data);
			if( $this->data['status'] === TRUE ){
				return Redirect::to($this->moduleURL.'permission/'.$this->data['id']);
			}
		}

		$this->layout->content 			= View::make('showPermission', $this->data);

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
