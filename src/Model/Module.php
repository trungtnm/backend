<?php
namespace Trungtnm\Backend\Model;

use Trungtnm\Backend\Core\AbstractModel;
use Trungtnm\Backend\Core\ModelTrait;

class Module extends AbstractModel
{
    use ModelTrait;

	protected $table = 'backend_modules';
	public $appends = ['moduleName'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
	public function menu(){
		return $this->hasOne('Trungtnm\Backend\Model\Menu', 'module_id');
	}

    /**
     * get module name
     *
     * @return mixed
     */
	function getModuleNameAttribute(){
		if(!empty($this->menu->id)){
			return $this->menu->name;
		}
		return $this->name;
	}
}
