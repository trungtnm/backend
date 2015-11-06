<?php

class UserGroup extends MyModel {
	protected $table = "backend_users_groups";
	
	public function user(){
		return $this->belongsTo("User");
	}

	public function group(){
		return $this->belongsTo('Group');
	}

}
