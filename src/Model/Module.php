<?php
namespace Trungtnm\Backend\Model;

use Trungtnm\Backend\Core\AbstractModel;

class Module extends AbstractModel {

	protected $table = 'backend_modules';
	public $appends = ['moduleName'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
	public function Menu(){
		return $this->hasOne('Trungtnm\Backend\Model\Menu', 'module_id');
	}

    /**
     * get module name
     *
     * @return mixed
     */
	function getModuleNameAttribute(){
		if(!empty($this->Menu->id)){
			return $this->Menu->name;
		}
		return $this->name;
	}
}
