<?php
namespace Trungtnm\Backend\Http\Controller;

use Trungtnm\Backend\Core\CoreBackendController;
use Trungtnm\Backend\Model\Language;

class LanguageController extends CoreBackendController
{
	public function __construct(Language $model) {
        $this->model = $model;
        $this->init();
	}	

    public function processData($id = 0)
    {
        $this->updateData = [
            'id'    =>  $id,
            'name'  => request('name'),
            'locale'  => request('locale')
        ];
    }

}
