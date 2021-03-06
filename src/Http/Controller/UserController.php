<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use Trungtnm\Backend\Core\BackendControllerInterface;
use Trungtnm\Backend\Core\CoreBackendController;
use Trungtnm\Backend\Model\Role;
use Trungtnm\Backend\Model\User;

class UserController extends CoreBackendController implements BackendControllerInterface
{
    /**
     * @var int
     */
    protected $specificRole;

    public function __construct(User $model)
    {
        //TODO: install for 1st times run this package
        $this->model = $model;
        $this->init();
    }

    public function getIndexData()
    {
        $this->getEditData();
    }

    public function getEditData()
    {
        $this->data['roles'] = array_column(Role::where('id', '!=', 1)->get()->toArray(), 'name', 'id');
    }

    /**
     * process data for saving
     *
     * @param int $id
     *
     * @return mixed|void
     */
    public function processData($id = 0)
    {
        $this->updateData = [
            'username'   => trim(strtolower(request('username'))),
            'email'      => trim(strtolower(request('email'))),
            'first_name' => request('first_name'),
            'last_name'  => request('last_name'),
            'status'     => (bool) request('status'),
        ];

        if (request('password')) {
            $this->updateData['password'] = request('password');
        }
    }

    /**
     *
     * @param int $id
     *
     * @return array
     */
    public function saveObject($id = 0)
    {
        // check validate
        if (!$id) {
            $this->model->updateRules['password'] = "required|min:6";
        } else {
            if (!empty($this->model->updateRules['email'])) {
                $this->model->updateRules['email'] = "required|email|unique:backend_users,email," . $id;
            }
            if (!empty($this->model->updateRules['email'])) {
                $this->model->updateRules['username'] = "required|min:6|unique:backend_users,username," . $id;
            }
        }
        $this->processData();
        $validateData = array_merge($this->updateData, request()->all());
        $validate = Validator::make($validateData, $this->model->updateRules, $this->model->updateLangs);
        if ($validate->passes()) {
            if ($password = request('password')) {
                $this->updateData['password'] = $password;
            }
            // File Upload
            $this->handleFileUpload();
            // End file Upload
            try {
                $roleId = intval(request('roleId', $this->specificRole));
                if (!$id) {
                    $user = Sentinel::registerAndActivate($this->updateData);
                } else {
                    $user = $this->model->find($id);
                    $oldRoleId = $user->roleId;
                    $user = Sentinel::update($user, $this->updateData);
                    // remove old role
                    if ($oldRoleId && $oldRoleId != $roleId) {
                        $oldRole = Sentinel::findRoleById($oldRoleId);
                        $oldRole->users()->detach($user);
                    }
                }

                try {
                    // add role
                    $role = Sentinel::findRoleById($roleId);
                    $role->users()->attach($user);
                } catch (\Exception $e) {

                }

                $this->data['id'] = $user->id;
                $user->renewCache();
                $action = !$id ? self::ACTION_CREATE : self::ACTION_EDIT;
                $this->afterSave($user, $action);

                return true;

            } catch (\Exception $e) {
                $this->data['message'] = $e->getMessage();
            }

        } else {
            $this->data['validate'] = $validate->messages();
        }

        return false;
    }

    /*----------------------------- END CREATE & UPDATE --------------------------------*/

    /*----------------------------- END DELETE --------------------------------*/


    public function changePasswordAction()
    {
        return view('TrungtnmBackend::user.changePassword', $this->data);
    }

    public function saveChangePasswordAction()
    {
        $validate = Validator::make(
            request()->all(),
            $this->model->changePasswordRules,
            $this->model->changePasswordLangs
        );
        if ($validate->passes()) {
            $oldPassword = request('oldPassword');
            $newPassword = request('newPassword');
            try {
                $user = Sentinel::getUser();
                $hasher = Sentinel::getHasher();
                if (!$hasher->check($oldPassword, $user->getUserPassword())) {
                    throw new \Exception("Old password does not match");
                }
                if (Sentinel::update($user, ['password' => $newPassword])) {
                    $this->data['status'] = true;
                    $this->data['message'] = "Your password has been changed successfully";
                }
            } catch (\Exception $e) {
                $this->data['message'] = $e->getMessage();
            }
        } else {
            $this->data['validate'] = $validate->messages();
        }

        return view('TrungtnmBackend::user.changePassword', $this->data);
    }
}
