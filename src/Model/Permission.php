<?php

class Permission extends MyModel {

	protected $table = 'backend_users_permissions';
	protected $fillable = array('name', 'slug', 'module_id', 'action', 'status');

	public function module()
    {
        return $this->belongsTo('Modules');
    }

}
