<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use Trungtnm\Backend\Core\AbstractBackendController;
use Trungtnm\Backend\Model\Role;
use Trungtnm\Backend\Model\User;

class UserController extends AbstractBackendController
{

    protected $data = [];

    protected $module = 'User';

    public function __construct(User $model) {
        //TODO: install for 1st times run this package
        $this->model = $model;
        $this->init();
    }

    public function getEditData()
    {
        $this->data['roles'] = array_column(Role::where('id', '!=', Role::ROOT)->get()->toArray(), 'name', 'id');
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public function saveObject($id = 0, &$data){
        // check validate
        $validate 		= Validator::make(Input::all(), $this->model->updateRules, $this->model->updateLangs);

        if( $validate->passes() ){
            // File Upload
            if(is_array($this->uploadFields)){
                foreach ($this->uploadFields as $field) {
                    if(request('inputURL_'. $field)){
                        $updateData[$field] = request($field);
                    }
                    else{
                        if(Input::hasFile($field)){
                            $updateData[$field] = uploadImage($field, $this->dirUpload);
                        }
                    }
                    if($id > 0){
                        //update - delete old file
                    }
                }
            }
            // End file Upload
            $model = get_class($this->model);
            if (!$id) {
                $user = Sentinel::createUser(array(
                    'username'  => request('username'),
                    'email'     => request('email'),
                    'password'  => request('password')
                ));
            }

            if( $groupID != 0 && is_numeric($groupID) ){
                $groupItem = Sentinel::findGroupById($groupID);
                $permissions = $groupItem->getPermissions();
                // dump_exit($permissions);
                $user->addGroup($groupItem);
                $user->permissions = $permissions;
                $user->save();
            }


            $data['status'] 		= TRUE;
            $data['id']				= $user->id;

            if($item->save()){
                $this->data['id'] = $item->id;
                $item->renewCache();
                $this->afterSave($item);
                return true;
            }

        }else{
            $data['validate'] = $validate->messages();
        }

        return FALSE;

    }


	function postUpdate($id = 0, &$data){

		// check validate
		$email 	= trim(request('email'));
		$password 	= trim(request('password'));
		$groupID 	= request('group');

		$UpdateRules = $this->model->getUpdateRules();
		
		if( $id != 0 ){
			if( empty($password) ){
				unset($UpdateRules['password']);
			}
		}

		$validate 		= Validator::make(request()->all(), $UpdateRules, $this->model->getUpdateLangs());
		
		if( $validate->passes() ){
			if( $id == 0 ){ // INSERT

				try{
				    $user = Sentinel::createUser(array(
				        //'username'  => $username,
				        'email'     => $email,
				        'password'  => $password,
				        'activated' => TRUE,
				    ));

				    if( $groupID != 0 && is_numeric($groupID) ){
				    	$groupItem = Sentinel::findGroupById($groupID);
				    	$permissions = $groupItem->getPermissions();
				    	// dump_exit($permissions);
					    $user->addGroup($groupItem);
					    $user->permissions = $permissions;
					    $user->save();
					}

				    $data['status'] 		= TRUE;
					$data['id']				= $user->id;
				}
				catch (\Cartalyst\Sentry\Users\UserExistsException $e)
				{
				    $data['message'] = 'Tên đăng nhập đã tồn tại. Vui lòng nhập tên đăng nhập khác';
				}


			}else{ // UPDATE

				try
				{
					$userData = Sentinel::findUserById($id);
					$userData->email = $email;
					$oldGroup = $userData->getGroups()->first();

					if( !empty($password) ){
						$userData->password = $password;
					}

					if($userData->save()){
							
						// dump($oldGroup);
						// dump($groupID);

						if( $oldGroup != NULL){
							if( $groupID != $oldGroup->id  ){
								$userData->removeGroup($oldGroup);
								if( $groupID != 0 && is_numeric($groupID) ){
									$newGroup = Sentinel::findGroupById($groupID);
							    	$permissions = $newGroup->getPermissions();
									$userData->addGroup($newGroup);
								    $userData->permissions = $permissions;
								    $userData->save();
								}
							}
						}else{
							if( $groupID != 0 && is_numeric($groupID) ){
								$newGroup = Sentinel::findGroupById($groupID);
								$userData->addGroup($newGroup);
							}
							
						}


						$data['status']  = TRUE;
						$data['id']      = $userData->id;
						$data['message'] = "Saved success";
					}
				}
				catch (\Cartalyst\Sentry\Users\WrongPasswordException $e)
				{
					$data['message'] = "Mật khẩu không chính xác";
				}
			}

		}else{
			$data['validate'] = $validate->messages();
		}

	}


	function showPermission( $id ){

		if($userData = Sentinel::findUserById($id)){
			if( $userData->isSuperUser() ){
                return Redirect::route($this->module.'Index');
			}
		}else{
			return Redirect::route($this->module.'Index');
		}

		$this->data['status'] = (Session::has("status")) ? Session::get("status") : FALSE ;
		$this->data['message'] = (Session::has("message")) ? Session::get("message") : "" ;
		$this->data['id'] = $id;

		
		// GET ALL PERMISSION
		$permissions = Permission::get()->toArray();
		$permissionMap = array();

		// GET ALL MODULE
		$moduleData = Modules::get()->toArray();

		if( !empty($permissions) ){
			foreach( $permissions as $permission ){
				$permissionMap[$permission['module_id']][] = $permission;
			}
		}

		if( !empty($moduleData) ){
			$moduleData = array_column($moduleData, 'name', 'id');
		}

		// GET USER PERMISSION
		$userPermissions = Sentinel::findUserById($id)->getPermissions();

		$this->data['permissionMap'] 	= $permissionMap;
		$this->data['moduleData']		= $moduleData;
		$this->data['userPermissions']	= $userPermissions;

		if (request()->isMethod('post'))
		{
			$this->postPermission($id,$userData, $this->data);
			if( $this->data['status'] === TRUE ){
				return Redirect::to($this->moduleURL.'permission/'.$this->data['id']);
			}
		}

		$this->layout->content 			= view('showPermission', $this->data);

	}

	function postPermission( $id, $userData, &$data ){

		$allData = request()->all();
		if( isset( $allData['_token'] ) ){
			unset( $allData['_token'] );
		}

		$userData->permissions = array();
		$userData->permissions = $allData;
		
		if($userData->save()){
			$data['status'] 	= TRUE;
		}

	}


	/*----------------------------- END CREATE & UPDATE --------------------------------*/

	/*----------------------------- END DELETE --------------------------------*/


	public function changePassword()
	{
		if (request()->isMethod('post'))
		{
			$this->data['status'] = FALSE;
			$this->data['message'] = "";
			$this->postChangePassword( $this->data );
		}
        $this->layout->content = view('changePassword', $this->data);
	}

	public function postChangePassword( &$messageArray )
	{	
		$validate = Validator::make(
            request()->all(),
            $this->model->$changePasswordRules,
            $this->model->$changePasswordLangs
        );
		if( $validate->passes() )
		{
			$oldPassword = request('oldPassword');
			$newPassword = request('newPassword');
			try
			{
				$userData = Sentinel::findUserByCredentials(array(
			        'email'      => Sentinel::getUser()->email,
			        'password'   	=> $oldPassword
			    ));
				$userData->password = $newPassword;
				if($userData->save()){
					$messageArray['status'] 	= TRUE;
					$messageArray['message'] 	= "Đổi mật khẩu thành công";
				}else{
					$messageArray['message'] 	= "Đã có lỗi xảy ra trong quá trình đổi mật khẩu. Bạn vui lòng thử lại sau";
				}
			}
			catch (\Cartalyst\Sentry\Users\WrongPasswordException $e)
			{
				$messageArray['message'] = "Mật khẩu không chính xác";
			}
		}
		else
		{
			$messageArray['validate'] = $validate->messages();
		}
	}

}
