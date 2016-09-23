<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use Trungtnm\Backend\Core\CoreBackendController;
use Trungtnm\Backend\Model\Module;
use Trungtnm\Backend\Model\Permission;
use Trungtnm\Backend\Model\Role;
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

        if (Sentinel::check() && (Sentinel::getUser()->inRole(Role::ROOT) || Sentinel::getUser()->inRole(Role::ADMIN)) ) {
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
                $user = User::where([
                        'email' => request('loginEmail'),
                        'status'   => 1,
                    ])
                    ->first();
                if (!$user) {
                    throw new \Exception('This user has been not active yet.');
                }
                if (!$user->inRole(Role::ROOT) && !$user->inRole(Role::ADMIN)) {
                    throw new \Exception('Account is not exists');
                }

                $remember = (bool) request('remember');
                $dataLogin = array(
                    'email' => request("loginEmail"),
                    'password' => request("loginPassword"),
                    'status'   => 1
                );
                $user = Sentinel::authenticate($dataLogin, $remember);
                debug($user);
                if ($user) {
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
     * @return mixed
     */
    public function processData($id = 0)
    {
        // TODO: Implement processData() method.
    }
}
