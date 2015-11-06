<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Sentinel;
use Trungtnm\Backend\Core\AbstractBackendController;
use Trungtnm\Backend\Model\User;

class BackendController extends AbstractBackendController
{
    protected $module = "backend";
    public function __construct()
    {
        $this->init();
    }

    public function addRoleAction()
    {
        Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Root',
            'slug' => 'root',
        ]);
    }

    public function indexAction()
    {
        return view('TrungtnmBackend::index');
    }

    public function loginAction()
    {
        $this->layout = null;

        if (Request::isMethod('post')) {
            $this->data['status'] = false;
            $this->data['message'] = "";
            $this->checkLogin($this->data);
            if ($this->data['status'] === true) {
                if (session_id() == '') {
                    @session_start();
                }
                $_SESSION['isLoggedIn'] = true;

                return Redirect::route('indexDashboard');
            }
        }

        return view('TrungtnmBackend::login', $this->data);
    }

    public function checkLogin(&$data)
    {

        $validate = Validator::make(Input::all(), User::$loginRules, User::$loginLangs);

        if ($validate->passes()) {
            try {
                $remember = (bool) request('remember');
                $dataLogin = array(
                    'email' => request("loginEmail"),
                    'password' => request("loginPassword")
                );
                if(Sentinel::authenticate($dataLogin, $remember)){
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
        $this->layout->content = view('trungtnm.backend.denied');
    }

}
