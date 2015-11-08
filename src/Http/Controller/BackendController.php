<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use Trungtnm\Backend\Core\CoreBackendController;
use Trungtnm\Backend\Model\User;

class BackendController extends CoreBackendController
{
    protected $module = "backend";
    public function __construct()
    {
        $this->init();
    }

    public function indexAction()
    {
        return view('TrungtnmBackend::index');
    }

    public function loginAction()
    {
        $this->layout = null;

        if (Sentinel::check()) {
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
                    'username' => request('loginUsername'),
                    'status'   => 1
                ])
                ->first();
                if (!$user) {
                    throw new \Exception('This user has been not active yet.');
                }

                $remember = (bool) request('remember');
                $dataLogin = array(
                    'username' => request("loginUsername"),
                    'password' => request("loginPassword"),
                    'status'   => 1
                );
                if ($checkLogin = Sentinel::authenticate($dataLogin, $remember)) {
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
        return view('TrungtnmBackend::denied');
    }

}
