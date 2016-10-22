<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use Trungtnm\Backend\Core\CoreBackendController;
use Trungtnm\Backend\Model\Module;
use Trungtnm\Backend\Model\User;

class BackendController extends CoreBackendController
{
    public function __construct()
    {
        $this->init();
    }

    public function indexAction()
    {
        return view('TrungtnmBackend::index', $this->data);
    }

    public function loginAction()
    {
        $this->layout = null;
        $user = Sentinel::getUser();
        if ($user && !$this->checkRole()) {
            return Redirect::route('indexDashboard');
        }

        if (Request::isMethod('post')) {
            $this->data['status'] = false;
            $this->data['message'] = "";
            $this->checkLogin($this->data);
            if ($this->data['status'] === true) {
                if (session_id() == '') {
                    @session_start();
                }
                // set session for ckeditor
                $_SESSION['isLoggedIn'] = true;

                return Redirect::route('indexDashboard');
            }
        }

        return view('TrungtnmBackend::login', $this->data);
    }

    public function checkLogin(&$data)
    {
        $user = new User();
        $validate = Validator::make(Input::all(), $user->loginRules, $user->loginLangs);
        if ($validate->passes()) {
            try {
                $remember = (bool) request('remember');
                $dataLogin = [
                    'username' => request("loginUsername"),
                    'password' => request("loginPassword"),
                    'status'   => 1
                ];
                $user = Sentinel::authenticate($dataLogin, $remember);
                if ($user) {
                    if (!$this->checkRole($user)) {
                        throw new \Exception('Account is not exists');
                    }
                    $data['status'] = true;
                }
            } catch (\Exception $e) {
                $data['message'] = $e->getMessage();
            }
        } else {
            $data['validate'] = $validate->messages();
        }
    }

    /**
     * log user out
     *
     * @return mixed
     */
    public function logoutAction()
    {

        Sentinel::logout();
        if (session_id() == '') {
            @session_start();
        }
        unset($_SESSION['isLoggedIn']);

        return Redirect::route('loginBackend');

    }

    /**
     * render access denied page
     */
    public function accessDeniedAction()
    {
        return view('TrungtnmBackend::denied', $this->data);
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function processData($id = 0)
    {
        // TODO: Implement processData() method.
    }

    /**
     * @param null $user
     *
     * @return bool
     */
    private function checkRole($user = null)
    {
        $allowed = false;
        if (!$user) {
            $user = Sentinel::getUser();
        }
        if ($user) {
            $roles = config('trungtnm.backend.roles', []);
            foreach ($roles as $role) {
                if ($user->inRole($role)) {
                    $allowed = true;
                    break;
                }
            }
        }

        return $allowed;
    }
}
