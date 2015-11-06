<?php
namespace Trungtnm\Backend\Model;

class User extends \Cartalyst\Sentinel\Users\EloquentUser {

	public $table = 'backend_users';
	public $showAddButton = true;
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

	public function getShowField(){
		return array(
			//'username'		=>	trans('text.username'),
			'email'		=>	trans('text.email'),
			'created_at'	=>	trans('text.created_at'),
		);
	}

	public function getSearchField(){
		return array(
			//'username'		=>	trans('text.username'),
			'email'			=> trans('text.username'),
		);
	}

	public function scopeSearch($query, $keyword = '', $filterBy = 0)
	{
		if( !empty($keyword) ){

			if( !empty($filterBy)  ){
				$query->where($filterBy, 'LIKE', "%{$keyword}%");
			}else{
				if( !empty($this->searchField) ){
					foreach( $this->searchField as $field => $title){
						$query->orWhere($field, 'LIKE', "%{$keyword}%");
					}
				}
			}

		}
		return $query;
	}


}
