<?php
namespace Trungtnm\Backend\Model;

use Trungtnm\Backend\Core\ModelTrait;

class User extends \Cartalyst\Sentinel\Users\EloquentUser
{
    use ModelTrait;

	public $table = 'backend_users';

	public static $loginRules	=	array(
		"loginEmail"	=>	"required",
		"loginPassword"	=>	"required"
	);

	public static $loginLangs	=	array(
		"loginEmail.required"	=>	"Please enter your email",
		"loginPassword.required"	=>	"Please enter your password"
	);

	public static $changePasswordRules	=	array(
		"oldPassword"				=>	"required",
		"newPassword"				=>	"required|confirmed|min:6",
		"newPassword_confirmation"	=>	"required|min:6"
	);

	public static $changePasswordLangs	=	array(
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
            'alias'         =>  'role.name'
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
        'role_id' => [
            'label'     => 'Role',
            'type'      =>  'select',
            'data'      =>  'roles',
        ],
    ];

	public function getUpdateRules(){
		return array(
			//"username"			=>	"required|min:5",
			"email"				=>	"required",
			'password'			=>	"required|min:6"
		);
	}

	public function getUpdateLangs(){
		return array(
			"email.required"	=>	trans('validation.email.required'),
			//"username.min"		=>	trans('validation.username.min'),
			"password.required"	=>	trans('validation.password.required'),
			"password.min"		=>	trans('validation.password.min'),
		);
	}




}
