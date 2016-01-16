<?php
namespace Trungtnm\Backend\Model;

use Trungtnm\Backend\Core\ModelTrait;

class User extends \Cartalyst\Sentinel\Users\EloquentUser
{
    use ModelTrait;

    protected $table = 'backend_users';
    protected $loginNames = ['email', 'status', 'username'];
    protected $appends = ['roles_name', 'role_id'];
    protected $fillable = ['username', 'first_name', 'last_name'];

    public $loginRules	=	array(
		"loginEmail"	=>	"required",
		"loginPassword"	=>	"required"
	);

	public $loginLangs	=	array(
		"loginEmail.required"	=>	"Please enter your email",
		"loginPassword.required"	=>	"Please enter your password"
	);

	public $changePasswordRules	=	array(
		"oldPassword"				=>	"required",
		"newPassword"				=>	"required|confirmed|min:6",
		"newPassword_confirmation"	=>	"required|min:6"
	);

	public $changePasswordLangs	=	array(
		"oldPassword.required"			=>	"Please enter your current password",
		"newPassword.required"          =>  "Please enter your new password",
        "newPassword.confirmed"         =>  "New password and password confirmation do not match",
        "newPassword.min"               =>  "The new password must be at least 6 characters",
        "newPassword_confirmation.required" =>  "Please enter password confirmation",
        "newPassword_confirmation.min"  =>  "Password confirmation must be at least 6 characters"
	);

    public $showFields = [
        'username'         =>  [
            'label'         =>  'Username',
            'type'          =>  'text'
        ],
        'email'         =>  [
            'label'         =>  'Email',
            'type'          =>  'text'
        ],
        'Role'         =>  [
            'label'         =>  'Role',
            'type'          =>  'text',
            'alias'         =>  'rolesName'
        ],
        'first_name'         =>  [
            'label'         =>  'First name',
            'type'          =>  'text'
        ],
        'last_name'         =>  [
            'label'         =>  'Last name',
            'type'          =>  'text'
        ],
        'status'         =>  [
            'label'         =>  'Status',
            'type'          =>  'boolean'
        ],
        'created_at'    =>  [
            'label'         =>  'Created at',
            'type'          =>  'text'
        ]
    ];

    public $dataFields = [
        'username' => [
            'label' =>  'Username',
            'type'  =>  'text'
        ],
        'email' => [
            'label' =>  'Email Address',
            'type'  =>  'text'
        ],
        'password' => [
            'label' =>  'Password',
            'type'  =>  'password'
        ],
        'first_name' => [
            'label' =>  'First name',
            'type'  =>  'text'
        ],
        'last_name' => [
            'label' =>  'Last name',
            'type'  =>  'text'
        ],
        'status' => [
            'label'     => 'Status',
            'type'      =>  'select',
            'defaultOption' => [ '1' => "Active", '0' => "Inactive"]
        ],
        'roleId' => [
            'label'     => 'Role',
            'type'      =>  'select',
            'data'      =>  'roles',
        ],
    ];

	public $updateRules = [
        "username"			=>	"required|min:5",
        "email"				=>	"required|unique:backend_users,email",
        'password'			=>	"min:6",
        "roleId"           =>  "required"
    ];

    public $searchFields = [
        'username'  =>  'Username',
        'email'  =>  'Email',
        'first_name'  =>  'First name',
        'last_name'  =>  'Last name',
    ];

    public $searchSelects = [
        'roleId'  =>  [
            'label' => 'Role',
            'options' => 'roles'
        ]
    ];

    public $updateLangs = [];


    /**
     * get display roles name of a user
     *
     * @return string
     */
    public function getRolesNameAttribute()
    {
        $names = [];
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            $names[] = $role->name;
        }
        return implode(', ', $names);
    }

    /**
     * get role id of user
     *
     * @return mixed
     */
    public function getRoleIdAttribute()
    {
        $roles = $this->getRoles();
        if (count($roles)) {
            return $roles->first()->id;
        } else {
            return 0;
        }
    }
}
